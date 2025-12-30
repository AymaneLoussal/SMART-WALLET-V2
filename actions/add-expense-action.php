<?php
session_start();
require_once '../models/expenses.php';
require_once '../models/category.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $expense = new Expense();
    
    try {
        if (empty($_POST['amount']) || empty($_POST['category']) || empty($_POST['date'])) {
            throw new Exception("All fields are required");
        }
        
        $success = $expense->create(
            $_SESSION['user_id'],
            $_POST['amount'],
            $_POST['category'],
            $_POST['description'] ?? '',
            $_POST['date']
        );
        
        if ($success) {
            header('Location: ../view-expenses.php?success=Expense added successfully');
            exit;
        } else {
            header('Location: ../forms/add-expense.php?error=Failed to add expense');
            exit;
        }
        
    } catch (Exception $e) {
        header('Location: ../forms/add-expense.php?error=' . urlencode($e->getMessage()));
        exit;
    }
} else {
    header('Location: ../forms/add-expense.php');
    exit;
}
?>