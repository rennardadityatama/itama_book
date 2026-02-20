<?php
require_once 'Database.php';

class ProductModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // READ ONE by ID
    public function getById($id)
    {
        $stmt = $this->db->prepare("
        SELECT * FROM products 
        WHERE id = :id AND is_active = 1
        ");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateStock(int $productId, int $qty): int
    {
        $stmt = $this->db->prepare("
        UPDATE products
        SET stock = stock - :qty
        WHERE id = :id
          AND stock >= :qty
    ");

        $stmt->execute([
            ':qty' => $qty,
            ':id'  => $productId
        ]);

        return $stmt->rowCount();
    }

    // READ ALL produk seller
    public function getBySeller($sellerId)
    {
        $sql = "SELECT p.*, c.name as category_name
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        WHERE p.seller_id = :sellerId
          AND p.is_active = 1
    ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['sellerId' => $sellerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // READ ALL products (untuk customer)
    public function getAllProducts()
    {
        $sql = "SELECT p.*, c.name AS category_name, u.name AS seller_name
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            LEFT JOIN users u ON p.seller_id = u.id
            WHERE p.stock > 0 AND p.is_active = 1"; // optional: hanya produk tersedia
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByCategory($category_id)
    {
        $sql = "SELECT p.*, c.name AS category_name, u.name AS seller_name
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            LEFT JOIN users u ON p.seller_id = u.id
            WHERE p.stock > 0 AND p.category_id = :category_id 
             AND p.is_active = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['category_id' => $category_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBestSellingProducts($limit = 5)
    {
        $sql = "
        SELECT 
            p.id,
            p.name,
            SUM(oi.qty) AS total_sold
        FROM order_items oi
        JOIN products p ON p.id = oi.product_id
        GROUP BY oi.product_id
        ORDER BY total_sold DESC
        LIMIT :limit
    ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLowStockProducts($threshold = 5)
    {
        $stmt = $this->db->prepare("
        SELECT id, name, stock 
        FROM products 
        WHERE stock <= :threshold
        ORDER BY stock ASC
    ");
        $stmt->execute(['threshold' => $threshold]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // CREATE products
    public function create($data)
    {
        $stmt = $this->db->prepare(
            "INSERT INTO products 
            (name, price, cost_price, margin, stock, image, description, seller_id, category_id)
            VALUES 
            (:name, :price, :cost_price, :margin, :stock, :image, :description, :seller_id, :category_id)"
        );

        return $stmt->execute([
            ':name'        => $data['name'],
            ':price'       => $data['price'],
            ':cost_price'  => $data['cost_price'],
            ':margin'      => $data['margin'],
            ':stock'       => $data['stock'],
            ':image'       => $data['image'] ?? '',
            ':description'  => $data['description'] ?? null,
            ':seller_id'   => $data['seller_id'],
            ':category_id' => $data['category_id'] ?? null
        ]);
    }

    // UPDATE products
    public function update($id, $data)
    {
        $fields = [];
        $params = [':id' => $id];

        foreach ($data as $key => $val) {
            $fields[] = "$key = :$key";
            $params[":$key"] = $val;
        }

        $sql = "UPDATE products SET " . implode(',', $fields) . " WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    // DELETE products
    public function softDelete($id)
    {
        $stmt = $this->db->prepare("
        UPDATE products 
        SET is_active = 0,
            deleted_at = NOW()
        WHERE id = :id
    ");
        return $stmt->execute([':id' => $id]);
    }

    public function findByNameSeller($name, $seller_id, $excludeId = null)
    {
        $sql = "SELECT * FROM products WHERE name = :name AND seller_id = :seller_id AND is_active = 1";
        $params = ['name' => $name, 'seller_id' => $seller_id];

        if ($excludeId) {
            $sql .= " AND id != :id";
            $params['id'] = $excludeId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function searchProducts($category_id = null, $keyword = null)
    {
        $sql = "SELECT p.*, c.name AS category_name, u.name AS seller_name
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            LEFT JOIN users u ON p.seller_id = u.id
            WHERE p.stock > 0 AND p.is_active = 1";

        $params = [];

        // FILTER CATEGORY
        if (!empty($category_id)) {
            $sql .= " AND p.category_id = :category_id";
            $params['category_id'] = $category_id;
        }

        // SEARCH KEYWORD
        if (!empty($keyword)) {
            $sql .= " AND LOWER(p.name) LIKE :keyword";
            $params['keyword'] = '%' . strtolower($keyword) . '%';
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBestSellingProductsBySeller($sellerId, $limit = 5)
    {
        $sql = "
        SELECT 
            p.id,
            p.name,
            p.price,
            p.image,
            COALESCE(SUM(oi.qty), 0) AS total_sold
        FROM products p
        LEFT JOIN order_items oi ON p.id = oi.product_id
        LEFT JOIN orders o ON o.id = oi.order_id AND o.payment_status = 'paid'
        WHERE p.seller_id = :seller_id
        AND p.is_active = 1
        GROUP BY p.id
        ORDER BY total_sold DESC
        LIMIT :limit
    ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':seller_id', $sellerId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
