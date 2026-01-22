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

    // READ - Tampilkan list kategori
    public function index()
    {
        $categories = $this->categoryModel->getAll();

        $this->render('category_list', [
            'title' => 'List Category | iTama Book',
            'menu'  => 'category',
            'js'    => ['category.js'],
            'categories' => $categories
        ]);
    }

    // CREATE - Tambah kategori baru
    public function store()
    {
        header('Content-Type: application/json');

        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Method not allowed');
            }

            $name = trim($_POST['name'] ?? '');

            if (empty($name)) {
                throw new Exception('Nama kategori harus diisi');
            }

            $newId = $this->categoryModel->create($name);

            if ($newId) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Kategori berhasil ditambahkan',
                    'id'      => $newId
                ]);
            } else {
                throw new Exception('Gagal menambahkan kategori');
            }
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        exit;
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
                throw new Exception('Kategori tidak ditemukan');
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
        header('Content-Type: application/json');

        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Method not allowed');
            }

            $name = trim($_POST['name'] ?? '');

            if (empty($name)) {
                throw new Exception('Nama kategori harus diisi');
            }

            $result = $this->categoryModel->update($id, $name);

            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Kategori berhasil diupdate'
                ]);
            } else {
                throw new Exception('Gagal mengupdate kategori');
            }
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }

    // DELETE - Hapus kategori
    public function destroy($id)
    {
        header('Content-Type: application/json');

        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Method not allowed');
            }

            $result = $this->categoryModel->delete($id);

            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Kategori berhasil dihapus'
                ]);
            } else {
                throw new Exception('Gagal menghapus kategori');
            }
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }
}
