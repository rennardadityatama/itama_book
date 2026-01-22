<?php
require_once BASE_PATH . '/app/controllers/BaseAdminController.php';
require_once BASE_PATH . '/app/models/CategoryModels.php';
require_once BASE_PATH . '/app/models/Database.php';

class AdminController extends BaseAdminController
{
    public function dashboard()
    {
        $this->render('dashboard', [
            'title' => 'Dashboard | iTama Book',
            'menu'  => 'dashboard',
            'js'    => ['dashboard/default.js']
        ]);
    }

    public function customer()
    {
        $this->render('customer_list', [
            'title' => 'List Customer | iTama Book',
            'menu'  => 'customer'
        ]);
    }
    public function seller()
    {
        $this->render('seller_list', [
            'title' => 'List Seller | iTama Book',
            'menu'  => 'seller'
        ]);
    }

    public function profile()
    {
        $this->render('profile', [
            'title' => 'Profile User | iTama Book',
            'menu'  => 'profile'
        ]);
    }

}
