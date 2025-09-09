<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskFlow - Simple & Powerful Todo App</title>
    <link rel="stylesheet" href="/css/index.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-logo">
                <h1>TaskFlow</h1>
            </div>
            <div class="nav-menu">
                <a href="#features" class="nav-link">Features</a>
                <a href="#about" class="nav-link">About</a>
                <?php if (!isset($_SESSION['user_id'])) : ?>
                    <a href="/login" class="nav-btn login-btn">Login</a>
                    <a href="/register" class="nav-btn signup-btn">Sign Up</a>
                <?php else : ?>
                    <a href="/dashboard" class="nav-btn signup-btn">Dashboard</a>
                <?php endif; ?>
            </div>
            <div class="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <div class="hero-text">
                <h1 class="hero-title">
                    Organize Your Life with 
                    <span class="gradient-text">TaskFlow</span>
                </h1>
                <p class="hero-description">
                    The simple yet powerful todo app that helps you stay organized, 
                    boost productivity, and achieve your goals. Get things done efficiently 
                    with our intuitive task management system.
                </p>
                <div class="hero-buttons">
                    <a href="/register" class="cta-button primary">Get Started Free</a>
                    <a href="#features" class="cta-button secondary">Learn More</a>
                </div>
                <div class="hero-stats">
                    <div class="stat">
                        <h3>10K+</h3>
                        <p>Active Users</p>
                    </div>
                    <div class="stat">
                        <h3>500K+</h3>
                        <p>Tasks Completed</p>
                    </div>
                    <div class="stat">
                        <h3>99.9%</h3>
                        <p>Uptime</p>
                    </div>
                </div>
            </div>
            <div class="hero-visual">
                <div class="mockup-container">
                    <div class="mockup-window">
                        <div class="window-header">
                            <div class="window-controls">
                                <span class="control red"></span>
                                <span class="control yellow"></span>
                                <span class="control green"></span>
                            </div>
                            <span class="window-title">TaskFlow Dashboard</span>
                        </div>
                        <div class="window-content">
                            <div class="mock-task completed">
                                <div class="task-check">✓</div>
                                <span>Complete project documentation</span>
                            </div>
                            <div class="mock-task">
                                <div class="task-check"></div>
                                <span>Review code changes</span>
                            </div>
                            <div class="mock-task">
                                <div class="task-check"></div>
                                <span>Update website design</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="features">
        <div class="container">
            <div class="section-header">
                <h2>Everything You Need to Stay Organized</h2>
                <p>Powerful features designed to make task management effortless</p>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">📝</div>
                    <h3>Simple Task Creation</h3>
                    <p>Create tasks in seconds with our intuitive interface. Add descriptions, set priorities, and organize your work effortlessly.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🎯</div>
                    <h3>Priority Management</h3>
                    <p>Set task priorities to focus on what matters most. High, medium, and low priority levels help you stay on track.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">📊</div>
                    <h3>Progress Tracking</h3>
                    <p>Monitor your productivity with visual progress indicators. See completed tasks and track your achievements over time.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🔒</div>
                    <h3>Secure & Private</h3>
                    <p>Your data is encrypted and secure. We respect your privacy and never share your personal information.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">📱</div>
                    <h3>Mobile Responsive</h3>
                    <p>Access your tasks anywhere, anytime. Our responsive design works perfectly on desktop, tablet, and mobile.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">⚡</div>
                    <h3>Lightning Fast</h3>
                    <p>Built for speed and performance. No loading delays, instant updates, and smooth user experience.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="about">
        <div class="container">
            <div class="about-content">
                <div class="about-text">
                    <h2>Built for Modern Productivity</h2>
                    <p>
                        TaskFlow was created with a simple mission: to help people get things done 
                        without the complexity. We believe that the best productivity tools are the 
                        ones that get out of your way and let you focus on what's important.
                    </p>
                    <p>
                        Our clean, intuitive interface combined with powerful features makes task 
                        management effortless. Whether you're managing personal goals or team 
                        projects, TaskFlow adapts to your workflow.
                    </p>
                    <div class="about-features">
                        <div class="about-feature">
                            <span class="checkmark">✓</span>
                            <span>No learning curve - start using immediately</span>
                        </div>
                        <div class="about-feature">
                            <span class="checkmark">✓</span>
                            <span>Clean, distraction-free interface</span>
                        </div>
                        <div class="about-feature">
                            <span class="checkmark">✓</span>
                            <span>Focus on getting things done</span>
                        </div>
                    </div>
                </div>
                <div class="about-visual">
                    <div class="stats-showcase">
                        <div class="showcase-stat">
                            <div class="stat-number">98%</div>
                            <div class="stat-label">User Satisfaction</div>
                        </div>
                        <div class="showcase-stat">
                            <div class="stat-number">3x</div>
                            <div class="stat-label">Productivity Boost</div>
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
                <h2>Ready to Get Organized?</h2>
                <p>Join thousands of users who have transformed their productivity with TaskFlow</p>
                <div class="cta-buttons">
                    <a href="/register" class="cta-button primary large">Start Free Today</a>
                    <a href="/login" class="cta-button secondary large">I Already Have an Account</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-brand">
                    <h3>TaskFlow</h3>
                    <p>Simple. Powerful. Productive.</p>
                </div>
                <div class="footer-links">
                    <div class="footer-column">
                        <h4>Product</h4>
                        <ul>
                            <li><a href="#features">Features</a></li>
                            <li><a href="#about">About</a></li>
                            <li><a href="/register">Sign Up</a></li>
                        </ul>
                    </div>
                    <div class="footer-column">
                        <h4>Support</h4>
                        <ul>
                            <li><a href="#">Help Center</a></li>
                            <li><a href="#">Contact Us</a></li>
                            <li><a href="#">Privacy Policy</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 TaskFlow. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        const hamburger = document.querySelector('.hamburger');
        const navMenu = document.querySelector('.nav-menu');

        hamburger.addEventListener('click', () => {
            hamburger.classList.toggle('active');
            navMenu.classList.toggle('active');
        });

        // Smooth scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Navbar scroll effect
        window.addEventListener('scroll', () => {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 100) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    </script>
</body>
</html>