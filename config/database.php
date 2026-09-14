<?php

require_once 'config.php';

class Database {
    private $conn;

    public function __construct() {
        try {
            $this->conn = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME . ";port=" . DB_PORT, DB_USERNAME, DB_PASSWORD);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public function getConnection() {
        return $this->conn;
    }
}

?>
