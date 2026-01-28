<?php
require_once BASE_PATH . '/app/controllers/BaseAdminController.php';
require_once BASE_PATH . '/app/models/CustomerModels.php';

class AdminCustomerController extends BaseAdminController
{
    private $customerModel;

    public function __construct()
    {
        parent::__construct();
        $this->customerModel = new CustomerModel();
    }

    /**
     * Standard JSON response helper
     */
    private function json($success, $message, $data = [])
    {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => $success,
            'message' => $message,
            'data'    => $data
        ]);
        exit;
    }

    private function uploadAvatar($file, $oldFile = null)
    {
        $allowed = ['jpg', 'jpeg', 'png'];
        $maxSize = 2 * 1024 * 1024;
        $uploadDir = BASE_PATH . '/public/uploads/avatars/';

        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('Avatar upload failed');
        }

        if ($file['size'] > $maxSize) {
            throw new Exception('Avatar max 2MB');
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed)) {
            throw new Exception('Avatar must JPG or PNG');
        }

        $filename = 'avatar_' . time() . '_' . rand(100, 999) . '.' . $ext;

        if (!move_uploaded_file($file['tmp_name'], $uploadDir . $filename)) {
            throw new Exception('Failed save avatar');
        }

        // hapus avatar lama
        if ($oldFile && file_exists($uploadDir . $oldFile)) {
            unlink($uploadDir . $oldFile);
        }

        return $filename;
    }

    /**
     * READ: Display seller list page
     */
    public function index()
    {
        $this->render('customer_list', [
            'title'   => 'List customer | iTama Book',
            'menu'    => 'customer',
            'js'      => ['customer.js'],
            'customers' => $this->customerModel->getAll()
        ]);
    }

    /**
     * CREATE: Store new customer
     */
    public function store()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Method not allowed');
            }

            $name     = trim($_POST['name'] ?? '');
            $nik      = trim($_POST['nik'] ?? '');
            $email    = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $address  = trim($_POST['address'] ?? '');
            $phone  = trim($_POST['phone'] ?? '');

            if (!$name || !$nik || !$email || !$password) {
                throw new Exception('Please fill all required fields');
            }

            if ($this->customerModel->findByEmail($email)) {
                $this->json(false, 'Email already exists');
            }

            if ($this->customerModel->findByPhone($phone)) {
                $this->json(false, 'Phone already exists');
            }

            if ($this->customerModel->findByNik($nik)) {
                $this->json(false, 'NIK already exists');
            }

            $avatar = null;
            if (!empty($_FILES['avatar']['name'])) {
                $avatar = $this->uploadAvatar($_FILES['avatar']);
            }

            $result = $this->customerModel->create([
                'name'           => $name,
                'nik'            => $nik,
                'email'          => $email,
                'password'       => password_hash($password, PASSWORD_DEFAULT),
                'address'        => $address,
                'phone'          => $phone,
                'avatar'         => $avatar
            ]);

            if (!$result) {
                throw new Exception('Failed to add customer');
            }

            $this->json(true, 'Customer added successfully');
        } catch (Exception $e) {
            $this->json(false, $e->getMessage());
        }
    }

    /**
     * READ: Get single seller data
     */
    public function show($id)
    {
        try {
            $customer = $this->customerModel->getById($id);

            if (!$customer) {
                throw new Exception('Seller not found');
            }

            $this->json(true, 'OK', $customer);
        } catch (Exception $e) {
            $this->json(false, $e->getMessage());
        }
    }

    /**
     * UPDATE: Update seller data
     */
    public function update($id)
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Method not allowed');
            }

            $customer = $this->customerModel->getById($id);
            if (!$customer) {
                throw new Exception('Customer not found');
            }

            // =====================
            // DUPLICATE VALIDATION
            // =====================

            if (
                !empty($_POST['email']) &&
                $this->customerModel->findByEmailExceptId($_POST['email'], $id)
            ) {
                $this->json(false, 'Email already used by another customer');
                return;
            }

            if (
                !empty($_POST['nik']) &&
                $this->customerModel->findByNikExceptId($_POST['nik'], $id)
            ) {
                $this->json(false, 'NIK already used by another customer');
                return;
            }

            if (
                !empty($_POST['phone']) &&
                $this->customerModel->findByPhoneExceptId($_POST['phone'], $id)
            ) {
                $this->json(false, 'Phone already used by another customer');
                return;
            }

            // =====================
            // BUILD UPDATE DATA
            // =====================

            $data = [];

            foreach (['name', 'nik', 'email', 'address', 'phone'] as $field) {
                if (isset($_POST[$field]) && $_POST[$field] !== '') {
                    $data[$field] = trim($_POST[$field]);
                }
            }

            if (!empty($_POST['password'])) {
                $data['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
            }

            if (!empty($_FILES['avatar']['name'])) {
                $data['avatar'] = $this->uploadAvatar(
                    $_FILES['avatar'],
                    $customer['avatar'] ?? null
                );
            }

            if (empty($data)) {
                throw new Exception('No data to update');
            }

            if (!$this->customerModel->update($id, $data)) {
                throw new Exception('Failed to update customer');
            }

            $this->json(true, 'Customer updated successfully');
        } catch (Exception $e) {
            $this->json(false, $e->getMessage());
        }
    }


    /**
     * DELETE: Remove seller and QRIS photo
     */
    public function destroy($id)
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Method not allowed');
            }

            $customer = $this->customerModel->getById($id);
            if (!$customer) {
                throw new Exception('Customer not found');
            }

            if ($this->customerModel->hasActiveCart($id)) {
                throw new Exception(
                    'This customer cannot be deleted because there are still items in the cart.'
                );
            }

            if (!$this->customerModel->delete($id)) {
                throw new Exception('Failed to delete customer');
            }

            $this->json(true, 'Customer deleted successfully');
        } catch (PDOException $e) {

            // ✅ FK SAFETY NET
            if ($e->getCode() == 23000) {
                $this->json(
                    false,
                    'This customer cannot be deleted because there are still items in the cart.'
                );
                return;
            }

            $this->json(false, 'Database error occurred');
        } catch (Exception $e) {
            $this->json(false, $e->getMessage());
        }
    }
}
