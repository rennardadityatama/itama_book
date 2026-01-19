<?php

class AuthController
{
  public function login()
  {
    require_once '../app/views/auth/login.php';
  }

  public function register()
  {
    require_once '../app/views/auth/register.php';
  }

  public function forgot()
  {
    require_once '../app/views/auth/forgot.php';
  }

  public function reset()
  {
    require_once '../app/views/auth/reset.php';
  }
}
