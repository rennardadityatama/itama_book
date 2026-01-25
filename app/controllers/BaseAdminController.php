<?php

require_once BASE_PATH . '/app/middlewares/Middleware.php';
require_once BASE_PATH . '/app/models/UserModels.php';
require_once BASE_PATH . '/app/helpers/Csrf.php';

class BaseAdminController
{
    protected $user;
    
    public function __construct()
    {
        Middleware::check();
        if (!Middleware::isRole(1)) {
            echo "Hanya admin yang bisa mengakses halaman ini";
            exit;
        }

        $this->user = new User();
    }

    protected function render($view, $data = [])
    {
        $data['js'] = $data['js'] ?? [];
        $data['content'] = BASE_PATH . '/app/views/admin/' . $view . '.php';
        extract($data);
        require_once BASE_PATH . '/app/views/admin/layouts/main.php';
    }
}
