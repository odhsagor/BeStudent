<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Support Dashboard | BeStudent</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/support_dashboard.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="support_dashboard.php">
                <i class="fas fa-user-shield"></i> Support Dashboard
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="support_dashboard.php"><i class="fas fa-home"></i> Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="salary.php"><i class="fas fa-money-bill-wave"></i> My Salary</a></li>
                    <li class="nav-item"><a class="nav-link" href="addBook.php"><i class="fas fa-book"></i> Add Book</a></li>
                    <li class="nav-item"><a class="nav-link" href="enroll_course.php"><i class="fas fa-user-plus"></i> Enroll Course</a></li>
                    <li class="nav-item"><a class="nav-link" href="list_sod.php"><i class="fas fa-star"></i> List of SoDs</a></li>
                    <li class="nav-item"><a class="nav-link" href="notifications.php"><i class="fas fa-bell"></i> Notifications</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-5 pt-5">
        <h2 class="text-center">Welcome, <?php echo $support_name; ?>!</h2>
        <p class="text-center">Manage your support tasks effectively with these options:</p>

        <!-- Quick Access Cards -->
        <div class="row mt-4">
            <div class="col-md-3 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-money-bill-wave"></i> My Salary</h5>
                        <p class="card-text">View your salary details and payment history.</p>
                        <a href="salary.php" class="btn btn-primary">View Salary</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-book"></i> Add Book</h5>
                        <p class="card-text">Upload new educational resources for students.</p>
                        <a href="add_book.php" class="btn btn-primary">Add Book</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-user-plus"></i> Enroll Course</h5>
                        <p class="card-text">Manage student enrollments in various courses.</p>
                        <a href="enroll_course.php" class="btn btn-primary">Enroll Students</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-star"></i> List of SoDs</h5>
                        <p class="card-text">View and manage Students of the Day.</p>
                        <a href="list_sod.php" class="btn btn-primary">View SoDs</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Support Tickets -->
        <div class="recent-tickets">
            <h3 class="tickets-title"><i class="fas fa-ticket-alt"></i> Recent Support Tickets</h3>
            
            <div class="ticket-item">
                <div class="ticket-icon">
                    <i class="fas fa-question"></i>
                </div>
                <div class="ticket-content">
                    <p>Student unable to access course materials</p>
                    <small class="ticket-time">30 minutes ago</small>
                </div>
                <span class="ticket-status status-pending">Pending</span>
            </div>
            
            <div class="ticket-item">
                <div class="ticket-icon">
                    <i class="fas fa-book"></i>
                </div>
                <div class="ticket-content">
                    <p>Request for additional reference books</p>
                    <small class="ticket-time">2 hours ago</small>
                </div>
                <span class="ticket-status status-resolved">Resolved</span>
            </div>
            
            <div class="ticket-item">
                <div class="ticket-icon">
                    <i class="fas fa-user"></i>
                </div>
                <div class="ticket-content">
                    <p>Password reset request</p>
                    <small class="ticket-time">5 hours ago</small>
                </div>
                <span class="ticket-status status-resolved">Resolved</span>
            </div>
            
            <div class="ticket-item">
                <div class="ticket-icon">
                    <i class="fas fa-video"></i>
                </div>
                <div class="ticket-content">
                    <p>Technical issues with video lectures</p>
                    <small class="ticket-time">1 day ago</small>
                </div>
                <span class="ticket-status status-pending">Pending</span>
            </div>
        </div>

        <!-- Recommended Books -->
        <div class="recommended-books mt-5">
            <h3 class="mb-4"><i class="fas fa-book-open"></i> Recommended Books</h3>
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
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container py-4">
            <div class="row">
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5 class="footer-heading"><i class="fas fa-user-shield me-2"></i> BeStudent Support</h5>
                    <p class="footer-text">Providing exceptional support to enhance the learning experience for all students and faculty.</p>
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
                        <li><a href="support_dashboard.php"><i class="fas fa-chevron-right me-1"></i> Dashboard</a></li>
                        <li><a href="add_book.php"><i class="fas fa-chevron-right me-1"></i> Add Books</a></li>
                        <li><a href="enroll_course.php"><i class="fas fa-chevron-right me-1"></i> Enrollments</a></li>
                        <li><a href="notifications.php"><i class="fas fa-chevron-right me-1"></i> Notifications</a></li>
                    </ul>
                </div>
                
                <div class="col-md-2 mb-4 mb-md-0">
                    <h5 class="footer-heading">Resources</h5>
                    <ul class="footer-links">
                        <li><a href="list_sod.php"><i class="fas fa-chevron-right me-1"></i> SoD List</a></li>
                        <li><a href="salary.php"><i class="fas fa-chevron-right me-1"></i> Salary</a></li>
                        <li><a href="#"><i class="fas fa-chevron-right me-1"></i> Help Center</a></li>
                        <li><a href="#"><i class="fas fa-chevron-right me-1"></i> FAQs</a></li>
                    </ul>
                </div>
                
                <div class="col-md-4">
                    <h5 class="footer-heading">Contact Support</h5>
                    <ul class="footer-contact">
                        <li><i class="fas fa-envelope me-2"></i> support@bestudent.edu</li>
                        <li><i class="fas fa-phone me-2"></i> +880 1312304166</li>
                        <li><i class="fas fa-clock me-2"></i> Mon-Fri: 8AM - 6PM</li>
                        <li><i class="fas fa-map-marker-alt me-2"></i> Dhaka Bangladesh</li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <div class="row">
                    <div class="col-md-6">
                        <p class="copyright">&copy; 2025 BeStudent Support Portal. All rights reserved.</p>
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