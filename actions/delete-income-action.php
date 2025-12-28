<?php
session_start();
require_once '../models/income.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../home.php');
    exit;
}

if (isset($_GET['id'])) {
    $income = new Income();
    $success = $income->delete($_GET['id'], $_SESSION['user_id']);
    
    if ($success) {
        header('Location: ../views/views-incomes.php?success=Income deleted successfully');
    } else {
        header('Location: ../views-incomes.php?error=Failed to delete income');
    }
    exit;
}

header('Location: ../views/views-incomes.php');
exit;
?>