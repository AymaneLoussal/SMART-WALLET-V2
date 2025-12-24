<?php
require_once '../config/database.php';

class User {
    private ?int $id;
    private string $full_name;
    private string $email;
    private string $password;
    private ?string $created_at;
    private PDO $conn;

    public function __construct() {
        $this->conn = database::getInstance()->getconnection();
    }
    
    private function validation(): bool {
        if(strlen($this->full_name)< 3){
            throw new Exception("Full name is too short");
        }
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)){
            throw new Exception("invalid email format");
        }
        if(strlen($this->password) < 6) {
            throw new Exception("password must be at least 6 characters");
        
        }
        return true;
    }

     public function findByEmail(string $email): ?array
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);

        $user = $stmt->fetch();

        return $user ?: null;
    }

    public function register(string $full_name, string $email, string $password): bool {
        $this->full_name = $full_name;
        $this->email = $email;
        $this->password = $password;

        $this->validation();

        if($this->findByEmail($email)) {
            throw new Exception("email already exists");
        
        }

        $hashed = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->conn->prepare("INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)");
        return $stmt->execute([$full_name, $email, $hashed]);
    }

    public function login(string $email, string $password): bool {
        $user = $this->findByEmail($email);

        if(!$user){
            throw new Exception("user not found");
        }

        if(!password_verify($password, $user['password'])) {
            throw new Exception("invalid credentials");
        }

        $this->id = $user['id'];
        $this->full_name = $user['full_name'];
        $this->created_at = $user['created_at'];

        return true;
    }
}
