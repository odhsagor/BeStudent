<?php
session_start();

// Check if the user is logged in and has the 'student' role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Student Dashboard | BeStudent</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="student_dashboard.php">BeStudent</a>
        <div class="navbar-nav ml-auto">
            <span class="navbar-text">Welcome, <?php echo $_SESSION['name']; ?>!</span>
            <a class="nav-item nav-link" href="logout.php">Logout</a>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h2 class="text-center mb-4">Student Dashboard</h2>
    <p>Welcome to the Student Dashboard! Here you can access your learning resources, forums, and more.</p>
    <!-- Add content specific to the student role here -->
</div>

</body>
</html>
