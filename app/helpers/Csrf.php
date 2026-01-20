<?php

class Csrf
{
  private static function startSession()
  {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }
  }

  // Digunakan di VIEW
  public static function token()
  {
    self::startSession();

    if (empty($_SESSION['csrf_token'])) {
      $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
  }

  // Digunakan di CONTROLLER
  public static function check($token)
  {
    self::startSession();

    return isset($_SESSION['csrf_token']) &&
           hash_equals($_SESSION['csrf_token'], $token);
  }

  // Setelah sukses submit
  public static function destroy()
  {
    self::startSession();
    unset($_SESSION['csrf_token']);
  }
}
