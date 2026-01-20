<?php
require_once BASE_PATH . '/app/models/UserModels.php';
require_once BASE_PATH . '/app/middlewares/Middleware.php';

class AdminController
{
  public function dashboard()
  {
    Middleware::check();
    Middleware::redirectByRole();
  }
}
