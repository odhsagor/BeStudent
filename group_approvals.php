<?php
require_once 'db_config.php';
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT gm.id as request_id, g.id as group_id, g.name as group_name, 
                      u.id as user_id, u.name as user_name, u.email, u.role, u.department, u.profile_image
                      FROM group_members gm
                      JOIN groups g ON gm.group_id = g.id
                      JOIN users u ON gm.user_id = u.id
                      WHERE gm.status = 'pending' 
                      AND g.creator_id = ?");
$stmt->execute([$user_id]);
$pending_requests = $stmt->fetchAll();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['process_request'])) {
    $request_id = $_POST['request_id'];
    $action = $_POST['action'];
    
    try {
        $stmt = $pdo->prepare("UPDATE group_members SET status = ? WHERE id = ?");
        $stmt->execute([$action, $request_id]);
        if ($action === 'approved') {
            $notif_stmt = $pdo->prepare("INSERT INTO group_notifications (user_id, group_id, message) 
                                        SELECT user_id, group_id, CONCAT('Your join request for group ', 
                                        (SELECT name FROM groups WHERE id = group_id), ' has been approved')
                                        FROM group_members WHERE id = ?");
            $notif_stmt->execute([$request_id]);
        }
        
        $_SESSION['success'] = "Request processed successfully";
    } catch (PDOException $e) {
        $_SESSION['error'] = "Failed to process request. Please try again.";
    }
    header("Location: group_approvals.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Group Join Requests</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/group_approval.css">
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


    <h1>Group Join Requests</h1>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="error"><?= htmlspecialchars($_SESSION['error']) ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="success"><?= htmlspecialchars($_SESSION['success']) ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    
    <?php if (empty($pending_requests)): ?>
        <p>No pending join requests.</p>
    <?php else: ?>
        <?php foreach ($pending_requests as $request): ?>
            <div class="request-card">
                <h3>Request to join: <?= htmlspecialchars($request['group_name']) ?></h3>
                
                <div class="user-info">
                    <img src="<?= htmlspecialchars($request['profile_image'] ?? 'default_avatar.png') ?>" 
                         alt="<?= htmlspecialchars($request['user_name']) ?>" class="user-avatar">
                    <div>
                        <strong><?= htmlspecialchars($request['user_name']) ?></strong><br>
                        <?= htmlspecialchars($request['email']) ?><br>
                        <?= htmlspecialchars($request['role']) ?> - <?= htmlspecialchars($request['department']) ?>
                    </div>
                </div>
                
                <form method="POST" action="group_approvals.php" style="display: inline;">
                    <input type="hidden" name="request_id" value="<?= $request['request_id'] ?>">
                    <input type="hidden" name="action" value="approved">
                    <button type="submit" name="process_request" class="action-btn approve-btn">Approve</button>
                </form>
                
                <form method="POST" action="group_approvals.php" style="display: inline;">
                    <input type="hidden" name="request_id" value="<?= $request['request_id'] ?>">
                    <input type="hidden" name="action" value="rejected">
                    <button type="submit" name="process_request" class="action-btn reject-btn">Reject</button>
                </form>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <p><a href="group.php">Back to Groups</a></p>



    
</body>
</html>