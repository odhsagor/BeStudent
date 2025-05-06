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

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$group_url = isset($_GET['group_url']) ? $_GET['group_url'] : null;
if (!$group_url) {
    header("Location: groups.php");
    exit();
}

$stmt = $conn->prepare("SELECT * FROM groups WHERE group_url = ?");
$stmt->execute([$group_url]);
$group = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$group) {
    header("Location: groups.php");
    exit();
}

$stmt = $conn->prepare("SELECT * FROM group_members WHERE group_id = ? AND user_id = ?");
$stmt->execute([$group['id'], $_SESSION['user_id']]);
$membership = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($group['approve_by_creator'] == 1 && !$membership) {
        $stmt = $conn->prepare("INSERT INTO join_requests (group_id, user_id) VALUES (?, ?)");
        $stmt->execute([$group['id'], $_SESSION['user_id']]);
        $message = "Your join request has been sent. Await approval.";
    } elseif ($group['approve_by_creator'] == 0 && !$membership) {
        $stmt = $conn->prepare("INSERT INTO group_members (group_id, user_id) VALUES (?, ?)");
        $stmt->execute([$group['id'], $_SESSION['user_id']]);
        $message = "You have successfully joined the group!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join Group</title>
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
    <h1><?= htmlspecialchars($group['group_name']) ?></h1>
    <p><?= htmlspecialchars($group['description']) ?></p>

    <?php if (isset($message)): ?>
        <div class="alert"><?= $message ?></div>
    <?php endif; ?>

    <?php if ($group['approve_by_creator'] == 1 && !$membership): ?>
        <form method="POST">
            <button type="submit" name="join_group">Request to Join</button>
        </form>
    <?php elseif ($group['approve_by_creator'] == 0 && !$membership): ?>
        <p>You can join this group automatically.</p>
    <?php endif; ?>
</body>
</html>
