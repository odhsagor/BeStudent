<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BeStudent | Collaborative Learning Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/home.css">
</head>
<body>
    <!-- Floating Particles Background -->
    <div id="particles-js"></div>
    
    <!-- Navbar with Large Logo -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="Image/BeStudent.png" alt="BeStudent Logo" class="logo-img">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link btn-gradient mx-2" href="getStarted.php">Get Started</a></li>
                    <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="#courses">Courses</a></li>
                    <li class="nav-item"><a class="nav-link" href="adminRegister.php">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="admin_login.php">Administrator Login</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section with Animated Elements -->
    <section class="hero-section text-center text-white">
        <div class="hero-content">
            <div class="floating-books">
                <i class="fas fa-book"></i>
                <i class="fas fa-graduation-cap"></i>
                <i class="fas fa-lightbulb"></i>
            </div>
            
            <h1 class="display-3 fw-bold mb-4 animate__animated animate__fadeInDown">
                <span class="text-gradient">Study Smarter,</span><br>
                <span class="d-inline-block animate__animated animate__tada animate__delay-1s">Together</span>
            </h1>
            
            <p class="lead mb-5 animate__animated animate__fadeIn animate__delay-1s">
                Join the largest student-powered learning community at BeStudent
            </p>
            
            <div class="search-bar animate__animated animate__fadeInUp animate__delay-1s">
                <div class="input-group">
                    <input type="text" class="form-control search-input" placeholder="Find course materials, SoDs, or groups...">
                    <button class="btn btn-gradient">
                        <i class="fas fa-search me-2"></i> Search
                    </button>
                </div>
            </div>

            <div class="stats-container animate__animated animate__fadeInUp animate__delay-2s">
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <h3>1K+</h3>
                        <p>Study Resources</p>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <h3>100+</h3>
                        <p>Active SoDs</p>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3>6k+</h3>
                        <p>Students</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="hero-wave">
            <svg viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z" opacity=".25"></path>
                <path d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z" opacity=".5"></path>
                <path d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z"></path>
            </svg>
        </div>
    </section>

    <!-- Features Grid -->
    <section class="features-section">
        <div class="container">
            <div class="section-header">
                <h2>Why Choose BeStudent?</h2>
                <p>Our platform brings together the best learning tools for university students</p>
            </div>
            
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>Course Groups</h3>
                    <p>Join real-time study sessions with classmates and collaborate on projects</p>
                    <ul class="feature-tags">
                        <li>Live Chat</li>
                        <li>Q&A Forum</li>
                        <li>File Sharing</li>
                    </ul>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <h3>Smart Resources</h3>
                    <p>Crowdsourced notes with AI-powered search and recommendations</p>
                    <ul class="feature-tags">
                        <li>PDF Viewer</li>
                        <li>Ratings</li>
                        <li>Annotations</li>
                    </ul>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <h3>Peer SoDing</h3>
                    <p>1:1 video sessions with top students in your courses</p>
                    <ul class="feature-tags">
                        <li>Video Call</li>
                        <li>Screen Share</li>
                        <li>Whiteboard</li>
                    </ul>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <h3>Study Planner</h3>
                    <p>Organize your schedule with smart reminders and progress tracking</p>
                    <ul class="feature-tags">
                        <li>Sync Calendar</li>
                        <li>Task Manager</li>
                        <li>Progress Reports</li>
                    </ul>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-robot"></i>
                    </div>
                    <h3>AI Assistant</h3>
                    <p>Get instant help with course concepts and homework questions</p>
                    <ul class="feature-tags">
                        <li>24/7 Help</li>
                        <li>Math Solver</li>
                        <li>Writing Feedback</li>
                    </ul>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <h3>Gamification</h3>
                    <p>Earn badges and rewards for your learning achievements</p>
                    <ul class="feature-tags">
                        <li>Leaderboards</li>
                        <li>Badges</li>
                        <li>Milestones</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

<!-- Testimonials Section -->
<section class="testimonials-section">
    <div class="container">
        <div class="section-header">
            <h2>What Students Say</h2>
            <p>Join thousands of students who improved their grades with BeStudent</p>
        </div>
        
        <div class="testimonials-slider">
            <!-- Testimonial 1 -->
            <div class="testimonial-card">
                <div class="testimonial-content">
                    <div class="quote-icon">
                        <i class="fas fa-quote-left"></i>
                    </div>
                    <p>BeStudent helped me find study partners for my toughest courses. The shared notes and group discussions cut my study time in half!</p>
                    <div class="testimonial-author-box">
                        <img src="Image/sagor.png" alt="Md. Obidul Huq Sagor" class="testimonial-img">
                        <div>
                            <h4>Md. Obidul Huq Sagor</h4>
                            <p>Computer Science, 4th Year</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Testimonial 2 -->
            <div class="testimonial-card">
                <div class="testimonial-content">
                    <div class="quote-icon">
                        <i class="fas fa-quote-left"></i>
                    </div>
                    <p>As an international student, the SoDing sessions were a lifesaver. My SoD explained concepts in ways I could understand.</p>
                    <div class="testimonial-author-box">
                        <img src="Image/sagor.png" alt="AHM Imtiaj" class="testimonial-img">
                        <div>
                            <h4>AHM Imtiaj</h4>
                            <p>Engineering, 4th Year</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Testimonial 3 -->
            <div class="testimonial-card">
                <div class="testimonial-content">
                    <div class="quote-icon">
                        <i class="fas fa-quote-left"></i>
                    </div>
                    <p>The AI study assistant helped me prepare for finals by creating personalized quizzes based on my weak areas. Game changer!</p>
                    <div class="testimonial-author-box">
                        <img src="Image/sagor.png" alt="TAAA" class="testimonial-img">
                        <div>
                            <h4>TAAA</h4>
                            <p>Law, 3rd Year</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-content">
                <h2>Ready to Transform Your Learning?</h2>
                <p>Join BeStudent today and access all features for free during our beta period</p>
                <div class="cta-buttons">
                    <a href="getStarted.php " class="btn btn-gradient btn-lg">
                        <i class="fas fa-user-plus me-2"></i> Sign Up Free
                    </a>
                    <a href="#demo" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-play-circle me-2"></i> Watch Demo
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-main">
            <div class="container">
                <div class="footer-grid">
                    <div class="footer-brand">
                        <img src="Image/BeStudent.png" alt="BeStudent Logo" class="footer-logo">
                        <p>Empowering students through collaborative learning since 2025</p>
                        <div class="social-links">
                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                            <a href="#"><i class="fab fa-instagram"></i></a>
                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#"><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>
                    
                    <div class="footer-links">
                        <h4>Platform</h4>
                        <ul>
                            <li><a href="#features">Features</a></li>
                            <li><a href="#courses">Courses</a></li>
                            <li><a href="#pricing">Pricing</a></li>
                            <li><a href="#download">Download App</a></li>
                            <li><a href="#status">Status</a></li>
                        </ul>
                    </div>
                    
                    <div class="footer-links">
                        <h4>Resources</h4>
                        <ul>
                            <li><a href="#blog">Blog</a></li>
                            <li><a href="#help">Help Center</a></li>
                            <li><a href="#SoDials">SoDials</a></li>
                            <li><a href="#webinars">Webinars</a></li>
                            <li><a href="#community">Community</a></li>
                        </ul>
                    </div>
                    
                    <div class="footer-links">
                        <h4>Company</h4>
                        <ul>
                            <li><a href="#about">About Us</a></li>
                            <li><a href="#careers">Careers</a></li>
                            <li><a href="#partners">Partners</a></li>
                            <li><a href="#contact">Contact</a></li>
                            <li><a href="#press">Press</a></li>
                        </ul>
                    </div>
                    
                    <div class="footer-newsletter">
                        <h4>Stay Updated</h4>
                        <p>Subscribe to our newsletter for the latest updates</p>
                        <form class="newsletter-form">
                            <input type="email" placeholder="Your email address">
                            <button type="submit">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="footer-bottom">
            <div class="container">
                <div class="footer-bottom-content">
                    <p>&copy; 2025 BeStudent. All rights reserved.</p>
                    <div class="footer-legal">
                        <a href="#privacy">Privacy Policy</a>
                        <a href="#terms">Terms of Service</a>
                        <a href="#cookies">Cookie Policy</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <script src="script.js"></script>
</body>
</html>