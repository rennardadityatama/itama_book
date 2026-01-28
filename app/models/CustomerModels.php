<?php

require_once 'Database.php';

class CustomerModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // READ ONE by ID
    public function getById($id)
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM users WHERE id = :id AND role = 3"
        );
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // READ ONE by Email
    public function findByEmail($email)
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM users WHERE email = :email AND role = 3 LIMIT 1"
        );
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findByNik($nik)
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM users WHERE nik = :nik AND role = 3 LIMIT 1"
        );
        $stmt->execute([':nik' => $nik]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findByPhone($phone)
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM users WHERE phone = :phone AND role = 3 LIMIT 1"
        );
        $stmt->execute([':phone' => $phone]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findByEmailExceptId($email, $id)
    {
        $stmt = $this->db->prepare(
            "SELECT id FROM users 
         WHERE email = :email AND role = 3 AND id != :id 
         LIMIT 1"
        );
        $stmt->execute([
            ':email' => $email,
            ':id'    => $id
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findByNikExceptId($nik, $id)
    {
        $stmt = $this->db->prepare(
            "SELECT id FROM users 
         WHERE nik = :nik AND role = 3 AND id != :id 
         LIMIT 1"
        );
        $stmt->execute([
            ':nik' => $nik,
            ':id'  => $id
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findByPhoneExceptId($phone, $id)
    {
        $stmt = $this->db->prepare(
            "SELECT id FROM users 
         WHERE phone = :phone AND role = 3 AND id != :id 
         LIMIT 1"
        );
        $stmt->execute([
            ':phone' => $phone,
            ':id'    => $id
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function hasActiveCart($customerId)
    {
        $stmt = $this->db->prepare(
            "SELECT 1 FROM carts WHERE customer_id = ? LIMIT 1"
        );
        $stmt->execute([$customerId]);
        return $stmt->fetch() !== false;
    }

    // READ ALL
    public function getAll()
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM users WHERE role = 3 ORDER BY id DESC"
        );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // CREATE
    public function create($data)
    {
        $stmt = $this->db->prepare(
            "INSERT INTO users 
            (name, nik, email, password, address, role, phone, avatar, status)
            VALUES (:name, :nik, :email, :password, :address, 3, :phone, :avatar, 'offline')"
        );

        return $stmt->execute([
            ':name'           => $data['name'],
            ':nik'            => $data['nik'],
            ':email'          => $data['email'],
            ':password'       => $data['password'],
            ':address'        => $data['address'],
            ':avatar'         => $data['avatar'] ?? null,
            ':phone'          => $data['phone'] ?? null,
        ]);
    }

    // UPDATE
    public function update($id, $data)
    {
        $fields = [];
        $params = [':id' => $id];

        foreach ($data as $key => $val) {
            $fields[] = "$key = :$key";
            $params[":$key"] = $val;
        }

        $sql = "UPDATE users SET " . implode(',', $fields) . " WHERE id = :id AND role = 3";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    // DELETE
    public function delete($id)
    {
        $stmt = $this->db->prepare(
            "DELETE FROM users WHERE id = :id AND role = 3"
        );
        return $stmt->execute([':id' => $id]);
    }
}
