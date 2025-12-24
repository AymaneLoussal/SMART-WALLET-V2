<?php
session_start();
$error = isset($_GET['error']) ? $_GET['error'] : '';
?>

<h2>Login</h2>

<?php if ($error): ?>
<p><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form method="POST" action="actions/login_action.php">
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <button type="submit">Login</button>
</form>

<a href="index.php">
    <button>Create an account</button>
</a>
