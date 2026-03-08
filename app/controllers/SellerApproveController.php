<?php

require_once BASE_PATH . '/app/controllers/BaseSellerController.php';
require_once BASE_PATH . '/app/models/OrderModels.php';
require_once BASE_PATH . '/app/models/ProductModels.php';
require_once BASE_PATH . '/app/models/NotificationModels.php';

class SellerApproveController extends BaseSellerController
{
    private $orderModel;
    private $productModel;
    private $notificationModel;

    public function __construct()
    {
        parent::__construct();
        $this->orderModel = new OrderModel();
        $this->productModel = new ProductModel();
        $this->notificationModel = new NotificationModel();
    }

    /**
     * Menampilkan halaman approve (list semua order)
     */
    public function index()
    {
        // Get seller ID dari session
        $sellerId = $_SESSION['user']['id'];

        $perPage = 25;
        $page = $_GET['page'] ?? 1;
        $page = max(1, (int)$page);

        $offset = ($page - 1) * $perPage;

        $orders = $this->orderModel->getOrdersBySellerPaginated($sellerId, $perPage, $offset);
        $totalOrders = $this->orderModel->countOrdersBySeller($sellerId);
        $totalPages = ceil($totalOrders / $perPage);

        // Render view
        $this->render('approve', [
            'title' => 'Approve | iTama Book',
            'menu'  => 'approve',
            'orders' => $orders,
            'currentPage' => $page,
            'totalPages' => $totalPages
        ]);
    }

    /**
     * Approve order (ubah status jadi 'approved')
     */
    public function approveOrder()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'index.php?c=sellerApprove&m=index');
            exit;
        }

        $orderId  = $_POST['order_id'] ?? null;
        $sellerId = $_SESSION['user']['id'];

        if (!$orderId) {
            $_SESSION['toast'] = ['type' => 'danger', 'message' => 'Order not found'];
            header('Location: ' . BASE_URL . 'index.php?c=sellerApprove&m=index');
            exit;
        }

        $order = $this->orderModel->getOrderByIdForSeller($orderId, $sellerId);

        if (!$order) {
            $_SESSION['toast'] = ['type' => 'danger', 'message' => 'Access denied'];
            header('Location: ' . BASE_URL . 'index.php?c=sellerApprove&m=index');
            exit;
        }

        if ($order['status'] !== 'pending') {
            $_SESSION['toast'] = ['type' => 'warning', 'message' => 'Order already processed'];
            header('Location: ' . BASE_URL . 'index.php?c=sellerApprove&m=index');
            exit;
        }

        $db = Database::getInstance();

        try {
            $db->beginTransaction();

            $items = $this->orderModel->getOrderItemsOnly($orderId);

            foreach ($items as $item) {

                $product = $this->productModel->getById($item['product_id']);

                if ($product['stock'] < $item['qty']) {
                    throw new Exception("Stock not enough for {$product['name']}");
                }

                $updated = $this->productModel->updateStock($item['product_id'], $item['qty']);

                if (!$updated) {
                    throw new Exception("Failed reduce stock");
                }
            }

            $this->orderModel->updateOrderStatus($orderId, 'approved');
            $this->orderModel->updatePaymentStatus($orderId, 'paid');

            $this->notificationModel->deleteByOrder($orderId, 'new_order');
            $this->notificationModel->create([
                'user_id' => $sellerId,
                'order_id' => $orderId,
                'type' => 'shipping',
                'title' => 'Input Tracking Number',
                'message' => "Order #" . str_pad($orderId, 6, '0', STR_PAD_LEFT) . " approved. Please input tracking number."
            ]);
            $this->notificationModel->create([
                'user_id' => $order['customer_id'],
                'order_id' => $orderId,
                'type' => 'order_approved',
                'title' => 'Order Approved',
                'message' => "Your order #" . str_pad($orderId, 6, '0', STR_PAD_LEFT) . " has been approved"
            ]);

            $db->commit();

            $_SESSION['toast'] = ['type' => 'success', 'message' => 'Order approved'];
        } catch (Exception $e) {

            $db->rollBack();

            $_SESSION['toast'] = [
                'type' => 'danger',
                'message' => $e->getMessage()
            ];
        }

        header('Location: ' . BASE_URL . 'index.php?c=sellerApprove&m=index');
        exit;
    }

    public function rejectOrder()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'index.php?c=sellerApprove&m=index');
            exit;
        }

        $orderId = $_POST['order_id'] ?? null;
        $sellerId = $_SESSION['user']['id'];

        if (!$orderId) {
            $_SESSION['toast'] = ['type' => 'danger', 'message' => 'Order not found'];
            header('Location: ' . BASE_URL . 'index.php?c=sellerApprove&m=index');
            exit;
        }

        // if (!$this->orderModel->rejectOrder($orderId)) {
        //     $_SESSION['toast'] = [
        //         'type' => 'danger',
        //         'message' => 'Failed to reject order'
        //     ];
        //     header('Location: ' . BASE_URL . 'index.php?c=sellerApprove&m=index');
        //     exit;
        // }

        // Validasi order
        $order = $this->orderModel->getOrderByIdForSeller($orderId, $sellerId);

        if (!$order) {
            $_SESSION['toast'] = ['type' => 'danger', 'message' => 'Access denied'];
            header('Location: ' . BASE_URL . 'index.php?c=sellerApprove&m=index');
            exit;
        }

        // Update status order jadi 'refund'
        $this->orderModel->rejectOrder($orderId);

        $_SESSION['toast'] = ['type' => 'warning', 'message' => 'Order rejected'];
        header('Location: ' . BASE_URL . 'index.php?c=sellerApprove&m=index');
        exit;
    }

    public function inputResi()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'index.php?c=sellerApprove&m=index');
            exit;
        }

        $orderId = $_POST['order_id'] ?? null;
        $resi = trim($_POST['shipping_resi'] ?? '');
        $tracking_link = trim($_POST['tracking_link'] ?? '');
        $sellerId = $_SESSION['user']['id'];

        if (!$orderId || empty($resi)) {
            $_SESSION['toast'] = ['type' => 'danger', 'message' => 'Order ID and Tracking Number required'];
            header('Location: ' . BASE_URL . 'index.php?c=sellerApprove&m=index');
            exit;
        }


        // Validasi order
        $order = $this->orderModel->getOrderByIdForSeller($orderId, $sellerId);

        if (!$order) {
            $_SESSION['toast'] = ['type' => 'danger', 'message' => 'Access denied'];
            header('Location: ' . BASE_URL . 'index.php?c=sellerApprove&m=index');
            exit;
        }

        // Update resi
        $this->orderModel->updateShippingResi($orderId, $resi, $tracking_link);

        // Update shipping status jadi 'shipped'
        $this->orderModel->updateShippingStatus($orderId, 'shipped');
        $this->notificationModel->create([
            'user_id' => $order['customer_id'],
            'order_id' => $orderId,
            'type' => 'shipping',
            'title' => 'Order Shipped',
            'message' => "Your order #" . str_pad($orderId, 6, '0', STR_PAD_LEFT) . " has been shipped"
        ]);

        // Set toast sukses
        $_SESSION['toast'] = ['type' => 'success', 'message' => 'Tracking number and link updated successfully'];
        header('Location: ' . BASE_URL . 'index.php?c=sellerApprove&m=index');
        exit;
    }

    /**
     * View payment proof
     */
    public function viewPaymentProof()
    {
        $orderId = $_GET['order_id'] ?? null;
        $sellerId = $_SESSION['user']['id'];

        if (!$orderId) {
            $_SESSION['toast'] = ['type' => 'danger', 'message' => 'Order not found'];
            header('Location: ' . BASE_URL . 'index.php?c=sellerApprove&m=index');
            exit;
        }

        // Validasi order
        $order = $this->orderModel->getOrderByIdForSeller($orderId, $sellerId);

        if (!$order) {
            $_SESSION['toast'] = ['type' => 'danger', 'message' => 'Access denied'];
            header('Location: ' . BASE_URL . 'index.php?c=sellerApprove&m=index');
            exit;
        }

        // Check apakah ada payment proof
        if (empty($order['payment_proof'])) {
            $_SESSION['toast'] = ['type' => 'warning', 'message' => 'No payment proof available'];
            header('Location: ' . BASE_URL . 'index.php?c=sellerApprove&m=index');
            exit;
        }

        // Redirect ke gambar payment proof
        $paymentProofUrl = BASE_URL . 'uploads/payments/' . $order['payment_proof'];
        header('Location: ' . $paymentProofUrl);
        exit;
    }

    public function deleteExpiredRefund()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit;
        }

        $this->orderModel->autoDeleteExpiredRefundOrders();
        echo json_encode(['status' => 'ok']);
    }

    public function deleteRefundOrder()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'index.php?c=sellerApprove&m=index');
            exit;
        }

        $orderId = $_POST['order_id'] ?? null;
        $sellerId = $_SESSION['user']['id'];

        if (!$orderId) {
            $_SESSION['toast'] = ['type' => 'danger', 'message' => 'Order not found'];
            header('Location: ' . BASE_URL . 'index.php?c=sellerApprove&m=index');
            exit;
        }

        $order = $this->orderModel->getOrderByIdForSeller($orderId, $sellerId);

        if (!$order || $order['status'] !== 'refund') {
            $_SESSION['toast'] = ['type' => 'danger', 'message' => 'Invalid action'];
            header('Location: ' . BASE_URL . 'index.php?c=sellerApprove&m=index');
            exit;
        }

        if (strtotime($order['refunded_at']) > time() - 60) {
            $_SESSION['toast'] = ['type' => 'warning', 'message' => 'Please wait 1 minute before deleting'];
            header('Location: ' . BASE_URL . 'index.php?c=sellerApprove&m=index');
            exit;
        }

        $this->orderModel->deleteOrderById($orderId);

        $_SESSION['toast'] = ['type' => 'success', 'message' => 'Refunded order deleted'];
        header('Location: ' . BASE_URL . 'index.php?c=sellerApprove&m=index');
        exit;
    }
}
