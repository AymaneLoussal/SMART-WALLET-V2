<?php
session_start();
require_once '/../models/expense.php';
require_once '/../models/category.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$expense = new Expense();
$expenseData = $expense->getById($_GET['id'] ?? 0, $_SESSION['user_id']);

if (!$expenseData) {
    header('Location: ../views/view-expenses.php?error=Expense not found');
    exit;
}

$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Expense</title>
    <style>
        body { font-family: Arial; margin: 40px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select { width: 300px; padding: 8px; }
        button { padding: 10px 20px; background: blue; color: white; border: none; cursor: pointer; }
        .cancel { padding: 10px 20px; background: gray; color: white; text-decoration: none; margin-left: 10px; }
        .error { color: red; background: #ffebee; padding: 10px; margin: 10px 0; border: 1px solid red; }
    </style>
</head>
<body>
    <h2>Edit Expense</h2>
    
    <?php if ($error): ?>
        <div class="error">❌ <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <form method="POST" action="../actions/edit-expense-action.php">
        <input type="hidden" name="id" value="<?= $expenseData['id'] ?>">
        
        <div class="form-group">
            <label>Amount ($):</label>
            <input type="number" name="amount" step="0.01" min="0.01" 
                   value="<?= $expenseData['amount'] ?>" required>
        </div>
        
        <div class="form-group">
            <label>Category:</label>
            <select name="category" required>
                <?php foreach (Category::getExpenseCategories() as $cat): ?>
                <option value="<?= $cat ?>" <?= $cat == $expenseData['category'] ? 'selected' : '' ?>>
                    <?= $cat ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label>Description:</label>
            <input type="text" name="description" value="<?= $expenseData['description'] ?>">
        </div>
        
        <div class="form-group">
            <label>Date:</label>
            <input type="date" name="date" value="<?= $expenseData['date'] ?>" required>
        </div>
        
        <button type="submit">Update Expense</button>
        <a href="../views/view-expenses.php" class="cancel">Cancel</a>
    </form>
</body>
</html>