<?php
require_once BASE_PATH . '/app/controllers/BaseSellerController.php';
require_once BASE_PATH . '/app/models/ChatModels.php';
require_once BASE_PATH . '/app/helpers/chat.php';

class SellerChatController extends BaseSellerController
{
    private $chatModel;

    public function __construct()
    {
        parent::__construct();
        $this->chatModel = new ChatModel();
    }

    private function json($status, $message, $data = [])
    {
        if (ob_get_length()) ob_clean();
        header('Content-Type: application/json');
        echo json_encode(compact('status', 'message', 'data'));
        exit;
    }

    /**
     * Halaman chat seller
     */
    public function index()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '/index.php?c=auth&m=login');
            exit;
        }

        $sellerId = $_SESSION['user']['id'];
        $roomId   = $_GET['room'] ?? null;

        $chatList = $this->chatModel->getSellerChatList($sellerId);

        $data = [
            'chatList'          => $chatList,
            'activeRoom'        => null,
            'messages'          => [],
            'discussedProducts' => []
        ];

        if ($roomId && $this->chatModel->isRoomMember($roomId, $sellerId)) {
            $data['activeRoom']        = $this->chatModel->getRoomDetail($roomId);
            $data['messages']          = $this->chatModel->getRoomMessages($roomId, $sellerId);
            $data['discussedProducts'] = $this->chatModel->getRoomProducts($roomId);
        } elseif (!empty($chatList)) {
            $firstRoom                 = $chatList[0]['room_id'];
            $data['activeRoom']        = $this->chatModel->getRoomDetail($firstRoom);
            $data['messages']          = $this->chatModel->getRoomMessages($firstRoom, $sellerId);
            $data['discussedProducts'] = $this->chatModel->getRoomProducts($firstRoom);
        }

        $this->render('chat', $data);
    }

    /**
     * Seller kirim pesan
     */
    public function sendMessage()
    {
        if (!isset($_SESSION['user'])) {
            $this->json('error', 'Unauthorized');
        }

        $sellerId  = $_SESSION['user']['id'];
        $roomId    = $_POST['room_id'] ?? null;
        $message   = trim($_POST['message'] ?? '');
        $productId = $_POST['product_id'] ?? null;

        if (!$roomId || $message === '') {
            $this->json('error', 'Invalid input');
        }

        if (!$this->chatModel->isRoomMember($roomId, $sellerId)) {
            $this->json('error', 'Access denied');
        }

        try {
            $messageId = $this->chatModel->sendMessage(
                $roomId,
                $sellerId,   
                $message,
                $productId
            );

            $this->json('success', 'Message sent', [
                'message_id' => $messageId,
                'sender_id'  => $sellerId,
                'message'    => htmlspecialchars($message),
                'created_at' => date('Y-m-d H:i:s')
            ]);
        } catch (Exception $e) {
            $this->json('error', $e->getMessage());
        }
    }

    /**
     * Load pesan (AJAX polling)
     */
    public function loadMessages()
    {
        if (!isset($_SESSION['user'])) {
            $this->json('error', 'Unauthorized');
        }

        $sellerId = $_SESSION['user']['id'];
        $roomId   = $_GET['room_id'] ?? null;

        if (!$roomId || !$this->chatModel->isRoomMember($roomId, $sellerId)) {
            $this->json('error', 'Access denied');
        }

        $this->json('success', 'OK', [
            'messages' => $this->chatModel->getRoomMessages($roomId, $sellerId),
            'room'     => $this->chatModel->getRoomDetail($roomId)
        ]);
    }
}
