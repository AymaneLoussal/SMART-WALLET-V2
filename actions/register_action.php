<?php
require_once "../models/User.php";

$user = new User();

try {
    $user->register(
        $_POST["full_name"],
        $_POST["email"],
        $_POST["password"]
    );

    header("Location: ../index.php?success=1");
    exit;

} catch (Exception $e) {
    header("Location: ../index.php?error=" . urlencode($e->getMessage()));
    exit;
}
