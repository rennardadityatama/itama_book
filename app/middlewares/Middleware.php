<?php

class Middleware
{
  public static function check()
  {
    if (empty($_SESSION['user'])) {
      header('Location: ' . BASE_URL . 'index.php?c=auth&m=login');
      exit;
    }
  }

  public static function role(array $roles)
  {
    self::check();

    if (!in_array((int)$_SESSION['user']['role'], $roles, true)) {
      http_response_code(403);
      echo "Akses ditolak";
      exit;
    }
  }

  public static function redirectByRole()
  {
    self::check();

    $url = self::getUrlByRole((int)$_SESSION['user']['role']);
    header('Location: ' . $url);
    exit;
  }

  public static function getUrlByRole($role)
  {
    switch ($role) {
      case 1:
        return BASE_URL . 'index.php?c=admin&m=dashboard';
      case 2:
        return BASE_URL . 'index.php?c=seller&m=dashboard';
      case 3:
        return BASE_URL . 'index.php?c=customer&m=dashboard';
      default:
        return BASE_URL . 'index.php?c=auth&m=login';
    }
  }
}
