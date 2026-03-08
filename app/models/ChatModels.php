<?php
require_once 'Database.php';

class ChatModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }


    public function getOrCreateRoom($customerId, $sellerId, $productId = null)
    {
        // Cek apakah room sudah ada (hanya berdasarkan customer + seller)
        $stmt = $this->db->prepare("
            SELECT id FROM chat_rooms 
            WHERE customer_id = ? AND seller_id = ?
        ");
        $stmt->execute([$customerId, $sellerId]);
        $room = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($room) {
            // Update last_product_id jika ada
            if ($productId) {
                $stmtUpdate = $this->db->prepare("
                    UPDATE chat_rooms 
                    SET last_product_id = ?, updated_at = CURRENT_TIMESTAMP 
                    WHERE id = ?
                ");
                $stmtUpdate->execute([$productId, $room['id']]);
            }
            return $room['id'];
        }

        // Buat room baru
        $stmt = $this->db->prepare("
            INSERT INTO chat_rooms (customer_id, seller_id, last_product_id) 
            VALUES (?, ?, ?)
        ");
        $stmt->execute([$customerId, $sellerId, $productId]);
        return $this->db->lastInsertId();
    }

    public function getCustomerChatList($customerId)
    {
        $stmt = $this->db->prepare("
            SELECT 
                cr.id as room_id,
                cr.seller_id,
                cr.last_product_id,
                u.name as seller_name,
                u.avatar as seller_avatar,
                u.last_activity as seller_last_activity,
                (SELECT message FROM chat_messages 
                 WHERE room_id = cr.id 
                 ORDER BY created_at DESC LIMIT 1) as last_message,
                (SELECT created_at FROM chat_messages 
                 WHERE room_id = cr.id 
                 ORDER BY created_at DESC LIMIT 1) as last_message_time,
                (SELECT COUNT(*) FROM chat_messages 
                 WHERE room_id = cr.id AND sender_id != ? AND is_read = 0) as unread_count
            FROM chat_rooms cr
            JOIN users u ON cr.seller_id = u.id
            WHERE cr.customer_id = ?
            ORDER BY cr.updated_at DESC
        ");
        $stmt->execute([$customerId, $customerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Ambil semua pesan dalam room
     */
    public function getRoomMessages($roomId, $customerId)
    {
        // Update status pesan menjadi sudah dibaca
        $stmtUpdate = $this->db->prepare("
            UPDATE chat_messages 
            SET is_read = 1 
            WHERE room_id = ? AND sender_id != ? AND is_read = 0
        ");
        $stmtUpdate->execute([$roomId, $customerId]);

        // Ambil semua pesan
        $stmt = $this->db->prepare("
            SELECT 
                cm.*,
                u.name as sender_name,
                u.avatar as sender_avatar,
                p.name as product_name,
                p.image as product_image
            FROM chat_messages cm
            JOIN users u ON cm.sender_id = u.id
            LEFT JOIN products p ON cm.product_id = p.id
            WHERE cm.room_id = ?
            ORDER BY cm.created_at ASC
        ");
        $stmt->execute([$roomId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function sendMessage($roomId, $senderId, $message, $productId = null)
    {
        $stmt = $this->db->prepare("
            INSERT INTO chat_messages (room_id, sender_id, message, product_id) 
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$roomId, $senderId, $message, $productId]);

        // Update timestamp room
        $stmtUpdate = $this->db->prepare("
            UPDATE chat_rooms SET updated_at = CURRENT_TIMESTAMP WHERE id = ?
        ");
        $stmtUpdate->execute([$roomId]);

        return $this->db->lastInsertId();
    }


    public function isRoomMember($roomId, $userId)
    {
        $stmt = $this->db->prepare("
            SELECT id FROM chat_rooms 
            WHERE id = ? AND (customer_id = ? OR seller_id = ?)
        ");
        $stmt->execute([$roomId, $userId, $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }

    public function getRoomDetail($roomId)
    {
        $stmt = $this->db->prepare("
            SELECT 
                cr.*,
                seller.name as seller_name,
                seller.avatar as seller_avatar,
                seller.last_activity as seller_last_activity,
                customer.name as customer_name,
                customer.avatar as customer_avatar
            FROM chat_rooms cr
            JOIN users seller ON cr.seller_id = seller.id
            JOIN users customer ON cr.customer_id = customer.id
            WHERE cr.id = ?
        ");
        $stmt->execute([$roomId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getRoomProducts($roomId)
    {
        $stmt = $this->db->prepare("
            SELECT DISTINCT p.id, p.name, p.image, p.price
            FROM chat_messages cm
            JOIN products p ON cm.product_id = p.id
            WHERE cm.room_id = ? AND cm.product_id IS NOT NULL
            ORDER BY cm.created_at DESC
        ");
        $stmt->execute([$roomId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSellerChatList($sellerId)
    {
        $stmt = $this->db->prepare("
        SELECT 
            cr.id AS room_id,
            u.name AS customer_name,
            u.avatar AS customer_avatar,
            u.last_activity AS customer_last_activity,
            (SELECT message FROM chat_messages 
             WHERE room_id = cr.id 
             ORDER BY created_at DESC LIMIT 1) AS last_message,
            (SELECT created_at FROM chat_messages 
             WHERE room_id = cr.id 
             ORDER BY created_at DESC LIMIT 1) AS last_message_time,
            (SELECT COUNT(*) FROM chat_messages 
             WHERE room_id = cr.id 
             AND sender_id != ? 
             AND is_read = 0) AS unread_count
        FROM chat_rooms cr
        JOIN users u ON cr.customer_id = u.id
        WHERE cr.seller_id = ?
        ORDER BY cr.updated_at DESC
    ");
        $stmt->execute([$sellerId, $sellerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMessagesAfter($roomId, $lastId)
    {
        $stmt = $this->db->prepare("
        SELECT 
            cm.*,
            u.avatar as sender_avatar
        FROM chat_messages cm
        JOIN users u ON cm.sender_id = u.id
        WHERE cm.room_id = ?
        AND cm.id > ?
        ORDER BY cm.id ASC
    ");

        $stmt->execute([$roomId, $lastId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function markRoomAsRead($roomId, $userId)
    {
        $stmt = $this->db->prepare("
        UPDATE chat_messages
        SET is_read = 1
        WHERE room_id = ?
        AND sender_id != ?
        AND is_read = 0
    ");

        return $stmt->execute([$roomId, $userId]);
    }
}
