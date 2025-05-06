<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header("Location: login.php");
    exit();
}

$host = 'localhost';     
$dbname = 'bestudent';
$username = 'root';      
$password = '';  

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database Connection Failed: " . $e->getMessage());
}

$group_id = isset($_GET['group_id']) ? $_GET['group_id'] : null;

if (!$group_id) {
    die("Group ID not specified");
}

$stmt = $conn->prepare("SELECT * FROM groups WHERE id = ?");
$stmt->execute([$group_id]);
$group = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $conn->prepare("SELECT group_messages.*, users.name as sender_name 
                        FROM group_messages
                        JOIN users ON group_messages.sender_id = users.id
                        WHERE group_messages.group_id = ?
                        ORDER BY group_messages.created_at DESC");
$stmt->execute([$group_id]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = $_POST['message'];
    $file = $_FILES['file'];

    $file_path = null;
    if ($file && $file['error'] === UPLOAD_ERR_OK) {
        $file_type = $file['type'] == 'application/pdf' ? 'pdf' : 'image';  
        $file_path = 'uploads/groups/' . time() . '_' . basename($file['name']);
        if (move_uploaded_file($file['tmp_name'], $file_path)) {
        } else {
            $file_path = null;
        }
    }
    $stmt = $conn->prepare("INSERT INTO group_messages (group_id, sender_id, message, file_path) VALUES (?, ?, ?, ?)");
    try {
        $stmt->execute([$group_id, $_SESSION['user_id'], $message, $file_path]);
        $success = "Message sent successfully!";
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Group Chat | <?php echo htmlspecialchars($group['group_name']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .chat-container {
            height: 500px;
            overflow-y: scroll;
            border: 1px solid #ddd;
            padding: 10px;
        }

        .chat-message {
            padding: 10px;
            margin: 5px 0;
            border-bottom: 1px solid #ddd;
        }

        .chat-message img {
            max-width: 50px;
            max-height: 50px;
            margin-right: 10px;
        }

        .message-header {
            font-weight: bold;
        }

        .file-link {
            color: #007bff;
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h1>Group Chat: <?php echo htmlspecialchars($group['group_name']); ?></h1>

    <?php if (isset($success)): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    <div class="chat-container">
        <?php foreach ($messages as $message): ?>
            <div class="chat-message">
                <div class="message-header">
                    <?php echo htmlspecialchars($message['sender_name']); ?> <small><?php echo $message['created_at']; ?></small>
                </div>
                <div class="message-content">
                    <?php echo nl2br(htmlspecialchars($message['message'])); ?>
                    <?php if ($message['file_path']): ?>
                        <div class="file-content">
                            <a href="<?php echo htmlspecialchars($message['file_path']); ?>" class="file-link" target="_blank">View File</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <textarea class="form-control" name="message" placeholder="Type your message..." rows="3" required></textarea>
        </div>
        <div class="mb-3">
            <input type="file" class="form-control" name="file" accept="application/pdf, image/*">
        </div>
        <button type="submit" class="btn btn-primary">Send Message</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
