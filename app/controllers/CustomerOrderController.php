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

    public function checkout()
    {
        $customerId = $_SESSION['user']['id'];
        $sellerId   = $_GET['seller_id'] ?? null;

        if (!$sellerId) {
            $_SESSION['toast'] = ['type' => 'danger', 'message' => 'Seller not found'];
            header('Location: ' . BASE_URL . 'index.php?c=customerCart&m=index');
            exit;
        }

        // Get cart items by seller
        $cartItems = $this->cartModel->getCartBySeller($customerId, $sellerId);

        if (empty($cartItems)) {
            $_SESSION['toast'] = ['type' => 'warning', 'message' => 'Cart is empty'];
            header('Location: ' . BASE_URL . 'index.php?c=customerCart&m=index');
            exit;
        }

        // Hitung total
        $totalOrder = 0;
        foreach ($cartItems as $item) {
            // check stock
            $product = $this->productModel->getById($item['product_id']);
            if ($product['stock'] < $item['qty']) {
                $_SESSION['toast'] = [
                    'type' => 'danger',
                    'message' => "Product {$product['name']} stock is not enough"
                ];
                header('Location: ' . BASE_URL . 'index.php?c=customerCart&m=index');
                exit;
            }
            $totalOrder += $item['price'] * $item['qty'];
        }

        $sellerPaymentInfo = $this->cartModel->getSellerPaymentInfo($sellerId);

        $this->render('checkout', [
            'cartItems'  => $cartItems,
            'sellerId'   => $sellerId,
            'totalOrder' => $totalOrder,
            'sellerPaymentInfo' => $sellerPaymentInfo
        ]);
    }

    public function placeOrder()
    {
        // Validasi method POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'index.php?c=customerCart&m=index');
            exit;
        }

        $customerId = $_SESSION['user']['id'];
        $sellerId   = $_POST['seller_id'] ?? null;

        if (!$sellerId) {
            $_SESSION['toast'] = ['type' => 'danger', 'message' => 'Seller not found'];
            header('Location: ' . BASE_URL . 'index.php?c=customerCart&m=index');
            exit;
        }

        // Get cart items by seller
        $cartItems = $this->cartModel->getCartBySeller($customerId, $sellerId);

        if (empty($cartItems)) {
            $_SESSION['toast'] = ['type' => 'warning', 'message' => 'Cart is empty'];
            header('Location: ' . BASE_URL . 'index.php?c=customerCart&m=index');
            exit;
        }

        $totalOrder = 0;
        foreach ($cartItems as $item) {
            // check stock again
            $product = $this->productModel->getById($item['product_id']);
            if ($product['stock'] < $item['qty']) {
                $_SESSION['toast'] = [
                    'type' => 'danger',
                    'message' => "Product {$product['name']} stock is not enough"
                ];
                header('Location: ' . BASE_URL . 'index.php?c=customerCart&m=index');
                exit;
            }
            $totalOrder += $item['price'] * $item['qty'];
        }

        $paymentProof = null;
        $paymentMethod = $_POST['payment_method'] ?? null;

        // Validasi payment method
        if (!in_array($paymentMethod, ['transfer', 'qris'])) {
            $_SESSION['toast'] = [
                'type' => 'danger',
                'message' => 'Invalid payment method'
            ];
            header('Location: ' . BASE_URL . 'index.php?c=customerOrder&m=checkout&seller_id=' . $sellerId);
            exit;
        }

        // Upload payment proof (WAJIB)
        if (isset($_FILES['payment_proof']) && $_FILES['payment_proof']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['payment_proof'];

            // Validasi tipe file
            $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            if (!in_array($file['type'], $allowedTypes)) {
                $_SESSION['toast'] = [
                    'type' => 'danger',
                    'message' => 'Only JPG, PNG, and GIF files are allowed'
                ];
                header('Location: ' . BASE_URL . 'index.php?c=customerOrder&m=checkout&seller_id=' . $sellerId);
                exit;
            }

            // Validasi ukuran file (max 2MB)
            if ($file['size'] > 2 * 1024 * 1024) {
                $_SESSION['toast'] = [
                    'type' => 'danger',
                    'message' => 'File size must be less than 2MB'
                ];
                header('Location: ' . BASE_URL . 'index.php?c=customerOrder&m=checkout&seller_id=' . $sellerId);
                exit;
            }

            // Generate unique filename
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $paymentProof = 'payment_' . time() . '_' . uniqid() . '.' . $extension;

            // Upload path
            $uploadDir = rtrim(UPLOAD_PATH, '/') . '/payments/';

            // Create directory if not exists
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $targetPath = $uploadDir . $paymentProof;

            // Move uploaded file
            if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
                $_SESSION['toast'] = [
                    'type' => 'danger',
                    'message' => 'Failed to upload payment proof'
                ];
                header('Location: ' . BASE_URL . 'index.php?c=customerOrder&m=checkout&seller_id=' . $sellerId);
                exit;
            }
        } else {
            // Jika tidak upload payment proof, error
            $_SESSION['toast'] = [
                'type' => 'danger',
                'message' => 'Payment proof is required'
            ];
            header('Location: ' . BASE_URL . 'index.php?c=customerOrder&m=checkout&seller_id=' . $sellerId);
            exit;
        }

        // create order
        $orderId = $this->orderModel->create([
            'customer_id'    => $customerId,
            'seller_id'      => $sellerId,
            'total_amount'   => $totalOrder,
            'payment_method' => $paymentMethod,
            'payment_proof'  => $paymentProof
        ]);

        // create order items + reduce stock
        foreach ($cartItems as $item) {
            $this->orderModel->createItem([
                'order_id'   => $orderId,
                'product_id' => $item['product_id'],
                'price'      => $item['price'],
                'qty'        => $item['qty'],
                'subtotal'   => $item['price'] * $item['qty']
            ]);

            // reduce stock
            $this->productModel->updateStock($item['product_id'], $item['qty']);
        }

        // remove cart items
        $this->cartModel->removeBySeller($customerId, $sellerId);

        // toast success
        $_SESSION['toast'] = ['type' => 'success', 'message' => 'Order successfully placed'];

        header('Location: ' . BASE_URL . 'index.php?c=customerOrder&m=invoice&order_id=' . $orderId);
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
