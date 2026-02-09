<?php

require_once 'Database.php';

class ChatModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // Ambil atau buat room
    public function getOrCreateRoom($sellerId, $customerId)
    {
        $stmt = $this->db->prepare("
            SELECT id FROM chat_room
            WHERE seller_id = :seller AND customer_id = :customer
        ");
        $stmt->execute([
            ':seller' => $sellerId,
            ':customer' => $customerId
        ]);

        $room = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($room) return $room['id'];

        $stmt = $this->db->prepare("
            INSERT INTO chat_room (seller_id, customer_id)
            VALUES (:seller, :customer)
        ");
        $stmt->execute([
            ':seller' => $sellerId,
            ':customer' => $customerId
        ]);

        return $this->db->lastInsertId();
    }

    // List customer di sidebar
    public function getChatList($sellerId)
    {
        $stmt = $this->db->prepare("
            SELECT cr.id AS room_id, u.id, u.name, u.avatar, u.status
            FROM chat_room cr
            JOIN users u ON u.id = cr.customer_id
            WHERE cr.seller_id = :seller
            ORDER BY cr.created_at DESC
        ");
        $stmt->execute([':seller' => $sellerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getChatListForCustomer($customerId)
    {
        $stmt = $this->db->prepare("
            SELECT cr.id AS room_id, u.id AS seller_id, u.name, u.avatar, u.status
            FROM chat_room cr
            JOIN users u ON u.id = cr.seller_id
            WHERE cr.customer_id = :customer
            ORDER BY cr.created_at DESC
        ");
        $stmt->execute([':customer' => $customerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Ambil pesan
    public function getMessages($roomId)
    {
        $stmt = $this->db->prepare("
            SELECT c.*, u.name, u.avatar
            FROM chats c
            JOIN users u ON u.id = c.sender_id
            WHERE c.room_id = :room
            ORDER BY c.created_at ASC
        ");
        $stmt->execute([':room' => $roomId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Kirim pesan
    public function sendMessage($roomId, $senderId, $message)
    {
        $stmt = $this->db->prepare("
            INSERT INTO chats (room_id, sender_id, message)
            VALUES (:room, :sender, :message)
        ");
        return $stmt->execute([
            ':room' => $roomId,
            ':sender' => $senderId,
            ':message' => $message
        ]);
    }
}
