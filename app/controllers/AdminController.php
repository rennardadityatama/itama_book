<?php
require_once BASE_PATH . '/app/models/UserModels.php';
require_once BASE_PATH . '/app/middlewares/Middleware.php';

class AdminController
{
  public function dashboard()
  {
    Middleware::check();
    if (!Middleware::isRole(1)) {
            echo "Hanya admin yang bisa mengakses halaman ini";
            exit;
        }


    require_once BASE_PATH . '/app/views/admin/dashboard.php';
  }
}
