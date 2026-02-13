<?php
require_once BASE_PATH . '/app/controllers/BaseAdminController.php';
require_once BASE_PATH . '/app/models/CategoryModels.php';

class AdminCategoriesController extends BaseAdminController
{
    private $categoryModel;

    public function __construct()
    {
        parent::__construct();
        $this->categoryModel = new CategoryModel();
    }

    private function json($success, $message, $data = [])
    {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => $success,
            'message' => $message,
            'data'    => $data
        ]);
        exit;
    }

    // READ - Tampilkan list kategori
    public function index()
    {
        $categories = $this->categoryModel->getAll();

        $this->render('category_list', [
            'title' => 'List Category | iTama Book',
            'menu'  => 'category',
            'categories' => $categories
        ]);
    }

    // CREATE - Tambah kategori baru
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $name = trim($_POST['name']);

            if (empty($name)) {
                $_SESSION['error'] = 'Nama kategori wajib diisi';
                header('Location: ' . BASE_URL . '?c=adminCategories&m=index');
                exit;
            }

            if ($this->categoryModel->findByName($name)) {
                $_SESSION['error'] = 'Kategori sudah ada';
                header('Location: ' . BASE_URL . '?c=adminCategories&m=index');
                exit;
            }

            $this->categoryModel->create($name);

            $_SESSION['success'] = 'Kategori berhasil ditambahkan';
            header('Location: ' . BASE_URL . '?c=adminCategories&m=index');
            exit;
        }
    }

    // READ - Get single category (untuk edit)
    public function show($id)
    {
        header('Content-Type: application/json');

        try {
            $category = $this->categoryModel->getById($id);

            if ($category) {
                echo json_encode([
                    'success' => true,
                    'data' => $category
                ]);
            } else {
                throw new Exception('Category not found');
            }
        } catch (Exception $e) {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }

    // UPDATE - Update kategori
    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (!is_numeric($id)) {
                $_SESSION['error'] = 'Invalid ID';
                header('Location: ' . BASE_URL . '?c=adminCategories&m=index');
                exit;
            }

            $name = trim($_POST['name']);

            if (empty($name)) {
                $_SESSION['error'] = 'Nama kategori wajib diisi';
                header('Location: ' . BASE_URL . '?c=adminCategories&m=index');
                exit;
            }

            if ($this->categoryModel->findByName($name, $id)) {
                $_SESSION['error'] = 'Kategori sudah ada';
                header('Location: ' . BASE_URL . '?c=adminCategories&m=index');
                exit;
            }

            $this->categoryModel->update($id, $name);

            $_SESSION['success'] = 'Kategori berhasil diupdate';
            header('Location: ' . BASE_URL . '?c=adminCategories&m=index');
            exit;
        }
    }

    // DELETE - Hapus kategori
    public function destroy($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (!is_numeric($id)) {
                $_SESSION['error'] = 'Invalid ID';
                header('Location: ' . BASE_URL . '?c=adminCategories&m=index');
                exit;
            }

            try {

                $this->categoryModel->delete($id);

                $_SESSION['success'] = 'Kategori berhasil dihapus';
            } catch (PDOException $e) {

                // Error karena foreign key
                if ($e->getCode() == 23000) {
                    $_SESSION['error'] = 'Kategori tidak bisa dihapus karena masih digunakan oleh produk / order.';
                } else {
                    $_SESSION['error'] = 'Terjadi kesalahan saat menghapus kategori.';
                }
            }

            header('Location: ' . BASE_URL . '?c=adminCategories&m=index');
            exit;
        }
    }
}
