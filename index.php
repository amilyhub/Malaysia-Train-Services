<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Malaysia Train Services</title>
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

        .home-container {
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

        .home-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--pink-accent) 0%, var(--green-accent) 100%);
        }

        .company-header {
            text-align: center;
            margin-bottom: 32px;
            padding-bottom: 24px;
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
        }

        .navigation {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 40px;
            flex-wrap: wrap;
        }

        .nav-btn {
            padding: 12px 24px;
            background: linear-gradient(135deg, var(--pink-accent) 0%, var(--green-accent) 100%);
            color: var(--white);
            border: none;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            text-align: center;
        }

        .nav-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 133, 162, 0.3);
        }

        .main-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }

        .welcome-section {
            background: linear-gradient(135deg, var(--pastel-pink) 0%, var(--pastel-green) 100%);
            padding: 30px;
            border-radius: 15px;
            border: 1px solid var(--border-light);
        }

        .welcome-section h2 {
            color: var(--text-dark);
            margin-bottom: 15px;
            font-size: 24px;
        }

        .welcome-section p {
            color: var(--text-dark);
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .quick-stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-top: 20px;
        }

        .stat-item {
            background: var(--white);
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            border: 1px solid var(--border-light);
        }

        .stat-item .number {
            font-size: 24px;
            font-weight: 700;
            background: linear-gradient(135deg, var(--pink-accent) 0%, var(--green-accent) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 5px;
        }

        .stat-item .label {
            font-size: 12px;
            color: var(--text-light);
        }

        .quick-actions {
            background: var(--white);
            padding: 25px;
            border-radius: 15px;
            border: 1px solid var(--border-light);
        }

        .quick-actions h3 {
            color: var(--text-dark);
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 20px;
            text-align: center;
            border-left: 3px solid var(--pink-accent);
            padding-left: 10px;
        }

        .action-links {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .action-links a {
            color: var(--text-dark);
            text-decoration: none;
            font-size: 14px;
            padding: 12px 16px;
            background: linear-gradient(135deg, var(--pastel-pink) 0%, var(--pastel-green) 100%);
            border-radius: 10px;
            transition: all 0.3s ease;
            border: 1px solid transparent;
            text-align: center;
            font-weight: 500;
        }

        .action-links a:hover {
            background: var(--white);
            border-color: var(--pink-accent);
            transform: translateX(5px);
        }

        .popular-routes {
            margin-top: 30px;
        }

        .popular-routes h3 {
            color: var(--text-dark);
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 20px;
            border-left: 3px solid var(--pink-accent);
            padding-left: 10px;
        }

        .routes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
        }

        .route-card {
            background: linear-gradient(135deg, var(--pastel-pink) 0%, var(--pastel-green) 100%);
            padding: 20px;
            border-radius: 12px;
            border: 1px solid var(--border-light);
            transition: all 0.3s ease;
        }

        .route-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(255, 133, 162, 0.2);
        }

        .route-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .route-name {
            font-weight: 600;
            color: var(--text-dark);
        }

        .route-price {
            background: var(--white);
            color: var(--pink-accent);
            padding: 4px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 14px;
        }

        .route-details {
            color: var(--text-light);
            font-size: 12px;
            margin-bottom: 15px;
        }

        .route-btn {
            width: 100%;
            padding: 8px;
            background: var(--white);
            color: var(--pink-accent);
            border: none;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .route-btn:hover {
            background: var(--pink-accent);
            color: var(--white);
        }

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
            .home-container {
                padding: 30px 20px;
                border-radius: 15px;
            }
            
            .company-header h1 {
                font-size: 28px;
            }
            
            .main-content {
                grid-template-columns: 1fr;
            }
            
            .navigation {
                gap: 10px;
            }
            
            .nav-btn {
                padding: 10px 15px;
                font-size: 12px;
            }
        }

        @media (max-width: 480px) {
            .company-header h1 {
                font-size: 24px;
            }
            
            .quick-stats {
                grid-template-columns: 1fr;
            }
            
            .routes-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="home-container">
        <!-- Header -->
        <div class="company-header">
            <h1>Malaysia Train Services</h1>
            <p class="tagline">Your Journey Begins With Us - Book Train Tickets Easily & Conveniently!</p>
        </div>

        <!-- Navigation -->
        <div class="navigation">
            <a href="index.php" class="nav-btn">Home</a>
            <a href="trainschedule.php" class="nav-btn">Train Schedule</a>
            <a href="login.php" class="nav-btn">Login</a>
            <a href="register.php" class="nav-btn">Register</a>
            <a href="about.php" class="nav-btn">About</a>
            <a href="support.php" class="nav-btn">Support</a>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Welcome Section -->
            <div class="welcome-section">
                <h2>Welcome to Malaysia Train Services</h2>
                <p>Experience comfortable and reliable train travel across Malaysia. Book your tickets easily and enjoy our premium services with modern amenities and exceptional customer care.</p>
                
                <div class="quick-stats">
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
                        <div class="label">Support</div>
                    </div>
                    <div class="stat-item">
                        <div class="number">15+</div>
                        <div class="label">Destinations</div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="quick-actions">
                <h3>Quick Actions</h3>
                <div class="action-links">
                    <a href="trainschedule.php">View Train Schedule</a>
                    <a href="login.php">Login to Book Tickets</a>
                    <a href="register.php">Create New Account</a>
                    <a href="support.php">Contact Support</a>
                </div>
            </div>
        </div>

        <!-- Popular Routes -->
        <div class="popular-routes">
            <h3>Popular Routes</h3>
            <div class="routes-grid">
                <div class="route-card">
                    <div class="route-header">
                        <div class="route-name">KL → Penang</div>
                        <div class="route-price">RM35</div>
                    </div>
                    <div class="route-details">Duration: 4 hours • Every 2 hours</div>
                    <button class="route-btn">Book Now</button>
                </div>
                <div class="route-card">
                    <div class="route-header">
                        <div class="route-name">KL → Johor Bahru</div>
                        <div class="route-price">RM45</div>
                    </div>
                    <div class="route-details">Duration: 5 hours • Every 3 hours</div>
                    <button class="route-btn">Book Now</button>
                </div>
                <div class="route-card">
                    <div class="route-header">
                        <div class="route-name">KL → Ipoh</div>
                        <div class="route-price">RM25</div>
                    </div>
                    <div class="route-details">Duration: 2.5 hours • Hourly</div>
                    <button class="route-btn">Book Now</button>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>&copy; 2024 Malaysia Train Services. All rights reserved.</p>
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
        document.querySelectorAll('.route-btn').forEach(button => {
            button.addEventListener('click', function() {
                alert('Please login to book tickets!');
                window.location.href = 'login.php';
            });
        });

        document.querySelectorAll('.action-links a').forEach(link => {
            link.addEventListener('mouseenter', function() {
                this.style.transform = 'translateX(8px)';
            });
            
            link.addEventListener('mouseleave', function() {
                this.style.transform = 'translateX(0)';
            });
        });
    </script>
</body>
</html>