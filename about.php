<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Malaysia Train Services</title>
    <style>
        :root {
            --pastel-pink: #ffd6e7;
            --pastel-green: #d6f5e3;
            --pink-accent: #ff85a2;
            --green-accent: #7ecf9b;
            --text-dark: #4a4a4a;
            --text-light: #888888;
            --border-light: #e8d4e0;
            --white: #ffffff;
            --background: #fef7fa;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, var(--pastel-pink) 0%, var(--pastel-green) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            color: var(--text-dark);
        }

        .about-container {
            background: var(--white);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(255, 133, 162, 0.15);
            padding: 40px 35px;
            width: 100%;
            max-width: 1000px;
            border: 1px solid var(--border-light);
            position: relative;
            overflow: hidden;
        }

        .about-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--pink-accent) 0%, var(--green-accent) 100%);
        }

        /* HEADER SAMA SEPERTI HOME PAGE */
        .company-header {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 2px solid var(--pastel-pink);
        }

        .company-header h1 {
            color: var(--text-dark);
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 8px;
            background: linear-gradient(135deg, var(--pink-accent) 0%, var(--green-accent) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .company-header .tagline {
            color: var(--text-light);
            font-size: 18px;
            font-weight: 400;
            margin-bottom: 15px;
        }

        /* NAVIGATION - PLAIN TEXT SEPERTI SCREENSHOT */
        .navigation {
            display: flex;
            justify-content: center;
            gap: 25px;
            margin-bottom: 40px;
            flex-wrap: wrap;
        }

        .nav-link {
            color: var(--text-dark);
            text-decoration: none;
            font-size: 16px;
            font-weight: 500;
            padding: 5px 0;
            transition: color 0.3s ease;
        }

        .nav-link:hover {
            color: var(--pink-accent);
        }

        .nav-link.active {
            color: var(--pink-accent);
            font-weight: 600;
        }

        /* ABOUT CONTENT - TIDAK DIUBAH */
        .about-content {
            margin-bottom: 30px;
        }

        .content-section {
            background: linear-gradient(135deg, var(--pastel-pink) 0%, var(--pastel-green) 100%);
            padding: 30px;
            border-radius: 15px;
            border: 1px solid var(--border-light);
            margin-bottom: 25px;
        }

        .content-section h2 {
            color: var(--text-dark);
            margin-bottom: 15px;
            font-size: 24px;
            border-left: 4px solid var(--pink-accent);
            padding-left: 15px;
        }

        .content-section p {
            color: var(--text-dark);
            line-height: 1.6;
            margin-bottom: 15px;
            font-size: 16px;
        }

        /* FEATURES GRID - TIDAK DIUBAH */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .feature-card {
            background: var(--white);
            padding: 25px;
            border-radius: 12px;
            text-align: center;
            border: 1px solid var(--border-light);
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }

        .feature-card h3 {
            color: var(--pink-accent);
            margin-bottom: 15px;
            font-size: 18px;
        }

        .feature-card p {
            color: var(--text-light);
            font-size: 14px;
            margin: 0;
        }

        /* STATS SECTION - TIDAK DIUBAH */
        .stats-section {
            background: var(--white);
            padding: 25px;
            border-radius: 15px;
            border: 1px solid var(--border-light);
            margin-top: 30px;
        }

        .stats-section h3 {
            color: var(--text-dark);
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 20px;
            text-align: center;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
        }

        .stat-item {
            background: linear-gradient(135deg, var(--pastel-pink) 0%, var(--pastel-green) 100%);
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            border: 1px solid var(--border-light);
        }

        .stat-item .number {
            font-size: 24px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 5px;
        }

        .stat-item .label {
            font-size: 12px;
            color: var(--text-light);
            font-weight: 500;
        }

        /* MISSION SECTION - TIDAK DIUBAH */
        .mission-section {
            background: linear-gradient(135deg, var(--pink-accent) 0%, var(--green-accent) 100%);
            color: var(--white);
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            margin-top: 30px;
        }

        .mission-section h3 {
            font-size: 22px;
            margin-bottom: 15px;
        }

        .mission-section p {
            color: var(--white);
            opacity: 0.9;
            margin: 0;
            font-size: 16px;
        }

        /* FOOTER - TIDAK DIUBAH */
        .footer {
            text-align: center;
            padding-top: 25px;
            border-top: 1px solid var(--pastel-pink);
            color: var(--text-light);
            font-size: 14px;
            margin-top: 30px;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 10px;
        }

        .footer-links a {
            color: var(--pink-accent);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-links a:hover {
            color: var(--green-accent);
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .about-container {
                padding: 30px 20px;
            }
            
            .company-header h1 {
                font-size: 28px;
            }
            
            .navigation {
                gap: 15px;
            }
            
            .nav-link {
                font-size: 14px;
            }
            
            .features-grid {
                grid-template-columns: 1fr;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 480px) {
            .company-header h1 {
                font-size: 24px;
            }
            
            .navigation {
                gap: 10px;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="about-container">
        <!-- Header Sama Seperti Home Page -->
        <div class="company-header">
            <h1>Malaysia Train Services</h1>
            <p class="tagline">We're Here to Help You</p>
            
            <!-- Navigation Menu - PLAIN TEXT SEPERTI SCREENSHOT -->
            <div class="navigation">
                <a href="index.php" class="nav-link">Home</a>
                <a href="trainschedule.php" class="nav-link">Train Schedule</a>
                <a href="login.php" class="nav-link">Login</a>
                <a href="register.php" class="nav-link">Register</a>
                <a href="about.php" class="nav-link active">About</a>
                <a href="support.php" class="nav-link">Support</a>
            </div>
        </div>

        <!-- About Content - TIDAK DIUBAH -->
        <div class="about-content">
            <div class="content-section">
                <h2>About Malaysia Train Services</h2>
                <p>Welcome to Malaysia Train Services, your trusted partner in comfortable and reliable train transportation across Malaysia. We are committed to providing exceptional service and ensuring your journey is safe, enjoyable, and memorable.</p>
                <p>With years of experience in the transportation industry, we have built a reputation for excellence, innovation, and customer satisfaction. Our modern fleet of trains and dedicated staff work together to make your travel experience seamless.</p>
            </div>

            <div class="content-section">
                <h2>Our Vision & Mission</h2>
                <p><strong>Vision:</strong> To be Malaysia's leading train service provider, connecting communities and fostering economic growth through sustainable transportation solutions.</p>
                <p><strong>Mission:</strong> To provide safe, affordable, and comfortable train services that exceed customer expectations while contributing to environmental sustainability and national development.</p>
            </div>

            <div class="features-grid">
                <div class="feature-card">
                    <h3>Safety First</h3>
                    <p>Your safety is our top priority with the highest standards maintained across all operations.</p>
                </div>
                <div class="feature-card">
                    <h3>Comfortable Travel</h3>
                    <p>Enjoy spacious seating, clean facilities, and pleasant journey experiences with us.</p>
                </div>
                <div class="feature-card">
                    <h3>Reliable Service</h3>
                    <p>We pride ourselves on punctuality and reliability you can count on for important journeys.</p>
                </div>
            </div>

            <div class="stats-section">
                <h3>Our Achievements</h3>
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="number">50K+</div>
                        <div class="label">Happy Customers</div>
                    </div>
                    <div class="stat-item">
                        <div class="number">95%</div>
                        <div class="label">On-Time Arrival</div>
                    </div>
                    <div class="stat-item">
                        <div class="number">24/7</div>
                        <div class="label">Customer Support</div>
                    </div>
                    <div class="stat-item">
                        <div class="number">15+</div>
                        <div class="label">Destinations</div>
                    </div>
                </div>
            </div>

            <div class="mission-section">
                <h3>Your Journey Begins With Us</h3>
                <p>Experience the difference with Malaysia Train Services - where every journey is crafted with care, professionalism, and dedication to excellence.</p>
            </div>
        </div>

        <!-- Footer - TIDAK DIUBAH -->
        <div class="footer">
            <p>&copy; <?php echo date("Y"); ?> Malaysia Train Services. All rights reserved.</p>
            <div class="footer-links">
                <a href="about.php">About Us</a>
                <a href="support.php">Support</a>
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
            </div>
        </div>
    </div>

    <script>
        // Add some interactive effects
        document.querySelectorAll('.feature-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-8px)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(-5px)';
            });
        });

        document.querySelectorAll('.stat-item').forEach(stat => {
            stat.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.05)';
            });
            
            stat.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1)';
            });
        });
    </script>
</body>
</html>