<?php
require_once BASE_PATH . '/app/controllers/BaseAdminController.php';
require_once BASE_PATH . '/app/models/CategoryModels.php';
require_once BASE_PATH . '/app/models/Database.php';


class SellerController extends BaseAdminController
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
