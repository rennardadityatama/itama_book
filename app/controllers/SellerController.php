<?php
require_once '../app/middlewares/auth.php';

class SellerController
{
  public function dashboard()
  {
    Middleware::role([2]);
    require_once BASE_PATH . 'app/views/seller/dashboard.php';
  }
}
