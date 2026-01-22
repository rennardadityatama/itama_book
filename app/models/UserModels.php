<?php

require_once 'Database.php';

class User
{
  private $db;

  public function __construct()
  {
    $this->db = Database::getInstance();
  }

  public function findByEmail($email)
  {
    $stmt = $this->db->prepare(
      "SELECT * FROM users WHERE email = ? LIMIT 1"
    );
    $stmt->execute([$email]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  /* =========================
     AUTH / LOGIN
  ========================= */

  public function login($email, $password)
  {
    $user = $this->findByEmail($email);

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
      INSERT INTO users (name, email, password, address, role, status)
      VALUES (:name, :email, :password, :address, :role, :status)
    ");

    return $stmt->execute([
      ':name'     => $data['name'],
      ':email'    => $data['email'],
      ':password' => password_hash($data['password'], PASSWORD_DEFAULT),
      ':address'  => $data['address'],
      ':role'     => $data['role'],
      ':status'   => $data['status']
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
