<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = 'localhost';
$dbname = 'bestudent';
$username = 'root';
$password = '';

$error = '';
$success = '';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header("Location: login.php");  
    exit();
}

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database Connection Failed: " . $e->getMessage());
}

$courses_query = "SELECT courses.id, courses.course_name, courses.section, courses.time, courses.capacity, 
                         users.name AS faculty_name, 
                         (courses.capacity - COUNT(enrollments.student_id)) AS available_capacity
                  FROM courses
                  LEFT JOIN users ON courses.faculty_id = users.id
                  LEFT JOIN enrollments ON courses.id = enrollments.course_id
                  GROUP BY courses.id";
$courses_stmt = $conn->prepare($courses_query);
$courses_stmt->execute();
$courses = $courses_stmt->fetchAll(PDO::FETCH_ASSOC);


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enroll_course'])) {
    $course_id = $_POST['course_id'];

    $check_enrollment_query = "SELECT * FROM enrollments WHERE student_id = :student_id AND course_id = :course_id";
    $check_enrollment_stmt = $conn->prepare($check_enrollment_query);
    $check_enrollment_stmt->execute(['student_id' => $_SESSION['user_id'], 'course_id' => $course_id]);

    if ($check_enrollment_stmt->rowCount() > 0) {
        $error = "You are already enrolled in this course!";
    } else {
        $available_capacity = 0;
        foreach ($courses as $course) {
            if ($course['id'] == $course_id) {
                $available_capacity = $course['available_capacity'];
                break;
            }
        }

        if ($available_capacity > 0) {
            // Enroll the student in the course
            $enroll_query = "INSERT INTO enrollments (student_id, course_id) VALUES (:student_id, :course_id)";
            $enroll_stmt = $conn->prepare($enroll_query);
            $enroll_stmt->execute(['student_id' => $_SESSION['user_id'], 'course_id' => $course_id]);

            $success = "You have successfully enrolled in the course!";
        } else {
            $error = "This course is full. You cannot enroll!";
        }
    }
}

$conn = null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses | Student Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
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
                        <a class="nav-link" href="courses.php"><i class="fas fa-book"></i> Courses</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="books.php"><i class="fas fa-book-open"></i> Library</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="groups.php"><i class="fas fa-users"></i> Study Groups</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="assignments.php"><i class="fas fa-tasks"></i> Assignments</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="notifications.php">
                            <i class="fas fa-bell"></i> Notifications
                            <span class="badge rounded-pill bg-accent ms-1">3</span>
                        </a>
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
        <h2>Available Courses</h2>
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
                        <th>Available Capacity</th>
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
                            <td><?php echo htmlspecialchars($course['available_capacity']); ?></td>
                            <td>
                                <?php if ($course['available_capacity'] > 0): ?>
                                    <form action="course.php" method="POST">
                                        <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
                                        <button type="submit" class="btn btn-primary" name="enroll_course">Enroll</button>
                                    </form>
                                <?php else: ?>
                                    <button class="btn btn-secondary" disabled>Full</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
