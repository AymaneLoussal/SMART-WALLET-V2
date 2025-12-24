<?php
require_once '../config/database.php';

class User {

    private ?int $id = null;
    private string $full_name;
    private string $email;
    private string $password;
    private ?string $created_at = null;

    private PDO $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function getUserId(): ?int {
        return $this->id;
    }

    private function validation(): bool {

        if(strlen($this->full_name) < 3){
            throw new Exception("Full name is too short");
        }

        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)){
            throw new Exception("Invalid email format");
        }

        if(strlen($this->password) < 6){
            throw new Exception("Password must be at least 6 characters");
        }

        return true;
    }

    public function findByEmail(string $email): ?array {

        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }

    public function register(string $full_name, string $email, string $password): bool {

        $this->full_name = $full_name;
        $this->email = $email;
        $this->password = $password;

        $this->validation();

        if($this->findByEmail($email)){
            throw new Exception("Email already exists");
        }

        $hashed = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->conn->prepare("
            INSERT INTO users (full_name, email, password)
            VALUES (?, ?, ?)
        ");

        return $stmt->execute([$full_name, $email, $hashed]);
    }

    public function login(string $email, string $password): bool {

        $user = $this->findByEmail($email);

        if(!$user){
            throw new Exception("User not found");
        }

        if(!password_verify($password, $user['password'])){
            throw new Exception("Invalid credentials");
        }

        $this->id = $user['id'];
        $this->full_name = $user['full_name'];
        $this->created_at = $user['created_at'];

        return true;
    }
}
