<?php

require_once BASE_PATH . '/app/controllers/BaseCustomerController.php';
require_once BASE_PATH . '/app/middlewares/Middleware.php';

class CustomerController extends BaseCustomerController
{
  public function __construct()
  {
    parent::__construct();
  }

  private function json($status, $message, $data = [])
  {
    header('Content-Type: application/json');
    echo json_encode([
      'status'  => $status,
      'message' => $message,
      'data'    => $data
    ]);
    exit;
  }

  public function dashboard()
  {
    $this->render('dashboard', [
      'title' => 'Dashboard | iTama Book',
      'menu'  => 'dashboard',
      'js'    => ['dashboard/default.js']
    ]);
  }
}
