<?php

require_once 'Database.php';

class CartModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getByCustomer($customerId)
    {
        $sql = "SELECT c.*, p.name, p.image
                FROM carts c
                JOIN products p ON c.product_id = p.id
                WHERE c.customer_id = :customer_id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['customer_id' => $customerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByCustomerAndProduct($customerId, $productId)
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM carts WHERE customer_id = :cid AND product_id = :pid"
        );
        $stmt->execute([
            'cid' => $customerId,
            'pid' => $productId
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $sql = "
        SELECT 
            c.id,
            c.qty,
            p.stock
        FROM carts c
        JOIN products p ON p.id = c.product_id
        WHERE c.id = :id
    ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getCartItemWithStock($cartId)
    {
        $sql = "
        SELECT 
            c.id,
            c.qty,
            p.stock
        FROM carts c
        JOIN products p ON p.id = c.product_id
        WHERE c.id = :id
        LIMIT 1
    ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $cartId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getCartByUserGroupedSeller($userId)
    {
        $sql = "
        SELECT 
            c.id AS cart_id,
            c.qty,
            p.id AS product_id,
            p.name,
            p.stock,
            p.price,
            p.image,
            p.seller_id,
            u.name AS seller_name
        FROM carts c
        JOIN products p ON p.id = c.product_id
        JOIN users u ON u.id = p.seller_id
        WHERE c.customer_id = :user_id
        ORDER BY p.seller_id
    ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateQty($id, $qty)
    {
        $stmt = $this->db->prepare(
            "UPDATE carts 
             SET qty = :qty, subtotal = price * :qty 
             WHERE id = :id"
        );
        return $stmt->execute([
            'qty' => $qty,
            'id'  => $id
        ]);
    }

    public function getCartBySeller($customerId, $sellerId)
    {
        $sql = "
            SELECT 
                c.id AS cart_id,
                c.qty,
                c.price,
                c.product_id,
                p.name,
                p.stock,
                p.image,
                p.seller_id,
                u.name AS seller_name
            FROM carts c
            JOIN products p ON p.id = c.product_id
            JOIN users u ON u.id = p.seller_id
            WHERE c.customer_id = :customer_id 
                AND p.seller_id = :seller_id
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':customer_id' => $customerId,
            ':seller_id'   => $sellerId
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function removeBySeller($customerId, $sellerId)
    {
        $sql = "
            DELETE c 
            FROM carts c
            JOIN products p ON p.id = c.product_id
            WHERE c.customer_id = :customer_id 
                AND p.seller_id = :seller_id
        ";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':customer_id' => $customerId,
            ':seller_id'   => $sellerId
        ]);
    }

    public function getSellerPaymentInfo($sellerId)
    {
        $stmt = $this->db->prepare("
        SELECT 
            id,
            name,
            account_number,
            qris_photo
        FROM users
        WHERE id = :seller_id
        LIMIT 1
    ");

        $stmt->execute([':seller_id' => $sellerId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $stmt = $this->db->prepare(
            "INSERT INTO carts 
            (customer_id, product_id, seller_id, qty, price, subtotal)
            VALUES 
            (:customer_id, :product_id, :seller_id, :qty, :price, :subtotal)"
        );
        return $stmt->execute($data);
    }
}
