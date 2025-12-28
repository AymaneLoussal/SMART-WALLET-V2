<?php
session_start();
require_once '../Expense.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

if (isset($_GET['id'])) {
    $expense = new Expense();
    $success = $expense->delete($_GET['id'], $_SESSION['user_id']);
    
    if ($success) {
        header('Location: ../view-expenses.php?success=Expense deleted successfully');
    } else {
        header('Location: ../view-expenses.php?error=Failed to delete expense');
    }
    exit;
}

header('Location: ../view-expenses.php');
exit;
?>