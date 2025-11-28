<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Malaysia Train Services</title>
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
            color: var(--text-dark);
        }

        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
        }

        .header h1 {
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 8px;
            background: linear-gradient(135deg, var(--pink-accent) 0%, var(--green-accent) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .welcome-card {
            background: var(--white);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(255, 133, 162, 0.15);
            padding: 40px;
            margin-bottom: 30px;
            border: 1px solid var(--border-light);
            text-align: center;
        }

        .welcome-card h2 {
            color: var(--text-dark);
            margin-bottom: 10px;
            font-size: 24px;
        }

        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .action-card {
            background: var(--white);
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 5px 20px rgba(255, 133, 162, 0.1);
            border: 1px solid var(--border-light);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .action-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(255, 133, 162, 0.2);
        }

        .action-card i {
            font-size: 40px;
            margin-bottom: 15px;
        }

        .action-card h3 {
            color: var(--text-dark);
            margin-bottom: 10px;
            font-size: 18px;
        }

        .action-card p {
            color: var(--text-light);
            font-size: 14px;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            margin: 5px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--pink-accent) 0%, var(--green-accent) 100%);
            color: white;
        }

        .btn-secondary {
            background: var(--pastel-pink);
            color: var(--text-dark);
        }

        .btn-danger {
            background: #ffe6e6;
            color: #ff6b6b;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .user-info {
            text-align: center;
            margin-top: 20px;
            color: var(--text-light);
        }

        @media (max-width: 768px) {
            .quick-actions {
                grid-template-columns: 1fr;
            }
            
            .dashboard-container {
                padding: 20px 15px;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="header">
            <h1>Malaysia Train Services</h1>
            <p class="tagline">Your Journey, Our Priority</p>
        </div>

        <div class="welcome-card">
            <h2>🎉 Welcome back, <?php echo $_SESSION['user_name']; ?>!</h2>
            <p>We're glad to see you again. Ready for your next adventure?</p>
            
            <div style="margin-top: 25px;">
                <a href="book_tickets.php" class="btn btn-primary">🚆 Book New Ticket</a>
                <a href="my_bookings.php" class="btn btn-secondary">📋 My Bookings</a>
                <a href="logout.php" class="btn btn-danger">🚪 Logout</a>
            </div>
        </div>

        <div class="quick-actions">
            <div class="action-card" onclick="location.href='trainschedule.php'">
                <div>🚄</div>
                <h3>Train Schedule</h3>
                <p>View all available train timings and routes</p>
            </div>

            <div class="action-card" onclick="location.href='my_bookings.php'">
                <div>📑</div>
                <h3>My Bookings</h3>
                <p>Manage your existing reservations and tickets</p>
            </div>

            <div class="action-card" onclick="location.href='profile.php'">
                <div>👤</div>
                <h3>My Profile</h3>
                <p>Update your personal information and preferences</p>
            </div>

            <div class="action-card" onclick="location.href='support.php'">
                <div>💬</div>
                <h3>Customer Support</h3>
                <p>Get help with your bookings and travel queries</p>
            </div>
        </div>

        <div class="user-info">
            <p>Logged in as: <?php echo $_SESSION['user_email']; ?></p>
        </div>
    </div>
</body>
</html>