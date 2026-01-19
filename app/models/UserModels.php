<?php

require_once 'Database.php';

class User
{
  private $db;

  public function __construct()
  {
    $this->db = (new Database())->connect();
  }

  public function findByEmail($email)
  {
    $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->fetch();
  }

  public function register($data)
  {
    $stmt = $this->db->prepare("
      INSERT INTO users (name, email, password, status, role)
      VALUES (?, ?, ?, 'Active', 'Customer')
    ");
    return $stmt->execute([
      $data['name'],
      $data['email'],
      password_hash($data['password'], PASSWORD_DEFAULT)
    ]);
  }

  public function saveResetToken($email, $token, $expiry)
  {
    $stmt = $this->db->prepare("
      UPDATE users SET reset_token=?, reset_expiry=? WHERE email=?
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
    return $stmt->fetch();
  }

  public function updatePassword($id, $password)
  {
    $stmt = $this->db->prepare("
      UPDATE users 
      SET password=?, reset_token=NULL, reset_expiry=NULL 
      WHERE id=?
    ");
    return $stmt->execute([
      password_hash($password, PASSWORD_DEFAULT),
      $id
    ]);
  }
}
