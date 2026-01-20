<?php
require_once '../app/middlewares/auth.php';

class CustomerController
{
  public function dashboard()
  {
    Middleware::role([3]);
    require_once BASE_PATH . '/app/views/customer/dashboard.php';
  }
}
