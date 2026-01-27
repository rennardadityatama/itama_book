<?php
require_once BASE_PATH . '/app/controllers/BaseSellerController.php';
require_once BASE_PATH . '/app/models/ProductModels.php';
require_once BASE_PATH . '/app/models/CategoryModels.php';

class SellerProductController extends BaseSellerController
{
    private $productModel;

    public function __construct()
    {
        parent::__construct();
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

    // READ - list semua product seller
    public function index()
    {
        $categoryModel = new CategoryModel();

        $products   = $this->productModel->getBySeller($_SESSION['user']['id']);
        $categories = $categoryModel->getAll();

        $products = $this->productModel->getBySeller($_SESSION['user']['id']);

        $this->render('products', [
            'title' => 'Products | iTama Book',
            'menu'  => 'products',
            'js'    => ['sellerProduct.js'],
            'products' => $products,
            'categories' => $categories
        ]);
    }

    // CREATE - tambah product baru
    public function store()
    {
        header('Content-Type: application/json');

        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Method not allowed');
            }

            $name        = trim($_POST['name'] ?? '');
            $price       = trim($_POST['price'] ?? '0');
            $cost_price  = trim($_POST['cost_price'] ?? '0');
            $margin      = trim($_POST['margin'] ?? '0');
            $stock       = trim($_POST['stock'] ?? '0');
            $category_id = trim($_POST['category_id'] ?? '');
            $description = trim($_POST['description'] ?? '');

            if (empty($name) || empty($price)) {
                throw new Exception('Name and Price are required');
            }

            // cek duplikat nama product per seller
            if ($this->productModel->findByNameSeller($name, $_SESSION['user']['id'])) {
                $this->json(false, 'Product name already exists');
            }

            // handle upload image
            if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
                $ext = pathinfo($_FILES['product_image']['name'], PATHINFO_EXTENSION);
                $filename = uniqid('prod_') . '.' . $ext;
                $target = BASE_PATH . '/public/uploads/products/' . $filename;

                if (!move_uploaded_file($_FILES['product_image']['tmp_name'], $target)) {
                    throw new Exception('Failed to upload image');
                }
                $image = $filename;
            }

            $newId = $this->productModel->create([
                'name'        => $name,
                'price'       => $price,
                'cost_price'  => $cost_price,
                'margin'      => $margin,
                'stock'       => $stock,
                'category_id' => $category_id,
                'seller_id'   => $_SESSION['user']['id'],
                'image'       => $image,
                'description'  => $description
            ]);

            if ($newId) {
                $this->json(true, 'Product added successfully', ['id' => $newId]);
            } else {
                throw new Exception('Failed to add product');
            }
        } catch (Exception $e) {
            http_response_code(400);
            $this->json(false, $e->getMessage());
        }
    }

    // READ - Get single product (untuk edit)
    public function show($id)
    {
        header('Content-Type: application/json');

        try {
            $product = $this->productModel->getById($id);

            if (!$product || $product['seller_id'] != $_SESSION['user']['id']) {
                throw new Exception('Product not found or forbidden');
            }

            $this->json(true, 'Product retrieved', $product);
        } catch (Exception $e) {
            http_response_code(404);
            $this->json(false, $e->getMessage());
        }
    }

    // UPDATE - Update product
    public function update($id)
    {
        header('Content-Type: application/json');

        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Method not allowed');
            }

            $product = $this->productModel->getById($id);
            if (!$product || $product['seller_id'] != $_SESSION['user']['id']) {
                throw new Exception('Product not found or forbidden');
            }

            $name        = trim($_POST['name'] ?? '');
            $price       = trim($_POST['price'] ?? '0'); // string
            $cost_price  = trim($_POST['cost_price'] ?? '0');
            $margin      = trim($_POST['margin'] ?? '0');
            $stock       = trim($_POST['stock'] ?? '0');
            $category_id = trim($_POST['category_id'] ?? '');
            $description = trim($_POST['description'] ?? '');

            if (empty($name) || empty($price)) {
                throw new Exception('Name and Price are required');
            }

            // cek duplikat nama product per seller kecuali id ini
            if ($this->productModel->findByNameSeller($name, $_SESSION['user']['id'], $id)) {
                $this->json(false, 'Product name already exists');
            }

            // Handle upload image baru
            $image = $product['image']; // default ke gambar lama
            if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
                $ext = pathinfo($_FILES['product_image']['name'], PATHINFO_EXTENSION);
                $filename = uniqid('prod_') . '.' . $ext;
                $target = BASE_PATH . '/public/uploads/products/' . $filename;

                if (!move_uploaded_file($_FILES['product_image']['tmp_name'], $target)) {
                    throw new Exception('Failed to upload image');
                }

                $image = $filename; // update ke gambar baru
            }

            $result = $this->productModel->update($id, [
                'name'        => $name,
                'price'       => $price,
                'cost_price'  => $cost_price,
                'margin'      => $margin,
                'stock'       => $stock,
                'category_id' => $category_id,
                'image'       => $image,
                'description'  => $description
            ]);

            if ($result) {
                $this->json(true, 'Product updated successfully');
            } else {
                throw new Exception('Failed to update product');
            }
        } catch (Exception $e) {
            http_response_code(400);
            $this->json(false, $e->getMessage());
        }
    }

    // DELETE - Hapus product
    public function destroy($id)
    {
        header('Content-Type: application/json');

        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Method not allowed');
            }

            $product = $this->productModel->getById($id);
            if (!$product || $product['seller_id'] != $_SESSION['user']['id']) {
                throw new Exception('Product not found or forbidden');
            }

            $result = $this->productModel->delete($id);

            if ($result) {
                $this->json(true, 'Product deleted successfully');
            } else {
                throw new Exception('Failed deleting product');
            }
        } catch (Exception $e) {
            http_response_code(400);
            $this->json(false, $e->getMessage());
        }
    }
}
