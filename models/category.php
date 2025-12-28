<?php
// models/category.php
class Category {
    
    private static $incomeCategories = [
        'Salary' => 'Salary',
        'Bonus' => 'Bonus', 
        'Investment' => 'Investment',
        'Freelance' => 'Freelance',
        'Gift' => 'Gift',
        'Other' => 'Other',
    ];
    
    private static $expenseCategories = [
        'Food' => 'Food',
        'Rent' => 'Rent',
        'Transport' => 'Transport',
        'Shopping' => 'Shopping',
        'Entertainment' => 'Entertainment',
        'Bills' => 'Bills',
        'Healthcare' => 'Healthcare',
        'Other' => 'Other',
    ];
    
    public static function getIncomeCategories() {
        return self::$incomeCategories;
    }
    
    public static function getExpenseCategories() {
        return self::$expenseCategories;
    }
    
    
    public static function isValidCategory($type, $category) {
        if ($type == 'income') {
            return in_array($category, self::$incomeCategories);
        } elseif ($type == 'expense') {
            return in_array($category, self::$expenseCategories);
        }
        return false;
    }
    
    public static function getIncomeOptions() {
        $options = '<option value="">Select Category</option>';
        foreach (self::$incomeCategories as $key => $value) {
            $options .= '<option value="' . htmlspecialchars($value) . '">' . htmlspecialchars($value) . '</option>';
        }
        return $options;
    }
    
    public static function getExpenseOptions() {
        $options = '<option value="">Select Category</option>';
        foreach (self::$expenseCategories as $key => $value) {
            $options .= '<option value="' . htmlspecialchars($value) . '">' . htmlspecialchars($value) . '</option>';
        }
        return $options;
    }
}
?>