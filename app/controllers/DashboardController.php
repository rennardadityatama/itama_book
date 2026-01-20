<?php
require_once BASE_PATH . '/app/middlewares/Middleware.php';

class DashboardController
{
    public function index()
    {
        Middleware::check();
        Middleware::redirectByRole();
    }
}
