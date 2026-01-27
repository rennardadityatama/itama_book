<?php

require_once BASE_PATH . '/app/middlewares/Middleware.php';
require_once BASE_PATH . '/app/models/UserModels.php';
require_once BASE_PATH . '/app/helpers/Csrf.php';

class BaseCustomerController
{
    protected $user;

    public function __construct()
    {
        // Pastikan user login
        Middleware::check();

        Middleware::role([3]);

        $this->user = new User();
    }

    

    protected function render($view, $data = [])
    {
        $data['js'] = $data['js'] ?? [];
        $data['content'] = BASE_PATH . '/app/views/customer/' . $view . '.php';
        extract($data);
        require_once BASE_PATH . '/app/views/customer/layouts/main.php';
    }
}
