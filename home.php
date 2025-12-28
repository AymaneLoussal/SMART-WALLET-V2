<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Wallet - Personal Finance Manager</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: #333;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        /* Navigation */
        .navbar {
            background: white;
            border-radius: 10px;
            padding: 15px 30px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #667eea;
            text-decoration: none;
        }
        
        .nav-links {
            display: flex;
            gap: 20px;
        }
        
        .nav-links a {
            color: #666;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 5px;
            transition: all 0.3s;
        }
        
        .nav-links a:hover {
            background: #f0f4ff;
            color: #667eea;
        }
        
        .auth-buttons {
            display: flex;
            gap: 10px;
        }
        
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        .btn-login {
            background: #f8f9fa;
            color: #667eea;
            border: 2px solid #667eea;
        }
        
        .btn-register {
            background: #667eea;
            color: white;
        }
        
        /* Hero Section */
        .hero {
            background: white;
            border-radius: 15px;
            padding: 60px 40px;
            text-align: center;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            margin-bottom: 40px;
        }
        
        .hero h1 {
            font-size: 48px;
            color: #333;
            margin-bottom: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .hero p {
            font-size: 20px;
            color: #666;
            max-width: 700px;
            margin: 0 auto 40px;
            line-height: 1.6;
        }
        
        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        
        .action-card {
            background: white;
            border-radius: 10px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            transition: transform 0.3s;
        }
        
        .action-card:hover {
            transform: translateY(-5px);
        }
        
        .action-icon {
            font-size: 40px;
            margin-bottom: 20px;
        }
        
        .income-icon { color: #4CAF50; }
        .expense-icon { color: #F44336; }
        .view-icon { color: #2196F3; }
        .dashboard-icon { color: #FF9800; }
        
        .action-card h3 {
            margin-bottom: 15px;
            color: #333;
        }
        
        .action-card p {
            color: #666;
            margin-bottom: 20px;
        }
        
        .btn-action {
            background: #667eea;
            color: white;
            width: 100%;
        }
        
        .btn-income { background: #4CAF50; }
        .btn-expense { background: #F44336; }
        .btn-view { background: #2196F3; }
        .btn-dashboard { background: #FF9800; }
        
        /* Features */
        .features {
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }
        
        .features h2 {
            text-align: center;
            margin-bottom: 40px;
            color: #333;
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }
        
        .feature {
            text-align: center;
            padding: 20px;
        }
        
        .feature-icon {
            font-size: 36px;
            margin-bottom: 20px;
            color: #667eea;
        }
        
        .feature h4 {
            margin-bottom: 15px;
            color: #333;
        }
        
        /* Footer */
        footer {
            text-align: center;
            margin-top: 50px;
            padding: 20px;
            color: white;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                gap: 15px;
            }
            
            .nav-links {
                flex-wrap: wrap;
                justify-content: center;
            }
            
            .hero h1 {
                font-size: 36px;
            }
            
            .hero p {
                font-size: 18px;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <!-- Navigation Bar -->
        <nav class="navbar">
            <a href="index.php" class="logo">
                <i class="fas fa-wallet"></i> SMART WALLET
            </a>
            
            <div class="nav-links">
                <a href="index.php"><i class="fas fa-home"></i> Home</a>
                <a href="#features"><i class="fas fa-star"></i> Features</a>
                <a href="#about"><i class="fas fa-info-circle"></i> About</a>
            </div>
            
            <div class="auth-buttons">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="dashboard.php" class="btn btn-dashboard">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                    <a href="logout.php" class="btn btn-login">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-login">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </a>
                    <a href="register.php" class="btn btn-register">
                        <i class="fas fa-user-plus"></i> Register
                    </a>
                <?php endif; ?>
            </div>
        </nav>
        
        <!-- Hero Section -->
        <section class="hero">
            <h1>Take Control of Your Finances</h1>
            <p>Smart Wallet helps you track income, manage expenses, and achieve your financial goals with easy-to-use tools and insightful analytics.</p>
            
            <?php if (isset($_SESSION['user_id'])): ?>
                <div style="display: flex; gap: 15px; justify-content: center; margin-top: 30px;">
                    <a href="forms/add-income.php" class="btn btn-income" style="padding: 15px 30px;">
                        <i class="fas fa-money-bill-wave"></i> Add Income
                    </a>
                    <a href="forms/add-expense.php" class="btn btn-expense" style="padding: 15px 30px;">
                        <i class="fas fa-shopping-cart"></i> Add Expense
                    </a>
                </div>
            <?php else: ?>
                <a href="register.php" class="btn btn-register" style="padding: 15px 40px; font-size: 18px;">
                    Get Started Free
                </a>
            <?php endif; ?>
        </section>
        
        <!-- Quick Actions (only for logged in users) -->
        <?php if (isset($_SESSION['user_id'])): ?>
        <section class="quick-actions">
            <div class="action-card">
                <div class="action-icon income-icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <h3>Add Income</h3>
                <p>Record your salary, bonuses, and other income sources</p>
                <a href="forms/add-income.php" class="btn btn-action btn-income">
                    <i class="fas fa-plus"></i> Add Income
                </a>
            </div>
            
            <div class="action-card">
                <div class="action-icon expense-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <h3>Add Expense</h3>
                <p>Track your spending by category and see where your money goes</p>
                <a href="forms/add-expense.php" class="btn btn-action btn-expense">
                    <i class="fas fa-plus"></i> Add Expense
                </a>
            </div>
            
                
            
            <div class="action-card">
                <div class="action-icon dashboard-icon">
                    <i class="fas fa-tachometer-alt"></i>
                </div>
                <h3>Dashboard</h3>
                <p>See your financial overview and track your progress</p>
                <a href="dashboard.php" class="btn btn-action btn-dashboard">
                    <i class="fas fa-tachometer-alt"></i> Go to Dashboard
                </a>
            </div>
        </section>
        <?php endif; ?>
        
        <!-- Features Section -->
        <section class="features" id="features">
            <h2>Why Choose Smart Wallet?</h2>
            <div class="features-grid">
                <div class="feature">
                    <div class="feature-icon">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <h4>Smart Analytics</h4>
                    <p>Visualize your finances with interactive charts and reports</p>
                </div>
                
                <div class="feature">
                    <div class="feature-icon">
                        <i class="fas fa-tags"></i>
                    </div>
                    <h4>Category Tracking</h4>
                    <p>Categorize income and expenses for better financial insights</p>
                </div>
                
                <div class="feature">
                    <div class="feature-icon">
                        <i class="fas fa-bell"></i>
                    </div>
                    <h4>Budget Alerts</h4>
                    <p>Get notified when you're approaching your spending limits</p>
                </div>
                
                <div class="feature">
                    <div class="feature-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h4>Mobile Friendly</h4>
                    <p>Access your finances from any device, anywhere</p>
                </div>
                
                <div class="feature">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h4>Secure & Private</h4>
                    <p>Your financial data is encrypted and protected</p>
                </div>
                
                <div class="feature">
                    <div class="feature-icon">
                        <i class="fas fa-download"></i>
                    </div>
                    <h4>Export Data</h4>
                    <p>Download your financial data in multiple formats</p>
                </div>
            </div>
        </section>
        
        <!-- About Section -->
        <section class="features" id="about" style="margin-top: 30px;">
            <h2>About Smart Wallet</h2>
            <div style="text-align: center; max-width: 800px; margin: 0 auto;">
                <p style="font-size: 18px; line-height: 1.6; color: #666; margin-bottom: 30px;">
                    Smart Wallet is a comprehensive personal finance management tool designed to help you take control of your money. 
                    Whether you want to track daily expenses, plan for big purchases, or save for the future, 
                    Smart Wallet provides the tools and insights you need to make informed financial decisions.
                </p>
                <div style="display: flex; justify-content: center; gap: 20px;">
                    <div style="text-align: center;">
                        <h3 style="color: #4CAF50; font-size: 36px;">100%</h3>
                        <p>Free to Use</p>
                    </div>
                    <div style="text-align: center;">
                        <h3 style="color: #2196F3; font-size: 36px;">24/7</h3>
                        <p>Access Anywhere</p>
                    </div>
                    <div style="text-align: center;">
                        <h3 style="color: #FF9800; font-size: 36px;">Easy</h3>
                        <p>To Use</p>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Footer -->
        <footer>
            <p>&copy; <?= date('Y') ?> Smart Wallet. All rights reserved.</p>
            <p style="margin-top: 10px; opacity: 0.8;">
                <a href="#" style="color: white; margin: 0 10px;">Privacy Policy</a> | 
                <a href="#" style="color: white; margin: 0 10px;">Terms of Service</a> | 
                <a href="#" style="color: white; margin: 0 10px;">Contact Us</a>
            </p>
        </footer>
    </div>
    
    <script>
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            if(targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            if(targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 80,
                    behavior: 'smooth'
                });
            }
        });
    });
    
    // Add animation to action cards
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.action-card');
        cards.forEach((card, index) => {
            card.style.animationDelay = `${index * 0.1}s`;
            card.classList.add('fade-in');
        });
    });
    </script>
</body>
</html>