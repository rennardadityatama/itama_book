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

    switch ((int)$_SESSION['user']['role']) {
      case 1:
        header('Location: ' . BASE_URL . 'index.php?c=admin&m=dashboard');
        break;

      case 2:
        header('Location: ' . BASE_URL . 'index.php?c=seller&m=dashboard');
        break;

      case 3:
        header('Location: ' . BASE_URL . 'index.php?c=customer&m=dashboard');
        break;

      default:
        session_destroy();
        header('Location: ' . BASE_URL . 'index.php?c=auth&m=login');
    }
    exit;
  }
}
