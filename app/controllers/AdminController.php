<?php
require_once '../app/middlewares/auth.php';

class AdminController
{
  public function dashboard()
  {
    Middleware::role([1]);
    require_once BASE_PATH . '/app/views/admin/dashboard.php';
  }
}
