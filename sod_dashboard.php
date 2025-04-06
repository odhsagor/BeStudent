<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SoD Dashboard | BeStudent</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.css">
    <style>
        :root {
            --primary-color: #6a11cb;
            --secondary-color: #2575fc;
            --accent-color: #ff5e62;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --info-color: #17a2b8;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8fafc;
            color: var(--dark-color);
        }
        
        .navbar {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 0.8rem 1rem;
        }
        
        .brand-logo {
            height: 32px;
            margin-right: 10px;
        }
        
        .profile-img {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
        }
        
        .profile-img:hover {
            border-color: white;
            transform: scale(1.05);
        }
        
        .dashboard-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.05);
            margin-bottom: 24px;
            border: none;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
        }
        
        .card-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 1rem 1.5rem;
            border-bottom: none;
        }
        
        .stat-card {
            text-align: center;
            padding: 1.5rem;
            position: relative;
            overflow: hidden;
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .stat-label {
            font-size: 1rem;
            color: var(--dark-color);
            opacity: 0.8;
            margin-bottom: 1rem;
        }
        
        .stat-icon {
            font-size: 2.5rem;
            opacity: 0.2;
            position: absolute;
            bottom: 10px;
            right: 20px;
            color: var(--primary-color);
        }
        
        .activity-item {
            border-left: 3px solid var(--primary-color);
            padding-left: 1rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }
        
        .activity-item:hover {
            background-color: rgba(106, 17, 203, 0.05);
            transform: translateX(5px);
        }
        
        .course-preview {
            max-height: 60px;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }
        
        .badge-pending {
            background-color: var(--warning-color);
        }
        
        .badge-active {
            background-color: var(--success-color);
        }
        
        .sidebar {
            background: white;
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.05);
            padding: 1.5rem;
            height: 100%;
        }
        
        .sidebar-nav .nav-link {
            color: var(--dark-color);
            border-radius: 8px;
            padding: 0.75rem 1rem;
            margin-bottom: 0.5rem;
            transition: all 0.3s ease;
        }
        
        .sidebar-nav .nav-link:hover, .sidebar-nav .nav-link.active {
            background: linear-gradient(135deg, rgba(106, 17, 203, 0.1), rgba(37, 117, 252, 0.1));
            color: var(--primary-color);
            font-weight: 500;
        }
        
        .sidebar-nav .nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        .welcome-header {
            background: linear-gradient(135deg, rgba(106, 17, 203, 0.1), rgba(37, 117, 252, 0.1));
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }
        
        .welcome-header::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 200px;
            height: 100%;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" fill="%236a11cb" opacity="0.05"><path d="M30,10 Q50,5 70,20 T90,50 Q95,70 80,90 T50,95 Q30,90 20,70 T10,50 Q5,30 20,20 T30,10"></path></svg>');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: right center;
        }
        
        .btn-gradient {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            transition: all 0.3s ease;
        }
        
        .btn-gradient:hover {
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        
        /* New Glassmorphism Effect */
        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.18);
        }
        
        /* Floating animation */
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
        
        .floating {
            animation: float 6s ease-in-out infinite;
        }
    </style>
</head>
<body>
    <!-- Navbar with BeStudent Logo -->
    <nav class="navbar navbar-expand-lg navbar-dark mb-4">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="sod_dashboard.php">
                <img src="image/BeStudent.png" alt="BeStudent Logo" class="brand-logo">
                <span class="font-weight-bold d-none d-md-inline">BeStudent</span>
            </a>
            <div class="d-flex align-items-center">
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="<?= htmlspecialchars($sod['profile_image'] ?? 'https://ui-avatars.com/api/?name='.urlencode($sod['name']).'&background=6a11cb&color=fff') ?>" 
                             class="profile-img me-2">
                        <span class="d-none d-md-inline"><?= htmlspecialchars($sod['name']) ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="dropdownUser">
                        <li><a class="dropdown-item" href="profile.php"><i class="fas fa-user-circle me-2"></i> Profile</a></li>
                        <li><a class="dropdown-item" href="settings.php"><i class="fas fa-cog me-2"></i> Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-2 d-none d-lg-block">
                <div class="sidebar sticky-top mb-4 glass-card" style="top: 20px;">
                    <h5 class="mb-4 text-center">SoD</h5>
                    <ul class="nav flex-column sidebar-nav">
                        <li class="nav-item">
                            <a class="nav-link active" href="sod_dashboard.php">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="courses.php">
                                <i class="fas fa-book-open"></i> Courses
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="faculty.php">
                                <i class="fas fa-chalkboard-teacher"></i> Faculty
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="students.php">
                                <i class="fas fa-users"></i> Students
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="curriculum.php">
                                <i class="fas fa-graduation-cap"></i> Curriculum
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="reports.php">
                                <i class="fas fa-chart-bar"></i> Reports
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="settings.php">
                                <i class="fas fa-cog"></i> Settings
                            </a>
                        </li>
                    </ul>
                    <div class="text-center mt-4">
                        <img src="image/BeStudent.png" alt="Design Icon" class="img-fluid floating" style="max-width: 80px;">
                    </div>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-lg-10">
                <!-- Welcome Header -->
                <div class="welcome-header mb-4 glass-card">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="fw-bold mb-2">Welcome back, <?= htmlspecialchars($sod['name']) ?>! <span class="badge bg-primary">SoD</span></h2>
                            <p class="lead mb-0"><?= htmlspecialchars($sod['department']) ?> Department</p>
                            <p class="text-muted mb-0"><i class="fas fa-envelope me-2"></i><?= htmlspecialchars($sod['email']) ?></p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <a href="announcements.php" class="btn btn-light me-2">
                                <i class="fas fa-bullhorn me-1"></i> Announcements
                            </a>
                            <a href="calendar.php" class="btn btn-light">
                                <i class="fas fa-calendar-alt me-1"></i> Calendar
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Stats Row -->
                <div class="row mb-4">
                    <div class="col-md-6 col-lg-3">
                        <div class="dashboard-card stat-card glass-card">
                            <div class="stat-number"><?= $stats['faculty_count'] ?></div>
                            <div class="stat-label">Faculty Members</div>
                            <i class="fas fa-chalkboard-teacher stat-icon"></i>
                            <a href="faculty.php" class="stretched-link"></a>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="dashboard-card stat-card glass-card">
                            <div class="stat-number"><?= $stats['student_count'] ?></div>
                            <div class="stat-label">Students</div>
                            <i class="fas fa-users stat-icon"></i>
                            <a href="students.php" class="stretched-link"></a>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="dashboard-card stat-card glass-card">
                            <div class="stat-number"><?= $stats['course_count'] ?></div>
                            <div class="stat-label">Total Courses</div>
                            <i class="fas fa-book-open stat-icon"></i>
                            <a href="courses.php" class="stretched-link"></a>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="dashboard-card stat-card glass-card">
                            <div class="stat-number"><?= $stats['pending_courses'] ?></div>
                            <div class="stat-label">Pending Approvals</div>
                            <i class="fas fa-clock stat-icon"></i>
                            <a href="#pending-approvals" class="stretched-link"></a>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Pending Approvals -->
                    <div class="col-lg-8">
                        <div class="dashboard-card mb-4 glass-card" id="pending-approvals">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0"><i class="fas fa-clock me-2"></i> Pending Course Approvals</h5>
                                <span class="badge bg-warning"><?= count($pending_courses) ?> pending</span>
                            </div>
                            <div class="card-body">
                                <?php if (!empty($pending_courses)): ?>
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Course Code</th>
                                                    <th>Course Name</th>
                                                    <th>Description</th>
                                                    <th>Faculty</th>
                                                    <th>Submitted</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($pending_courses as $course): ?>
                                                    <tr>
                                                        <td class="fw-bold"><?= htmlspecialchars($course['course_code']) ?></td>
                                                        <td><?= htmlspecialchars($course['course_name']) ?></td>
                                                        <td class="course-preview" title="<?= htmlspecialchars($course['description']) ?>">
                                                            <?= htmlspecialchars($course['description']) ?>
                                                        </td>
                                                        <td><?= htmlspecialchars($course['faculty_name']) ?></td>
                                                        <td><?= date('M d, Y', strtotime($course['created_at'])) ?></td>
                                                        <td>
                                                            <div class="btn-group btn-group-sm">
                                                                <a href="course_preview.php?id=<?= $course['course_id'] ?>" class="btn btn-outline-primary" title="Preview">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                                <a href="approve_course.php?id=<?= $course['course_id'] ?>" class="btn btn-outline-success" title="Approve">
                                                                    <i class="fas fa-check"></i>
                                                                </a>
                                                                <a href="reject_course.php?id=<?= $course['course_id'] ?>" class="btn btn-outline-danger" title="Reject">
                                                                    <i class="fas fa-times"></i>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else: ?>
                                    <div class="alert alert-success mb-0">
                                        <i class="fas fa-check-circle me-2"></i> No pending course approvals!
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Courses Overview -->
                        <div class="dashboard-card glass-card">
                            <div class="card-header">
                                <h5 class="card-title mb-0"><i class="fas fa-chart-line me-2"></i> Courses Overview</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="coursesChart" height="250"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activities & Quick Actions -->
                    <div class="col-lg-4">
                        <!-- Recent Activities -->
                        <div class="dashboard-card mb-4 glass-card">
                            <div class="card-header">
                                <h5 class="card-title mb-0"><i class="fas fa-bell me-2"></i> Recent Activities</h5>
                            </div>
                            <div class="card-body">
                                <?php if (!empty($activities)): ?>
                                    <?php foreach ($activities as $activity): ?>
                                        <div class="activity-item">
                                            <h6 class="mb-1 fw-bold"><?= htmlspecialchars(ucfirst($activity['activity_type'])) ?></h6>
                                            <p class="mb-1 small"><?= htmlspecialchars($activity['description']) ?></p>
                                            <p class="text-muted small mb-0">
                                                <i class="far fa-clock me-1"></i>
                                                <?= date('M j, g:i a', strtotime($activity['created_at'])) ?>
                                            </p>
                                        </div>
                                    <?php endforeach; ?>
                                    <a href="activities.php" class="btn btn-outline-primary btn-sm w-100 mt-2">
                                        View All Activities
                                    </a>
                                <?php else: ?>
                                    <div class="alert alert-info mb-0">
                                        <i class="fas fa-info-circle me-2"></i> No recent activities found.
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="dashboard-card glass-card">
                            <div class="card-header">
                                <h5 class="card-title mb-0"><i class="fas fa-bolt me-2"></i> Quick Actions</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <a href="create_course.php" class="btn btn-gradient mb-2">
                                        <i class="fas fa-plus me-2"></i> Create New Course
                                    </a>
                                    <a href="manage_faculty.php" class="btn btn-outline-primary mb-2">
                                        <i class="fas fa-users-cog me-2"></i> Manage Faculty
                                    </a>
                                    <a href="department_report.php" class="btn btn-outline-primary mb-2">
                                        <i class="fas fa-file-pdf me-2"></i> Generate Report
                                    </a>
                                    <a href="meeting_scheduler.php" class="btn btn-outline-primary">
                                        <i class="fas fa-video me-2"></i> Schedule Meeting
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
    <script>
        // Courses Chart
        const ctx = document.getElementById('coursesChart').getContext('2d');
        const coursesChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Active Courses', 'Pending Approval', 'Archived'],
                datasets: [{
                    data: [<?= $stats['active_courses'] ?>, <?= $stats['pending_courses'] ?>, 0],
                    backgroundColor: [
                        'rgba(40, 167, 69, 0.8)',
                        'rgba(255, 193, 7, 0.8)',
                        'rgba(108, 117, 125, 0.8)'
                    ],
                    borderColor: [
                        'rgba(40, 167, 69, 1)',
                        'rgba(255, 193, 7, 1)',
                        'rgba(108, 117, 125, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += context.raw;
                                return label;
                            }
                        }
                    }
                }
            }
        });

        // Add animation to stat cards on hover
        document.querySelectorAll('.stat-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
            });
            card.addEventListener('mouseleave', function() {
                this.style.transform = '';
            });
        });
    </script>
</body>
</html>