<?php

class Database
{
  private $host = DB_HOST;
  private $db   = DB_NAME;
  private $user = DB_USER;
  private $pass = DB_PASS;

  public $conn;

  public function connect()
  {
    try {
      $this->conn = new PDO(
        "mysql:host={$this->host};dbname={$this->db}",
        $this->user,
        $this->pass,
        [
          PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
          PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
      );
      return $this->conn;
    } catch (PDOException $e) {
      die("DB Error: " . $e->getMessage());
    }
  }
}
