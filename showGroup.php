<?php
require_once 'db_config.php';
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT g.id, g.name, 
                      (SELECT COUNT(*) FROM group_messages gm WHERE gm.group_id = g.id) as message_count,
                      (SELECT gm.message FROM group_messages gm WHERE gm.group_id = g.id ORDER BY gm.created_at DESC LIMIT 1) as last_message,
                      (SELECT u.name FROM group_messages gm JOIN users u ON gm.sender_id = u.id WHERE gm.group_id = g.id ORDER BY gm.created_at DESC LIMIT 1) as last_sender,
                      (SELECT gm.created_at FROM group_messages gm WHERE gm.group_id = g.id ORDER BY gm.created_at DESC LIMIT 1) as last_message_time
                      FROM groups g
                      JOIN group_members gm ON g.id = gm.group_id
                      WHERE gm.user_id = ? AND gm.status = 'approved'
                      ORDER BY last_message_time DESC");
$stmt->execute([$user_id]);
$user_groups = $stmt->fetchAll();
$admin_stmt = $pdo->prepare("SELECT COUNT(*) as pending_count 
                            FROM group_members gm
                            JOIN groups g ON gm.group_id = g.id
                            WHERE gm.status = 'pending'
                            AND g.creator_id = ?");
$admin_stmt->execute([$user_id]);
$pending_count = $admin_stmt->fetch()['pending_count'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Groups</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/showGroup.css">
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
    <h1>Join Groups</h1>
    
    <?php if ($pending_count > 0): ?>
        <p>
            <a href="group_approvals.php">You have <?= $pending_count ?> pending join requests</a>
            <span class="pending-count"><?= $pending_count ?></span>
        </p>
    <?php endif; ?>
    
    <?php if (empty($user_groups)): ?>
        <p>You haven't joined any groups yet. <a href="group.php">Browse groups</a> to join one!</p>
    <?php else: ?>
        <?php foreach ($user_groups as $group): ?>
            <div class="group-item" onclick="location.href='chat.php?group_id=<?= $group['id'] ?>'">
                <h3><?= htmlspecialchars($group['name']) ?></h3>
                <?php if ($group['message_count'] > 0): ?>
                    <p>
                        <strong><?= htmlspecialchars($group['last_sender']) ?>:</strong>
                        <?= htmlspecialchars(substr($group['last_message'], 0, 50)) ?>
                        <?= strlen($group['last_message']) > 50 ? '...' : '' ?>
                    </p>
                    <small><?= date('M j, g:i a', strtotime($group['last_message_time'])) ?></small>
                <?php else: ?>
                    <p>No messages yet</p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <p><a href="group.php">Browse all groups</a></p>


    
</body>
</html>