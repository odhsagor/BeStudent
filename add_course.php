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

$faculty_query = "SELECT id, name FROM users WHERE role = 'faculty'";
$faculty_stmt = $conn->prepare($faculty_query);
$faculty_stmt->execute();
$faculty_list = $faculty_stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['update_course'])) {
    $course_name = trim($_POST['course_name']);
    $section = trim($_POST['section']);
    $time = trim($_POST['time']);
    $capacity = trim($_POST['capacity']);
    $faculty_id = $_POST['faculty'];

    if (empty($course_name) || empty($section) || empty($time) || empty($capacity) || empty($faculty_id)) {
        $error = "Please fill in all required fields.";
    } else {
        $stmt = $conn->prepare("INSERT INTO courses (course_name, section, time, capacity, faculty_id) VALUES (?, ?, ?, ?, ?)");
        try {
            $stmt->execute([$course_name, $section, $time, $capacity, $faculty_id]);
            $success = "Course added successfully! 🎉";
        } catch (PDOException $e) {
            $error = "Something went wrong: " . $e->getMessage();
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_course'])) {
    $course_id = $_POST['course_id'];
    $course_name = trim($_POST['course_name']);
    $section = trim($_POST['section']);
    $time = trim($_POST['time']);
    $capacity = trim($_POST['capacity']);
    $faculty_id = $_POST['faculty'];

    if (empty($course_name) || empty($section) || empty($time) || empty($capacity) || empty($faculty_id)) {
        $error = "Please fill in all required fields.";
    } else {
        $stmt = $conn->prepare("UPDATE courses SET course_name = ?, section = ?, time = ?, capacity = ?, faculty_id = ? WHERE id = ?");
        try {
            $stmt->execute([$course_name, $section, $time, $capacity, $faculty_id, $course_id]);
            $success = "Course updated successfully! 🎉";
        } catch (PDOException $e) {
            $error = "Something went wrong: " . $e->getMessage();
        }
    }
}

$courses_query = "SELECT courses.id, courses.course_name, courses.section, courses.time, courses.capacity, users.name AS faculty_name 
                  FROM courses 
                  JOIN users ON courses.faculty_id = users.id";
$courses_stmt = $conn->prepare($courses_query);
$courses_stmt->execute();
$courses = $courses_stmt->fetchAll(PDO::FETCH_ASSOC);
$conn = null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add/Update Course | Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/add_course.css">
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
        <h2>Add/Update Course</h2>
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

        <!-- Course form -->
        <div class="card mb-5">
            <div class="card-body">
                <form action="add_course.php" method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="course_name" class="form-label">Course Name</label>
                            <input type="text" class="form-control" id="course_name" name="course_name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="section" class="form-label">Section</label>
                            <input type="text" class="form-control" id="section" name="section" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="time" class="form-label">Time</label>
                            <input type="text" class="form-control" id="time" name="time" placeholder="e.g. MWF 10:00-11:00" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="capacity" class="form-label">Capacity</label>
                            <input type="number" class="form-control" id="capacity" name="capacity" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="faculty" class="form-label">Faculty</label>
                        <select class="form-control" id="faculty" name="faculty" required>
                            <option value="">Select Faculty</option>
                            <?php foreach ($faculty_list as $faculty): ?>
                                <option value="<?php echo $faculty['id']; ?>"><?php echo $faculty['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Course</button>
                </form>
            </div>
        </div>

        <h3>Existing Courses</h3>

        <!-- Courses Table -->
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Course Name</th>
                        <th>Section</th>
                        <th>Time</th>
                        <th>Capacity</th>
                        <th>Faculty</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($courses as $course): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($course['course_name']); ?></td>
                            <td><?php echo htmlspecialchars($course['section']); ?></td>
                            <td><?php echo htmlspecialchars($course['time']); ?></td>
                            <td><?php echo htmlspecialchars($course['capacity']); ?></td>
                            <td><?php echo htmlspecialchars($course['faculty_name']); ?></td>
                            <td>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $course['id']; ?>">
                                    Edit
                                </button>
                                <div class="modal fade" id="editModal<?php echo $course['id']; ?>" tabindex="-1" aria-labelledby="editModalLabel<?php echo $course['id']; ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editModalLabel<?php echo $course['id']; ?>">Edit Course: <?php echo htmlspecialchars($course['course_name']); ?></h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="add_course.php" method="POST">
                                                    <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">

                                                    <div class="mb-3">
                                                        <label for="course_name" class="form-label">Course Name</label>
                                                        <input type="text" class="form-control" id="course_name" name="course_name" value="<?php echo htmlspecialchars($course['course_name']); ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="section" class="form-label">Section</label>
                                                        <input type="text" class="form-control" id="section" name="section" value="<?php echo htmlspecialchars($course['section']); ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="time" class="form-label">Time</label>
                                                        <input type="text" class="form-control" id="time" name="time" value="<?php echo htmlspecialchars($course['time']); ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="capacity" class="form-label">Capacity</label>
                                                        <input type="number" class="form-control" id="capacity" name="capacity" value="<?php echo htmlspecialchars($course['capacity']); ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="faculty" class="form-label">Faculty</label>
                                                        <select class="form-control" id="faculty" name="faculty" required>
                                                            <option value="">Select Faculty</option>
                                                            <?php foreach ($faculty_list as $faculty): ?>
                                                                <option value="<?php echo $faculty['id']; ?>" <?php echo ($faculty['id'] == $course['faculty_id']) ? 'selected' : ''; ?>>
                                                                    <?php echo $faculty['name']; ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary" name="update_course">Update Course</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Recommended Books -->
        <div class="recommended-books mt-5">
            <h3><i class="fas fa-book-open me-2"></i> Recommended Textbooks</h3>
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
                    <h5 class="footer-heading"><i class="fas fa-chalkboard-teacher me-2"></i> BeStudent Admin</h5>
                    <p class="footer-text">Empowering education through innovative course management tools for administrators.</p>
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
                        <li><a href="facultyList.php"><i class="fas fa-chevron-right me-1"></i> Faculty</a></li>
                        <li><a href="notifications.php"><i class="fas fa-chevron-right me-1"></i> Notifications</a></li>
                    </ul>
                </div>
                
                <div class="col-md-2 mb-4 mb-md-0">
                    <h5 class="footer-heading">Resources</h5>
                    <ul class="footer-links">
                        <li><a href="add_pdf.php"><i class="fas fa-chevron-right me-1"></i> Add Books</a></li>
                        <li><a href="salary.php"><i class="fas fa-chevron-right me-1"></i> Salary</a></li>
                        <li><a href="#"><i class="fas fa-chevron-right me-1"></i> Help Center</a></li>
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
</body>
</html>