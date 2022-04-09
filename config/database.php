<?php
class Database {
    private $host = "localhost";
    private $db_name = "mysalon";
    private $username = "root";
    private $pwd = "root";
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->pwd);
            $this->conn->exec("set names utf8");
            
        } catch (PDOException $e) {
            echo "Connection Error: ".$e->getMessage();
        }

        return $this->conn;
    }
}
?>