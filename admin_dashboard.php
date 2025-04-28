<?php
session_start();

if (!isset($_SESSION['admin']) || $_SESSION['role'] != 'admin') {
    header("Location: admin_login.php");
    exit();
}

echo "<h1>Welcome to the Admin Dashboard!</h1>";
echo "<a href='logout.php'>Logout</a>";
?>
