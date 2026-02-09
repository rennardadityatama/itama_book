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

    private function json($status, $message, $data = [])
    {
        header('Content-Type: application/json');
        echo json_encode([
            'status'  => $status,
            'message' => $message,
            'data'    => $data
        ]);
        exit;
    }

    public function index()
    {
        $roomId = $_GET['room_id'] ?? null;
        $targetId = $_GET['target'] ?? null;
        $userId = $_SESSION['user']['id'];

        $chatList = $this->chatModel->getChatRooms($userId);
        $messages = [];
        $activeTarget = null;

        if ($roomId) {
            $messages = $this->chatModel->getMessagesByRoom($roomId);
        }

        if ($targetId) {
            $activeTarget = $this->chatModel->getUserById($targetId);
        }

        $this->render('chat', [
            'chatList'   => $chatList,
            'messages'   => $messages,
            'activeRoom' => $roomId,
            'target'     => $activeTarget
        ]);
    }

    public function send()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $roomId   = $_POST['room_id'] ?? null;
            $message  = $_POST['message'] ?? '';
            $senderId = $_SESSION['user']['id'];

            if ($roomId && $message !== '') {
                $success = $this->chatModel->sendMessage($roomId, $senderId, $message);
                if ($success) $this->json('success', 'Pesan terkirim');
            }
            $this->json('error', 'Gagal mengirim pesan');
        }
    }

    public function startChat()
    {
        $sellerId = $_GET['seller_id'] ?? null;
        $customerId = $_SESSION['user']['id'];

        if (!$sellerId) {
            header('Location: ' . BASE_URL . 'index.php?c=customerProduct&m=index');
            exit;
        }

        $existingRoom = $this->chatModel->findExistingRoom($customerId, $sellerId);

        if ($existingRoom) {
            $roomId = $existingRoom['room_id'];
        } else {
            $roomId = time() . rand(10, 99); // Generate room unik
        }

        header("Location: " . BASE_URL . "index.php?c=customerChat&m=index&room_id=$roomId&target=$sellerId");
        exit;
    }
}
