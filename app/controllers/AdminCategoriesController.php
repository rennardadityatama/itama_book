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

    // READ - Show category list
    public function index()
    {
        $categories = $this->categoryModel->getAll();

        $this->render('category_list', [
            'title' => 'Category List | iTama Book',
            'menu'  => 'category',
            'categories' => $categories
        ]);
    }

    // CREATE - Add new category
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $name = trim($_POST['name']);

            if (empty($name)) {
                $this->json(false, 'Category name is required.');
            }

            if ($this->categoryModel->findByName($name)) {
                $this->json(false, 'Category already exists.');
            }

            $id = $this->categoryModel->create($name);

            $this->json(true, 'Category added successfully', [
                'id' => $id,
                'name' => $name
            ]);
        }
    }

    // READ - Get single category (for edit)
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
                throw new Exception('Category not found.');
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

    // UPDATE - Update category
    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $name = trim($_POST['name']);

            if (empty($name)) {
                $this->json(false, 'Category name is required.');
            }

            if ($this->categoryModel->findByName($name, $id)) {
                $this->json(false, 'Category already exists.');
            }

            $this->categoryModel->update($id, $name);

            $this->json(true, 'Category updated successfully', [
                'id' => $id,
                'name' => $name
            ]);
        }
    }

    // DELETE - Delete category
    public function destroy($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            try {

                $this->categoryModel->delete($id);

                $this->json(true, 'Category deleted successfully');
            } catch (PDOException $e) {

                if ($e->getCode() == 23000) {
                    $this->json(false, 'Category is still used by products.');
                }

                $this->json(false, 'Failed to delete category.');
            }
        }
    }
}
