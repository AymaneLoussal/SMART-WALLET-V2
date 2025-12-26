<?php
// Expense.php
require_once 'Database.php';
require_once 'Category.php';

class Expense {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    // CREATE
    public function create($userId, $amount, $category, $description, $date) {
        // VALIDATION - Prevent invalid data
        if (!is_numeric($amount) || $amount <= 0) {
            throw new Exception("Amount must be a positive number");
        }
        
        if (!Category::isValidCategory('expense', $category)) {
            throw new Exception("Invalid category");
        }
        
        $sql = "INSERT INTO expenses (user_id, amount, category, description, date) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$userId, $amount, $category, $description, $date]);
    }
    
    // GET ALL
    public function getAll($userId) {
        $sql = "SELECT * FROM expenses WHERE user_id = ? ORDER BY date DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
    
    // GET BY ID
    public function getById($id, $userId) {
        $sql = "SELECT * FROM expenses WHERE id = ? AND user_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id, $userId]);
        return $stmt->fetch();
    }
    
    // GET BY CATEGORY
    public function getByCategory($userId, $category) {
        $sql = "SELECT * FROM expenses WHERE user_id = ? AND category = ? ORDER BY date DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId, $category]);
        return $stmt->fetchAll();
    }
    
    // UPDATE
    public function update($id, $userId, $amount, $category, $description, $date) {
        if (!is_numeric($amount) || $amount <= 0) {
            throw new Exception("Amount must be a positive number");
        }
        
        $sql = "UPDATE expenses SET amount = ?, category = ?, description = ?, date = ?
                WHERE id = ? AND user_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$amount, $category, $description, $date, $id, $userId]);
    }
    
    // DELETE
    public function delete($id, $userId) {
        $sql = "DELETE FROM expenses WHERE id = ? AND user_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id, $userId]);
    }
    
    // Get total expenses
    public function getTotal($userId) {
        $sql = "SELECT SUM(amount) as total FROM expenses WHERE user_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }
}
?>