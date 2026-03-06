<?php

require_once BASE_PATH . '/app/controllers/BaseCustomerController.php';
require_once BASE_PATH . '/app/models/CartModels.php';
require_once BASE_PATH . '/app/models/ProductModels.php';
require_once BASE_PATH . '/app/models/OrderModels.php';

class CustomerOrderController extends BaseCustomerController
{
    private $orderModel;
    private $cartModel;
    private $productModel;

    public function __construct()
    {
        parent::__construct();
        $this->orderModel   = new OrderModel();
        $this->cartModel    = new CartModel();
        $this->productModel = new ProductModel();
    }

    public function checkoutAll()
    {
        $customerId = $_SESSION['user']['id'];

        $cartItems = $this->cartModel->getCartByUser($customerId);

        if (empty($cartItems)) {
            $_SESSION['toast'] = [
                'type' => 'warning',
                'message' => 'Cart is empty'
            ];

            header('Location: ' . BASE_URL . 'index.php?c=customerCart&m=index');
            exit;
        }

        $totalOrder = 0;
        $sellers = [];

        foreach ($cartItems as $item) {

            $product = $this->productModel->getById($item['product_id']);

            $pendingQty = $this->orderModel
                ->getPendingQtyByProduct($item['product_id']);

            $availableStock = $product['stock'] - $pendingQty;

            if ($availableStock < $item['qty']) {

                $_SESSION['toast'] = [
                    'type' => 'danger',
                    'message' => "Product {$product['name']} stock is reserved"
                ];

                header('Location: ' . BASE_URL . 'index.php?c=customerCart&m=index');
                exit;
            }

            $subtotal = $item['price'] * $item['qty'];

            $totalOrder += $subtotal;

            $sellerId = $item['seller_id'];

            if (!isset($sellers[$sellerId])) {

                $paymentInfo = $this->cartModel->getSellerPaymentInfo($sellerId);

                $sellers[$sellerId] = [
                    'id' => $sellerId,
                    'seller_name' => $item['seller_name'],
                    'account_number' => $paymentInfo['account_number'] ?? '-',
                    'items' => [],
                    'total' => 0
                ];
            }

            $sellers[$sellerId]['items'][] = $item;
            $sellers[$sellerId]['total'] += $subtotal;
        }

        $this->render('checkout', [
            'cartItems' => $cartItems,
            'sellers' => $sellers,
            'totalOrder' => $totalOrder
        ]);
    }

    public function placeOrderAll()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'index.php?c=customerCart&m=index');
            exit;
        }

        $customerId = $_SESSION['user']['id'];

        $cartItems = $this->cartModel->getCartByUser($customerId);

        if (empty($cartItems)) {
            $_SESSION['toast'] = [
                'type' => 'warning',
                'message' => 'Cart is empty'
            ];
            header('Location: ' . BASE_URL . 'index.php?c=customerCart&m=index');
            exit;
        }

        $paymentMethod = $_POST['payment_method'] ?? null;

        if (!in_array($paymentMethod, ['transfer', 'qris'])) {

            $_SESSION['toast'] = [
                'type' => 'danger',
                'message' => 'Invalid payment method'
            ];

            header('Location: ' . BASE_URL . 'index.php?c=customerOrder&m=checkoutAll');
            exit;
        }

        /* ======================
       UPLOAD PAYMENT PROOF
    ====================== */

        if (!isset($_FILES['payment_proof']) || $_FILES['payment_proof']['error'] !== UPLOAD_ERR_OK) {

            $_SESSION['toast'] = [
                'type' => 'danger',
                'message' => 'Payment proof required'
            ];

            header('Location: ' . BASE_URL . 'index.php?c=customerOrder&m=checkoutAll');
            exit;
        }

        $file = $_FILES['payment_proof'];

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);

        $paymentProof = 'payment_' . time() . '_' . uniqid() . '.' . $extension;

        $uploadDir = rtrim(UPLOAD_PATH, '/') . '/payments/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        move_uploaded_file($file['tmp_name'], $uploadDir . $paymentProof);


        /* ======================
       GROUP CART BY SELLER
    ====================== */

        $grouped = [];

        foreach ($cartItems as $item) {

            $grouped[$item['seller_id']][] = $item;
        }

        $createdOrders = [];

        foreach ($grouped as $sellerId => $items) {

            $totalOrder = 0;

            foreach ($items as $item) {
                $totalOrder += $item['price'] * $item['qty'];
            }

            /* CREATE ORDER */

            $orderId = $this->orderModel->create([
                'customer_id' => $customerId,
                'seller_id' => $sellerId,
                'total_amount' => $totalOrder,
                'payment_method' => $paymentMethod,
                'payment_proof' => $paymentProof
            ]);

            /* CREATE ORDER ITEMS */

            foreach ($items as $item) {

                $this->orderModel->createItem([
                    'order_id' => $orderId,
                    'product_id' => $item['product_id'],
                    'price' => $item['price'],
                    'qty' => $item['qty'],
                    'subtotal' => $item['price'] * $item['qty']
                ]);
            }

            $createdOrders[] = $orderId;
        }

        /* REMOVE CART */

        foreach ($grouped as $sellerId => $items) {
            $this->cartModel->removeBySeller($customerId, $sellerId);
        }

        $_SESSION['toast'] = [
            'type' => 'success',
            'message' => 'Order successfully placed'
        ];

        header('Location: ' . BASE_URL . 'index.php?c=customerOrder&m=history');
        exit;
    }

    public function invoice()
    {
        $orderId = $_GET['order_id'] ?? null;

        if (!$orderId) {
            $_SESSION['toast'] = ['type' => 'danger', 'message' => 'Order not found'];
            header('Location: ' . BASE_URL . 'index.php?c=customerOrder&m=history');
            exit;
        }

        // Get order detail
        $order = $this->orderModel->getOrderById($orderId);

        if (!$order) {
            $_SESSION['toast'] = ['type' => 'danger', 'message' => 'Order not found'];
            header('Location: ' . BASE_URL . 'index.php?c=customerOrder&m=history');
            exit;
        }

        // Validasi: hanya customer yang buat order yang bisa akses
        if ($order['customer_id'] != $_SESSION['user']['id']) {
            $_SESSION['toast'] = ['type' => 'danger', 'message' => 'Access denied'];
            header('Location: ' . BASE_URL . 'index.php?c=customerOrder&m=history');
            exit;
        }

        // Get order items
        $orderItems = $this->orderModel->getOrderItems($orderId);

        $this->render('invoice', [
            'order' => $order,
            'orderItems' => $orderItems
        ]);
    }

    public function history()
    {
        $customerId = $_SESSION['user']['id'];
        $orders = $this->orderModel->getOrdersByCustomer($customerId);

        $this->render('order_history', [
            'orders' => $orders
        ]);
    }
}
