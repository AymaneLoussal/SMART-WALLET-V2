<?php
session_start();
require_once "../models/User.php";

$user = new User();

try {

    $user->login($_POST["email"], $_POST["password"]);

    $_SESSION["user_id"] = $user->getUserId();
    $_SESSION["user_email"] = $_POST["email"];

    header("Location: ../home.php");
    exit;

} catch (Exception $e) {

    header("Location: ../login.php?error=" . urlencode($e->getMessage()));
    exit;
}
