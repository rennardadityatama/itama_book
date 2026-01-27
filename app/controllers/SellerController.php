<?php
require_once BASE_PATH . '/app/controllers/BaseSellerController.php';
require_once BASE_PATH . '/app/models/CategoryModels.php';
require_once BASE_PATH . '/app/models/Database.php';


class SellerController extends BaseSellerController
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
