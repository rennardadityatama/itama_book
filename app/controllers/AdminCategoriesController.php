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
                $_SESSION['error'] = 'Category name is required.';
                header('Location: ' . BASE_URL . '?c=adminCategories&m=index');
                exit;
            }

            if ($this->categoryModel->findByName($name)) {
                $_SESSION['error'] = 'Category already exists.';
                header('Location: ' . BASE_URL . '?c=adminCategories&m=index');
                exit;
            }

            $this->categoryModel->create($name);

            $_SESSION['success'] = 'Category has been successfully added.';
            header('Location: ' . BASE_URL . '?c=adminCategories&m=index');
            exit;
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

            if (!is_numeric($id)) {
                $_SESSION['error'] = 'Invalid category ID.';
                header('Location: ' . BASE_URL . '?c=adminCategories&m=index');
                exit;
            }

            $name = trim($_POST['name']);

            if (empty($name)) {
                $_SESSION['error'] = 'Category name is required.';
                header('Location: ' . BASE_URL . '?c=adminCategories&m=index');
                exit;
            }

            if ($this->categoryModel->findByName($name, $id)) {
                $_SESSION['error'] = 'Category already exists.';
                header('Location: ' . BASE_URL . '?c=adminCategories&m=index');
                exit;
            }

            $this->categoryModel->update($id, $name);

            $_SESSION['success'] = 'Category has been successfully updated.';
            header('Location: ' . BASE_URL . '?c=adminCategories&m=index');
            exit;
        }
    }

    // DELETE - Delete category
    public function destroy($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (!is_numeric($id)) {
                $_SESSION['error'] = 'Invalid category ID.';
                header('Location: ' . BASE_URL . '?c=adminCategories&m=index');
                exit;
            }

            try {

                $this->categoryModel->delete($id);

                $_SESSION['success'] = 'Category has been successfully deleted.';

            } catch (PDOException $e) {

                if ($e->getCode() == 23000) {
                    $_SESSION['error'] = 'This category cannot be deleted because it is still associated with products or orders.';
                } else {
                    $_SESSION['error'] = 'An error occurred while deleting the category.';
                }
            }

            header('Location: ' . BASE_URL . '?c=adminCategories&m=index');
            exit;
        }
    }
}
