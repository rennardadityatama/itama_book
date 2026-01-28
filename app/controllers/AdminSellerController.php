<?php
require_once BASE_PATH . '/app/controllers/BaseAdminController.php';
require_once BASE_PATH . '/app/models/SellerModels.php';

class AdminSellerController extends BaseAdminController
{
    private $sellerModel;

    public function __construct()
    {
        parent::__construct();
        $this->sellerModel = new SellerModel();
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

    /**
     * Upload QRIS photo with validation and auto delete old file
     */
    private function uploadQris($file, $oldFile = null)
    {
        $allowedExt = ['jpg', 'jpeg', 'png', 'webp'];
        $maxSize = 2 * 1024 * 1024; // 2MB max size
        $uploadDir = BASE_PATH . '/public/uploads/qris/';

        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('File upload failed');
        }

        if ($file['size'] > $maxSize) {
            throw new Exception('Image size must be under 2MB');
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowedExt)) {
            throw new Exception('Invalid image format (JPG, PNG, WEBP only)');
        }

        $filename = 'qris_' . time() . '_' . rand(100, 999) . '.' . $ext;

        if (!move_uploaded_file($file['tmp_name'], $uploadDir . $filename)) {
            throw new Exception('Failed to save image');
        }

        // Auto delete old image if exists
        if ($oldFile && file_exists($uploadDir . $oldFile)) {
            unlink($uploadDir . $oldFile);
        }

        return $filename;
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
        $this->render('seller_list', [
            'title'   => 'List Seller | iTama Book',
            'menu'    => 'seller',
            'js'      => ['seller.js'],
            'sellers' => $this->sellerModel->getAll()
        ]);
    }

    /**
     * CREATE: Store new seller
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
            $account  = trim($_POST['account_number'] ?? '');

            if (!$name || !$nik || !$email || !$password) {
                throw new Exception('Please fill all required fields');
            }

            if ($this->sellerModel->findByEmail($email)) {
                $this->json(false, 'Email already exists');
            }

            if ($this->sellerModel->findByNik($nik)) {
                $this->json(false, 'NIK already exists');
            }

            if ($this->sellerModel->findByACNumber($account)) {
                $this->json(false, 'Account Number already exists');
            }

            $qrisPhoto = null;
            if (!empty($_FILES['qris_photo']['name'])) {
                $qrisPhoto = $this->uploadQris($_FILES['qris_photo']);
            }

            $avatar = null;
            if (!empty($_FILES['avatar']['name'])) {
                $avatar = $this->uploadAvatar($_FILES['avatar']);
            }

            $result = $this->sellerModel->create([
                'name'           => $name,
                'nik'            => $nik,
                'email'          => $email,
                'password'       => password_hash($password, PASSWORD_DEFAULT),
                'address'        => $address,
                'account_number' => $account,
                'qris_photo'     => $qrisPhoto,
                'avatar'         => $avatar
            ]);

            if (!$result) {
                throw new Exception('Failed to add seller');
            }

            $this->json(true, 'Seller added successfully');
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
            $seller = $this->sellerModel->getById($id);

            if (!$seller) {
                throw new Exception('Seller not found');
            }

            $this->json(true, 'OK', $seller);
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

            $seller = $this->sellerModel->getById($id);
            if (!$seller) {
                throw new Exception('Seller not found');
            }

            if (!empty($_POST['email'])) {
                if ($this->sellerModel->findByEmailExceptId($_POST['email'], $id)) {
                    $this->json(false, 'Email already used by another seller');
                }
            }

            if (!empty($_POST['nik'])) {
                if ($this->sellerModel->findByNikExceptId($_POST['nik'], $id)) {
                    $this->json(false, 'NIK already used by another seller');
                }
            }

            if (!empty($_POST['account_number'])) {
                if ($this->sellerModel->findByACNumberExceptId($_POST['account_number'], $id)) {
                    $this->json(false, 'Account Number already used by another seller');
                }
            }

            $data = [];

            // Update only if filled
            if (isset($_POST['name']) && $_POST['name'] !== '') {
                $data['name'] = trim($_POST['name']);
            }

            if (isset($_POST['nik']) && $_POST['nik'] !== '') {
                $data['nik'] = trim($_POST['nik']);
            }

            if (isset($_POST['email']) && $_POST['email'] !== '') {
                $data['email'] = trim($_POST['email']);
            }

            if (isset($_POST['address'])) {
                $data['address'] = trim($_POST['address']);
            }

            if (isset($_POST['account_number'])) {
                $data['account_number'] = trim($_POST['account_number']);
            }

            // Optional password
            if (!empty($_POST['password'])) {
                $data['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
            }

            // Optional QRIS
            if (!empty($_FILES['qris_photo']['name'])) {
                $data['qris_photo'] = $this->uploadQris(
                    $_FILES['qris_photo'],
                    $seller['qris_photo'] ?? null
                );
            }

            if (!empty($_FILES['avatar']['name'])) {
                $data['avatar'] = $this->uploadAvatar(
                    $_FILES['avatar'],
                    $seller['avatar'] ?? null
                );
            }

            if (!$this->sellerModel->update($id, $data)) {
                throw new Exception('Failed to update seller');
            }

            $this->json(true, 'Seller updated successfully');
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

            $seller = $this->sellerModel->getById($id);
            if (!$seller) {
                throw new Exception('Seller not found');
            }

            // Delete QRIS image if exists
            if (!empty($seller['qris_photo'])) {
                $path = BASE_PATH . '/public/uploads/qris/' . $seller['qris_photo'];
                if (file_exists($path)) {
                    unlink($path);
                }
            }

            if (!$this->sellerModel->delete($id)) {
                throw new Exception('Failed to delete seller');
            }

            $this->json(true, 'Seller deleted successfully');
        } catch (Exception $e) {
            $this->json(false, $e->getMessage());
        }
    }
}
