<?php
$message = "";
if (isset($_GET['error'])) {
    $message = $_GET['error'];
} elseif (isset($_GET['success'])) {
    $message = "Account created successfully ✔";
}
?>


<?php if ($message): ?>
<p><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-sm">
        <!-- Registration Form -->
        <form method="POST" action="actions/register_action.php" class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Create Account</h2>
            
            <!-- Message Display -->
            <?php if ($message): ?>
            <div class="mb-4 p-3 rounded-md <?php echo isset($_GET['error']) ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700'; ?>">
                <p><?= htmlspecialchars($message) ?></p>
            </div>
            <?php endif; ?>
            
            <!-- Full Name Input -->
            <div class="mb-4">
                <input 
                    type="text" 
                    name="full_name" 
                    placeholder="Full name" 
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
            </div>
            
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
                class="w-full bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 mb-4"
            >
                Create Account
            </button>
            
            <!-- Login Link -->
            <div class="text-center">
                <a 
                    href="login.php" 
                    class="inline-block w-full bg-gray-200 text-gray-800 py-2 px-4 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2"
                >
                    Already have an account? Login
                </a>
            </div>
        </form>
    </div>
</body>
</html>

