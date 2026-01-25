<?php

class Csrf
{
  // Digunakan di VIEW
  public static function token()
  {

    if (empty($_SESSION['csrf_token'])) {
      $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
  }

  // Digunakan di CONTROLLER
  public static function check($token)
  {
    return isset($_SESSION['csrf_token']) &&
      hash_equals($_SESSION['csrf_token'], $token);
  }

  // Setelah sukses submit
  public static function destroy()
  {
    unset($_SESSION['csrf_token']);
  }
}
