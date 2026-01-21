<?php

require_once BASE_PATH . '/app/middlewares/Middleware.php';

class BaseAdminController
{
    protected function render($view, $data = [])
    {
        Middleware::check();
        if (!Middleware::isRole(1)) {
            echo "Hanya admin yang bisa mengakses halaman ini";
            exit;
        }

        $data['content'] = BASE_PATH . '/app/views/admin/' . $view . '.php';

        extract($data);
        require_once BASE_PATH . '/app/views/admin/layouts/main.php';
    }
}
