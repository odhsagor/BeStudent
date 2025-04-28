<?php
include('db_connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $admin_username = mysqli_real_escape_string($conn, $_POST['username']);
    $admin_password = mysqli_real_escape_string($conn, $_POST['password']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $sql = "SELECT * FROM admins WHERE username='$admin_username' AND role='$role'";

    $result = $conn->query($sql);
    $admin_data = $result->fetch_assoc();

    if ($result->num_rows > 0) {
        if (password_verify($admin_password, $admin_data['password'])) {
            session_start();
            $_SESSION['admin'] = $admin_username; 
            $_SESSION['role'] = $admin_data['role']; 

            if ($admin_data['role'] == 'admin') {
                header("Location: admin_dashboard.php"); 
            } else {
                header("Location: support_dashboard.php"); 
            }
            exit();
        } else {
            echo "<script>alert('Invalid password. Please try again.'); window.location.href='admin_login.php';</script>";
        }
    } else {
        echo "<script>alert('Invalid username or role. Please try again.'); window.location.href='admin_login.php';</script>";
    }
}
$conn->close();
?>
