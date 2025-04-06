<?php
session_start();

// Check if user is logged in and has student role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}

// Database connection with error handling
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'bestudent';

try {
    $conn = new mysqli($host, $user, $password, $dbname);
    
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Get user data with prepared statement
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT name, email, profile_image FROM users WHERE id = ?");
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($name, $email, $profile_image);
    $stmt->fetch();
    $stmt->close();

    // Get student statistics with proper error handling
    $enrolled_courses = 0;
    $assignments_due = 0;
    $unread_messages = 0;
    $upcoming_events = 0;

    // Enrolled courses count
    $stmt = $conn->prepare("SELECT COUNT(*) FROM student_courses WHERE student_id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($enrolled_courses);
        $stmt->fetch();
        $stmt->close();
    }

    // Assignments due count
    $stmt = $conn->prepare("SELECT COUNT(*) FROM assignments 
                          WHERE deadline > NOW() 
                          AND course_id IN (SELECT course_id FROM student_courses WHERE student_id = ?)");
    if ($stmt) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($assignments_due);
        $stmt->fetch();
        $stmt->close();
    }

    // Unread messages count
    $stmt = $conn->prepare("SELECT COUNT(*) FROM messages WHERE recipient_id = ? AND is_read = 0");
    if ($stmt) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($unread_messages);
        $stmt->fetch();
        $stmt->close();
    }

    // Upcoming events count
    $result = $conn->query("SELECT COUNT(*) FROM events WHERE event_date > NOW()");
    if ($result) {
        $upcoming_events = $result->fetch_row()[0];
    }

    // Get enrolled courses with proper error handling
    $courses = [];
    $stmt = $conn->prepare("SELECT c.course_id, c.course_name, c.course_code, u.name AS instructor 
                          FROM courses c
                          JOIN faculty_courses fc ON c.course_id = fc.course_id
                          JOIN users u ON fc.faculty_id = u.id
                          JOIN student_courses sc ON c.course_id = sc.course_id
                          WHERE sc.student_id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $courses[] = $row;
        }
        $stmt->close();
    }

    $conn->close();

} catch (Exception $e) {
    // Log the error (in a real application, you would log to a file)
    error_log($e->getMessage());
    
    // Display a user-friendly message
    die("An error occurred while processing your request. Please try again later.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard | BeStudent</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --accent-color: #4cc9f0;
            --light-color: #f8f9fa;
            --dark-color: #212529;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            min-height: 100vh;
        }
        
        /* Navbar Styles */
        .navbar {
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            padding: 0.8rem 1rem;
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
        }
        
        .user-profile {
            display: flex;
            align-items: center;
        }
        
        .profile-img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid white;
            margin-right: 10px;
        }
        
        /* Dashboard Styles */
        .dashboard-header {
            background: linear-gradient(135deg, rgba(67, 97, 238, 0.1) 0%, rgba(63, 55, 201, 0.1) 100%);
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }
        
        .dashboard-header::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(67, 97, 238, 0.05) 0%, rgba(67, 97, 238, 0) 70%);
        }
        
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s;
            border-left: 4px solid var(--primary-color);
            margin-bottom: 1.5rem;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .stat-icon {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }
        
        .stat-number {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--dark-color);
        }
        
        .stat-label {
            color: #6c757d;
            font-size: 0.9rem;
        }
        
        /* Courses Section */
        .course-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s;
            margin-bottom: 1.5rem;
            overflow: hidden;
        }
        
        .course-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .course-header {
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 1rem 1.5rem;
        }
        
        .course-code {
            font-weight: 700;
            letter-spacing: 1px;
        }
        
        .course-body {
            padding: 1.5rem;
        }
        
        .instructor-badge {
            background-color: rgba(76, 201, 240, 0.1);
            color: var(--accent-color);
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: 500;
        }
        
        .progress-bar {
            height: 8px;
            border-radius: 4px;
            background-color: #e9ecef;
            margin-bottom: 1rem;
        }
        
        .progress-fill {
            height: 100%;
            border-radius: 4px;
            background: linear-gradient(to right, var(--accent-color), var(--primary-color));
            width: 65%; /* Example value - replace with actual progress */
        }
        
        .action-btn {
            border-radius: 50px;
            padding: 0.5rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        /* Quick Links */
        .quick-link {
            display: flex;
            align-items: center;
            padding: 1rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            transition: all 0.3s;
            margin-bottom: 1rem;
            text-decoration: none;
            color: var(--dark-color);
        }
        
        .quick-link:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            color: var(--primary-color);
        }
        
        .quick-link-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            font-size: 1.2rem;
        }
        
        /* Footer Styles */
        footer {
            background: linear-gradient(to right, var(--dark-color), #343a40);
            color: white;
            padding: 2rem 0;
            margin-top: 3rem;
        }
        
        .footer-links a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: all 0.3s;
            margin-right: 1.5rem;
        }
        
        .footer-links a:hover {
            color: white;
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
            background: var(--accent-color);
            transform: translateY(-3px);
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="student_dashboard.php">
                <i class="fas fa-graduation-cap me-2"></i>BeStudent
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-calendar-alt me-1"></i> Calendar</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-envelope me-1"></i> Messages 
                            <?php if ($unread_messages > 0): ?>
                                <span class="badge bg-danger ms-1"><?php echo $unread_messages; ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <div class="user-profile">
                            <img src="<?php echo $profile_image ? $profile_image : 'https://ui-avatars.com/api/?name='.urlencode($name).'&background=random'; ?>" class="profile-img" alt="Profile">
                            <span class="text-white d-none d-lg-inline"><?php echo $name; ?></span>
                            <a href="logout.php" class="btn btn-sm btn-outline-light ms-3"><i class="fas fa-sign-out-alt"></i></a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container py-4">
        <!-- Dashboard Header -->
        <div class="dashboard-header animate__animated animate__fadeIn">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="fw-bold">Student Dashboard</h1>
                    <p class="lead">Welcome back, <?php echo $name; ?>! Ready to learn something new today?</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <button class="btn btn-primary me-2"><i class="fas fa-search me-1"></i> Find Courses</button>
                    <button class="btn btn-outline-primary"><i class="fas fa-bell me-1"></i> Notifications</button>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row animate__animated animate__fadeInUp">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <div class="stat-number"><?php echo $enrolled_courses; ?></div>
                    <div class="stat-label">Enrolled Courses</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <div class="stat-number"><?php echo $assignments_due; ?></div>
                    <div class="stat-label">Assignments Due</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="stat-number"><?php echo $unread_messages; ?></div>
                    <div class="stat-label">Unread Messages</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="stat-number"><?php echo $upcoming_events; ?></div>
                    <div class="stat-label">Upcoming Events</div>
                </div>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="row mt-4 animate__animated animate__fadeIn">
            <div class="col-md-3">
                <a href="#" class="quick-link">
                    <div class="quick-link-icon">
                        <i class="fas fa-video"></i>
                    </div>
                    <div>
                        <h6 class="mb-0">Lecture Videos</h6>
                        <small class="text-muted">Access recordings</small>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="#" class="quick-link">
                    <div class="quick-link-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div>
                        <h6 class="mb-0">Study Materials</h6>
                        <small class="text-muted">Download resources</small>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="#" class="quick-link">
                    <div class="quick-link-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div>
                        <h6 class="mb-0">Discussion Forums</h6>
                        <small class="text-muted">Join conversations</small>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="#" class="quick-link">
                    <div class="quick-link-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div>
                        <h6 class="mb-0">Grades</h6>
                        <small class="text-muted">View progress</small>
                    </div>
                </a>
            </div>
        </div>

        <!-- Enrolled Courses -->
        <div class="row mt-4 animate__animated animate__fadeIn">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3><i class="fas fa-book-open me-2"></i> Your Courses</h3>
                    <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                
                <?php if (count($courses) > 0): ?>
                    <div class="row">
                        <?php foreach ($courses as $course): ?>
                            <div class="col-md-4">
                                <div class="course-card">
                                    <div class="course-header">
                                        <h5 class="course-code mb-0"><?php echo $course['course_code']; ?></h5>
                                    </div>
                                    <div class="course-body">
                                        <h4><?php echo $course['course_name']; ?></h4>
                                        <div class="progress-bar mt-3">
                                            <div class="progress-fill"></div>
                                        </div>
                                        <small class="text-muted">65% Complete</small>
                                        <div class="d-flex justify-content-between align-items-center mt-3">
                                            <span class="instructor-badge">
                                                <i class="fas fa-chalkboard-teacher me-1"></i> <?php echo $course['instructor']; ?>
                                            </span>
                                            <a href="#" class="btn btn-sm btn-primary action-btn">
                                                <i class="fas fa-arrow-right me-1"></i> Continue
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i> You are not enrolled in any courses yet. 
                        <a href="#" class="alert-link">Browse available courses</a> to get started.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="fas fa-graduation-cap me-2"></i> BeStudent</h5>
                    <p class="mt-3">Empowering students with innovative learning solutions.</p>
                    <div class="social-icons mt-3">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="footer-links">
                        <a href="#">Academic Calendar</a>
                        <a href="#">Library</a>
                        <a href="#">Support</a>
                        <a href="#">Help Center</a>
                    </div>
                    <p class="mt-3">&copy; <?php echo date("Y"); ?> BeStudent. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Animation trigger
        document.addEventListener('DOMContentLoaded', function() {
            const elements = document.querySelectorAll('.animate__animated');
            
            elements.forEach((element, index) => {
                // Add delay based on index for staggered animation
                element.style.animationDelay = `${index * 0.1}s`;
            });
        });
    </script>
</body>
</html>