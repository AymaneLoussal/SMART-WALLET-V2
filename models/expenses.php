<?php
// models/expense.php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/category.php';

class Expense {
    private $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }
    
    // CREATE - Add new expense
    public function create($userId, $amount, $category, $description, $date) {
        // VALIDATION - Prevent invalid data
        if (!is_numeric($amount) || $amount <= 0) {
            throw new Exception("Amount must be a positive number");
        }
        
        if (!Category::isValidCategory('expense', $category)) {
            throw new Exception("Invalid category: '$category'");
        }
        
        if (empty($date)) {
            throw new Exception("Date is required");
        }
        
        try {
            $sql = "INSERT INTO expenses (user_id, amount, category, description, date) 
                    VALUES (:user_id, :amount, :category, :description, :date)";
            $stmt = $this->db->prepare($sql);
            
            return $stmt->execute([
                ':user_id' => $userId,
                ':amount' => $amount,
                ':category' => $category,
                ':description' => $description,
                ':date' => $date
            ]);
            
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }
    
    // GET ALL expenses for user
    public function getAll($userId) {
        try {
            $sql = "SELECT * FROM expenses WHERE user_id = :user_id ORDER BY date DESC, id DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':user_id' => $userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }
    
    // GET BY ID - Get one expense
    public function getById($id, $userId) {
        try {
            $sql = "SELECT * FROM expenses WHERE id = :id AND user_id = :user_id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id, ':user_id' => $userId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }
    
    // GET BY CATEGORY - Filter expenses
    public function getByCategory($userId, $category) {
        try {
            $sql = "SELECT * FROM expenses WHERE user_id = :user_id AND category = :category 
                    ORDER BY date DESC, id DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':user_id' => $userId, ':category' => $category]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }
    
    // GET BY DATE RANGE
    public function getByDateRange($userId, $startDate, $endDate) {
        try {
            $sql = "SELECT * FROM expenses 
                    WHERE user_id = :user_id AND date BETWEEN :start_date AND :end_date 
                    ORDER BY date DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':user_id' => $userId,
                ':start_date' => $startDate,
                ':end_date' => $endDate
            ]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }
    
    // UPDATE - Edit expense
    public function update($id, $userId, $amount, $category, $description, $date) {
        if (!is_numeric($amount) || $amount <= 0) {
            throw new Exception("Amount must be a positive number");
        }
        
        if (!Category::isValidCategory('expense', $category)) {
            throw new Exception("Invalid category: '$category'");
        }
        
        try {
            $sql = "UPDATE expenses 
                    SET amount = :amount, category = :category, 
                        description = :description, date = :date
                    WHERE id = :id AND user_id = :user_id";
            
            $stmt = $this->db->prepare($sql);
            
            return $stmt->execute([
                ':amount' => $amount,
                ':category' => $category,
                ':description' => $description,
                ':date' => $date,
                ':id' => $id,
                ':user_id' => $userId
            ]);
            
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }
    
    // DELETE - Remove expense
    public function delete($id, $userId) {
        try {
            $sql = "DELETE FROM expenses WHERE id = :id AND user_id = :user_id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([':id' => $id, ':user_id' => $userId]);
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }
    
    // Get total expenses
    public function getTotal($userId) {
        try {
            $sql = "SELECT SUM(amount) as total FROM expenses WHERE user_id = :user_id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':user_id' => $userId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }
    
    // Get total by category
    public function getTotalByCategory($userId, $category) {
        try {
            $sql = "SELECT SUM(amount) as total FROM expenses 
                    WHERE user_id = :user_id AND category = :category";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':user_id' => $userId, ':category' => $category]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }
    
    // Get recent expenses (last N)
    public function getRecent($userId, $limit = 10) {
        try {
            $sql = "SELECT * FROM expenses WHERE user_id = :user_id 
                    ORDER BY date DESC, id DESC LIMIT :limit";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }
    
    // Get monthly total
    public function getMonthlyTotal($userId, $year, $month) {
        try {
            $sql = "SELECT SUM(amount) as total FROM expenses 
                    WHERE user_id = :user_id 
                    AND YEAR(date) = :year AND MONTH(date) = :month";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':user_id' => $userId,
                ':year' => $year,
                ':month' => $month
            ]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }
    
    // Get category summary (for charts)
    public function getCategorySummary($userId) {
        try {
            $sql = "SELECT category, SUM(amount) as total, COUNT(*) as count 
                    FROM expenses 
                    WHERE user_id = :user_id 
                    GROUP BY category 
                    ORDER BY total DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':user_id' => $userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }
}
?>