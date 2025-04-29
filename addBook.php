        <?php
        session_start();
        error_reporting(E_ALL); 
        ini_set('display_errors', 1);
        if (!isset($_SESSION['admin']) || $_SESSION['role'] != 'admin') {
            header("Location: admin_login.php");
            exit();
        }
        $host = 'localhost';     
        $dbname = 'bestudent';
        $username = 'root';      
        $password = '';  

        $error = '';
        $success = '';

        try {
            $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database Connection Failed: " . $e->getMessage());
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $book_name = trim($_POST['book_name']);
            $pdf_file = $_FILES['pdf_file'];

            if (empty($book_name) || empty($pdf_file['name'])) {
                $error = "Please fill in all required fields.";
            } else {
                $allowed_types = ['application/pdf'];
                if (!in_array($pdf_file['type'], $allowed_types)) {
                    $error = "Please upload a valid PDF file.";
                } else {
                    $pdf_data = file_get_contents($pdf_file['tmp_name']);

                    $stmt = $conn->prepare("INSERT INTO books (name, pdf_data) VALUES (?, ?)");
                    try {
                        $stmt->execute([$book_name, $pdf_data]);
                        $success = "Book added successfully! 🎉";
                    } catch (PDOException $e) {
                        $error = "Something went wrong: " . $e->getMessage();
                    }
                }
            }
        }

            $courses_query = "SELECT books.id, books.name, books.uploaded_at FROM books";
            $courses_stmt = $conn->prepare($courses_query);
            $courses_stmt->execute();
            $books = $courses_stmt->fetchAll(PDO::FETCH_ASSOC);


        $conn = null;
        ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Book | Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/add_book.css">
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
    <div class="container mt-5">
        <h2>Add New Book</h2>

        <?php if ($error): ?>
            <div class="alert alert-danger">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success">
                <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <!-- Book form -->
        <div class="card">
            <div class="card-body">
                <form action="addBook.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="book_name" class="form-label">Book Name</label>
                        <input type="text" class="form-control" id="book_name" name="book_name" required>
                    </div>

                    <div class="mb-3">
                        <label for="pdf_file" class="form-label">Upload PDF</label>
                        <input type="file" class="form-control" id="pdf_file" name="pdf_file" accept="application/pdf" required>
                        <label for="pdf_file" class="custom-file-upload">
                            <i class="fas fa-cloud-upload-alt"></i> Choose PDF File
                        </label>
                        <div class="file-info" id="file-info">No file selected</div>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus-circle"></i> Add Book
                    </button>
                </form>
            </div>
        </div>

        <!-- Book Gallery -->
        <div class="book-gallery">
            <h3><i class="fas fa-book-open me-2"></i> Recently Added Books</h3>
            <div class="book-grid">
            <?php foreach ($books as $book): ?>
                <div class="book-item">
                    <div class="book-cover">
                        <i class="fas fa-file-pdf"></i>
                    </div>
                    <div class="book-details">
                        <h5 class="book-title"><?php echo htmlspecialchars($book['name']); ?></h5>
                        <div class="book-actions">
                            <button class="btn btn-sm btn-primary" onclick="window.location.href='view_pdf.php?id=<?php echo $book['id']; ?>'">View</button>
                            <button class="btn btn-sm btn-outline-secondary" onclick="window.location.href='download_pdf.php?id=<?php echo $book['id']; ?>'">Download</button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container py-4">
            <div class="row">
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5 class="footer-heading"><i class="fas fa-chalkboard-teacher me-2"></i> BeStudent Admin</h5>
                    <p class="footer-text">Empowering education through innovative resource management tools for administrators.</p>
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
                        <li><a href="addBook.php"><i class="fas fa-chevron-right me-1"></i> Add Books</a></li>
                        <li><a href="add_course.php"><i class="fas fa-chevron-right me-1"></i> Add Courses</a></li>
                        <li><a href="notifications.php"><i class="fas fa-chevron-right me-1"></i> Notifications</a></li>
                    </ul>
                </div>
                
                <div class="col-md-2 mb-4 mb-md-0">
                    <h5 class="footer-heading">Resources</h5>
                    <ul class="footer-links">
                        <li><a href="#"><i class="fas fa-chevron-right me-1"></i> Documentation</a></li>
                        <li><a href="#"><i class="fas fa-chevron-right me-1"></i> Help Center</a></li>
                        <li><a href="#"><i class="fas fa-chevron-right me-1"></i> Tutorials</a></li>
                        <li><a href="#"><i class="fas fa-chevron-right me-1"></i> FAQs</a></li>
                    </ul>
                </div>
                
                <div class="col-md-4">
                    <h5 class="footer-heading">Contact Support</h5>
                    <ul class="footer-contact">
                        <li><i class="fas fa-envelope me-2"></i> admin@bestudent.edu</li>
                        <li><i class="fas fa-phone me-2"></i> +1 (555) 123-4567</li>
                        <li><i class="fas fa-clock me-2"></i> Mon-Fri: 9AM - 5PM</li>
                        <li><i class="fas fa-map-marker-alt me-2"></i> 123 Education St, Campus City</li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <div class="row">
                    <div class="col-md-6">
                        <p class="copyright">&copy; 2023 BeStudent Admin Portal. All rights reserved.</p>
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
    <script>
        // Show selected file name
        document.getElementById('pdf_file').addEventListener('change', function(e) {
            const fileName = e.target.files[0] ? e.target.files[0].name : 'No file selected';
            document.getElementById('file-info').textContent = fileName;
        });
    </script>
</body>
</html>