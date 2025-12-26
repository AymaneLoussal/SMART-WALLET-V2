<?php
// Income.php
require_once 'Database.php';
require_once 'Category.php';

class Income {
    private $db;
    private $userId;
    private $amount;
    private $category;
    private $description;
    private $date;
    
    public function __construct() {
        $this->db = (new Database())->getConnection();
    }
    
    // CREATE - Add new income
    public function create($userId, $amount, $category, $description, $date) {
        // VALIDATION
        if (!is_numeric($amount) || $amount <= 0) {
            throw new Exception("Amount must be a positive number");
        }
        
        if (!Category::isValidCategory('income', $category)) {
            throw new Exception("Invalid category");
        }
        
        if (empty($date)) {
            throw new Exception("Date is required");
        }
        
        // Save to database
        $sql = "INSERT INTO incomes (user_id, amount, category, description, date) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$userId, $amount, $category, $description, $date]);
    }
    
    // GET ALL incomes for user
    public function getAll($userId) {
        $sql = "SELECT * FROM incomes WHERE user_id = ? ORDER BY date DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
    
    // GET BY ID - Get one income
    public function getById($id, $userId) {
        $sql = "SELECT * FROM incomes WHERE id = ? AND user_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id, $userId]);
        return $stmt->fetch();
    }
    
    // GET BY CATEGORY - Filter incomes
    public function getByCategory($userId, $category) {
        $sql = "SELECT * FROM incomes WHERE user_id = ? AND category = ? ORDER BY date DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId, $category]);
        return $stmt->fetchAll();
    }
    
    // UPDATE - Edit income
    public function update($id, $userId, $amount, $category, $description, $date) {
        // Validation (same as create)
        if (!is_numeric($amount) || $amount <= 0) {
            throw new Exception("Amount must be a positive number");
        }
        
        if (!Category::isValidCategory('income', $category)) {
            throw new Exception("Invalid category");
        }
        
        $sql = "UPDATE incomes SET amount = ?, category = ?, description = ?, date = ?
                WHERE id = ? AND user_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$amount, $category, $description, $date, $id, $userId]);
    }
    
    // DELETE - Remove income
    public function delete($id, $userId) {
        $sql = "DELETE FROM incomes WHERE id = ? AND user_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id, $userId]);
    }
    
    // Get total income
    public function getTotal($userId) {
        $sql = "SELECT SUM(amount) as total FROM incomes WHERE user_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }
}
?>