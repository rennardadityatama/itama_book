<?php

require_once '../app/models/UserModels.php';

class AuthController
{
  private $user;

  public function __construct()
  {
    session_start();
    $this->user = new User();
  }

  // ================= LOGIN =================
  public function login()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $user = $this->user->findByEmail($_POST['email']);

      if (!$user || !password_verify($_POST['password'], $user['password'])) {
        $_SESSION['error'] = 'Email atau password salah';
        header('Location: ' . BASE_URL . 'index.php?c=auth&m=login');
        exit;
      }

      $_SESSION['user'] = [
        'id' => $user['id'],
        'name' => $user['name'],
        'role' => $user['role']
      ];

      header('Location: ' . BASE_URL . 'index.php?c=dashboard&m=index');
      exit;
    }

    require_once '../app/views/auth/login.php';
  }

  // ================= REGISTER =================
  public function register()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      if ($this->user->findByEmail($_POST['email'])) {
        $_SESSION['error'] = 'Email sudah terdaftar';
        header('Location: ' . BASE_URL . 'index.php?c=auth&m=register');
        exit;
      }

      $this->user->register($_POST);
      header('Location: ' . BASE_URL . 'index.php?c=auth&m=login');
      exit;
    }

    require_once '../app/views/auth/register.php';
  }

  // ================= FORGOT =================
  public function forgot()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $user = $this->user->findByEmail($_POST['email']);

      if (!$user) {
        die('Email tidak terdaftar');
      }

      $token  = bin2hex(random_bytes(32));
      $expiry = date('Y-m-d H:i:s', strtotime('+15 minutes'));

      $this->user->saveResetToken($_POST['email'], $token, $expiry);

      $this->sendResetEmail($_POST['email'], $token);

      echo "Link reset password telah dikirim ke email";
      exit;
    }

    require_once '../app/views/auth/forgot.php';
  }

  // ================= RESET =================
  public function reset()
  {
    $token = $_GET['token'] ?? null;
    $user = $this->user->findByToken($token);

    if (!$user) {
      die('Token tidak valid atau kadaluarsa');
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $this->user->updatePassword($user['id'], $_POST['password']);
      header('Location: ' . BASE_URL . 'index.php?c=auth&m=login');
      exit;
    }

    require_once '../app/views/auth/reset.php';
  }

  // ================= EMAIL =================
  private function sendResetEmail($email, $token)
  {
    require_once '../vendor/phpmailer/PHPMailer.php';
    require_once '../vendor/phpmailer/SMTP.php';
    require_once '../vendor/phpmailer/Exception.php';

    // $mail = new PHPMailer\PHPMailer\PHPMailer(true);

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'EMAIL_KAMU@gmail.com';
    $mail->Password = 'APP_PASSWORD_GMAIL';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('EMAIL_KAMU@gmail.com', 'iTAMA Book');
    $mail->addAddress($email);

    $link = BASE_URL . "index.php?c=auth&m=reset&token=$token";

    $mail->isHTML(true);
    $mail->Subject = 'Reset Password';
    $mail->Body = "Klik link berikut untuk reset password:<br><a href='$link'>$link</a>";

    $mail->send();
  }
}
