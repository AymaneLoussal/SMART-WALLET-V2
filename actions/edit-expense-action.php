<?php
session_start();
require_once '../Expense.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $expense = new Expense();
    
    try {
        if (empty($_POST['amount']) || empty($_POST['category']) || empty($_POST['date'])) {
            throw new Exception("All fields are required");
        }
        
        $success = $expense->update(
            $_POST['id'],
            $_SESSION['user_id'],
            $_POST['amount'],
            $_POST['category'],
            $_POST['description'] ?? '',
            $_POST['date']
        );
        
        if ($success) {
            header('Location: ../views/view-expenses.php?success=Expense updated successfully');
            exit;
        } else {
            header('Location: ../forms/edit-expense.php?id=' . $_POST['id'] . '&error=Failed to update');
            exit;
        }
        
    } catch (Exception $e) {
        header('Location: ../forms/edit-expense.php?id=' . $_POST['id'] . '&error=' . urlencode($e->getMessage()));
        exit;
    }
} else {
    header('Location: ../views/view-expenses.php');
    exit;
}
?>