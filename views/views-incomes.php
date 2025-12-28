<?php
session_start();
require_once '../models/income.php';
require_once '../models/category.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$income = new Income();
$userId = $_SESSION['user_id'];

// Filter by category if selected
$selectedCategory = $_GET['category'] ?? '';
if ($selectedCategory) {
    $incomes = $income->getByCategory($userId, $selectedCategory);
} else {
    $incomes = $income->getAll($userId);
}

// Get messages
$success = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Incomes</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #f2f2f2; }
        .success { color: green; background: #e8f5e9; padding: 10px; margin: 10px 0; }
        .error { color: red; background: #ffebee; padding: 10px; margin: 10px 0; }
        .actions a { margin-right: 10px; }
    </style>
</head>
<body>
    <h2>My Incomes</h2>
    
    <?php if ($success): ?>
        <div class="success">✅ <?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    
    <?php if ($error): ?>
        <div class="error">❌ <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <!-- Filter by Category -->
    <div style="margin: 20px 0;">
        <strong>Filter by Category:</strong>
        <a href="?">All</a>
        <?php foreach (Category::getIncomeCategories() as $cat): ?>
        | <a href="?category=<?= urlencode($cat) ?>"><?= $cat ?></a>
        <?php endforeach; ?>
    </div>
    
    <!-- Add Income Button -->
    <div style="margin: 20px 0;">
        <a href="../forms/add-income.php">
            <button style="background: green; color: white; padding: 10px 20px; border: none;">
                + Add New Income
            </button>
        </a>
    </div>
    
    <!-- Incomes Table -->
    <table>
        <tr>
            <th>Date</th>
            <th>Category</th>
            <th>Amount</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>
        <?php if (empty($incomes)): ?>
        <tr>
            <td colspan="5" style="text-align: center;">No incomes found</td>
        </tr>
        <?php else: ?>
        <?php foreach ($incomes as $inc): ?>
        <tr>
            <td><?= htmlspecialchars($inc['date']) ?></td>
            <td><?= htmlspecialchars($inc['category']) ?></td>
            <td style="color:green; font-weight: bold;">$<?= number_format($inc['amount'], 2) ?></td>
            <td><?= htmlspecialchars($inc['description']) ?></td>
            <td class="actions">
                <a href="../forms/edite-income.php?id=<?= $inc['id'] ?>">Edit</a>
                <a href="../actions/delete-income-action.php?id=<?= $inc['id'] ?>" 
                   onclick="return confirm('Are you sure you want to delete this income?')"
                   style="color: red;">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php endif; ?>
    </table>
    
    <p><a href="../dashboard.php">← Back to Dashboard</a></p>
</body>
</html>