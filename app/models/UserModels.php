<?php

require_once 'Database.php';

class User
{
  private $db;

  public function __construct()
  {
    $this->db = Database::getInstance();
  }

  public function findById($id)
  {
    $stmt = $this->db->prepare("
        SELECT 
            u.*, 
            r.name AS role_name
        FROM users u
        LEFT JOIN roles r ON u.role = r.id
        WHERE u.id = ?
        LIMIT 1
    ");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }


  public function findByEmail($email)
  {
    $stmt = $this->db->prepare(
      "SELECT * FROM users WHERE email = ? LIMIT 1"
    );
    $stmt->execute([$email]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function findByNik($nik)
  {
    $stmt = $this->db->prepare(
      "SELECT * FROM users WHERE nik = ? LIMIT 1"
    );
    $stmt->execute([$nik]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function findByPhone($phone)
  {
    $stmt = $this->db->prepare(
      "SELECT * FROM users WHERE phone = ? LIMIT 1"
    );
    $stmt->execute([$phone]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function updateProfile($id, $data)
  {
    if (empty($data)) {
      return false; // tidak ada yang diupdate
    }

    $fields = [];
    $params = [];

    foreach ($data as $key => $value) {
      $fields[] = "$key = :$key";
      $params[":$key"] = $value;
    }

    $params[':id'] = $id;

    $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = :id";

    $stmt = $this->db->prepare($sql);
    return $stmt->execute($params);
  }


  /* =========================
     AUTH / LOGIN
  ========================= */

  public function login($email, $password)
  {
    $stmt = $this->db->prepare("
    SELECT 
      u.*, 
      r.name AS role_name
    FROM users u
    LEFT JOIN roles r ON u.role = r.id
    WHERE u.email = ?
    LIMIT 1
  ");

    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
      return false; // email tidak ditemukan
    }

    if (!password_verify($password, $user['password'])) {
      return false; // password salah
    }

    return $user; // login valid
  }

  public function setStatus($id, $status)
  {
    $stmt = $this->db->prepare(
      "UPDATE users SET status = ? WHERE id = ?"
    );
    return $stmt->execute([$status, $id]);
  }

  /* =========================
     REGISTER
  ========================= */

  public function register($data)
  {
    $stmt = $this->db->prepare("
      INSERT INTO users (name, nik, email, password, phone, address, role, status, account_number, qris_photo  )
      VALUES (:name, :nik, :email, :password, :phone, :address, :role, :status, :account_number, :qris_photo)
    ");

    return $stmt->execute([
      ':name'           => $data['name'],
      ':nik'            => $data['nik'],
      ':email'          => $data['email'],
      ':password'       => password_hash($data['password'], PASSWORD_DEFAULT),
      ':phone'          => $data['phone'],
      ':address'        => $data['address'],
      ':role'           => $data['role'],
      ':account_number' => !empty($data['account_number']) ? $data['account_number'] : '',
      ':qris_photo'     => $data['qris_photo'] ?? null,
      ':status'         => $data['status']
    ]);
  }

  /* =========================
     RESET PASSWORD
  ========================= */

  public function saveResetToken($email, $token, $expiry)
  {
    $stmt = $this->db->prepare("
      UPDATE users 
      SET reset_token = ?, reset_expiry = ? 
      WHERE email = ?
    ");
    return $stmt->execute([$token, $expiry, $email]);
  }

  public function findByToken($token)
  {
    $stmt = $this->db->prepare("
      SELECT * FROM users
      WHERE reset_token = ?
        AND reset_expiry >= NOW()
    ");
    $stmt->execute([$token]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function updatePassword($id, $password)
  {
    $stmt = $this->db->prepare("
      UPDATE users 
      SET password = ?, reset_token = NULL, reset_expiry = NULL 
      WHERE id = ?
    ");
    return $stmt->execute([
      password_hash($password, PASSWORD_DEFAULT),
      $id
    ]);
  }
}
