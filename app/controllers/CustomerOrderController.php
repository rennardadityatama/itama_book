<?php

require_once BASE_PATH . '/app/controllers/BaseCustomerController.php';
require_once BASE_PATH . '/app/models/CartModels.php';
require_once BASE_PATH . '/app/models/ProductModels.php';
require_once BASE_PATH . '/app/models/OrderModels.php';
require_once BASE_PATH . '/app/models/NotificationModels.php';

class CustomerOrderController extends BaseCustomerController
{
    private $orderModel;
    private $cartModel;
    private $productModel;
    private $notificationModel;

    public function __construct()
    {
        parent::__construct();
        $this->orderModel   = new OrderModel();
        $this->cartModel    = new CartModel();
        $this->productModel = new ProductModel();
        $this->notificationModel = new NotificationModel();
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

                $paymentInfo = $this->cartModel->getSellerPaymentInfo($sellerId) ?? [];

                $sellers[$sellerId] = [
                    'id' => $sellerId,
                    'seller_name' => $item['seller_name'],
                    'account_number' => $paymentInfo['account_number'] ?? '-',
                    'qris_photo' => $paymentInfo['qris_photo'] ?? null,
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

        $paymentMethods = $_POST['payment_method'] ?? [];
        $paymentProofs  = $_FILES['payment_proof'] ?? null;

        /* ======================
       GROUP CART BY SELLER
    ====================== */

        $grouped = [];

        foreach ($cartItems as $item) {

            $grouped[$item['seller_id']][] = $item;
        }

        $createdOrders = [];

        foreach ($grouped as $sellerId => $items) {

            if (!isset($paymentMethods[$sellerId])) {
                $_SESSION['toast'] = [
                    'type' => 'danger',
                    'message' => 'Payment method missing'
                ];
                header('Location: ' . BASE_URL . 'index.php?c=customerOrder&m=checkoutAll');
                exit;
            }

            $method = $paymentMethods[$sellerId];

            if (!in_array($method, ['transfer', 'qris'])) {
                continue;
            }

            if (!isset($paymentProofs['name'][$sellerId])) {
                $_SESSION['toast'] = [
                    'type' => 'danger',
                    'message' => 'Payment proof required'
                ];
                header('Location: ' . BASE_URL . 'index.php?c=customerOrder&m=checkoutAll');
                exit;
            }

            $fileName = $paymentProofs['name'][$sellerId];
            $tmpName  = $paymentProofs['tmp_name'][$sellerId];

            $ext = pathinfo($fileName, PATHINFO_EXTENSION);

            $paymentProof = 'payment_' . time() . '_' . $sellerId . '.' . $ext;

            $uploadDir = rtrim(UPLOAD_PATH, '/') . '/payments/';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            move_uploaded_file($tmpName, $uploadDir . $paymentProof);

            // HITUNG TOTAL
            $totalOrder = 0;

            foreach ($items as $item) {
                $totalOrder += $item['price'] * $item['qty'];
            }

            // CREATE ORDER
            $orderId = $this->orderModel->create([
                'customer_id' => $customerId,
                'seller_id' => $sellerId,
                'total_amount' => $totalOrder,
                'payment_method' => $method,
                'payment_proof' => $paymentProof
            ]);

            $this->notificationModel->create([
                'user_id' => $sellerId,
                'order_id' => $orderId,
                'type' => 'new_order',
                'title' => 'New Order',
                'message' => "Order #" . str_pad($orderId, 6, '0', STR_PAD_LEFT) . " needs approval"
            ]);

            $createdOrders[] = $orderId;

            foreach ($items as $item) {

                $this->orderModel->createItem([
                    'order_id' => $orderId,
                    'product_id' => $item['product_id'],
                    'price' => $item['price'],
                    'qty' => $item['qty'],
                    'subtotal' => $item['price'] * $item['qty']
                ]);
            }
        }

        /* REMOVE CART */

        foreach ($grouped as $sellerId => $items) {
            $this->cartModel->removeBySeller($customerId, $sellerId);
        }

        $_SESSION['toast'] = [
            'type' => 'success',
            'message' => 'Order successfully placed'
        ];

        $orderIds = implode(',', $createdOrders);

        header('Location: ' . BASE_URL . 'index.php?c=customerOrder&m=invoice&orders=' . $orderIds);
        exit;
    }

    public function invoice()
    {
        $orderIds = $_GET['orders'] ?? '';

        if (!$orderIds) {
            header('Location: ' . BASE_URL . 'index.php?c=customerOrder&m=history');
            exit;
        }

        $ids = explode(',', $orderIds);

        $orders = $this->orderModel->getOrdersByIds($ids);
        $orderItems = $this->orderModel->getItemsByOrderIds($ids);

        $this->render('invoice', [
            'orders' => $orders,
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
