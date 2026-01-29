<?php

require_once 'Database.php';

class SellerModel
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
            "SELECT * FROM users WHERE id = :id AND role = 2"
        );
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // READ ONE by Email
    public function findByEmail($email)
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM users WHERE email = :email AND role = 2 LIMIT 1"
        );
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findByNik($nik)
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM users WHERE nik = :nik AND role = 2 LIMIT 1"
        );
        $stmt->execute([':nik' => $nik]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findByACNumber($account_number)
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM users WHERE account_number = :account_number AND role = 2 LIMIT 1"
        );
        $stmt->execute([':account_number' => $account_number]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findByEmailExceptId($email, $id)
    {
        $stmt = $this->db->prepare(
            "SELECT id FROM users 
         WHERE email = :email AND role = 2 AND id != :id 
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
         WHERE nik = :nik AND role = 2 AND id != :id 
         LIMIT 1"
        );
        $stmt->execute([
            ':nik' => $nik,
            ':id'  => $id
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findByACNumberExceptId($account_number, $id)
    {
        $stmt = $this->db->prepare(
            "SELECT id FROM users 
         WHERE account_number = :account_number AND role = 2 AND id != :id 
         LIMIT 1"
        );
        $stmt->execute([
            ':account_number' => $account_number,
            ':id'  => $id
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function hasCart($sellerId)
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM carts WHERE seller_id = :id"
        );
        $stmt->execute([':id' => $sellerId]);
        return $stmt->fetchColumn() > 0;
    }


    // READ ALL
    public function getAll()
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM users WHERE role = 2 ORDER BY id DESC"
        );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // CREATE
    public function create($data)
    {
        $stmt = $this->db->prepare(
            "INSERT INTO users 
            (name, nik, email, password, address, role, account_number, qris_photo, avatar, status)
            VALUES (:name, :nik, :email, :password, :address, 2, :account_number, :qris_photo, :avatar, 'offline')"
        );

        return $stmt->execute([
            ':name'           => $data['name'],
            ':nik'            => $data['nik'],
            ':email'          => $data['email'],
            ':password'       => $data['password'],
            ':address'        => $data['address'],
            ':avatar'         => $data['avatar'] ?? null,
            ':account_number' => $data['account_number'] ?? null,
            ':qris_photo'     => $data['qris_photo'] ?? null
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

        $sql = "UPDATE users SET " . implode(',', $fields) . " WHERE id = :id AND role = 2";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    // DELETE
    public function delete($id)
    {
        $stmt = $this->db->prepare(
            "DELETE FROM users WHERE id = :id AND role = 2"
        );
        return $stmt->execute([':id' => $id]);
    }
}
