<?php
require_once 'db_config.php';
if (!isset($_GET['group_id'])) {
    header("Location: showGroup.php");
    exit();
}

$group_id = $_GET['group_id'];
$stmt = $pdo->prepare("SELECT g.name, g.creator_id, gm.is_admin 
                      FROM groups g
                      JOIN group_members gm ON g.id = gm.group_id
                      WHERE g.id = ? AND gm.user_id = ? AND gm.status = 'approved'");
$stmt->execute([$group_id, $_SESSION['user_id']]);
$group_info = $stmt->fetch();

if (!$group_info) {
    header("Location: showGroup.php");
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_message'])) {
    $message = trim($_POST['message']);
    $attachment_path = null;
    $attachment_type = null;
    if (empty($message) && empty($_FILES['attachment']['name'])) {
        $_SESSION['error'] = "Message or attachment is required";
    } else {
        if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
            $file_type = mime_content_type($_FILES['attachment']['tmp_name']);
            
            if (array_key_exists($file_type, ALLOWED_TYPES)) {
                $file_ext = ALLOWED_TYPES[$file_type];
                $file_name = uniqid() . '.' . $file_ext;
                $file_path = UPLOAD_DIR . $file_name;
                
                if (move_uploaded_file($_FILES['attachment']['tmp_name'], $file_path)) {
                    $attachment_path = $file_path;
                    if (strpos($file_type, 'image/') === 0) {
                        $attachment_type = 'image';
                    } elseif ($file_type === 'application/pdf') {
                        $attachment_type = 'pdf';
                    } else {
                        $attachment_type = 'other';
                    }
                }
            } else {
                $_SESSION['error'] = "Invalid file type. Only images and PDFs are allowed.";
            }
        }
        
        if (!isset($_SESSION['error'])) {
            try {
                $stmt = $pdo->prepare("INSERT INTO group_messages (group_id, sender_id, message, attachment_path, attachment_type) 
                                      VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$group_id, $_SESSION['user_id'], $message, $attachment_path, $attachment_type]);
            } catch (PDOException $e) {
                $_SESSION['error'] = "Failed to send message. Please try again.";
            }
        }
    }
    header("Location: chat.php?group_id=$group_id");
    exit();
}
$stmt = $pdo->prepare("SELECT gm.*, u.name as sender_name, u.profile_image, u.role, u.department
                      FROM group_messages gm
                      JOIN users u ON gm.sender_id = u.id
                      WHERE gm.group_id = ?
                      ORDER BY gm.created_at ASC");
$stmt->execute([$group_id]);
$messages = $stmt->fetchAll();
$members_stmt = $pdo->prepare("SELECT u.id, u.name, u.profile_image, u.role, gm.is_admin
                             FROM group_members gm
                             JOIN users u ON gm.user_id = u.id
                             WHERE gm.group_id = ? AND gm.status = 'approved'
                             ORDER BY gm.is_admin DESC, u.name ASC");
$members_stmt->execute([$group_id]);
$members = $members_stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($group_info['name']) ?> - Chat</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/">
</head>
<body>

    <nav class="navbar">
            <div class="navbar-container">
                <a href="student_dashboard.php" class="navbar-brand">
                    <i class="fas fa-user-graduate"></i> Student Portal
                </a>
                
                <button class="navbar-toggler">
                    <i class="fas fa-bars"></i>
                </button>
                
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a href="student_dashboard.php" class="nav-link">
                            <i class="fas fa-book"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="group.php" class="nav-link">
                            <i class="fas fa-book"></i> Groups
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="showGroup.php" class="nav-link">
                            <i class="fas fa-book"></i> Show Group
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="group_approvals.php" class="nav-link">
                            <i class="fas fa-book"></i> Group approval
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="chat.php" class="nav-link">
                            <i class="fas fa-book"></i> Chat
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

    <div class="group-header">
        <h1><?= htmlspecialchars($group_info['name']) ?></h1>
    </div>
    
    <div class="chat-container">
        <div class="members-sidebar">
            <h3>Group Members</h3>
            <?php foreach ($members as $member): ?>
                <div class="member-item">
                    <img src="<?= htmlspecialchars($member['profile_image'] ?? 'default_avatar.png') ?>" 
                         alt="<?= htmlspecialchars($member['name']) ?>" class="member-avatar">
                    <span><?= htmlspecialchars($member['name']) ?></span>
                    <?php if ($member['is_admin']): ?>
                        <span class="admin-badge">Admin</span>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="chat-main">
            <?php if (isset($_SESSION['error'])): ?>
                <div class="error"><?= htmlspecialchars($_SESSION['error']) ?></div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
            
            <div class="messages">
                <?php if (empty($messages)): ?>
                    <p>No messages yet. Start the conversation!</p>
                <?php else: ?>
                    <?php foreach ($messages as $message): ?>
                        <div class="message <?= $message['sender_id'] == $_SESSION['user_id'] ? 'sent' : '' ?>">
                            <div class="message-info">
                                <img src="<?= htmlspecialchars($message['profile_image'] ?? 'default_avatar.png') ?>" 
                                     alt="<?= htmlspecialchars($message['sender_name']) ?>" class="sender-avatar">
                                <span class="sender-name"><?= htmlspecialchars($message['sender_name']) ?></span>
                                <span class="sender-role"><?= htmlspecialchars($message['role']) ?></span>
                                <span class="message-time"><?= date('M j, g:i a', strtotime($message['created_at'])) ?></span>
                            </div>
                            <div class="message-text"><?= htmlspecialchars($message['message']) ?></div>
                            
                            <?php if ($message['attachment_path']): ?>
                                <div class="attachment">
                                    <?php if ($message['attachment_type'] === 'image'): ?>
                                        <img src="<?= htmlspecialchars($message['attachment_path']) ?>" alt="Image attachment">
                                    <?php elseif ($message['attachment_type'] === 'pdf'): ?>
                                        <a href="<?= htmlspecialchars($message['attachment_path']) ?>" target="_blank">Download PDF</a>
                                    <?php else: ?>
                                        <a href="<?= htmlspecialchars($message['attachment_path']) ?>" target="_blank">Download File</a>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <form class="message-form" method="POST" action="chat.php?group_id=<?= $group_id ?>" enctype="multipart/form-data">
                <textarea name="message" placeholder="Type what you want to send..."></textarea>
                <div>
                    <input type="file" name="attachment" accept="image/*,application/pdf">
                    <button type="submit" name="send_message">Send</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>