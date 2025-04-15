<?php
// dashboard.php
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard | BeStudent</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-5">
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['user_name'], ENT_QUOTES, 'UTF-8'); ?>!</h2>
    <p>You are logged in as: <strong><?php echo htmlspecialchars($_SESSION['user_role'], ENT_QUOTES, 'UTF-8'); ?></strong></p>
    <p>Your email: <?php echo htmlspecialchars($_SESSION['user_email'], ENT_QUOTES, 'UTF-8'); ?></p>
    <p>Your last login time is updated on successful authentication.</p>
    <a href="logout.php" class="btn btn-danger mt-3">Logout</a>
</body>
</html>
