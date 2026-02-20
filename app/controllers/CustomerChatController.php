<?php
require_once BASE_PATH . '/app/controllers/BaseCustomerController.php';
require_once BASE_PATH . '/app/models/ChatModels.php';
require_once BASE_PATH . '/app/helpers/chat.php';


class CustomerChatController extends BaseCustomerController
{
    private $chatModel;

    public function __construct()
    {
        parent::__construct();
        $this->chatModel = new ChatModel();
    }

    private function json($status, $message, $data = [])
    {
        if (ob_get_length()) {
            ob_clean();
        }
        header('Content-Type: application/json');
        echo json_encode([
            'status'  => $status,
            'message' => $message,
            'data'    => $data
        ]);
        exit;
    }

    /**
     * Halaman utama chat
     */
    public function index()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '/index.php?c=auth&m=login');
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $roomId = $_GET['room'] ?? null;

        // Ambil chat list
        $chatList = $this->chatModel->getCustomerChatList($userId);

        // Data untuk view
        $data = [
            'chatList' => $chatList,
            'activeRoom' => null,
            'messages' => [],
            'discussedProducts' => [] // TAMBAHAN
        ];

        // Jika ada room yang aktif
        if ($roomId) {
            if ($this->chatModel->isRoomMember($roomId, $userId)) {
                $data['activeRoom'] = $this->chatModel->getRoomDetail($roomId);
                $data['messages'] = $this->chatModel->getRoomMessages($roomId, $userId);
                $data['discussedProducts'] = $this->chatModel->getRoomProducts($roomId); // TAMBAHAN
            }
        } elseif (!empty($chatList)) {
            // Default ke chat pertama
            $firstRoom = $chatList[0]['room_id'];
            $data['activeRoom'] = $this->chatModel->getRoomDetail($firstRoom);
            $data['messages'] = $this->chatModel->getRoomMessages($firstRoom, $userId);
            $data['discussedProducts'] = $this->chatModel->getRoomProducts($firstRoom); // TAMBAHAN
        }

        $this->render('chat', $data);
    }

    /**
     * Mulai chat dari halaman product
     * PERUBAHAN: productId opsional untuk tracking saja
     */
    public function start()
    {
        if (!isset($_SESSION['user'])) {
            $this->json('error', 'Please login first');
        }

        $customerId = $_SESSION['user']['id'];
        $sellerId = $_POST['seller_id'] ?? null;
        $productId = $_POST['product_id'] ?? null; // Opsional

        if (!$sellerId) {
            $this->json('error', 'Seller ID tidak valid');
        }

        try {
            // Buat atau ambil room (hanya berdasarkan customer + seller)
            $roomId = $this->chatModel->getOrCreateRoom($customerId, $sellerId, $productId);

            $this->json('success', 'Chat is ready', [
                'room_id' => $roomId,
                'redirect_url' => BASE_URL . '/index.php?c=customerChat&m=index&room=' . $roomId
            ]);
        } catch (Exception $e) {
            $this->json('error', 'Failed to create message: ' . $e->getMessage());
        }
    }

    /**
     * Kirim pesan (AJAX)
     * PERUBAHAN: Bisa attach product_id
     */
    public function sendMessage()
    {
        if (!isset($_SESSION['user'])) {
            $this->json('error', 'Unauthorized');
        }

        $userId = $_SESSION['user']['id'];
        $roomId = $_POST['room_id'] ?? null;
        $message = trim($_POST['message'] ?? '');
        $productId = $_POST['product_id'] ?? null; // OPSIONAL

        if (!$roomId || empty($message)) {
            $this->json('error', 'Invalid input');
        }

        if (!$this->chatModel->isRoomMember($roomId, $userId)) {
            $this->json('error', 'Access denied');
        }

        try {
            $messageId = $this->chatModel->sendMessage($roomId, $userId, $message, $productId);

            $this->json('success', 'Message sent', [
                'message_id' => $messageId,
                'sender_id' => $userId,
                'message' => htmlspecialchars($message),
                'product_id' => $productId,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        } catch (Exception $e) {
            $this->json('error', 'Failed to send message: ' . $e->getMessage());
        }
    }

    /**
     * Load pesan room tertentu (AJAX)
     */
    public function loadMessages()
    {
        if (!isset($_SESSION['user'])) {
            $this->json('error', 'Unauthorized');
        }

        $userId = $_SESSION['user']['id'];
        $roomId = $_GET['room_id'] ?? null;

        if (!$roomId) {
            $this->json('error', 'Room ID required');
        }

        if (!$this->chatModel->isRoomMember($roomId, $userId)) {
            $this->json('error', 'Access denied');
        }

        try {
            $messages = $this->chatModel->getRoomMessages($roomId, $userId);
            $roomDetail = $this->chatModel->getRoomDetail($roomId);

            $this->json('success', 'Messages loaded', [
                'messages' => $messages,
                'room' => $roomDetail
            ]);
        } catch (Exception $e) {
            $this->json('error', 'Failed to load message: ' . $e->getMessage());
        }
    }

    public function fetchNewMessages()
    {
        if (!isset($_SESSION['user'])) {
            $this->json('error', 'Unauthorized');
        }

        $userId = $_SESSION['user']['id'];
        $roomId = $_GET['room_id'] ?? null;
        $lastId = $_GET['last_id'] ?? 0;

        if (!$roomId) {
            $this->json('error', 'Room ID required');
        }

        if (!$this->chatModel->isRoomMember($roomId, $userId)) {
            $this->json('error', 'Access denied');
        }

        $messages = $this->chatModel->getMessagesAfter($roomId, $lastId);

        $this->json('success', 'New messages', $messages);
    }
}
