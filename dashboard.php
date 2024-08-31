<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login_form.php?error=unauthorized");
    exit();
}

echo "Welcome, " . htmlspecialchars($_SESSION['username']) . "!";
?>

