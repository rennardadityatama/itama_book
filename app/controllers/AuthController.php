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
      $this->json(false, 'CSRF token tidak valid');
    }

    // Ambil input email & password
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!$email || !$password) {
      $this->json(false, 'Email dan password wajib diisi');
    }

    // Cek login melalui User model
    $user = $this->user->login($email, $password);

    if (!$user) {
      $this->json(false, 'Email atau password salah');
    }

    // Regenerate session ID untuk keamanan
    session_regenerate_id(true);

    // Simpan data user di session
    $_SESSION['user'] = [
      'id'    => $user['id'],
      'name'  => $user['name'],
      'email' => $user['email'],
      'role'  => (int)$user['role'], // pastikan integer
    ];

    // Update status user jadi online
    $this->user->setStatus($user['id'], 'online');

    // Hapus token CSRF lama
    Csrf::destroy();

    // Tentukan URL redirect berdasarkan role
    $redirectUrl = Middleware::getUrlByRole((int)$user['role']);

    $this->json(true, 'Login berhasil', [
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
      $this->json(false, 'CSRF token tidak valid');
    }

    if ($_POST['password'] !== $_POST['confirm_password']) {
      $this->json(false, 'Password tidak sama');
    }

    if ($this->user->findByEmail($_POST['email'])) {
      $this->json(false, 'Email sudah terdaftar');
    }

    $this->user->register([
      'name'     => $_POST['name'],
      'email'    => $_POST['email'],
      'password' => $_POST['password'],
      'address'  => $_POST['address'],
      'role'     => $_POST['role'],
      'status'   => 'offline'
    ]);

    $this->json(true, 'Registrasi berhasil', [
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
      $this->json(false, 'CSRF token tidak valid');
    }

    $user = $this->user->findByEmail($_POST['email']);
    if (!$user) {
      $this->json(false, 'Email tidak terdaftar');
    }

    $token  = bin2hex(random_bytes(32));
    $expiry = date('Y-m-d H:i:s', strtotime('+15 minutes'));

    $this->user->saveResetToken($user['email'], $token, $expiry);

    $link = BASE_URL . "index.php?c=auth&m=reset&token=$token";

    $body = "
    <p>Klik link berikut untuk reset password:</p>
    <a href='$link'>$link</a>
    <p>Link berlaku 15 menit</p>
  ";

    if (!sendMail($user['email'], 'Reset Password', $body)) {
      $this->json(false, 'Gagal mengirim email');
    }

    $this->json(true, 'Link reset password berhasil dikirim ke email');
  }

  /* =========================
      RESET PASSWORD
  ========================= */
  public function reset()
  {
    $token = $_GET['token'] ?? null;
    $user  = $this->user->findByToken($token);

    if (!$user) {
      die('Token tidak valid atau kadaluarsa');
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

      if (!Csrf::check($_POST['csrf_token'] ?? '')) {
        $this->json(false, 'CSRF token tidak valid');
      }

      if ($_POST['password'] !== $_POST['confirm_password']) {
        $this->json(false, 'Password tidak sama');
      }

      $this->user->updatePassword($user['id'], $_POST['password']);
      Csrf::destroy();

      $this->json(true, 'Password berhasil diubah');
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
