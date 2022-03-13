<?php
class Database {
    private $host = "localhost";
    private $db_name = "id18517020_mysalon";
    private $username = "id18517020_mysalon_u";
    private $pwd = "Qwaszx@12345";
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