<?php
// File: app/controllers/SellerApproveController.php

require_once BASE_PATH . '/app/controllers/BaseSellerController.php';
require_once BASE_PATH . '/app/models/OrderModels.php';

class SellerApproveController extends BaseSellerController
{
    private $orderModel;

    public function __construct()
    {
        parent::__construct();
        $this->orderModel = new OrderModel();
    }

    /**
     * Menampilkan halaman approve (list semua order)
     */
    public function index()
    {
        // Get seller ID dari session
        $sellerId = $_SESSION['user']['id'];

        // Get all orders untuk seller ini
        $orders = $this->orderModel->getOrdersBySeller($sellerId);

        // Render view
        $this->render('approve', [
            'orders' => $orders
        ]);
    }

    /**
     * Approve order (ubah status jadi 'approved')
     */
    public function approveOrder()
    {
        // Validasi method POST
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

        // Validasi: apakah order ini milik seller yang login?
        $order = $this->orderModel->getOrderByIdForSeller($orderId, $sellerId);

        if (!$order) {
            $_SESSION['toast'] = ['type' => 'danger', 'message' => 'Access denied'];
            header('Location: ' . BASE_URL . 'index.php?c=sellerApprove&m=index');
            exit;
        }

        // Update status order jadi 'approved'
        $this->orderModel->updateOrderStatus($orderId, 'approved');

        // Update payment status jadi 'completed'
        $this->orderModel->updatePaymentStatus($orderId, 'completed');

        $_SESSION['toast'] = ['type' => 'success', 'message' => 'Order approved successfully'];
        header('Location: ' . BASE_URL . 'index.php?c=sellerApprove&m=index');
        exit;
    }

    /**
     * Reject order (ubah status jadi 'refund')
     */
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

        // Validasi order
        $order = $this->orderModel->getOrderByIdForSeller($orderId, $sellerId);

        if (!$order) {
            $_SESSION['toast'] = ['type' => 'danger', 'message' => 'Access denied'];
            header('Location: ' . BASE_URL . 'index.php?c=sellerApprove&m=index');
            exit;
        }

        // Update status order jadi 'refund'
        $this->orderModel->updateOrderStatus($orderId, 'refund');

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
}
