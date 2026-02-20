<?php

require_once BASE_PATH . '/app/controllers/BaseCustomerController.php';
require_once BASE_PATH . '/app/models/ProductModels.php';
require_once BASE_PATH . '/app/models/CategoryModels.php';

class CustomerProductController extends BaseCustomerController
{
    private $productModel;
    private $categoryModel;

    public function __construct()
    {
        parent::__construct();
        $this->productModel  = new ProductModel();
        $this->categoryModel = new CategoryModel();
    }

    public function index()
    {
        // ambil filter category_id dari GET
        $category_id = isset($_GET['category_id']) && $_GET['category_id'] !== ''
            ? (int) $_GET['category_id']
            : null;
        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';

        if ($keyword === '') {
            $keyword = null;
        }

        // ambil semua kategori untuk filter
        $categories = $this->categoryModel->getAll();

        // ambil produk sesuai kategori
        $products = $this->productModel->searchProducts($category_id, $keyword);

        $this->render('product', [
            'title'      => 'Products | iTama Book',
            'menu'       => 'products',
            'categories' => $categories,
            'keyword'    => $keyword,
            'products'   => $products,
            'selected_category' => $category_id
        ]);
    }

    public function detail()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) die('Product ID required');

        $product = $this->productModel->getById($id);
        if (!$product) die('Product not found');

        $this->render('product-details', [
            'title'   => $product['name'] . ' | iTama Book',
            'product' => $product
        ]);
    }
}
