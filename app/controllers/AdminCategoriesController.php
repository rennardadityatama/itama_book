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
                throw new Exception('Category name is required');
            }

            if ($this->categoryModel->findByName($_POST['name'])) {
                $this->json(false, 'Category name has been already');
            }

            $newId = $this->categoryModel->create($name);

            if ($newId) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Category added successfully',
                    'id'      => $newId
                ]);
            } else {
                throw new Exception('Failed to add category');
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
        header('Content-Type: application/json');

        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Method not allowed');
            }

            $name = trim($_POST['name'] ?? '');

            if (empty($name)) {
                throw new Exception('Category name is required');
            }

            if ($this->categoryModel->findByName($_POST['name'])) {
                $this->json(false, 'Category name has been already');
            }

            $result = $this->categoryModel->update($id, $name);

            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Category successfully update'
                ]);
            } else {
                throw new Exception('Failed update category');
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
                    'message' => 'Category has been added'
                ]);
            } else {
                throw new Exception('Failed deleting category');
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
