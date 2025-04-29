<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | BeStudent</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/admin_dashboard.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="admin_dashboard.php">
                <i class="fas fa-chalkboard-teacher"></i> Admin Dashboard
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="admin_dashboard.php"><i class="fas fa-home"></i> Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="salary.php"><i class="fas fa-money-bill-wave"></i> Salary</a></li>
                    <li class="nav-item"><a class="nav-link" href="addBook.php"><i class="fas fa-book"></i> Add Book</a></li>
                    <li class="nav-item"><a class="nav-link" href="add_course.php"><i class="fas fa-plus-circle"></i> Add Course</a></li>
                    <li class="nav-item"><a class="nav-link" href="facultyList.php"><i class="fas fa-chalkboard-teacher"></i> Faculty</a></li>
                    <li class="nav-item"><a class="nav-link" href="sodList.php"><i class="fas fa-user-graduate"></i> SODs</a></li>
                    <li class="nav-item"><a class="nav-link" href="supportList.php"><i class="fas fa-headset"></i> Support</a></li>
                    <li class="nav-item"><a class="nav-link" href="notifications.php"><i class="fas fa-bell"></i> Notifications</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-5 pt-5">
        <h2 class="text-center">Welcome, <?php echo $admin_name; ?>!</h2>
        <p class="text-center">Manage your platform effectively with the following options:</p>

        <!-- Quick Stats -->
        <div class="quick-stats">
            <div class="stat-card">
                <div class="stat-number">24</div>
                <div class="stat-label">Active Courses</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">156</div>
                <div class="stat-label">Total Students</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">42</div>
                <div class="stat-label">Faculty Members</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">18</div>
                <div class="stat-label">New Notifications</div>
            </div>
        </div>

        <!-- Main Cards -->
        <div class="row mt-4">
            <div class="col-md-3 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-money-bill-wave me-2"></i>Salary</h5>
                        <p class="card-text">Manage and process salaries for faculty and staff members.</p>
                        <a href="salary.php" class="btn btn-primary">Manage Salary</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-book me-2"></i>Add PDF</h5>
                        <p class="card-text">Upload new study materials and resources for students.</p>
                        <a href="add_pdf.php" class="btn btn-primary">Upload PDF</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-bell me-2"></i>Notifications</h5>
                        <p class="card-text">Send important announcements to users.</p>
                        <a href="notifications.php" class="btn btn-primary">Send Notifications</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-plus-circle me-2"></i>Add Course</h5>
                        <p class="card-text">Create new courses and manage existing ones.</p>
                        <a href="add_course.php" class="btn btn-primary">Add New Course</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recommended Books Section -->
        <div class="recommended-books">
            <h3 class="mb-4"><i class="fas fa-book-open me-2"></i>Recommended Books</h3>
            <div class="row">
                <div class="col-md-3 mb-4">
                    <div class="book-card">
                        <div class="book-cover" style="background-image: url('https://m.media-amazon.com/images/I/41xShlnTZTL._SX376_BO1,204,203,200_.jpg');"></div>
                        <div class="book-info">
                            <h5 class="book-title">Clean Code</h5>
                            <p class="book-author">Robert C. Martin</p>
                            <button class="book-btn">Add to Resources</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="book-card">
                        <div class="book-cover" style="background-image: url('https://m.media-amazon.com/images/I/51VkB5O0sDL._SY425_.jpg');"></div>
                        <div class="book-info">
                            <h5 class="book-title">Design Patterns</h5>
                            <p class="book-author">Erich Gamma</p>
                            <button class="book-btn">Add to Resources</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="book-card">
                        <div class="book-cover" style="background-image: url('https://m.media-amazon.com/images/I/61jL8ZtGjBL._SY425_.jpg');"></div>
                        <div class="book-info">
                            <h5 class="book-title">Algorithm Design</h5>
                            <p class="book-author">Jon Kleinberg</p>
                            <button class="book-btn">Add to Resources</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="book-card">
                        <div class="book-cover" style="background-image: url('https://m.media-amazon.com/images/I/41JOmGpQoxL._SY425_.jpg');"></div>
                        <div class="book-info">
                            <h5 class="book-title">Database Systems</h5>
                            <p class="book-author">Raghu Ramakrishnan</p>
                            <button class="book-btn">Add to Resources</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="recent-activity mt-5">
            <h3 class="activity-title"><i class="fas fa-history me-2"></i>Recent Activity</h3>
            <div class="activity-item">
                <div class="activity-icon">
                    <i class="fas fa-user-plus"></i>
                </div>
                <div class="activity-content">
                    <p>New faculty member "Dr. Smith" joined the platform</p>
                    <small class="activity-time">2 hours ago</small>
                </div>
            </div>
            <div class="activity-item">
                <div class="activity-icon">
                    <i class="fas fa-book"></i>
                </div>
                <div class="activity-content">
                    <p>"Advanced Algorithms" course material was updated</p>
                    <small class="activity-time">5 hours ago</small>
                </div>
            </div>
            <div class="activity-item">
                <div class="activity-icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="activity-content">
                    <p>Salary processed for 15 faculty members</p>
                    <small class="activity-time">1 day ago</small>
                </div>
            </div>
            <div class="activity-item">
                <div class="activity-icon">
                    <i class="fas fa-bell"></i>
                </div>
                <div class="activity-content">
                    <p>New notification sent to all students</p>
                    <small class="activity-time">2 days ago</small>
                </div>
            </div>
        </div>
    </div>


    <!-- Add this just before the closing </body> tag -->
<footer class="footer mt-5">
    <div class="container py-4">
        <div class="row">
            <div class="col-md-4 mb-4 mb-md-0">
                <h5 class="footer-heading"><i class="fas fa-chalkboard-teacher me-2"></i> BeStudent Admin</h5>
                <p class="footer-text">Empowering education through innovative management tools for administrators.</p>
                <div class="social-links mt-3">
                    <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
            
            <div class="col-md-2 mb-4 mb-md-0">
                <h5 class="footer-heading">Quick Links</h5>
                <ul class="footer-links">
                    <li><a href="admin_dashboard.php"><i class="fas fa-chevron-right me-1"></i> Dashboard</a></li>
                    <li><a href="add_course.php"><i class="fas fa-chevron-right me-1"></i> Add Course</a></li>
                    <li><a href="add_pdf.php"><i class="fas fa-chevron-right me-1"></i> Add Resources</a></li>
                    <li><a href="notifications.php"><i class="fas fa-chevron-right me-1"></i> Notifications</a></li>
                </ul>
            </div>
            
            <div class="col-md-2 mb-4 mb-md-0">
                <h5 class="footer-heading">User Management</h5>
                <ul class="footer-links">
                    <li><a href="facultyList.php"><i class="fas fa-chevron-right me-1"></i> Faculty</a></li>
                    <li><a href="sodList.php"><i class="fas fa-chevron-right me-1"></i> SODs</a></li>
                    <li><a href="supportList.php"><i class="fas fa-chevron-right me-1"></i> Support</a></li>
                    <li><a href="salary.php"><i class="fas fa-chevron-right me-1"></i> Salary</a></li>
                </ul>
            </div>
            
            <div class="col-md-4">
                <h5 class="footer-heading">Contact Support</h5>
                <ul class="footer-contact">
                    <li><i class="fas fa-envelope me-2"></i> admin@bestudent.edu</li>
                    <li><i class="fas fa-phone me-2"></i> +880 1312304166</li>
                    <li><i class="fas fa-map-marker-alt me-2"></i> Dhaka Bangladesh</li>
                </ul>
                <div class="newsletter mt-3">
                    <input type="email" class="form-control" placeholder="Your email">
                    <button class="btn btn-primary mt-2">Subscribe</button>
                </div>
            </div>
        </div>
        
        <div class="footer-bottom mt-4 pt-3 border-top">
            <div class="row">
                <div class="col-md-6">
                    <p class="copyright">&copy; 2025 BeStudent Admin Portal. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="legal-links">
                        <a href="#">Privacy Policy</a>
                        <a href="#">Terms of Service</a>
                        <a href="#">Cookie Policy</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>