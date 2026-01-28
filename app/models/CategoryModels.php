<?php

require_once 'Database.php';

class CategoryModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function findByName($nik)
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM categories WHERE name = ? LIMIT 1"
        );
        $stmt->execute([$nik]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getTopCategories($limit = 5)
    {
        $sql = "
        SELECT 
            c.name AS category,
            SUM(oi.qty) AS total_sold
        FROM order_items oi
        JOIN products p ON p.id = oi.product_id
        JOIN categories c ON c.id = p.category_id
        GROUP BY c.id
        ORDER BY total_sold DESC
        LIMIT :limit
    ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // READ ALL
    public function getAll()
    {
        $stmt = $this->db->prepare(
            "SELECT id, name FROM categories ORDER BY id DESC"
        );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // CREATE
    public function create($name)
    {
        $stmt = $this->db->prepare(
            "INSERT INTO categories (name) VALUES (:name)"
        );
        return $stmt->execute([
            ':name' => $name
        ]);

        if ($result) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    // READ ONE
    public function getById($id)
    {
        $stmt = $this->db->prepare(
            "SELECT id, name FROM categories WHERE id = :id"
        );
        $stmt->execute([
            ':id' => $id
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // UPDATE
    public function update($id, $name)
    {
        $stmt = $this->db->prepare(
            "UPDATE categories SET name = :name WHERE id = :id"
        );
        return $stmt->execute([
            ':name' => $name,
            ':id'   => $id
        ]);
    }

    // DELETE
    public function delete($id)
    {
        $stmt = $this->db->prepare(
            "DELETE FROM categories WHERE id = :id"
        );
        return $stmt->execute([
            ':id' => $id
        ]);
    }
}
