<?php
session_start();
require_once '../Expense.php';
require_once '../Category.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$expense = new Expense();
$userId = $_SESSION['user_id'];

// Filter by category if selected
$selectedCategory = $_GET['category'] ?? '';
if ($selectedCategory) {
    $expenses = $expense->getByCategory($userId, $selectedCategory);
} else {
    $expenses = $expense->getAll($userId);
}

// Get messages
$success = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';

// Calculate total
$total = 0;
foreach ($expenses as $exp) {
    $total += $exp['amount'];
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Expenses</title>
    <style>
        body { font-family: Arial; margin: 40px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #f2f2f2; }
        .success { color: green; background: #e8f5e9; padding: 10px; margin: 10px 0; border: 1px solid green; }
        .error { color: red; background: #ffebee; padding: 10px; margin: 10px 0; border: 1px solid red; }
        .actions a { margin-right: 10px; text-decoration: none; }
        .filter { margin: 20px 0; padding: 10px; background: #f5f5f5; }
        .total { margin: 20px 0; padding: 15px; background: #fff3cd; border: 1px solid #ffeaa7; }
        .btn { padding: 10px 20px; color: white; border: none; text-decoration: none; display: inline-block; }
        .btn-add { background: red; }
        .btn-back { background: gray; }
        .btn-edit { color: blue; }
        .btn-delete { color: red; }
    </style>
</head>
<body>
    <h2>My Expenses</h2>
    
    <?php if ($success): ?>
        <div class="success">✅ <?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    
    <?php if ($error): ?>
        <div class="error">❌ <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <!-- Total Summary -->
    <div class="total">
        <strong>Total Expenses:</strong> $<?= number_format($total, 2) ?>
    </div>
    
    <!-- Filter by Category -->
    <div class="filter">
        <strong>Filter by Category:</strong>
        <a href="?">All</a>
        <?php foreach (Category::getExpenseCategories() as $cat): ?>
        | <a href="?category=<?= urlencode($cat) ?>"><?= $cat ?></a>
        <?php endforeach; ?>
    </div>
    
    <!-- Add Expense Button -->
    <div style="margin: 20px 0;">
        <a href="../forms/add-expense.php" class="btn btn-add">+ Add New Expense</a>
        <a href="../dashboard.php" class="btn btn-back">← Back to Dashboard</a>
    </div>
    
    <!-- Expenses Table -->
    <table>
        <tr>
            <th>Date</th>
            <th>Category</th>
            <th>Amount</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>
        <?php if (empty($expenses)): ?>
        <tr>
            <td colspan="5" style="text-align: center;">No expenses found. <a href="../forms/add-expense.php">Add your first expense</a></td>
        </tr>
        <?php else: ?>
        <?php foreach ($expenses as $exp): ?>
        <tr>
            <td><?= htmlspecialchars($exp['date']) ?></td>
            <td><?= htmlspecialchars($exp['category']) ?></td>
            <td style="color:red; font-weight: bold;">$<?= number_format($exp['amount'], 2) ?></td>
            <td><?= htmlspecialchars($exp['description']) ?></td>
            <td class="actions">
                <a href="../forms/edit-expense.php?id=<?= $exp['id'] ?>" class="btn-edit">Edit</a>
                <a href="../actions/delete-expense-action.php?id=<?= $exp['id'] ?>" 
                   onclick="return confirm('Are you sure you want to delete this expense?')"
                   class="btn-delete">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php endif; ?>
    </table>
    
    <!-- Pagination or summary -->
    <div style="margin-top: 20px; color: #666;">
        Showing <?= count($expenses) ?> expense(s)
    </div>
</body>
</html>