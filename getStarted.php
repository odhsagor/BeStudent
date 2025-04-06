<?php

$host = 'localhost';     // or 127.0.0.1
$dbname = 'bestudent';
$username = 'root';      // use your MySQL username
$password = '';          // use your MySQL password

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database Connection Failed: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name       = trim($_POST['name']);
    $email      = trim($_POST['email']);
    $password   = $_POST['password'];
    $role       = $_POST['role'];
    $department = $_POST['department'];

    // Basic validation
    if (empty($name) || empty($email) || empty($password) || empty($role) || empty($department)) {
        $error = "Please fill in all required fields.";
    } else {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert user into database
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, role, department) VALUES (?, ?, ?, ?, ?)");
        try {
            $stmt->execute([$name, $email, $hashedPassword, $role, $department]);
            $success = "Account created successfully! 🎉";
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $error = "Email already exists. Try logging in.";
            } else {
                $error = "Something went wrong: " . $e->getMessage();
            }
        }
    }
}
?>


<?php
// Sample PHP snippet to handle form errors (connect backend here if needed)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Example basic validation
    if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['password'])) {
        $error = "Please fill in all required fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Get Started | BeStudent</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
    
    <!-- Animate.css & Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <link rel="stylesheet" href="css/home.css">
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #6e8efb, #a777e3);
            --secondary-gradient: linear-gradient(135deg, #a777e3, #6e8efb);
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            color: #333;
            overflow-x: hidden;
        }

        .text-gradient {
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .btn-gradient {
            background: var(--primary-gradient);
            border: none;
            color: white;
            transition: 0.3s ease;
        }

        .btn-gradient:hover {
            background: var(--secondary-gradient);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .get-started-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 100px 0;
        }

        .welcome-card, .form-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            padding: 40px;
        }

        .form-header h2 {
            font-weight: 700;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .form-control {
            border-radius: 10px;
            padding: 12px 15px;
            margin-bottom: 20px;
        }

        .form-control:focus {
            border-color: #a777e3;
            box-shadow: 0 0 0 0.25rem rgba(167, 119, 227, 0.25);
        }

        .role-selector {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .role-selector input[type="radio"] {
            display: none;
        }

        .role-option {
            flex: 1;
            padding: 15px;
            text-align: center;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            cursor: pointer;
            transition: 0.3s ease;
        }

        .role-option i {
            font-size: 24px;
            margin-bottom: 5px;
            color: #6e8efb;
        }

        .role-selector input[type="radio"]:checked + .role-option {
            border-color: #6e8efb;
            background: rgba(110, 142, 251, 0.1);
        }

        .password-strength {
            height: 5px;
            background: #e0e0e0;
            border-radius: 5px;
            margin-top: 5px;
            overflow: hidden;
        }

        .strength-bar {
            height: 100%;
            width: 0%;
            background: #dc3545;
            transition: all 0.3s ease;
        }

        .floating-icons {
            position: absolute;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
        }

        .floating-icon {
            position: absolute;
            color: rgba(110, 142, 251, 0.2);
            font-size: 24px;
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
        }

        @media (max-width: 992px) {
            .get-started-container {
                padding: 60px 0;
            }
        }
    </style>
</head>
<body>

<div id="particles-js"></div>

<div class="floating-icons">
    <i class="fas fa-book floating-icon" style="top: 20%; left: 10%; animation-delay: 0s;"></i>
    <i class="fas fa-graduation-cap floating-icon" style="top: 70%; left: 15%; animation-delay: 1s;"></i>
    <i class="fas fa-lightbulb floating-icon" style="top: 30%; left: 85%; animation-delay: 2s;"></i>
    <i class="fas fa-users floating-icon" style="top: 80%; left: 80%; animation-delay: 3s;"></i>
    <i class="fas fa-chalkboard-teacher floating-icon" style="top: 50%; left: 20%; animation-delay: 4s;"></i>
</div>

<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <img src="Image/BeStudent.png" alt="BeStudent Logo" class="logo-img">
        </a>
        <div class="navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link btn-gradient mx-2" href="getStarted.php">Get Started</a></li>
                <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                <li class="nav-item"><a class="nav-link" href="#courses">Courses</a></li>
                <li class="nav-item"><a class="nav-link" href="#SoDing">SoDing</a></li>
                <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="get-started-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="row g-4">
                    <!-- Left Section -->
                    <div class="col-lg-6">
                        <div class="welcome-card animate__animated animate__fadeInLeft">
                            <h1 class="display-4 fw-bold mb-4">Join <span class="text-gradient">BeStudent</span> Today</h1>
                            <p class="lead">Become part of the largest student-powered learning community. </p>
                        </div>
                    </div>

                    <!-- Form -->
                    <div class="col-lg-6">
                        <div class="form-card">
                            <div class="form-header text-center">
                                <h2 class="display-6 fw-bold">Create Account</h2>
                                <p class="text-muted">Start your learning journey in minutes</p>
                            </div>

                            <?php if (isset($error)): ?>
                                <div class="alert alert-danger"><?php echo $error; ?></div>
                            <?php endif; ?>

                            <form method="POST">

                            <?php if (isset($success)): ?>
                                <div class="alert alert-success"><?php echo $success; ?></div>
                            <?php endif; ?>

                                <div class="mb-3">
                                    <label for="name" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                    <div class="password-strength mt-2">
                                        <div class="strength-bar"></div>
                                        <small class="strength-text">Password strength: Weak</small>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">I am a:</label>
                                    <div class="role-selector">
                                        <input type="radio" id="student" name="role" value="student" checked>
                                        <label for="student" class="role-option"><i class="fas fa-user-graduate"></i>Student</label>
                                        <input type="radio" id="SoD" name="role" value="SoD">
                                        <label for="SoD" class="role-option"><i class="fas fa-chalkboard-teacher"></i>SoD</label>
                                        <input type="radio" id="faculty" name="role" value="faculty">
                                        <label for="faculty" class="role-option"><i class="fas fa-user-tie"></i>Faculty</label>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="department" class="form-label">Department</label>
                                    <select class="form-select" id="department" name="department" required>
                                        <option value="">Select your department</option>
                                        <option value="Computer Science">Computer Science</option>
                                        <option value="Engineering">Engineering</option>
                                        <option value="Business">Business</option>
                                        <option value="Law">Law</option>
                                        <option value="Arts">Arts</option>
                                        <option value="Science">Science</option>
                                    </select>
                                </div>

                                <div class="form-check mb-4">
                                    <input class="form-check-input" type="checkbox" id="terms" required>
                                    <label class="form-check-label" for="terms">
                                        I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>
                                    </label>
                                </div>

                                <button type="submit" class="btn btn-gradient w-100 py-3">
                                    <i class="fas fa-user-plus me-2"></i> Create Account
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>    
</div>

<!-- JS scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>

<script>
    particlesJS("particles-js", {
        particles: {
            number: { value: 80, density: { enable: true, value_area: 800 } },
            color: { value: "#6e8efb" },
            shape: { type: "circle" },
            opacity: { value: 0.3 },
            size: { value: 3, random: true },
            line_linked: {
                enable: true, distance: 150, color: "#6e8efb", opacity: 0.2, width: 1
            },
            move: {
                enable: true, speed: 2, direction: "none", out_mode: "out"
            }
        },
        interactivity: {
            events: {
                onhover: { enable: true, mode: "grab" },
                onclick: { enable: true, mode: "push" },
                resize: true
            },
            modes: {
                grab: { distance: 140, line_linked: { opacity: 1 } },
                push: { particles_nb: 4 }
            }
        },
        retina_detect: true
    });

    // Password strength checker
    document.getElementById("password").addEventListener("input", function () {
        const password = this.value;
        const bar = document.querySelector(".strength-bar");
        const text = document.querySelector(".strength-text");
        let strength = 0;

        if (password.length >= 6) strength++;
        if (password.match(/[a-z]/)) strength++;
        if (password.match(/[A-Z]/)) strength++;
        if (password.match(/[0-9]/)) strength++;
        if (password.match(/[^a-zA-Z0-9]/)) strength++;

        let percent = strength * 20;
        bar.style.width = percent + "%";

        if (strength <= 2) {
            bar.style.backgroundColor = "#dc3545";
            text.textContent = "Password strength: Weak";
        } else if (strength <= 4) {
            bar.style.backgroundColor = "#ffc107";
            text.textContent = "Password strength: Medium";
        } else {
            bar.style.backgroundColor = "#28a745";
            text.textContent = "Password strength: Strong";
        }
    });
</script>
</body>
</html>
