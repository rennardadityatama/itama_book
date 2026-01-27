<?php

require_once 'Database.php';

class OrderModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO orders 
                (customer_id, seller_id, total_amount, payment_method, payment_proof, shipping_resi, tracking_link, shipping_status, status, payment_status)
            VALUES
                (:customer_id, :seller_id, :total_amount, :payment_method, :payment_proof, :shipping_resi, :tracking_link, 'pending', 'pending', 'completed')
        ");

        $stmt->execute([
            ':customer_id' => $data['customer_id'],
            ':seller_id'   => $data['seller_id'],
            ':total_amount' => $data['total_amount'],
            ':payment_method' => $data['payment_method'] ?? null,
            ':payment_proof'   => $data['payment_proof'] ?? null,
            ':shipping_resi'  => $data['shipping_resi'] ?? null,
            ':tracking_link'  => $data['tracking_link'] ?? null
        ]);

        return $this->db->lastInsertId();
    }

    public function createItem($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO order_items (order_id, product_id, price, qty, subtotal)
            VALUES (:order_id, :product_id, :price, :qty, :subtotal)
        ");

        $stmt->execute([
            ':order_id'   => $data['order_id'],
            ':product_id' => $data['product_id'],
            ':price'      => $data['price'],
            ':qty'        => $data['qty'],
            ':subtotal'   => $data['subtotal']
        ]);
    }

    public function getOrderById($orderId)
    {
        $stmt = $this->db->prepare("
        SELECT 
            o.*,
            c.name AS customer_name,
            c.email AS customer_email,
            c.phone AS customer_phone,
            c.address AS customer_address,
            s.name AS seller_name,
            s.email AS seller_email,
            s.phone AS seller_phone
        FROM orders o
        JOIN users c ON c.id = o.customer_id
        JOIN users s ON s.id = o.seller_id
        WHERE o.id = :order_id
        LIMIT 1
    ");

        $stmt->execute([':order_id' => $orderId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getOrdersByCustomer($customerId)
    {
        $stmt = $this->db->prepare("
            SELECT o.*, s.name AS seller_name
            FROM orders o
            JOIN users s ON s.id = o.seller_id
            WHERE o.customer_id = :customer_id
        ");

        $stmt->execute([':customer_id' => $customerId]);
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // get items for each order
        foreach ($orders as &$order) {
            $order['items'] = $this->getOrderItems($order['id']);
        }

        return $orders;
    }

    /**
     * Get order items by order_id
     */
    public function getOrderItems($orderId)
    {
        $stmt = $this->db->prepare("
            SELECT oi.*, p.name AS product_name
            FROM order_items oi
            JOIN products p ON p.id = oi.product_id
            WHERE oi.order_id = :order_id
        ");

        $stmt->execute([':order_id' => $orderId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOrdersBySeller($sellerId)
    {
        $stmt = $this->db->prepare("
        SELECT 
            o.*,
            c.name AS customer_name,
            c.email AS customer_email,
            c.phone AS customer_phone,
            c.address AS customer_address
            FROM orders o
            JOIN users c ON c.id = o.customer_id
            WHERE o.seller_id = :seller_id
            ");

        $stmt->execute([':seller_id' => $sellerId]);
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Get items for each order
        foreach ($orders as &$order) {
            $order['items'] = $this->getOrderItems($order['id']);
        }

        return $orders;
    }

    public function updateOrderStatus($orderId, $status)
    {
        $stmt = $this->db->prepare("
        UPDATE orders 
        SET status = :status 
        WHERE id = :order_id
    ");

        return $stmt->execute([
            ':status'   => $status,
            ':order_id' => $orderId
        ]);
    }

    public function updatePaymentStatus($orderId, $status)
    {
        $stmt = $this->db->prepare("
            UPDATE orders SET payment_status = :status WHERE id = :order_id
        ");
        $stmt->execute([
            ':status'   => $status,
            ':order_id' => $orderId
        ]);
    }


    public function updateShippingStatus($orderId, $status)
    {
        $stmt = $this->db->prepare("
                    UPDATE orders 
                    SET shipping_status = :status 
                    WHERE id = :order_id
                ");

        return $stmt->execute([
            ':status'   => $status,
            ':order_id' => $orderId
        ]);
    }

    public function updateShippingResi($orderId, $resi, $tracking_link = null)
    {
        $stmt = $this->db->prepare("
                    UPDATE orders 
                    SET shipping_resi = :resi,
                    tracking_link = :tracking_link
                    WHERE id = :order_id
                    ");

        return $stmt->execute([
            ':resi'     => $resi,
            ':tracking_link'     => $tracking_link,
            ':order_id' => $orderId
        ]);
    }

    public function getOrderByIdForSeller($orderId, $sellerId)
    {
        $stmt = $this->db->prepare("
        SELECT 
            o.*,
            c.name AS customer_name,
            c.email AS customer_email,
            c.phone AS customer_phone,
            c.address AS customer_address
        FROM orders o
        JOIN users c ON c.id = o.customer_id
        WHERE o.id = :order_id AND o.seller_id = :seller_id
        LIMIT 1
    ");

        $stmt->execute([
            ':order_id'  => $orderId,
            ':seller_id' => $sellerId
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getOrderByIdForCustomer($customerId)
    {
        $stmt = $this->db->prepare("
        SELECT 
            o.*, 
            u.name AS seller_name, 
            u.email AS seller_email, 
            u.phone AS seller_phone
        FROM orders o
        JOIN users u ON u.id = o.seller_id AND u.role = 2
        WHERE o.customer_id = :customer_id
        ORDER BY o.id DESC
    ");

        $stmt->execute([':customer_id' => $customerId]);
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($orders as &$order) {
            $order['items'] = $this->getOrderItems($order['id']);
        }

        return $orders;
    }

    public function getOrderByIdForCustomerSingle($orderId, $customerId)
    {
        $stmt = $this->db->prepare("
        SELECT 
            o.*,
            c.name AS customer_name,
            c.email AS customer_email,
            c.phone AS customer_phone,
            c.address AS customer_address,
            s.name AS seller_name,
            s.email AS seller_email,
            s.phone AS seller_phone
        FROM orders o
        JOIN users c ON c.id = o.customer_id
        JOIN users s ON s.id = o.seller_id AND s.role = 2
        WHERE o.id = :order_id AND o.customer_id = :customer_id
        LIMIT 1
    ");

        $stmt->execute([
            ':order_id' => $orderId,
            ':customer_id' => $customerId
        ]);

        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($order) {
            $order['items'] = $this->getOrderItems($order['id']);
        }

        return $order;
    }
}
