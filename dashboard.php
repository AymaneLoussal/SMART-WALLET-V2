 <?php
session_start();
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/income.php';
require_once __DIR__ . '/models/expenses.php';
require_once __DIR__ . '/models/category.php';
require_once __DIR__ . '/models/user.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$income = new Income();
$expense = new Expense();
$userId = $_SESSION['user_id'];

// Get totals
$totalIncome = $income->getTotal($userId);
$totalExpense = $expense->getTotal($userId);
$balance = $totalIncome - $totalExpense;

// Get recent transactions (last 5)
$recentIncomes = array_slice($income->getAll($userId), 0, 5);
$recentExpenses = array_slice($expense->getAll($userId), 0, 5);

// Get by category for charts
$incomeByCategory = [];
foreach ($income->getAll($userId) as $inc) {
    $cat = $inc['category'];
    $incomeByCategory[$cat] = ($incomeByCategory[$cat] ?? 0) + $inc['amount'];
}

$expenseByCategory = [];
foreach ($expense->getAll($userId) as $exp) {
    $cat = $exp['category'];
    $expenseByCategory[$cat] = ($expenseByCategory[$cat] ?? 0) + $exp['amount'];
}

// Get monthly data for line chart
$monthlyIncome = [];
$monthlyExpense = [];

// You can implement this later for monthly trends
?> 
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Smart Wallet</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: Arial; margin: 0; padding: 0; background: #f5f5f5; }
        .navbar { background: #333; color: white; padding: 15px 30px; }
        .navbar a { color: white; margin: 0 15px; text-decoration: none; }
        .navbar a:hover { text-decoration: underline; }
        .container { padding: 30px; }
        .welcome { font-size: 24px; margin-bottom: 30px; }
        .summary-cards { display: flex; gap: 20px; margin-bottom: 40px; }
        .card { flex: 1; background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .card-income { border-top: 5px solid #4CAF50; }
        .card-expense { border-top: 5px solid #F44336; }
        .card-balance { border-top: 5px solid #2196F3; }
        .card h3 { margin-top: 0; }
        .card-amount { font-size: 32px; font-weight: bold; margin: 10px 0; }
        .quick-actions { display: flex; gap: 15px; margin: 30px 0; }
        .btn { padding: 12px 24px; color: white; border: none; border-radius: 5px; text-decoration: none; display: inline-block; }
        .btn-income { background: #4CAF50; }
        .btn-expense { background: #F44336; }
        .btn-view { background: #2196F3; }
        .charts { display: flex; gap: 30px; margin: 40px 0; }
        .chart-container { flex: 1; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .recent-transactions { display: flex; gap: 30px; }
        .transaction-list { flex: 1; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f8f9fa; }
        .positive { color: #4CAF50; }
        .negative { color: #F44336; }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <div class="navbar">
        <strong>SMART WALLET</strong>
        <a href="home.php">Home</a>
        <a href="forms/add-income.php">Add Income</a>
        <a href="forms/add-expense.php">Add Expense</a>
        <a href="views/view-incomes.php">Incomes</a>
        <a href="views/view-expenses.php">Expenses</a>
        <a href="logout.php" style="float: right;">Logout</a>
    </div>
    
    <div class="container">
        <!-- Welcome Message -->
        <div class="welcome">
            Welcome back, <?= htmlspecialchars($_SESSION['full_name'] ?? 'User') ?>! 👋
        </div>
        
        <!-- Summary Cards -->
        <div class="summary-cards">
            <div class="card card-income">
                <h3>Total Income</h3>
                <div class="card-amount positive">$<?= number_format($totalIncome, 2) ?></div>
                <p>Your total earnings</p>
            </div>
            
            <div class="card card-expense">
                <h3>Total Expenses</h3>
                <div class="card-amount negative">$<?= number_format($totalExpense, 2) ?></div>
                <p>Your total spending</p>
            </div>
            
            <div class="card card-balance">
                <h3>Current Balance</h3>
                <div class="card-amount" style="color: <?= $balance >= 0 ? '#4CAF50' : '#F44336' ?>;">
                    $<?= number_format($balance, 2) ?>
                </div>
                <p>Available balance</p>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="quick-actions">
            <a href="forms/add-income.php" class="btn btn-income">+ Add Income</a>
            <a href="forms/add-expense.php" class="btn btn-expense">+ Add Expense</a>
            <a href="views/views-incomes.php" class="btn btn-view">View Incomes</a>
            <a href="views/view-expenses.php" class="btn btn-view">View Expenses</a>
        </div>
        
        <!-- Charts -->
        <div class="charts">
            <div class="chart-container">
                <h3>Income by Category</h3>
                <canvas id="incomeChart" height="250"></canvas>
            </div>
            
            <div class="chart-container">
                <h3>Expenses by Category</h3>
                <canvas id="expenseChart" height="250"></canvas>
            </div>
        </div>
        
        <!-- Recent Transactions -->
        <div class="recent-transactions">
            <div class="transaction-list">
                <h3>Recent Incomes</h3>
                <?php if (empty($recentIncomes)): ?>
                    <p>No incomes yet. <a href="forms/add-income.php">Add your first income</a></p>
                <?php else: ?>
                <table>
                    <tr>
                        <th>Date</th>
                        <th>Category</th>
                        <th>Amount</th>
                    </tr>
                    <?php foreach ($recentIncomes as $inc): ?>
                    <tr>
                        <td><?= $inc['date'] ?></td>
                        <td><?= $inc['category'] ?></td>
                        <td class="positive">$<?= number_format($inc['amount'], 2) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
                <p style="text-align: right; margin-top: 10px;">
                    <a href="views/view-incomes.php">View all incomes →</a>
                </p>
                <?php endif; ?>
            </div>
            
            <div class="transaction-list">
                <h3>Recent Expenses</h3>
                <?php if (empty($recentExpenses)): ?>
                    <p>No expenses yet. <a href="forms/add-expense.php">Add your first expense</a></p>
                <?php else: ?>
                <table>
                    <tr>
                        <th>Date</th>
                        <th>Category</th>
                        <th>Amount</th>
                    </tr>
                    <?php foreach ($recentExpenses as $exp): ?>
                    <tr>
                        <td><?= $exp['date'] ?></td>
                        <td><?= $exp['category'] ?></td>
                        <td class="negative">$<?= number_format($exp['amount'], 2) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
                <p style="text-align: right; margin-top: 10px;">
                    <a href="views/view-expenses.php">View all expenses →</a>
                </p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <script>
    // Income Chart
    const incomeCtx = document.getElementById('incomeChart').getContext('2d');
    new Chart(incomeCtx, {
        type: 'pie',
        data: {
            labels: <?= json_encode(array_keys($incomeByCategory)) ?>,
            datasets: [{
                data: <?= json_encode(array_values($incomeByCategory)) ?>,
                backgroundColor: ['#4CAF50', '#2196F3', '#FF9800', '#9C27B0', '#3F51B5', '#00BCD4']
            }]
        },
        options: {
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
    
    // Expense Chart
    const expenseCtx = document.getElementById('expenseChart').getContext('2d');
    new Chart(expenseCtx, {
        type: 'doughnut',
        data: {
            labels: <?= json_encode(array_keys($expenseByCategory)) ?>,
            datasets: [{
                data: <?= json_encode(array_values($expenseByCategory)) ?>,
                backgroundColor: ['#F44336', '#E91E63', '#9C27B0', '#673AB7', '#3F51B5', '#FF9800']
            }]
        },
        options: {
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
    
    // Simple animation for balance
    document.addEventListener('DOMContentLoaded', function() {
        const balanceAmount = document.querySelector('.card-balance .card-amount');
        if (balanceAmount) {
            setTimeout(() => {
                balanceAmount.style.transform = 'scale(1.1)';
                balanceAmount.style.transition = 'transform 0.3s ease';
                
                setTimeout(() => {
                    balanceAmount.style.transform = 'scale(1)';
                }, 300);
            }, 1000);
        }
    });
    </script>
</body>
</html>