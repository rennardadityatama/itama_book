<?php

require_once BASE_PATH . '/app/middlewares/Middleware.php';
require_once BASE_PATH . '/app/models/UserModels.php';
require_once BASE_PATH . '/app/helpers/Csrf.php';

class BaseSellerController
{
    protected $user;

    public function __construct()
    {
        // Pastikan user login
        Middleware::check();

        // Pastikan role seller (2)
        Middleware::role([2]);

        $this->user = new User();
    }

    protected function render($view, $data = [])
    {
        $data['js'] = $data['js'] ?? [];
        $data['content'] = BASE_PATH . '/app/views/seller/' . $view . '.php';
        extract($data);
        require_once BASE_PATH . '/app/views/seller/layouts/main.php';
    }
}
