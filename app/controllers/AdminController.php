<?php
require_once BASE_PATH . '/app/controllers/BaseAdminController.php';
require_once BASE_PATH . '/app/models/CategoryModels.php';
require_once BASE_PATH . '/app/models/Database.php';

class AdminController extends BaseAdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    private function json($status, $message, $data = [])
    {
        header('Content-Type: application/json');
        echo json_encode([
            'status'  => $status,
            'message' => $message,
            'data'    => $data
        ]);
        exit;
    }

    public function dashboard()
    {
        $this->render('dashboard', [
            'title' => 'Dashboard | iTama Book',
            'menu'  => 'dashboard',
            'js'    => ['dashboard/default.js']
        ]);
    }

    public function customer()
    {
        $this->render('customer_list', [
            'title' => 'List Customer | iTama Book',
            'menu'  => 'customer'
        ]);
    }

    public function profile()
    {
        $user = $this->user->findById($_SESSION['user']['id']);

        $this->render('profile', [
            'title' => 'Profile User | iTama Book',
            'menu'  => 'profile',
            'user'  => $user,
        ]);
    }

    public function updateProfile()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(false, 'Invalid request');
        }

        if (!Csrf::check($_POST['csrf_token'] ?? '')) {
            $this->json(false, 'Invalid CSRF token');
        }

        $id = $_SESSION['user']['id'];

        $oldUser = $this->user->findById($id);
        if (!$oldUser) {
            $this->json(false, 'User not found');
        }

        $data = [];

        if (!empty($_POST['name']) && $_POST['name'] !== $oldUser['name']) {
            $data['name'] = trim($_POST['name']);
        }

        if (!empty($_POST['email']) && $_POST['email'] !== $oldUser['email']) {
            $data['email'] = trim($_POST['email']);
        }

        if (!empty($_POST['nik']) && $_POST['nik'] !== $oldUser['nik']) {
            $data['nik'] = trim($_POST['nik']);
        }

        if (!empty($_POST['address']) && $_POST['address'] !== $oldUser['address']) {
            $data['address'] = trim($_POST['address']);
        }

        if (!empty($_POST['password'])) {
            if ($_POST['password'] !== $_POST['confirm_password']) {
                $this->json(false, 'Password confirmation does not match');
            }

            $data['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
        }

        if (!empty($_FILES['avatar']['name'])) {
            $file = $_FILES['avatar'];

            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            if (!in_array($file['type'], $allowedTypes)) {
                $this->json(false, 'Invalid image type (jpg, jpeg, png only)');
            }

            if ($file['size'] > 2 * 1024 * 1024) {
                $this->json(false, 'Image size must be under 2MB');
            }

            // generate filename: photo_{id}_{random5}.jpg
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $random = substr(bin2hex(random_bytes(3)), 0, 5);
            $filename = "{$id}_{$random}.{$ext}";

            $uploadDir  = BASE_PATH . '/public/uploads/avatars/';
            $uploadPath = $uploadDir . $filename;

            if (!empty($_SESSION['user']['avatar'])) {
                $oldAvatar = $uploadDir . $_SESSION['user']['avatar'];
                if (file_exists($oldAvatar)) {
                    unlink($oldAvatar);
                }
            }

            if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
                $this->json(false, 'Failed to upload avatar');
            }

            $data['avatar'] = $filename;
            $_SESSION['user']['avatar'] = $filename;
        }

        $updated = $this->user->updateProfile($id, $data);
        if (!$updated) {
            $this->json(false, 'No data changed');
        }

        foreach ($data as $key => $val) {
            $_SESSION['user'][$key] = $val;
        }

        $this->json(true, 'Profile updated successfully');
    }
}
