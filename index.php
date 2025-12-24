<?php
$message = "";
if (isset($_GET['error'])) {
    $message = $_GET['error'];
} elseif (isset($_GET['success'])) {
    $message = "Account created successfully ✔";
}
?>

<h2>Register</h2>

<?php if ($message): ?>
<p><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<form method="POST" action="actions/register_action.php">
    <input type="text" name="full_name" placeholder="Full name" required><br>
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <button type="submit">Create account</button>
</form>
<a href="login.php"><button>login</button></a>

