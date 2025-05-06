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

$stmt = $conn->prepare("SELECT * FROM group_chat WHERE group_id = ? ORDER BY created_at DESC");
$stmt->execute([$group['id']]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = $_POST['message'];
    $media = null;

    if (isset($_FILES['media']) && $_FILES['media']['error'] == 0) {
        $media_name = time() . '_' . $_FILES['media']['name'];
        $media_path = 'uploads/' . $media_name;
        move_uploaded_file($_FILES['media']['tmp_name'], $media_path);
        $media = $media_path;
    }

    $stmt = $conn->prepare("INSERT INTO group_chat (group_id, user_id, message, media_url) VALUES (?, ?, ?, ?)");
    $stmt->execute([$group['id'], $_SESSION['user_id'], $message, $media]);

    header("Location: chat.php?group_url=" . $group['group_url']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat - <?= htmlspecialchars($group['group_name']) ?></title>
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
<h1>Chat - <?= htmlspecialchars($group['group_name']) ?></h1>

<h3>Messages</h3>
<?php foreach ($messages as $message): ?>
    <div>
        <strong><?= htmlspecialchars($message['user_name']) ?>:</strong>
        <p><?= htmlspecialchars($message['message']) ?></p>
        <?php if ($message['media_url']): ?>
            <img src="<?= htmlspecialchars($message['media_url']) ?>" alt="Media" style="max-width: 200px;">
        <?php endif; ?>
    </div>
<?php endforeach; ?>

<form action="chat.php?group_url=<?= htmlspecialchars($group['group_url']) ?>" method="POST" enctype="multipart/form-data">
    <textarea name="message" placeholder="Type your message..." required></textarea><br>
    <input type="file" name="media" accept="image/*,application/pdf"><br>
    <button type="submit">Send Message</button>
</form>

</body>
</html>
