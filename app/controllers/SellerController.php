<?php
require_once BASE_PATH . '/app/controllers/BaseSellerController.php';
require_once BASE_PATH . '/app/models/OrderModels.php';
require_once BASE_PATH . '/app/models/ProductModels.php';
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


  private function getTotalRevenue($sellerId)
  {
    $db = Database::getInstance();
    $stmt = $db->prepare("
        SELECT COALESCE(SUM(total_amount), 0) as total 
        FROM orders 
        WHERE seller_id = :seller_id 
        AND payment_status = 'paid'
    ");
    $stmt->execute([':seller_id' => $sellerId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total'];
  }

  private function getTotalProducts($sellerId)
  {
    $db = Database::getInstance();
    $stmt = $db->prepare("
        SELECT COUNT(*) as total 
        FROM products 
        WHERE seller_id = :seller_id 
        AND is_active = 1
    ");
    $stmt->execute([':seller_id' => $sellerId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total'];
  }

  private function getTotalProductsSold($sellerId)
  {
    $db = Database::getInstance();
    $stmt = $db->prepare("
        SELECT COALESCE(SUM(oi.qty), 0) as total 
        FROM order_items oi
        JOIN orders o ON o.id = oi.order_id
        WHERE o.seller_id = :seller_id
        AND o.payment_status = 'paid'
    ");
    $stmt->execute([':seller_id' => $sellerId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total'];
  }

  public function dashboard()
  {
    $sellerId = $_SESSION['user']['id'];

    $productModel = new ProductModel();
    $orderModel = new OrderModel();

    $totalRevenue = $this->getTotalRevenue($sellerId);

    $totalProducts = $this->getTotalProducts($sellerId);

    $totalSold = $this->getTotalProductsSold($sellerId);

    $bestSellingProducts = $productModel->getBestSellingProductsBySeller($sellerId, 5);
    $recentOrders        = $orderModel->getRecentOrdersBySeller($sellerId, 5);
    $financeSummary = $orderModel->getRevenueCostMargin($sellerId);

    $this->render('dashboard', [
      'title' => 'Dashboard | iTama Book',
      'menu'  => 'dashboard',
      'js'    => ['dashboard/default.js'],
      'totalRevenue' => $totalRevenue,
      'totalProducts' => $totalProducts,
      'totalSold' => $totalSold,
      'bestSellingProducts' => $bestSellingProducts,
      'recentOrders'        => $recentOrders,
      'financeSummary' => $financeSummary
    ]);
  }

  public function profile()
  {
    $user = $this->user->findById($_SESSION['user']['id']);

    $this->render('profile', [
      'title' => 'Profile User | iTama Book',
      'menu'  => 'profile',
      'user'  => $user,
    ]);
  }

  public function updateProfile()
  {
    if ($_SESSION['user']['role'] != 2) { // 2 = seller
      $this->json(false, 'Forbidden access');
    }

    header('Content-Type: application/json');

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      $this->json(false, 'Invalid request');
    }

    if (!Csrf::check($_POST['csrf_token'] ?? '')) {
      $this->json(false, 'Invalid CSRF token');
    }

    $id = $_SESSION['user']['id'];

    $oldUser = $this->user->findById($id);
    if (!$oldUser) {
      $this->json(false, 'User not found');
    }

    $data = [];

    if (!empty($_POST['name']) && $_POST['name'] !== $oldUser['name']) {
      $data['name'] = trim($_POST['name']);
    }

    if (!empty($_POST['email']) && $_POST['email'] !== $oldUser['email']) {
      if ($this->user->findByEmail($_POST['email'])) {
        $this->json(false, 'Email already in used');
      }
      $data['email'] = trim($_POST['email']);
    }

    if (!empty($_POST['nik']) && $_POST['nik'] !== $oldUser['nik']) {
      if ($this->user->findByNik($_POST['nik'])) {
        $this->json(false, 'NIK already registered');
      }
      $data['nik'] = trim($_POST['nik']);
    }

    if (!empty($_POST['address']) && $_POST['address'] !== $oldUser['address']) {
      $data['address'] = trim($_POST['address']);
    }

    if (
      !empty($_POST['account_number']) &&
      $_POST['account_number'] !== $oldUser['account_number']
    ) {
      $data['account_number'] = trim($_POST['account_number']);
    }

    if (!empty($_POST['password'])) {
      if ($_POST['password'] !== $_POST['confirm_password']) {
        $this->json(false, 'Password confirmation does not match');
      }

      $data['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
    }

    if (!empty($_FILES['avatar']['name'])) {
      $file = $_FILES['avatar'];

      $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
      if (!in_array($file['type'], $allowedTypes)) {
        $this->json(false, 'Invalid image type (jpg, jpeg, png only)');
      }

      if ($file['size'] > 2 * 1024 * 1024) {
        $this->json(false, 'Image size must be under 2MB');
      }

      // generate filename: photo_{id}_{random5}.jpg
      $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
      $random = substr(bin2hex(random_bytes(3)), 0, 5);
      $filename = "{$id}_{$random}.{$ext}";

      $uploadDir  = BASE_PATH . '/public/uploads/avatars/';
      $uploadPath = $uploadDir . $filename;

      if (!empty($_SESSION['user']['avatar'])) {
        $oldAvatar = $uploadDir . $_SESSION['user']['avatar'];
        if (file_exists($oldAvatar)) {
          unlink($oldAvatar);
        }
      }

      if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
        $this->json(false, 'Failed to upload avatar');
      }

      $data['avatar'] = $filename;
      $_SESSION['user']['avatar'] = $filename;
    }

    if (!empty($_FILES['qris_photo']['name'])) {
      $file = $_FILES['qris_photo'];

      $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
      if (!in_array($file['type'], $allowedTypes)) {
        $this->json(false, 'Invalid QRIS image type');
      }

      if ($file['size'] > 2 * 1024 * 1024) {
        $this->json(false, 'QRIS image must be under 2MB');
      }

      $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
      $filename = 'qris_' . $id . '_' . time() . '.' . $ext;
      $uploadDir = BASE_PATH . '/public/uploads/qris/';
      $uploadPath = $uploadDir . $filename;

      // hapus QRIS lama
      if (!empty($oldUser['qris_photo'])) {
        $oldFile = $uploadDir . $oldUser['qris_photo'];
        if (file_exists($oldFile)) unlink($oldFile);
      }

      if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
        $this->json(false, 'Failed upload QRIS image');
      }

      $data['qris_photo'] = $filename;
    }

    $updated = $this->user->updateProfile($id, $data);
    if (!$updated) {
      $this->json(false, 'No data changed');
    }

    foreach ($data as $key => $val) {
      $_SESSION['user'][$key] = $val;
    }

    $this->json(true, 'Profile updated successfully');
  }

  public function faq()
  {
    $user = $this->user->findById($_SESSION['user']['id']);

    $this->render('faq', [
      'title' => 'FAQ | iTama Book',
      'menu'  => 'faq',
      'user'  => $user,
    ]);
  }
}
