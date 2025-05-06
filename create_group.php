<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
$host = 'localhost';
$dbname = 'bestudent';
$username = 'root';
$password = '';
$conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $group_name = $_POST['group_name'];
    $group_url = strtolower(str_replace(" ", "-", $group_name)); 
    $description = $_POST['description'];
    $approve_by_creator = $_POST['approve_by_creator'];

    $stmt = $conn->prepare("INSERT INTO groups (group_name, group_url, description, approve_by_creator) VALUES (?, ?, ?, ?)");
    $stmt->execute([$group_name, $group_url, $description, $approve_by_creator]);

    header("Location: group.php?group_url=" . $group_url);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Group</title>
</head>
<body>



<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="student_dashboard.php">
                <i class="fas fa-user-graduate"></i> Student Portal
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="student_dashboard.php"><i class="fas fa-book"></i> Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="create_group.php"><i class="fas fa-book"></i> create group</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="groups.php"><i class="fas fa-book"></i> Groups</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="chat.php"><i class="fas fa-book-open"></i> chat</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="joinGroup.php"><i class="fas fa-users"></i> join Group</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="notifications.php">
                            <i class="fas fa-bell"></i> Notifications
                            <span class="badge rounded-pill bg-accent ms-1">3</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <form action="create_group.php" method="POST">
        <label for="group_name">Group Name:</label>
        <input type="text" name="group_name" required><br>

        <label for="description">Description:</label>
        <textarea name="description" required></textarea><br>

        <label for="approve_by_creator">Require Approval:</label>
        <select name="approve_by_creator" required>
            <option value="1">Yes, Approval Required</option>
            <option value="0">No, Auto Join</option>
        </select><br>

        <button type="submit">Create Group</button>
    </form>
</body>
</html>
