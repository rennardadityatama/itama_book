<?php

require_once BASE_PATH . '/app/controllers/BaseCustomerController.php';
require_once BASE_PATH . '/app/models/ChatModels.php';

class CustomerChatController extends BaseCustomerController
{
    private $chatModel;

    public function __construct()
    {
        parent::__construct();
        $this->chatModel = new ChatModel();
    }

    /**
     * Halaman utama chat customer
     */
    public function index($sellerId = null)
    {
        $customerId = $_SESSION['user']['id'];

        // Jika ada sellerId, buat/ambil room
        $roomId = null;
        if ($sellerId) {
            $roomId = $this->chatModel->getOrCreateRoom($sellerId, $customerId);
        }

        $chatList = $this->chatModel->getChatListForCustomer($customerId);

        $this->render('chat', [
            'chatList' => $chatList,
            'currentRoom' => $roomId,
        ]);
    }

    /**
     * Fetch pesan (AJAX)
     */
    public function fetchMessages()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            return;
        }

        $roomId = intval($_GET['room_id'] ?? 0);

        if (!$roomId) {
            http_response_code(400);
            echo json_encode([]);
            return;
        }

        echo json_encode($this->chatModel->getMessages($roomId));
    }

    /**
     * Kirim pesan (AJAX)
     */
    public function sendMessage()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            return;
        }

        $roomId  = intval($_POST['room_id'] ?? 0);
        $message = trim($_POST['message'] ?? '');
        $senderId = $_SESSION['user']['id'];

        if (!$roomId || $message === '') {
            http_response_code(400);
            echo json_encode(['success' => false]);
            return;
        }

        $this->chatModel->sendMessage($roomId, $senderId, $message);

        echo json_encode(['success' => true]);
    }
}
