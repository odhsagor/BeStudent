<?php
require_once 'db_config.php';
$user_id = $_SESSION['user_id'];
$department = $_SESSION['department'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_group'])) {
    $group_name = trim($_POST['group_name']);
    $description = trim($_POST['description']);
    $requires_approval = isset($_POST['requires_approval']) ? 1 : 0;
    $join_code = bin2hex(random_bytes(5));
    
    try {
        $check_stmt = $pdo->prepare("SELECT id FROM groups WHERE name = ?");
        $check_stmt->execute([$group_name]);
        
        if ($check_stmt->rowCount() > 0) {
            $_SESSION['error'] = "Group name already exists";
        } else {
            $stmt = $pdo->prepare("INSERT INTO groups (name, description, creator_id, join_code, requires_approval, department) 
                                  VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$group_name, $description, $user_id, $join_code, $requires_approval, $department]);
            
            $group_id = $pdo->lastInsertId();
            $stmt = $pdo->prepare("INSERT INTO group_members (group_id, user_id, is_admin, status) 
                                  VALUES (?, ?, 1, 'approved')");
            $stmt->execute([$group_id, $user_id]);
            
            $_SESSION['success'] = "Group created successfully!";
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Failed to create group. Please try again.";
    }
    header("Location: group.php");
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['join_group'])) {
    $group_id = $_POST['group_id'];
    
    try {
        $check_stmt = $pdo->prepare("SELECT id FROM group_members WHERE group_id = ? AND user_id = ?");
        $check_stmt->execute([$group_id, $user_id]);
        
        if ($check_stmt->rowCount() > 0) {
            $_SESSION['error'] = "You are already a member of this group";
        } else {
            $group_stmt = $pdo->prepare("SELECT requires_approval FROM groups WHERE id = ?");
            $group_stmt->execute([$group_id]);
            $group = $group_stmt->fetch();
            
            $status = $group['requires_approval'] ? 'pending' : 'approved';
            
            $stmt = $pdo->prepare("INSERT INTO group_members (group_id, user_id, status) 
                                  VALUES (?, ?, ?)");
            $stmt->execute([$group_id, $user_id, $status]);
            
            if ($status === 'pending') {
                $_SESSION['success'] = "Join request sent. Waiting for approval.";
            } else {
                $_SESSION['success'] = "You have successfully joined the group!";
            }
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Failed to join group. Please try again.";
    }
    header("Location: group.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['join_by_code'])) {
    $join_code = trim($_POST['join_code']);
    
    try {
        $stmt = $pdo->prepare("SELECT id, requires_approval FROM groups WHERE join_code = ?");
        $stmt->execute([$join_code]);
        $group = $stmt->fetch();
        
        if ($group) {
            $check_stmt = $pdo->prepare("SELECT id FROM group_members WHERE group_id = ? AND user_id = ?");
            $check_stmt->execute([$group['id'], $user_id]);
            
            if ($check_stmt->rowCount() > 0) {
                $_SESSION['error'] = "You are already a member of this group";
            } else {
                $status = $group['requires_approval'] ? 'pending' : 'approved';
                
                $stmt = $pdo->prepare("INSERT INTO group_members (group_id, user_id, status) 
                                      VALUES (?, ?, ?)");
                $stmt->execute([$group['id'], $user_id, $status]);
                
                if ($status === 'pending') {
                    $_SESSION['success'] = "Join request sent. Waiting for approval.";
                } else {
                    $_SESSION['success'] = "You have successfully joined the group!";
                }
            }
        } else {
            $_SESSION['error'] = "Invalid join code";
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Failed to join group. Please try again.";
    }
    header("Location: group.php");
    exit();
}
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$department_filter = isset($_GET['department']) ? $_GET['department'] : '';
$where = "WHERE 1=1";
$params = [$user_id, $user_id];

if (!empty($search)) {
    $where .= " AND g.name LIKE ?";
    $params[] = "%$search%";
}

if (!empty($department_filter)) {
    $where .= " AND g.department = ?";
    $params[] = $department_filter;
}

$query = "SELECT g.*, 
          (SELECT COUNT(*) FROM group_members gm WHERE gm.group_id = g.id AND gm.user_id = ?) as is_member,
          (SELECT status FROM group_members gm WHERE gm.group_id = g.id AND gm.user_id = ?) as member_status,
          u.name as creator_name
          FROM groups g
          JOIN users u ON g.creator_id = u.id
          $where
          ORDER BY g.created_at DESC";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$groups = $stmt->fetchAll();

$dept_stmt = $pdo->query("SELECT DISTINCT department FROM users WHERE department != '' ORDER BY department");
$departments = $dept_stmt->fetchAll(PDO::FETCH_COLUMN);
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
    <title>Groups</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/group.css">
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


    <h1>Groups</h1>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="error"><?= htmlspecialchars($_SESSION['error']) ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="success"><?= htmlspecialchars($_SESSION['success']) ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    
    <form method="GET" action="group.php" class="form-group">
        <div>
            <input type="text" name="search" placeholder="Search groups..." value="<?= htmlspecialchars($search) ?>">
        </div>
        <div>
            <select name="department">
                <option value="">All Departments</option>
                <?php foreach ($departments as $dept): ?>
                    <option value="<?= htmlspecialchars($dept) ?>" <?= $department_filter === $dept ? 'selected' : '' ?>>
                        <?= htmlspecialchars($dept) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit">Search</button>
    </form>
    
    <?php if ($pending_count > 0): ?>
        <p>
            <a href="group_approvals.php">You have <?= $pending_count ?> pending join requests</a>
            <span class="pending-count"><?= $pending_count ?></span>
        </p>
    <?php endif; ?>
    
    <h2>Available Groups</h2>
    <?php if (empty($groups)): ?>
        <p>No groups found matching your criteria.</p>
    <?php else: ?>
        <?php foreach ($groups as $group): ?>
            <div class="group-card">
                <h3><?= htmlspecialchars($group['name']) ?></h3>
                <p><?= htmlspecialchars($group['description']) ?></p>
                <p><small>Created by: <?= htmlspecialchars($group['creator_name']) ?> | Department: <?= htmlspecialchars($group['department']) ?></small></p>
                
                <?php if ($group['is_member']): ?>
                    <?php if ($group['member_status'] === 'pending'): ?>
                        <span class="pending">Join request pending approval</span>
                    <?php else: ?>
                        <span>Already a member</span>
                    <?php endif; ?>
                <?php else: ?>
                    <form method="POST" action="group.php" style="display: inline;">
                        <input type="hidden" name="group_id" value="<?= $group['id'] ?>">
                        <button type="submit" name="join_group" class="join-btn">Join</button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <h2>Join Group with Code</h2>
    <form method="POST" action="group.php" class="form-group">
        <input type="text" name="join_code" placeholder="Enter group code">
        <button type="submit" name="join_by_code">Join with Code</button>
    </form>
    
    <h2>Create New Group</h2>
    <form method="POST" action="group.php" class="form-group">
        <div>
            <label>Group Name:</label>
            <input type="text" name="group_name" required>
        </div>
        <div>
            <label>Description:</label>
            <textarea name="description"></textarea>
        </div>
        <div>
            <label>
                <input type="checkbox" name="requires_approval" checked>
                Approve new members (Group creator must approve join requests)
            </label>
        </div>
        <button type="submit" name="create_group">Create Group</button>
    </form>


    
    
</body>
</html>