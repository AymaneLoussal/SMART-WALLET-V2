<?php
session_start();
require_once '../models/income.php';
require_once '../models/category.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$income = new Income();
$incomeData = $income->getById($_GET['id'] ?? 0, $_SESSION['user_id']);

if (!$incomeData) {
    header('Location: ../view-incomes.php?error=Income not found');
    exit;
}

$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Income</title>
</head>
<body>
    <h2>Edit Income</h2>
    
    <?php if ($error): ?>
        <div style="color: red; padding: 10px; border: 1px solid red; margin: 10px 0;">
            ❌ <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>
    
    <form method="POST" action="../actions/edit-income-action.php">
        <input type="hidden" name="id" value="<?= $incomeData['id'] ?>">
        
        <div style="margin-bottom: 15px;">
            <label>Amount ($):</label><br>
            <input type="number" name="amount" step="0.01" min="0.01" 
                   value="<?= $incomeData['amount'] ?>" required style="width: 300px; padding: 8px;">
        </div>
        
        <div style="margin-bottom: 15px;">
            <label>Category:</label><br>
            <select name="category" required style="width: 320px; padding: 8px;">
                <?php foreach (Category::getIncomeCategories() as $cat): ?>
                <option value="<?= $cat ?>" <?= $cat == $incomeData['category'] ? 'selected' : '' ?>>
                    <?= $cat ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div style="margin-bottom: 15px;">
            <label>Description:</label><br>
            <input type="text" name="description" value="<?= $incomeData['description'] ?>" style="width: 300px; padding: 8px;">
        </div>
        
        <div style="margin-bottom: 15px;">
            <label>Date:</label><br>
            <input type="date" name="date" value="<?= $incomeData['date'] ?>" required style="width: 300px; padding: 8px;">
        </div>
        
        <button type="submit" style="padding: 10px 20px; background: blue; color: white; border: none;">
            Update Income
        </button>
        
        <a href="../view-incomes.php" style="padding: 10px 20px; background: gray; color: white; text-decoration: none; margin-left: 10px;">
            Cancel
        </a>
    </form>
</body>
</html>