<?php
class database {
    private string $host = "localhost";
    private string $db_name = "smartWalletV2";
    private string $username = "root";
    private string $password = "";
    private ?PDO $conn = null;

    private static ?database $instance = null;

    private function __construct(){
        try{
            $this->conn = new PDO("mysql:host={$this->host};dbname={$this->db_name};charset=utf8mb4",
            $this->username,
            $this->password
        );
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);


        } catch(PDOException $exception){
            die("database connection error: " . $exception->getMessage());

        }
    }

    public static function getInstance(): database
    {
        if (self::$instance === null) {
            self::$instance = new database();
        }
        return self::$instance;
    }

    public function getconnection(): PDO {
        return $this->conn;
    }
}