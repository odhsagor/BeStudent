<?php
session_start();
$host = 'localhost';     
$dbname = 'bestudent';
$username = 'root';      
$password = '';  

$error = '';
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database Connection Failed: " . $e->getMessage());
}
$stmt = $conn->prepare("SELECT id, name FROM books");
$stmt->execute();
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);
$conn = null;
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Gallery | BeStudent</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/book_gallery.css">
    <link rel="stylesheet" href="css/student_dashboard.css">
</head>
<body>
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

    <div class="container mt-5">
        <h2>Our Book Collection</h2>
        <p class="lead">Explore our extensive collection of educational resources and textbooks</p>
        <div class="book-grid">
            <?php foreach ($books as $book): ?>
                <div class="book-item">
                    <div class="book-cover">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <div class="book-details">
                        <h5 class="book-title"><?php echo htmlspecialchars($book['name']); ?></h5>
                        <div class="book-actions">
                            <a href="view_pdf.php?id=<?php echo $book['id']; ?>" class="btn btn-primary">
                                <i class="fas fa-eye me-1"></i> View
                            </a>
                            <a href="download_pdf.php?id=<?php echo $book['id']; ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-download me-1"></i> Download
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
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
    <script>
        document.querySelectorAll('.book-item').forEach(book => {
            book.addEventListener('mouseenter', () => {
                const icon = book.querySelector('.book-cover i');
                icon.style.animation = 'none';
                setTimeout(() => {
                    icon.style.animation = 'float 3s ease-in-out infinite';
                }, 10);
            });
        });
    </script>
</body>
</html>