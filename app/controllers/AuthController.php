<?php

require_once BASE_PATH . '/app/models/UserModels.php';
require_once BASE_PATH . '/app/helpers/Csrf.php';
require_once BASE_PATH . '/app/middlewares/Middleware.php';


class AuthController
{
  private $user;

  public function __construct()
  {
    $this->user = new User();
  }

  /* =========================
      RESPONSE JSON
  ========================= */
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

  /* =========================
      LOGIN
  ========================= */
  public function login()
  {
    // JIKA SUDAH LOGIN
    if (isset($_SESSION['user'])) {
      $role = (int) $_SESSION['user']['role'];

      // Redirect sesuai role
      $redirectUrl = Middleware::getUrlByRole($role);
      header('Location: ' . $redirectUrl);
      exit;
    }

    require_once BASE_PATH . '/app/views/auth/login.php';
  }

  public function loginProcess()
  {
    // Pastikan request POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      $this->json(false, 'Invalid request');
    }

    if (!Csrf::check($_POST['csrf_token'] ?? '')) {
      $this->json(false, 'Invalid CSRF token');
    }

    // Ambil input email & password
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!$email || !$password) {
      $this->json(false, 'Email and password is required');
    }

    // Cek login melalui User model
    $user = $this->user->login($email, $password);

    if (!$user) {
      $this->json(false, 'Invalid email or password');
    }

    // Regenerate session ID untuk keamanan
    session_regenerate_id(true);

    // Simpan data user di session
    $_SESSION['user'] = [
      'id'    => $user['id'],
      'name'  => $user['name'],
      'nik'  => $user['nik'],
      'address'  => $user['address'],
      'email' => $user['email'],
      'role'  => (int)$user['role'],
      'role_name' => $user['role_name'],
      'status' => $user['status'],
      'avatar' => $user['avatar'],
    ];

    // Update status user jadi online
    $this->user->setStatus($user['id'], 'online');

    // Hapus token CSRF lama
    Csrf::destroy();

    // Tentukan URL redirect berdasarkan role
    $redirectUrl = Middleware::getUrlByRole((int)$user['role']);

    $this->json(true, 'Successfull to Login', [
      'redirect' => $redirectUrl
    ]);
  }

  /* =========================
      REGISTER
  ========================= */
  public function register()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      require_once '../app/views/auth/register.php';
      return;
    }

    if (!Csrf::check($_POST['csrf_token'] ?? '')) {
      $this->json(false, 'Invalid CSRF token');
    }

    if ($_POST['password'] !== $_POST['confirm_password']) {
      $this->json(false, 'Incorect confirm password');
    }

    if ($this->user->findByEmail($_POST['email'])) {
      $this->json(false, 'Email has been already');
    }

    if ($this->user->findByNik($_POST['nik'])) {
      $this->json(false, 'NIK has been already');
    }

    $role = $_POST['role'];

    // Default values for optional seller fields
    $account_number = null;
    $qris_photo = null;

    if ($role == '2') { // Seller
      $account_number = trim($_POST['account_number'] ?? '');
      if (empty($account_number)) {
        $this->json(false, 'Account Number is required for Seller');
      }

      if (empty($_FILES['qris_photo']['name'])) {
        $this->json(false, 'QRIS Photo is required for Seller');
      } else {
        $file = $_FILES['qris_photo'];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];

        if (!in_array($file['type'], $allowedTypes)) {
          $this->json(false, 'QRIS photo must be jpg, jpeg, or png');
        }

        if ($file['size'] > 2 * 1024 * 1024) {
          $this->json(false, 'QRIS photo size must be under 2MB');
        }

        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $random = substr(bin2hex(random_bytes(3)), 0, 5);
        $filename = "qris_{$random}.{$ext}";
        $uploadDir  = BASE_PATH . '/public/uploads/qris/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
        $uploadPath = $uploadDir . $filename;

        if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
          $this->json(false, 'Failed to upload QRIS photo');
        }

        $qris_photo = $filename;
      }
    }

    $this->user->register([
      'name'     => $_POST['name'],
      'nik'     => $_POST['nik'],
      'email'    => $_POST['email'],
      'password' => $_POST['password'],
      'address'  => $_POST['address'],
      'role'     => $_POST['role'],
      'account_number' => $account_number,
      'qris_photo'     => $qris_photo,
      'status'   => 'offline'
    ]);

    $this->json(true, 'Account Created Successfully', [
      'redirect' => BASE_URL . 'index.php?c=auth&m=login'
    ]);
  }

  /* =========================
      FORGOT PASSWORD
  ========================= */
  public function forgot()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      require_once '../app/views/auth/forgot.php';
      return;
    }

    if (!Csrf::check($_POST['csrf_token'] ?? '')) {
      $this->json(false, 'Invalid CSRF token');
    }

    $user = $this->user->findByEmail($_POST['email']);
    if (!$user) {
      $this->json(false, 'Email not registered');
    }

    $token  = bin2hex(random_bytes(32));
    $expiry = date('Y-m-d H:i:s', strtotime('+10 minutes'));

    $this->user->saveResetToken($user['email'], $token, $expiry);

    $link = BASE_URL . "index.php?c=auth&m=reset&token=$token";

    $body = "
    <p>Klik link berikut untuk reset password:</p>
    <a href='$link'>$link</a>
    <p>Link berlaku 10 menit</p>
  ";

    if (!sendMail($user['email'], 'Reset Password', $body)) {
      $this->json(false, 'Failed to send email');
    }

    $this->json(true, 'Password reset link successfully sent to email');
  }

  /* =========================
      RESET PASSWORD
  ========================= */
  public function reset()
  {
    $token = $_GET['token'] ?? null;
    $user  = $this->user->findByToken($token);

    if (!$user) {
      die('Invalid Token');
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

      if (!Csrf::check($_POST['csrf_token'] ?? '')) {
        $this->json(false, 'Invalid CSRF Token');
      }

      if ($_POST['password'] !== $_POST['confirm_password']) {
        $this->json(false, 'Incorect Confirm Password');
      }

      $this->user->updatePassword($user['id'], $_POST['password']);
      Csrf::destroy();

      $this->json(true, 'Password has been changed');
      exit;
    }

    require_once '../app/views/auth/reset.php';
  }

  /* =========================
      LOGOUT
  ========================= */
  public function logout()
  {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      http_response_code(405);
      exit('Method Not Allowed');
    }

    if (!Csrf::check($_POST['csrf_token'] ?? '')) {
      http_response_code(403);
      exit('CSRF token tidak valid');
    }

    if (isset($_SESSION['user'])) {
      $this->user->setStatus($_SESSION['user']['id'], 'offline');
    }

    session_destroy();

    header('Location: ' . BASE_URL . 'index.php?c=auth&m=login');
    exit;
  }
}
