<?php
class Category {
    
    private static $incomeCategories = [
        'Salary', 'Bonus', 'Investment', 'Freelance', 'Gift', 'Other'
    ];
    
    private static $expenseCategories = [
        'Food', 'Rent', 'Transport', 'Shopping', 'Entertainment', 'Bills', 'Other'
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
}
?>