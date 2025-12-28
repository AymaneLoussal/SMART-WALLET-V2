<?php
session_start();
require_once '../models/income.php';
require_once '../models/category.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $income = new Income();
    
    try {
        // Validate required fields
        if (empty($_POST['amount']) || empty($_POST['category']) || empty($_POST['date'])) {
            throw new Exception("All fields are required");
        }
        
        $success = $income->create(
            $_SESSION['user_id'],
            $_POST['amount'],
            $_POST['category'],
            $_POST['description'] ?? '',
            $_POST['date']
        );
        
        if ($success) {
            // Redirect with success message
            header('Location: ../home.php?success=Income added successfully');
            exit;
        } else {
            header('Location: ../forms/add-income.php?error=Failed to add income');
            exit;
        }
        
    } catch (Exception $e) {
        // Redirect back with error
        header('Location: ../forms/add-income.php?error=' . urlencode($e->getMessage()));
        exit;
    }
} else {
    // If not POST request, redirect to form
    header('Location: ../forms/add-income.php');
    exit;
}
?>