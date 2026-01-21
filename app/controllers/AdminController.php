<?php
require_once BASE_PATH . '/app/controllers/BaseAdminController.php';

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

    public function category()
    {
        $this->render('category_list', [
            'title' => 'List Category | iTama Book',
            'menu'  => 'category',
            'js'    => []
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

}
