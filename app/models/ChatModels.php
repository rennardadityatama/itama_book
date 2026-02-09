<?php
require_once 'Database.php';

class ChatModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getUserById($id)
    {
        $stmt = $this->db->prepare("SELECT id, name, avatar, last_activity FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getChatRooms($userId)
    {
        $stmt = $this->db->prepare("
            SELECT c.room_id, u.name, u.avatar, u.last_activity, c.message, c.created_at
            FROM chats c
            JOIN users u ON u.id != :userId AND u.id = (SELECT sender_id FROM chats WHERE room_id = c.room_id AND sender_id != :userId LIMIT 1)
            WHERE c.id IN (SELECT MAX(id) FROM chats GROUP BY room_id)
            ORDER BY c.created_at DESC
        ");
        $stmt->execute(['userId' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMessagesByRoom($roomId)
    {
        $stmt = $this->db->prepare("SELECT * FROM chats WHERE room_id = :roomId ORDER BY created_at ASC");
        $stmt->execute(['roomId' => $roomId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function sendMessage($roomId, $senderId, $message)
    {
        $stmt = $this->db->prepare("
            INSERT INTO chats (room_id, sender_id, message, is_read, created_at)
            VALUES (:roomId, :senderId, :message, 0, NOW())
        ");
        return $stmt->execute([
            'roomId' => $roomId,
            'senderId' => $senderId,
            'message' => $message
        ]);
    }

    public function findExistingRoom($userA, $userB)
    {
        $stmt = $this->db->prepare("
            SELECT room_id FROM chats
            WHERE (sender_id = :userA AND room_id IN (SELECT room_id FROM chats WHERE sender_id = :userB))
               OR (sender_id = :userB AND room_id IN (SELECT room_id FROM chats WHERE sender_id = :userA))
            LIMIT 1
        ");
        $stmt->execute(['userA' => $userA, 'userB' => $userB]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
