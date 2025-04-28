<?php
session_start();

// Check if the user is logged in and is Support
if (!isset($_SESSION['admin']) || $_SESSION['role'] != 'support') {
    header("Location: admin_login.php");
    exit();
}

echo "<h1>Welcome to the Support Dashboard!</h1>";
echo "<a href='logout.php'>Logout</a>";
?>
