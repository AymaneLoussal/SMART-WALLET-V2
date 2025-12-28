<?php
session_start();
require_once '../models/category.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$success = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Expense</title>
</head>
<body>
    <h2>Add New Expense</h2>
    
    <?php if ($success): ?>
        <div style="color: green; padding: 10px; border: 1px solid green; margin: 10px 0;">
            ✅ <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>
    
    <?php if ($error): ?>
        <div style="color: red; padding: 10px; border: 1px solid red; margin: 10px 0;">
            ❌ <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>
    
    <form method="POST" action="../actions/add-expense-action.php">
        <div style="margin-bottom: 15px;">
            <label>Amount ($):</label><br>
            <input type="number" name="amount" step="0.01" min="0.01" required style="width: 300px; padding: 8px;">
        </div>
        
        <div style="margin-bottom: 15px;">
            <label>Category:</label><br>
            <select name="category" required style="width: 320px; padding: 8px;">
                <option value="">Select Category</option>
                <?php foreach (Category::getExpenseCategories() as $cat): ?>
                <option value="<?= $cat ?>"><?= $cat ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div style="margin-bottom: 15px;">
            <label>Description (optional):</label><br>
            <input type="text" name="description" style="width: 300px; padding: 8px;">
        </div>
        
        <div style="margin-bottom: 15px;">
            <label>Date:</label><br>
            <input type="date" name="date" value="<?= date('Y-m-d') ?>" required style="width: 300px; padding: 8px;">
        </div>
        
        <button type="submit" style="padding: 10px 20px; background: red; color: white; border: none;">
            Add Expense
        </button>
        
        <a href="../view-expenses.php" style="padding: 10px 20px; background: gray; color: white; text-decoration: none; margin-left: 10px;">
            Cancel
        </a>
    </form>
</body>
</html>