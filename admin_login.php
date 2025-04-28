<?php
session_start();

if (isset($_SESSION['admin'])) {
    if ($_SESSION['role'] == 'admin') {
        header("Location: admin_dashboard.php"); 
        exit();
    } elseif ($_SESSION['role'] == 'support') {
        header("Location: support_dashboard.php"); 
        exit();
    }
}
$error_message = '';
if (isset($_GET['error']) && $_GET['error'] == 'role_mismatch') {
    $error_message = "Incorrect role selected for this username. Please select the correct role.";
} elseif (isset($_GET['error']) && $_GET['error'] == 'invalid_credentials') {
    $error_message = "Invalid username or password. Please try again.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin/Support Login | BeStudent</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Login</h2>
        <?php if ($error_message): ?>
            <div class="alert alert-danger">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
        
        <form action="admin_login_process.php" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select class="form-control" id="role" name="role" required>
                    <option value="admin">Admin</option>
                    <option value="support">Support</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Login</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
