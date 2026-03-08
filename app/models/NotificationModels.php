<?php

require_once 'Database.php';

class NotificationModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
        INSERT INTO notifications (user_id, order_id, room_id, type, title, message) 
        VALUES (?,?,?,?,?,?)
        ");

        return $stmt->execute([
            $data['user_id'] ?? null,
            $data['order_id'] ?? null,
            $data['room_id'] ?? null,
            $data['type'] ?? null,
            $data['title'] ?? null,
            $data['message'] ?? null,
        ]);
    }

    public function getUserById($user_id)
    {
        $stmt = $this->db->prepare("
        SELECT COUNT(*) as total FROM notifications 
        WHERE user_id =? AND is_read=0
        ");

        $stmt->execute([$user_id]);
        return $stmt->fetch()['total'];
    }

    public function getUnreadByUser($user_id)
    {
        $stmt = $this->db->prepare("
        SELECT * FROM notifications
        WHERE user_id = ? AND is_read = 0
        ORDER BY created_at DESC
    ");

        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countUnread($user_id)
    {
        $stmt = $this->db->prepare("
        SELECT COUNT(*) as total
        FROM notifications
        WHERE user_id=? AND is_read=0
    ");

        $stmt->execute([$user_id]);
        return $stmt->fetch()['total'];
    }

    public function markAsRead($id)
    {
        $stmt = $this->db->prepare("
        UPDATE notifications SET is_read=1 WHERE id = ?
        ");

        return $stmt->execute([$id]);
    }

    public function deleteByOrder($orderId, $type)
    {
        $stmt = $this->db->prepare("
        DELETE FROM notifications
        WHERE order_id = ? AND type = ?
    ");

        return $stmt->execute([$orderId, $type]);
    }

    public function markChatRoomAsRead($roomId, $userId)
    {
        $stmt = $this->db->prepare("
        UPDATE notifications
        SET is_read = 1
        WHERE room_id = ?
        AND user_id = ?
        AND type = 'chat'
    ");

        return $stmt->execute([$roomId, $userId]);
    }
}
