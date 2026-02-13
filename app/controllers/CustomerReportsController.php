<?php

use Dompdf\Dompdf;
use Dompdf\Options;

require_once BASE_PATH . '/app/controllers/BaseCustomerController.php';
require_once BASE_PATH . '/app/models/CustomerModels.php';
require_once BASE_PATH . '/app/models/OrderModels.php';

class CustomerReportsController extends BaseCustomerController
{
    private $customerModel;
    private $orderModel;

    public function __construct()
    {
        parent::__construct();
        $this->customerModel = new CustomerModel();
        $this->orderModel    = new OrderModel();
    }

    public function index()
    {
        $customerId = $_SESSION['user']['id'];

        $month = (int)($_GET['month'] ?? date('m'));
        $year  = (int)($_GET['year'] ?? date('Y'));
        // Tabel order
        $orders  = $this->orderModel
            ->getCustomerReport($customerId, $month, $year);

        // Summary
        $summary = $this->orderModel
            ->getCustomerReportSummary($customerId, $month, $year);

        $this->render('reports', [
            'title'     => 'My Purchase Report | iTama Book',
            'menu'      => 'customer_reports',
            'orders'    => $orders ?? [],
            'summary'   => $summary ?? [],
            'month'     => $month,
            'year'      => $year
        ]);
    }

    public function exportPdf()
    {
        $customerId = $_SESSION['user']['id'];

        $month = $_POST['month'] ?? date('m');
        $year  = $_POST['year'] ?? date('Y');
        $chartImage = $_POST['chart_image'] ?? null;

        $orders  = $this->orderModel
            ->getCustomerReport($customerId, $month, $year);

        $summary = $this->orderModel
            ->getCustomerReportSummary($customerId, $month, $year);

        ob_start();
        include BASE_PATH . '/app/views/customer/report_pdf.php';
        $html = ob_get_clean();

        $options = new Options();
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $dompdf->stream(
            "my-purchase-report-{$month}-{$year}.pdf",
            ['Attachment' => true]
        );
    }
}
