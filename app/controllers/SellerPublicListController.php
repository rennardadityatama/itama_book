<?php

require_once BASE_PATH . '/app/controllers/BaseSellerController.php';
require_once BASE_PATH . '/app/models/SellerModels.php';

class SellerPublicListController extends BaseSellerController
{
    private $sellerModel;

    public function __construct()
    {
        parent::__construct();
        $this->sellerModel = new SellerModel();
    }

    public function index()
    {
        $currentSellerId = $_SESSION['user']['id'];
        $sellers = $this->sellerModel->getAllExcept($currentSellerId);
        
        $this->render('list', [
            'title'   => 'Seller List',
            'menu'    => 'seller_list',
            'sellers' => $sellers
        ]);
    }
}
