<?php
session_start();

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <style>
        :root {
    --primary: #6C5CE7;
    --secondary: #A55EEA;
    --accent: #FD79A8;
    --dark: #2D3436;
    --light: #F5F6FA;
    --white: #ffffff;
    --gray: #64748B;
    
    --main-gradient: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 50%, var(--accent) 100%);
    --glass-gradient: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
    
    --glass-bg: rgba(255, 255, 255, 0.15);
    --glass-border: rgba(255, 255, 255, 0.2);
    --soft-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    --text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    
    --section-padding: 6rem 0;
    --card-radius: 1.5rem;
}

/* Body and Global Styles */
body {
    font-family: 'Poppins', sans-serif;
    background-color: var(--light);
    color: var(--dark);
    padding-top: 70px; /* For fixed navbar */
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}

h1, h2, h3, h4 {
    font-weight: 700;
    line-height: 1.2;
}

a {
    text-decoration: none;
    color: inherit;
}

img {
    max-width: 100%;
    height: auto;
}

/* Navbar */
.navbar {
    position: fixed;
    width: 100%;
    padding: 1.5rem 0;
    background: var(--main-gradient);
    box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
    z-index: 1000;
    transition: all 0.4s ease;
}

.navbar.scrolled {
    padding: 1rem 0;
    backdrop-filter: blur(10px);
    background: rgba(108, 92, 231, 0.9) !important;
}

.navbar-brand img {
    height: 3.5rem;
    transition: transform 0.3s ease;
}

.navbar-brand:hover img {
    transform: rotate(-5deg) scale(1.05);
}

.nav-link {
    font-weight: 500;
    padding: 0.5rem 1rem !important;
    position: relative;
    color: rgba(255,255,255,0.9);
    transition: all 0.3s ease;
}

.nav-link:not(.btn-gradient)::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 1rem;
    width: 0;
    height: 2px;
    background: white;
    transition: width 0.3s ease;
}

.nav-link:not(.btn-gradient):hover::after {
    width: calc(100% - 2rem);
}

/* Login Page Container */
.login-container {
    perspective: 1000px;
    flex: 1;
    display: flex;
    justify-content: center;
    align-items: center;
}

.login-card {
    border: none;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    transform-style: preserve-3d;
    transition: all 0.5s ease;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
}

.login-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
}

/* Header of Login */
.login-header {
    background: var(--main-gradient);
    color: white;
    padding: 1.5rem;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.login-header::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
    transform: rotate(30deg);
    animation: shine 3s infinite;
}

@keyframes shine {
    0% { transform: rotate(30deg) translate(-30%, -30%); }
    100% { transform: rotate(30deg) translate(30%, 30%); }
}

.brand-logo {
    font-weight: 700;
    font-size: 1.8rem;
    letter-spacing: 1px;
    display: inline-block;
    margin-bottom: 0.5rem;
}

.brand-slogan {
    font-size: 0.9rem;
    opacity: 0.9;
}

/* Form Fields */
.form-control {
    border-radius: 8px;
    padding: 12px 15px;
    border: 1px solid #e0e0e0;
    transition: all 0.3s;
}

.form-control:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
}

.btn-login {
    background: var(--main-gradient);
    border: none;
    border-radius: 8px;
    padding: 12px;
    font-weight: 600;
    letter-spacing: 0.5px;
    transition: all 0.3s;
    position: relative;
    overflow: hidden;
}

.btn-login:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(67, 97, 238, 0.4);
}

.btn-login::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: var(--secondary);
    opacity: 0;
    transition: opacity 0.3s;
}

.btn-login:hover::after {
    opacity: 1;
}

/* Role Selection */
.role-icon {
    margin-right: 8px;
    color: var(--primary);
}

footer {
    background: linear-gradient(to right, var(--dark), #343a40);
    color: white;
    padding: 2rem 0;
    margin-top: auto;
}

.footer-logo img {
    height: 30px;
    margin-bottom: 1rem;
}

.footer-links-column h5 {
    color: var(--accent);
    margin-bottom: 1rem;
}

.footer-links-column ul {
    list-style: none;
    padding-left: 0;
}

.footer-links-column ul li {
    margin-bottom: 0.5rem;
}

.footer-links-column ul li a {
    color: rgba(255, 255, 255, 0.7);
    text-decoration: none;
    transition: all 0.3s;
}

.footer-links-column ul li a:hover {
    color: white;
    padding-left: 5px;
}

.social-icons a {
    color: white;
    background: rgba(255, 255, 255, 0.1);
    width: 36px;
    height: 36px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    margin-right: 0.5rem;
    transition: all 0.3s;
}

.social-icons a:hover {
    background: var(--accent);
    transform: translateY(-3px);
}

.copyright {
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    padding-top: 1.5rem;
    margin-top: 1.5rem;
    font-size: 0.9rem;
    color: rgba(255, 255, 255, 0.6);
}

/* Footer Links */
.footer-links {
    display: flex;
    justify-content: space-between;
    margin-top: 1rem;
    font-size: 0.9rem;
}

.footer-links a {
    color: var(--dark);
    text-decoration: none;
    transition: color 0.3s;
}

.footer-links a:hover {
    color: var(--primary);
}

    </style>
</head>
<body>
    <!-- Navbar with Large Logo -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="Image/BeStudent.png" alt="BeStudent Logo" class="logo-img">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link btn-gradient mx-2" href="index.Php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="getStarted.php">Get Started</a></li>
                    <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="courses.php">Courses</a></li>
                    <li class="nav-item"><a class="nav-link" href="book.php">Book</a></li>
                    <li class="nav-item"><a class="nav-link" href="adminRegister.php">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="admin_login.php">Administrator Login</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container min-vh-100 d-flex align-items-center justify-content-center login-container">
        <div class="login-card animate__animated animate__fadeIn" style="width: 100%; max-width: 420px;">
            <div class="login-header animate__animated animate__fadeInDown">
                <div class="brand-logo">Be<span style="color: var(--accent-color);">Student</span></div>
                <div class="brand-slogan">Empowering your educational journey</div>
            </div>
            
            <div class="card-body p-4">
                <?php if ($error): ?>
                    <div class="alert alert-danger animate__animated animate__shakeX">
                        <i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="mb-3 animated-field delay-1">
                        <label for="email" class="form-label">Email address</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email" name="email" class="form-control" placeholder="Enter your email" required />
                        </div>
                    </div>

                    <div class="mb-3 animated-field delay-2">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" name="password" class="form-control" placeholder="Enter your password" required />
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="mb-4 animated-field delay-3">
                        <label for="role" class="form-label">Select Role</label>
                        <select name="role" class="form-select" required>
                            <option value="" disabled selected>Select your role</option>
                            <option value="student"><i class="fas fa-user-graduate role-icon"></i> Student</option>
                            <option value="SoD"><i class="fas fa-user-tie role-icon"></i> SoD</option>
                            <option value="faculty"><i class="fas fa-chalkboard-teacher role-icon"></i> Faculty</option>
                        </select>
                    </div>

                    <div class="d-grid gap-2 animated-field delay-4">
                        <button type="submit" class="btn btn-login text-white">
                            <i class="fas fa-sign-in-alt me-2"></i>Sign In
                        </button>
                    </div>
                </form>

                <div class="text-center mt-4">
                    <p class="mb-2">Don't have an account? <a href="getStarted.php" class="text-primary">Register here</a></p>
                    <div class="footer-links">
                        <a href="#"><i class="fas fa-question-circle me-1"></i>Help</a>
                        <a href="#"><i class="fas fa-lock me-1"></i>Forgot password?</a>
                        <a href="#"><i class="fas fa-info-circle me-1"></i>About</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Section -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4 mb-md-0">
                    <div class="footer-logo">
                        <img src="Image/BeStudent.png" alt="BeStudent Logo" style="height: 35px;">
                    </div>
                    <p class="mt-2" style="color: rgba(255, 255, 255, 0.7);">Empowering students and educators with innovative learning solutions.</p>
                    <div class="social-icons mt-3">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="col-md-2 col-6 mb-4 mb-md-0">
                    <div class="footer-links-column">
                        <h5>Quick Links</h5>
                        <ul>
                            <li><a href="index.php">Home</a></li>
                            <li><a href="#courses">Courses</a></li>
                            <li><a href="#SoDing">SoDing</a></li>
                            <li><a href="#about">About Us</a></li>
                            <li><a href="getStarted.php">Get Started</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-2 col-6 mb-4 mb-md-0">
                    <div class="footer-links-column">
                        <h5>Resources</h5>
                        <ul>
                            <li><a href="#">Blog</a></li>
                            <li><a href="#">FAQs</a></li>
                            <li><a href="#">Help Center</a></li>
                            <li><a href="#">SoDials</a></li>
                            <li><a href="#">Webinars</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="footer-links-column">
                        <h5>Contact Us</h5>
                        <ul>
                            <li><i class="fas fa-map-marker-alt me-2"></i> Dhaka, Bangladesh</li>
                            <li><i class="fas fa-phone me-2"></i> +8801312304166</li>
                            <li><i class="fas fa-envelope me-2"></i> info@bestudent.com</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 text-center copyright">
                    <p>&copy; <?php echo date("Y"); ?> BeStudent. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>

        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.querySelector('input[name="password"]');
            const icon = this.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
        

        const animatedElements = document.querySelectorAll('.animated-field');
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate__animated', 'animate__fadeInUp');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });
        
        animatedElements.forEach(element => {
            observer.observe(element);
        });
    </script>
</body>
</html>