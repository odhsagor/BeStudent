<?php
session_start();
require_once 'db_config.php';

if (!isset($_SESSION['admin']) || $_SESSION['admin']['role'] !== 'admin') {
    header("Location: adminRegister.php");
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = $_POST['role'];
    $created_by = $_SESSION['admin']['id'];

    // Validation
    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "All fields are required!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format!";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords don't match!";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters!";
    } else {
        try {
            // Check if email exists
            $stmt = $conn->prepare("SELECT id FROM admin_users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();
            
            if ($stmt->num_rows > 0) {
                $error = "Email already exists!";
            } else {
                // Handle profile picture upload
                $profile_picture = null;
                if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
                    $upload_dir = 'uploads/profiles/';
                    if (!is_dir($upload_dir)) {
                        mkdir($upload_dir, 0755, true);
                    }
                    
                    $file_ext = pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION);
                    $file_name = uniqid() . '.' . $file_ext;
                    $target_file = $upload_dir . $file_name;
                    
                    if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_file)) {
                        $profile_picture = $target_file;
                    }
                }
                
                // Hash password
                $hashed_password = password_hash($password, PASSWORD_BCRYPT);
                
                // Insert new user
                $insert = $conn->prepare("INSERT INTO admin_users 
                    (name, email, password, role, profile_picture, created_by) 
                    VALUES (?, ?, ?, ?, ?, ?)");
                $insert->bind_param("sssssi", $name, $email, $hashed_password, $role, $profile_picture, $created_by);
                
                if ($insert->execute()) {
                    $success = "User registered successfully!";
                    $_POST = []; // Clear form
                } else {
                    $error = "Registration failed: " . $conn->error;
                }
            }
        } catch (Exception $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register Admin/Support</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .profile-preview {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container bg-white">
            <h2 class="text-center mb-4">Register New User</h2>
            
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3 text-center">
                    <img id="profilePreview" src="https://via.placeholder.com/150" class="profile-preview">
                    <input type="file" class="form-control d-none" id="profile_picture" name="profile_picture" accept="image/*">
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="document.getElementById('profile_picture').click()">
                        Choose Profile Picture
                    </button>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" value="<?php echo $_POST['name'] ?? ''; ?>" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?php echo $_POST['email'] ?? ''; ?>" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="confirm_password" class="form-control" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-select" required>
                        <option value="admin" <?php echo ($_POST['role'] ?? '') === 'admin' ? 'selected' : ''; ?>>Admin</option>
                        <option value="support" <?php echo ($_POST['role'] ?? '') === 'support' ? 'selected' : ''; ?>>Support Team</option>
                    </select>
                </div>
                
                <button type="submit" class="btn btn-primary w-100">Register User</button>
            </form>
        </div>
    </div>

    <script>
        // Profile picture preview
        document.getElementById('profile_picture').addEventListener('change', function(e) {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profilePreview').src = e.target.result;
                }
                reader.readAsDataURL(this.files[0]);
            }
        });
    </script>
</body>
</html>