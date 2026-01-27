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
        $category_id = $_GET['category_id'] ?? null;

        // ambil semua kategori untuk filter
        $categories = $this->categoryModel->getAll();

        // ambil produk sesuai kategori
        if ($category_id) {
            $products = $this->productModel->getByCategory($category_id);
        } else {
            $products = $this->productModel->getAllProducts();
        }

        $this->render('product', [
            'title'      => 'Products | iTama Book',
            'menu'       => 'products',
            'categories' => $categories,
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
