<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'faculty') {
    header("Location: login.php");
    exit();
}


require_once 'db.php';

try {
    $faculty_id = $_SESSION['user_id'];
    $stmt = $pdo->prepare("SELECT name, email, department, profile_image FROM users WHERE id = ?");
    $stmt->execute([$faculty_id]);
    $faculty = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("SELECT c.course_id, c.course_code, c.course_name, 
                          COUNT(sc.student_id) as student_count
                          FROM courses c
                          JOIN faculty_courses fc ON c.course_id = fc.course_id
                          LEFT JOIN student_courses sc ON c.course_id = sc.course_id
                          WHERE fc.faculty_id = ?
                          GROUP BY c.course_id");
    $stmt->execute([$faculty_id]);
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt = $pdo->prepare("SELECT a.assignment_id, a.title, a.deadline, c.course_code,
                          COUNT(s.submission_id) as submission_count,
                          SUM(CASE WHEN s.score IS NULL THEN 1 ELSE 0 END) as ungraded_count
                          FROM assignments a
                          JOIN courses c ON a.course_id = c.course_id
                          LEFT JOIN assignment_submissions s ON a.assignment_id = s.assignment_id
                          WHERE a.created_by = ?
                          AND a.deadline > NOW()
                          GROUP BY a.assignment_id
                          ORDER BY a.deadline ASC
                          LIMIT 5");
    $stmt->execute([$faculty_id]);
    $assignments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("SELECT title, content, created_at 
                          FROM announcements 
                          WHERE target_role IN ('faculty', 'all')
                          ORDER BY created_at DESC 
                          LIMIT 3");
    $stmt->execute();
    $announcements = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $total_courses = count($courses);
    $total_students = array_sum(array_column($courses, 'student_count'));
    $total_assignments = count($assignments);
    $total_ungraded = array_sum(array_column($assignments, 'ungraded_count'));

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Dashboard | BeStudent</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #2e59d9;
            --accent-color: #e74a3b;
            --light-color: #f8f9fc;
            --dark-color: #5a5c69;
        }
        
        body {
            font-family: 'Nunito', sans-serif;
            background-color: var(--light-color);
        }
        
        .navbar {
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }
        
        .card {
            border: none;
            border-radius: 0.35rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
            margin-bottom: 1.5rem;
        }
        
        .card-header {
            background-color: #f8f9fc;
            border-bottom: 1px solid #e3e6f0;
            font-weight: 600;
        }
        
        .bg-primary {
            background-color: var(--primary-color) !important;
        }
        
        .text-primary {
            color: var(--primary-color) !important;
        }
        
        .stat-card {
            border-left: 0.25rem solid var(--primary-color);
        }
        
        .stat-card .stat-number {
            font-size: 1.5rem;
            font-weight: 700;
        }
        
        .course-card {
            border-left: 0.25rem solid var(--primary-color);
            transition: all 0.3s;
        }
        
        .course-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        
        .assignment-item {
            border-left: 0.25rem solid var(--accent-color);
            transition: all 0.3s;
        }
        
        .assignment-item:hover {
            background-color: rgba(231, 74, 59, 0.05);
        }
        
        .profile-img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #fff;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark shadow">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <i class="fas fa-chalkboard-teacher me-2"></i> Faculty Dashboard
            </a>
            <div class="d-flex align-items-center">
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" 
                       id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="<?= htmlspecialchars($faculty['profile_image'] ?? 'https://ui-avatars.com/api/?name='.urlencode($faculty['name'])) ?>" 
                             class="profile-img me-2">
                        <span><?= htmlspecialchars($faculty['name']) ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownUser">
                        <li><a class="dropdown-item" href="profile.php"><i class="fas fa-user me-2"></i> Profile</a></li>
                        <li><a class="dropdown-item" href="settings.php"><i class="fas fa-cog me-2"></i> Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container-fluid mt-4">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
            <a href="create_assignment.php" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50"></i> New Assignment
            </a>
        </div>

        <!-- Stats Cards -->
        <div class="row">
            <!-- Courses Card -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Courses Teaching</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_courses ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-book fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Students Card -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Students</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_students ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Assignments Card -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Active Assignments</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_assignments ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-tasks fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ungraded Card -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Submissions to Grade</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_ungraded ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Row -->
        <div class="row">
            <!-- Courses Column -->
            <div class="col-lg-8">
                <!-- Courses Card -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">My Courses</h6>
                        <a href="courses.php" class="btn btn-sm btn-primary">View All</a>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($courses)): ?>
                            <div class="row">
                                <?php foreach ($courses as $course): ?>
                                    <div class="col-md-6 mb-4">
                                        <div class="card course-card h-100">
                                            <div class="card-body">
                                                <h5 class="card-title"><?= htmlspecialchars($course['course_name']) ?></h5>
                                                <h6 class="card-subtitle mb-2 text-muted"><?= htmlspecialchars($course['course_code']) ?></h6>
                                                <p class="card-text">
                                                    <span class="badge bg-primary">
                                                        <?= $course['student_count'] ?> students
                                                    </span>
                                                </p>
                                                <div class="d-flex justify-content-between">
                                                    <a href="course.php?id=<?= $course['course_id'] ?>" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye"></i> View
                                                    </a>
                                                    <a href="attendance.php?course=<?= $course['course_id'] ?>" class="btn btn-sm btn-primary">
                                                        <i class="fas fa-clipboard-list"></i> Attendance
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i> You are not assigned to any courses yet.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Assignments Card -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Assignments to Grade</h6>
                        <a href="assignments.php" class="btn btn-sm btn-primary">View All</a>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($assignments)): ?>
                            <div class="list-group">
                                <?php foreach ($assignments as $assignment): ?>
                                    <a href="grade.php?id=<?= $assignment['assignment_id'] ?>" 
                                       class="list-group-item list-group-item-action assignment-item">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1"><?= htmlspecialchars($assignment['title']) ?></h6>
                                            <small><?= date('M d', strtotime($assignment['deadline'])) ?></small>
                                        </div>
                                        <small class="text-muted"><?= htmlspecialchars($assignment['course_code']) ?></small>
                                        <span class="badge bg-danger float-end">
                                            <?= $assignment['ungraded_count'] ?> to grade
                                        </span>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle me-2"></i> No assignments require grading at this time.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="col-lg-4">
                <!-- Announcements Card -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Announcements</h6>
                        <a href="announcements.php" class="btn btn-sm btn-primary">View All</a>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($announcements)): ?>
                            <div class="list-group">
                                <?php foreach ($announcements as $announcement): ?>
                                    <div class="list-group-item mb-2">
                                        <h6 class="mb-1"><?= htmlspecialchars($announcement['title']) ?></h6>
                                        <p class="mb-1 small"><?= htmlspecialchars(substr($announcement['content'], 0, 100)) ?>...</p>
                                        <small class="text-muted">
                                            <?= date('M d, Y', strtotime($announcement['created_at'])) ?>
                                        </small>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i> No announcements available.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Quick Actions Card -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="create_assignment.php" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i> Create Assignment
                            </a>
                            <a href="gradebook.php" class="btn btn-outline-primary">
                                <i class="fas fa-book me-2"></i> Gradebook
                            </a>
                            <a href="messages.php" class="btn btn-outline-primary">
                                <i class="fas fa-envelope me-2"></i> Send Message
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>