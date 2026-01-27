<?php
require_once BASE_PATH . '/app/controllers/BaseCustomerController.php';
require_once BASE_PATH . '/app/models/OrderModels.php';

use Dompdf\Dompdf;
use Dompdf\Options;

class CustomerStatusController extends BaseCustomerController
{
    private $orderModel;

    public function __construct()
    {
        parent::__construct();
        $this->orderModel = new OrderModel();
    }

    /**
     * Menampilkan halaman status order untuk customer
     */
    public function index()
    {
        $customerId = $_SESSION['user']['id'];

        // Ambil semua order customer
        $orders = $this->orderModel->getOrdersByCustomer($customerId);

        $this->render('status', [
            'orders' => $orders
        ]);
    }

    /**
     * View payment proof jika diperlukan
     */
    public function viewPaymentProof()
    {
        $orderId = $_GET['order_id'] ?? null;
        $customerId = $_SESSION['user']['id'];

        if (!$orderId) {
            $_SESSION['toast'] = ['type' => 'danger', 'message' => 'Order not found'];
            header('Location: ' . BASE_URL . 'index.php?c=customerStatus&m=index');
            exit;
        }

        // Validasi order milik customer
        $order = $this->orderModel->getOrderByIdForCustomerSingle($orderId, $customerId);
        if (!$order) {
            $_SESSION['toast'] = ['type' => 'danger', 'message' => 'Access denied'];
            header('Location: ' . BASE_URL . 'index.php?c=customerStatus&m=index');
            exit;
        }

        if (empty($order['payment_proof'])) {
            $_SESSION['toast'] = ['type' => 'warning', 'message' => 'No payment proof available'];
            header('Location: ' . BASE_URL . 'index.php?c=customerStatus&m=index');
            exit;
        }

        $paymentProofUrl = BASE_URL . 'uploads/payments/' . $order['payment_proof'];
        header('Location: ' . $paymentProofUrl);
        exit;
    }

    public function downloadInvoice($orderId)
    {
        $orderModel = new OrderModel();

        // Ambil order sesuai customer
        $order = $orderModel->getOrderByIdForCustomerSingle($orderId, $_SESSION['user']['id']);

        if (!$order) {
            $_SESSION['toast'] = [
                'type' => 'danger',
                'message' => 'Order not found'
            ];
            header('Location: ' . BASE_URL . 'index.php?c=customerStatus&m=index');
            exit;
        }

        // Ambil items
        $orderItems = $order['items'] ?? [];

        // Debug: pastikan data lengkap
        // echo "<pre>"; print_r($order); exit;

        // Load HTML invoice
        ob_start();
        require_once BASE_PATH . "/app/views/customer/invoice_pdf.php";
        $html = ob_get_clean();

        // Setup Dompdf
        $options = new Options();
        $options->set('isRemoteEnabled', true); // biar bisa load gambar/css external
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Download PDF
        $filename = "Invoice-Order-#" . str_pad($order['id'], 6, '0', STR_PAD_LEFT) . ".pdf";
        $dompdf->stream($filename, ["Attachment" => true]);
    }
}
