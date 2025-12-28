<?php
session_start();
require_once '../Income.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $income = new Income();
    
    try {
        if (empty($_POST['amount']) || empty($_POST['category']) || empty($_POST['date'])) {
            throw new Exception("All fields are required");
        }
        
        $success = $income->update(
            $_POST['id'],
            $_SESSION['user_id'],
            $_POST['amount'],
            $_POST['category'],
            $_POST['description'] ?? '',
            $_POST['date']
        );
        
        if ($success) {
            header('Location: ../view-incomes.php?success=Income updated successfully');
            exit;
        } else {
            header('Location: ../forms/edit-income.php?id=' . $_POST['id'] . '&error=Failed to update');
            exit;
        }
        
    } catch (Exception $e) {
        header('Location: ../forms/edit-income.php?id=' . $_POST['id'] . '&error=' . urlencode($e->getMessage()));
        exit;
    }
} else {
    header('Location: ../view-incomes.php');
    exit;
}
?>