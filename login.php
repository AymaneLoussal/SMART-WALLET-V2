<?php
session_start();
$error = isset($_GET['error']) ? $_GET['error'] : '';
?>


<?php if ($error): ?>
<p><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-sm">
        <!-- Login Form -->
        <form method="POST" action="actions/login_action.php" class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Login</h2>
            
            <!-- Email Input -->
            <div class="mb-4">
                <input 
                    type="email" 
                    name="email" 
                    placeholder="Email" 
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
            </div>
            
            <!-- Password Input -->
            <div class="mb-6">
                <input 
                    type="password" 
                    name="password" 
                    placeholder="Password" 
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
            </div>
            
            <!-- Submit Button -->
            <button 
                type="submit" 
                class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
            >
                Login
            </button>
        </form>
        
        <!-- Create Account Button -->
        <div class="mt-4 text-center">
            <a 
                href="index.php" 
                class="inline-block w-full bg-gray-200 text-gray-800 py-2 px-4 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2"
            >
                Create an account
            </a>
        </div>
    </div>
</body>
</html>
