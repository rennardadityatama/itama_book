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
                (:customer_id, :seller_id, :total_amount, :payment_method, :payment_proof, :shipping_resi, :tracking_link, 'pending', 'pending', 'paid')
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
            SELECT oi.*, 
            p.name AS product_name,
            p.image AS product_image
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

    public function rejectOrder($orderId)
    {
        $stmt = $this->db->prepare("
        UPDATE orders 
        SET status = 'refund',
        shipping_status = 'refund',
            refunded_at = NOW()
        WHERE id = :order_id
    ");

        return $stmt->execute([
            ':order_id' => $orderId
        ]);
    }

    public function autoDeleteExpiredRefundOrders()
    {
        $stmt = $this->db->prepare("
        DELETE FROM orders
        WHERE status = 'refund'
          AND refunded_at IS NOT NULL
          AND refunded_at <= DATE_SUB(NOW(), INTERVAL 1 MINUTE)
    ");

        $stmt->execute();
    }

    public function deleteOrderById($orderId)
    {
        // Hapus items dulu
        $stmt = $this->db->prepare("DELETE FROM order_items WHERE order_id = :id");
        $stmt->execute([':id' => $orderId]);

        // Hapus order
        $stmt = $this->db->prepare("DELETE FROM orders WHERE id = :id");
        return $stmt->execute([':id' => $orderId]);
    }

    public function getMonthlySales($year = null)
    {
        if (!$year) {
            $year = date('Y');
        }

        $stmt = $this->db->prepare("
        SELECT 
            MONTH(created_at) as month,
            SUM(total_amount) as total
        FROM orders
        WHERE status = 'approved'
          AND payment_status = 'paid'
          AND YEAR(created_at) = :year
        GROUP BY MONTH(created_at)
        ORDER BY month ASC
    ");

        $stmt->execute([':year' => $year]);

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Default 12 bulan = 0
        $data = array_fill(1, 12, 0);

        foreach ($results as $row) {
            $data[(int)$row['month']] = (float)$row['total'];
        }

        return $data;
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

    public function getDashboardSummary()
    {
        $stmt = $this->db->query("
        SELECT 
            (SELECT COUNT(*) FROM orders) AS total_orders,
            (SELECT SUM(total_amount) FROM orders) AS total_revenue,
            (SELECT COUNT(*) FROM users WHERE role = 3) AS total_customers,
            (SELECT COUNT(*) FROM products) AS total_products
    ");

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getReportBySeller($sellerId)
    {
        $stmt = $this->db->prepare("
        SELECT 
            o.id AS order_id,
            o.created_at,
            o.status,
            o.payment_status,
            o.shipping_status,
            o.total_amount,
            c.name AS customer_name
        FROM orders o
        JOIN users c ON c.id = o.customer_id
        WHERE o.seller_id = :seller_id
        ORDER BY o.created_at DESC
    ");

        $stmt->execute([':seller_id' => $sellerId]);
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($orders as &$order) {
            $order['items'] = $this->getOrderItems($order['order_id']);
        }

        return $orders;
    }

    public function getSalesChartBySeller($sellerId, $month, $year)
    {
        $stmt = $this->db->prepare("
        SELECT 
            DATE(o.created_at) AS order_date,
            SUM(o.total_amount) AS total_sales
        FROM orders o
        WHERE o.seller_id = :seller_id
          AND o.status = 'approved'
          AND o.payment_status = 'paid'
          AND MONTH(o.created_at) = :month
          AND YEAR(o.created_at) = :year
        GROUP BY DATE(o.created_at)
        ORDER BY order_date ASC
    ");

        $stmt->execute([
            ':seller_id' => $sellerId,
            ':month'     => (int)$month,
            ':year'      => (int)$year
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRevenueReportBySeller($sellerId, $month, $year)
    {
        $stmt = $this->db->prepare("
        SELECT 
            o.id,
            o.created_at,
            o.total_amount,
            o.payment_status,
            u.name AS customer_name
        FROM orders o
        JOIN users u ON u.id = o.customer_id
        WHERE o.seller_id = :seller_id
          AND o.status = 'approved'
          AND o.payment_status = 'paid'
          AND MONTH(o.created_at) = :month
          AND YEAR(o.created_at) = :year
        ORDER BY o.created_at DESC
    ");

        $stmt->execute([
            ':seller_id' => $sellerId,
            ':month'     => (int)$month,
            ':year'      => (int)$year
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSellerReportSummary($sellerId, $month, $year)
    {
        $stmt = $this->db->prepare("
        SELECT
            COUNT(o.id) AS total_orders,
            SUM(o.total_amount) AS total_revenue,
            AVG(o.total_amount) AS avg_order
        FROM orders o
        WHERE o.seller_id = :seller_id
          AND o.status = 'approved'
          AND o.payment_status = 'paid'
          AND MONTH(o.created_at) = :month
          AND YEAR(o.created_at) = :year
    ");

        $stmt->execute([
            ':seller_id' => $sellerId,
            ':month'     => (int)$month,
            ':year'      => (int)$year
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getRecentOrdersBySeller($sellerId, $limit = 5)
    {
        $stmt = $this->db->prepare("
        SELECT 
            o.id,
            o.created_at,
            o.total_amount,
            c.name AS customer_name,
            c.address AS customer_address
        FROM orders o
        JOIN users c ON c.id = o.customer_id
        WHERE o.seller_id = :seller_id
        ORDER BY o.created_at DESC
        LIMIT :limit
    ");

        $stmt->bindValue(':seller_id', $sellerId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRevenueCostMargin($sellerId)
    {
        $db = Database::getInstance();

        $stmt = $db->prepare("
        SELECT 
            COALESCE(SUM(oi.qty * p.price), 0) AS revenue,
            COALESCE(SUM(oi.qty * p.cost_price), 0) AS cost
        FROM order_items oi
        JOIN orders o ON o.id = oi.order_id
        JOIN products p ON p.id = oi.product_id
        WHERE o.seller_id = :seller_id
          AND o.payment_status = 'paid'
    ");

        $stmt->execute([':seller_id' => $sellerId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $margin = $row['revenue'] - $row['cost'];

        return [
            'revenue' => (float)$row['revenue'],
            'cost'    => (float)$row['cost'],
            'margin'  => (float)$margin
        ];
    }

    public function getCustomerOrderSummary($customerId)
    {
        $stmt = $this->db->prepare("
        SELECT
            SUM(CASE WHEN payment_status = 'unpaid' THEN 1 ELSE 0 END) AS pending_payment,
            SUM(CASE WHEN status IN ('approved','processing','shipped') THEN 1 ELSE 0 END) AS in_progress,
            SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) AS completed_orders
        FROM orders
        WHERE customer_id = :customer_id
    ");

        $stmt->execute([':customer_id' => $customerId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getRecentOrdersByCustomer($customerId, $limit = 5)
    {
        $stmt = $this->db->prepare("
        SELECT id, status, payment_status, created_at
        FROM orders
        WHERE customer_id = :customer_id
        ORDER BY created_at DESC
        LIMIT :limit
    ");

        $stmt->bindValue(':customer_id', $customerId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRecentlyBoughtProducts($customerId, $limit = 5)
    {
        $stmt = $this->db->prepare("
        SELECT 
            p.name,
            oi.price,
            p.image
        FROM order_items oi
        JOIN orders o ON o.id = oi.order_id
        JOIN products p ON p.id = oi.product_id
        WHERE o.customer_id = :customer_id
        ORDER BY o.created_at DESC
        LIMIT :limit
    ");

        $stmt->bindValue(':customer_id', $customerId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getActiveShipments($customerId, $limit = 10)
    {
        $stmt = $this->db->prepare("
        SELECT 
            o.id,
            o.created_at,
            o.shipping_status,
            o.shipping_resi,
            o.tracking_link,
            MIN(p.name) AS product_name,
            MIN(p.image) AS product_image
        FROM orders o
        JOIN order_items oi ON oi.order_id = o.id
        JOIN products p ON p.id = oi.product_id
        WHERE o.customer_id = :customer_id
          AND o.shipping_status IN ('pending','processing','shipped')
        GROUP BY o.id
        ORDER BY o.created_at DESC
        LIMIT :limit
    ");

        $stmt->bindValue(':customer_id', (int)$customerId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCustomerReport($customerId, $month, $year)
    {
        $stmt = $this->db->prepare("
        SELECT o.*, u.name as customer_name
        FROM orders o
        JOIN users u ON u.id = o.customer_id
        WHERE o.customer_id = :customer_id
          AND MONTH(o.created_at) = :month
          AND YEAR(o.created_at) = :year
        ORDER BY o.created_at DESC
    ");

        $stmt->execute([
            ':customer_id' => $customerId,
            ':month'       => $month,
            ':year'        => $year
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCustomerReportSummary($customerId, $month, $year)
    {
        $stmt = $this->db->prepare("
        SELECT 
            SUM(CASE 
                    WHEN payment_status = 'paid' 
                    THEN total_amount 
                    ELSE 0 
                END) as total_revenue,

            COUNT(*) as total_orders,

            COUNT(CASE 
                    WHEN payment_status = 'paid' 
                    THEN 1 
                 END) as paid_orders

        FROM orders
        WHERE customer_id = :customer_id
          AND MONTH(created_at) = :month
          AND YEAR(created_at) = :year
    ");

        $stmt->execute([
            ':customer_id' => $customerId,
            ':month'       => $month,
            ':year'        => $year
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getOrdersBySellerPaginated($sellerId, $limit, $offset)
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
        ORDER BY o.created_at DESC
        LIMIT :limit OFFSET :offset
    ");

        $stmt->bindValue(':seller_id', $sellerId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($orders as &$order) {
            $order['items'] = $this->getOrderItems($order['id']);
        }

        return $orders;
    }

    public function countOrdersBySeller($sellerId)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM orders WHERE seller_id = ?");
        $stmt->execute([$sellerId]);
        return $stmt->fetchColumn();
    }
}
