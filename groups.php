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
$student_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_group'])) {
    $group_name = $_POST['group_name'];
    $description = $_POST['description'];
    $approve_by_creator = isset($_POST['approve_by_creator']) ? 1 : 0;
    $group_url = uniqid('group_');  

    $stmt = $conn->prepare("INSERT INTO groups (group_name, description, creator_id, approve_by_creator, group_url) VALUES (?, ?, ?, ?, ?)");
    try {
        $stmt->execute([$group_name, $description, $student_id, $approve_by_creator, $group_url]);
        $success = "Group created successfully!";
    } catch (PDOException $e) {
        $error = "Something went wrong: " . $e->getMessage();
    }
}
$groups_query = "SELECT * FROM groups WHERE group_name LIKE :search";
$search = isset($_POST['search']) ? "%" . $_POST['search'] . "%" : "%";
$stmt = $conn->prepare($groups_query);
$stmt->execute(['search' => $search]);
$groups = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['join_group'])) {
    $group_id = $_GET['join_group'];
    $stmt = $conn->prepare("SELECT approve_by_creator FROM groups WHERE id = ?");
    $stmt->execute([$group_id]);
    $group = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($group['approve_by_creator'] == 1) {
        $stmt = $conn->prepare("INSERT INTO group_members (group_id, student_id, status) VALUES (?, ?, 'pending')");
        $stmt->execute([$group_id, $student_id]);
        $success = "Your join request has been sent. Await approval.";
    } else {
        $stmt = $conn->prepare("INSERT INTO group_members (group_id, student_id, status) VALUES (?, ?, 'approved')");
        $stmt->execute([$group_id, $student_id]);
        $success = "You have successfully joined the group!";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Groups | Student Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h1>Groups</h1>
    <?php if (isset($success)): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <h2>Create New Group</h2>
    <form method="POST">
        <div class="mb-3">
            <label for="group_name" class="form-label">Group Name</label>
            <input type="text" class="form-control" id="group_name" name="group_name" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
        </div>
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="approve_by_creator" name="approve_by_creator">
            <label class="form-check-label" for="approve_by_creator">Approve new members (Group creator must approve join requests)</label>
        </div>
        <button type="submit" class="btn btn-primary mt-3" name="create_group">Create Group</button>
    </form>
    <h2 class="mt-5">Search Groups</h2>
    <form method="POST">
        <div class="input-group mb-3">
            <input type="text" class="form-control" name="search" placeholder="Search group by name" value="<?php echo isset($_POST['search']) ? $_POST['search'] : ''; ?>">
            <button class="btn btn-outline-secondary" type="submit">Search</button>
        </div>
    </form>
    <h3>Available Groups</h3>
    <div class="list-group">
        <?php foreach ($groups as $group): ?>
            <div class="list-group-item">
                <h5 class="mb-1"><?php echo htmlspecialchars($group['group_name']); ?></h5>
                <p class="mb-1"><?php echo htmlspecialchars($group['description']); ?></p>
                <a href="?join_group=<?php echo $group['id']; ?>" class="btn btn-sm btn-primary">Join Group</a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
