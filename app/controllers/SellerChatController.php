<?php

require_once BASE_PATH . '/app/controllers/BaseSellerController.php';
require_once BASE_PATH . '/app/models/ChatModels.php';

class SellerChatController extends BaseSellerController
{
    private $chatModel;

    public function __construct()
    {
        parent::__construct();
        $this->chatModel = new ChatModel();
    }

    /**
     * Halaman utama chat seller
     */
    public function index()
    {
        $sellerId = $_SESSION['user']['id'];

        $chatList = $this->chatModel->getChatList($sellerId);

        $this->render('chat', [
            'chatList' => $chatList
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

        $roomId = $_GET['room_id'] ?? null;

        if (!$roomId) {
            http_response_code(400);
            echo json_encode([]);
            return;
        }

        echo json_encode(
            $this->chatModel->getMessages($roomId)
        );
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

        $roomId  = $_POST['room_id'] ?? null;
        $message = trim($_POST['message'] ?? '');
        $senderId = $_SESSION['user']['id'];

        if (!$roomId || $message === '') {
            http_response_code(400);
            echo json_encode(['success' => false]);
            return;
        }

        $this->chatModel->sendMessage(
            $roomId,
            $senderId,
            $message
        );

        echo json_encode(['success' => true]);
    }
}
