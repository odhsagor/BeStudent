<?php
session_start();

// Database connection (adjust credentials as needed)
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'bestudent';

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role'];

    if (empty($email) || empty($password) || empty($role)) {
        $error = "Please fill in all fields, including selecting a role.";
    } else {
        // Look for the user by email
        $stmt = $conn->prepare("SELECT id, name, email, password, role FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($id, $name, $email_db, $hashedPassword, $userRole);
            $stmt->fetch();

            if (password_verify($password, $hashedPassword) && $userRole === $role) {
                // Login success
                $_SESSION['user_id'] = $id;
                $_SESSION['name'] = $name;
                $_SESSION['email'] = $email_db;
                $_SESSION['role'] = $role;

                // Role-based redirection
                if ($role === 'student') {
                    header("Location: student_dashboard.php");
                } elseif ($role === 'SoD') {
                    header("Location: sod_dashboard.php");
                } elseif ($role === 'faculty') {
                    header("Location: faculty_dashboard.php");
                }
                exit;
            } else {
                $error = "Invalid password or role mismatch.";
            }
        } else {
            $error = "User not found.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Login | BeStudent</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
</head>
<body class="bg-light">

<div class="container d-flex align-items-center justify-content-center min-vh-100">
    <div class="card p-4 shadow" style="width: 100%; max-width: 400px;">
        <h3 class="text-center mb-4">Login to <span class="text-primary">BeStudent</span></h3>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" name="email" class="form-control" required />
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required />
            </div>

            <div class="mb-3">
                <label for="role" class="form-label">Select Role</label>
                <select name="role" class="form-select" required>
                    <option value="" disabled selected>Select your role</option>
                    <option value="student">Student</option>
                    <option value="SoD">SoD</option>
                    <option value="faculty">Faculty</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary w-100">Sign In</button>
        </form>

        <p class="text-center mt-3">
            Don't have an account? <a href="getStarted.php">Register</a>
        </p>
    </div>
</div>

</body>
</html>
