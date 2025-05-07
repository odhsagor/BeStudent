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
$query = "SELECT name FROM users WHERE id = :student_id AND role = 'student'";
$stmt = $conn->prepare($query);
$stmt->execute(['student_id' => $student_id]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

$student_name = $student ? $student['name'] : 'Student';

?>

<<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/student_dashboard.css">
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="student_dashboard.php">
                <i class="fas fa-user-graduate"></i> Student Portal
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="student_dashboard.php"><i class="fas fa-book"></i> Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="courses.php"><i class="fas fa-book"></i> Courses</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="library.php"><i class="fas fa-book-open"></i> Library</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="group.php"><i class="fas fa-users"></i> Study Groups</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-tasks"></i> Assignments</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-tasks"></i>JOB</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-5 pt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2>Welcome back, <?php echo htmlspecialchars($student_name); ?>! 👋</h2>
                <p class="text-muted">Student ID: <?php echo $student_id; ?></p>
            </div>
            <div class="text-end">
                <div class="badge bg-primary">Current Semester: 3rd</div>
                <div class="mt-2">
                    <small class="text-muted">Last login: 2 hours ago</small>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-book-open me-2"></i>Courses</h5>
                        <h2>5</h2>
                        <p class="mb-0">Active Courses</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-check-circle me-2"></i>Assignments</h5>
                        <h2>12/15</h2>
                        <p class="mb-0">Completed</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-users me-2"></i>Groups</h5>
                        <h2>3</h2>
                        <p class="mb-0">Active Groups</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-dark">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-clock me-2"></i>Deadlines</h5>
                        <h2>2</h2>
                        <p class="mb-0">Upcoming</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dashboard Content -->
        <div class="row mt-4">
            <!-- Upcoming Courses -->
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-calendar-alt me-2"></i>Today's Schedule</h5>
                        <div class="list-group">
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6>Advanced Calculus</h6>
                                    <small class="text-muted">10:00 AM - 11:30 AM</small>
                                </div>
                                <span class="badge bg-primary">Room 302</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6>Data Structures</h6>
                                    <small class="text-muted">02:00 PM - 03:30 PM</small>
                                </div>
                                <span class="badge bg-primary">Lab B</span>
                            </div>
                        </div>
                        <a href="courses.php" class="btn btn-primary mt-3">
                            <i class="fas fa-arrow-right me-2"></i>View Full Schedule
                        </a>
                    </div>
                </div>
            </div>

            <!-- Assignments Progress -->
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-tasks me-2"></i>Assignments Progress</h5>
                        <div class="mb-3">
                            <h6>Web Development Project <span class="float-end">75%</span></h6>
                            <div class="progress">
                                <div class="progress-bar" style="width: 75%"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <h6>Database Design <span class="float-end">40%</span></h6>
                            <div class="progress">
                                <div class="progress-bar" style="width: 40%"></div>
                            </div>
                        </div>
                        <a href="assignments.php" class="btn btn-primary mt-3">
                            <i class="fas fa-arrow-right me-2"></i>View All Assignments
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5>About BeStudent</h5>
                    <p>Empowering students with modern learning tools and resources to achieve academic excellence.</p>
                    <div class="social-links">
                        <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="col-md-2 mb-4">
                    <h5>Quick Links</h5>
                    <ul class="footer-links">
                        <li><a href="courses.php">Courses</a></li>
                        <li><a href="library.php">Library</a></li>
                        <li><a href="groups.php">Study Groups</a></li>
                        <li><a href="calendar.php">Calendar</a></li>
                    </ul>
                </div>
                <div class="col-md-3 mb-4">
                    <h5>Resources</h5>
                    <ul class="footer-links">
                        <li><a href="help-center.php">Help Center</a></li>
                        <li><a href="tutorials.php">Video Tutorials</a></li>
                        <li><a href="blog.php">Student Blog</a></li>
                        <li><a href="faq.php">FAQs</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5>Contact</h5>
                    <ul class="footer-links">
                        <li><i class="fas fa-envelope me-2"></i> support@bestudent.edu</li>
                        <li><i class="fas fa-phone me-2"></i> +8801312304166</li>
                        <li><i class="fas fa-map-marker-alt me-2"></i> Dhaka Bangladesh</li>
                    </ul>
                </div>
            </div>
            <div class="text-center pt-3 border-top">
                <p class="mb-0">&copy; 2025 BeStudent. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>