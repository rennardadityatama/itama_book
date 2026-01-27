<?php

require_once BASE_PATH . '/app/controllers/BaseCustomerController.php';
require_once BASE_PATH . '/app/models/CartModels.php';
require_once BASE_PATH . '/app/models/ProductModels.php';

class CustomerCartController extends BaseCustomerController
{
    private $cartModel;
    private $productModel;

    public function __construct()
    {
        parent::__construct();
        $this->cartModel    = new CartModel();
        $this->productModel = new ProductModel();
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

    public function updateQty()
    {
        $cartId = $_POST['cart_id'] ?? null;
        $action = $_POST['action'] ?? null;

        if (!$cartId || !$action) {
            $this->json('error', 'Request tidak valid');
        }

        $item = $this->cartModel->getCartItemWithStock($cartId);

        if (!$item) {
            $this->json('error', 'Item tidak ditemukan');
        }

        $qty   = (int) $item['qty'];
        $stock = (int) $item['stock'];

        if ($action === 'plus') {
            if ($stock <= 0 || $qty >= $stock) {
                $this->json('warning', 'Stok tidak mencukupi');
            }
            $qty++;
        }

        if ($action === 'minus' && $qty > 1) {
            $qty--;
        }

        $this->cartModel->updateQty($cartId, $qty);

        $this->json('success', 'Jumlah produk diperbarui', [
            'qty' => $qty
        ]);
    }


    // ======================
    // ADD TO CART
    // ======================
    public function add()
    {
        $customerId = $_SESSION['user']['id'] ?? null;
        $productId  = $_POST['product_id'] ?? null;
        $qty        = (int) ($_POST['qty'] ?? 1);

        if (!$customerId || !$productId) {
            $this->json('error', 'Data tidak valid');
        }

        $product = $this->productModel->getById($productId);

        if (!$product) {
            $this->json('error', 'Produk tidak ditemukan');
        }

        if ($product['stock'] <= 0) {
            $this->json('warning', 'Stok produk sudah habis');
        }

        if ($product['stock'] < $qty) {
            $this->json('warning', 'Stok tidak mencukupi');
        }

        $existing = $this->cartModel->findByCustomerAndProduct($customerId, $productId);

        if ($existing) {
            $newQty = $existing['qty'] + $qty;

            if ($newQty > $product['stock']) {
                $this->json('warning', 'Jumlah melebihi stok tersedia');
            }

            $this->cartModel->updateQty($existing['id'], $newQty);
        } else {
            $this->cartModel->create([
                'customer_id' => $customerId,
                'product_id'  => $productId,
                'seller_id'   => $product['seller_id'],
                'qty'         => $qty,
                'price'       => $product['price'],
                'subtotal'    => $product['price'] * $qty
            ]);
        }

        $this->json('success', 'Produk berhasil ditambahkan ke keranjang', [
            'product_id' => $productId
        ]);
    }

    // ======================
    // VIEW CART
    // ======================
    public function index()
    {
        $userId = $_SESSION['user']['id'];

        $rows = $this->cartModel->getCartByUserGroupedSeller($userId);

        $grouped = [];

        foreach ($rows as $row) {
            $sellerId = $row['seller_id'];

            if (!isset($grouped[$sellerId])) {
                $grouped[$sellerId] = [
                    'seller_id' => $sellerId,
                    'seller_name' => $row['seller_name'],
                    'items' => [],
                    'subtotal' => 0
                ];
            }

            $total = $row['price'] * $row['qty'];
            $row['total'] = $total;

            $grouped[$sellerId]['items'][] = $row;
            $grouped[$sellerId]['subtotal'] += $total;
        }

        $this->render('cart', [
            'groupedCart' => $grouped
        ]);
    }
}
