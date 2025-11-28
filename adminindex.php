<?php
session_start();
include 'admindb_connection.php';

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: adminlogin.php");
    exit();
}

// Get statistics
$stats_sql = "
    SELECT 
        (SELECT COUNT(*) FROM users) as total_users,
        (SELECT COUNT(*) FROM bookings) as total_bookings,
        (SELECT COUNT(*) FROM trains WHERE status = 'active') as active_trains,
        (SELECT SUM(total_fare) FROM bookings WHERE booking_status = 'confirmed') as total_revenue,
        (SELECT COUNT(*) FROM bookings WHERE booking_status = 'pending') as pending_bookings,
        (SELECT COUNT(*) FROM bookings WHERE DATE(booking_date) = CURDATE()) as today_bookings
";

$stats_result = $conn->query($stats_sql);
$stats = $stats_result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Malaysia Train Services</title>
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

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Header */
        .header {
            background: var(--white);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 8px 25px rgba(255, 133, 162, 0.15);
            border: 1px solid var(--border-light);
        }

        .company-header h1 {
            color: var(--text-dark);
            font-size: 36px;
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
            margin-bottom: 20px;
        }

        .user-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
            padding-top: 15px;
            border-top: 1px solid var(--border-light);
        }

        .welcome-text {
            color: var(--text-light);
            font-size: 16px;
        }

        .welcome-text strong {
            color: var(--pink-accent);
        }

        /* Navigation */
        .nav-menu {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .nav-btn {
            padding: 12px 24px;
            background: linear-gradient(135deg, var(--pastel-pink) 0%, var(--pastel-green) 100%);
            border: none;
            border-radius: 12px;
            color: var(--text-dark);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            border: 1px solid var(--border-light);
        }

        .nav-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 133, 162, 0.2);
            background: linear-gradient(135deg, var(--pink-accent) 0%, var(--green-accent) 100%);
            color: var(--white);
        }

        .nav-btn.active {
            background: linear-gradient(135deg, var(--pink-accent) 0%, var(--green-accent) 100%);
            color: var(--white);
        }

        /* Main Content */
        .admin-content {
            background: var(--white);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 8px 25px rgba(255, 133, 162, 0.15);
            border: 1px solid var(--border-light);
            margin-bottom: 30px;
        }

        .section-title {
            color: var(--text-dark);
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 3px solid var(--pastel-pink);
        }

        /* Stats Cards */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: linear-gradient(135deg, var(--pastel-pink) 0%, var(--pastel-green) 100%);
            border-radius: 15px;
            padding: 25px;
            text-align: center;
            border: 1px solid var(--border-light);
            transition: transform 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--pink-accent) 0%, var(--green-accent) 100%);
        }

        .stat-icon {
            font-size: 40px;
            margin-bottom: 15px;
        }

        .stat-number {
            font-size: 32px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 14px;
            color: var(--text-light);
            font-weight: 600;
        }

        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .action-card {
            background: linear-gradient(135deg, var(--pastel-pink) 0%, var(--pastel-green) 100%);
            padding: 25px;
            border-radius: 15px;
            text-align: center;
            text-decoration: none;
            color: var(--text-dark);
            border: 1px solid var(--border-light);
            transition: all 0.3s ease;
        }

        .action-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(255, 133, 162, 0.2);
        }

        .action-icon {
            font-size: 32px;
            margin-bottom: 10px;
        }

        .action-text {
            font-weight: 600;
            font-size: 16px;
        }

        @media (max-width: 768px) {
            .nav-menu {
                flex-direction: column;
                align-items: center;
            }
            
            .stats-container {
                grid-template-columns: 1fr;
            }
            
            .quick-actions {
                grid-template-columns: 1fr;
            }
            
            .user-info {
                flex-direction: column;
                gap: 10px;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="company-header">
                <h1>Malaysia Train Services</h1>
                <p class="tagline">Admin Dashboard</p>
                
                <div class="user-info">
                    <div class="welcome-text">
                        Welcome back, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>! 
                        Last login: <?php echo date('Y-m-d H:i'); ?>
                    </div>
                    <div class="welcome-text">
                        <strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['email']); ?>
                    </div>
                </div>
            </div>
            
            <!-- Navigation -->
            <div class="nav-menu">
                <a href="adminindex.php" class="nav-btn active">Dashboard</a>
                <a href="manageusers.php" class="nav-btn">Manage Users</a>
                <a href="managebookings.php" class="nav-btn">Manage Bookings</a>
                <a href="managertains.php" class="nav-btn">Manage Trains</a>
                <a href="adminreports.php" class="nav-btn">Reports</a>
                <a href="adminlogout.php" class="nav-btn">Logout</a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="admin-content">
            <h2 class="section-title">📊 Dashboard Overview</h2>
            
            <!-- Statistics Cards -->
            <div class="stats-container">
                <div class="stat-card">
                    <div class="stat-icon">👥</div>
                    <div class="stat-number"><?php echo $stats['total_users'] ?? 0; ?></div>
                    <div class="stat-label">Total Users</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">📋</div>
                    <div class="stat-number"><?php echo $stats['total_bookings'] ?? 0; ?></div>
                    <div class="stat-label">Total Bookings</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">🚆</div>
                    <div class="stat-number"><?php echo $stats['active_trains'] ?? 0; ?></div>
                    <div class="stat-label">Active Trains</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">💰</div>
                    <div class="stat-number">RM <?php echo number_format($stats['total_revenue'] ?? 0, 2); ?></div>
                    <div class="stat-label">Total Revenue</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">⏳</div>
                    <div class="stat-number"><?php echo $stats['pending_bookings'] ?? 0; ?></div>
                    <div class="stat-label">Pending Bookings</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">📅</div>
                    <div class="stat-number"><?php echo $stats['today_bookings'] ?? 0; ?></div>
                    <div class="stat-label">Today's Bookings</div>
                </div>
            </div>

            <!-- Quick Actions -->
            <h3 class="section-title">⚡ Quick Actions</h3>
            <div class="quick-actions">
                <a href="managertains.php" class="action-card">
                    <div class="action-icon">🚆</div>
                    <div class="action-text">Manage Trains</div>
                </a>
                <a href="managebookings.php" class="action-card">
                    <div class="action-icon">📋</div>
                    <div class="action-text">Manage Bookings</div>
                </a>
                <a href="manageusers.php" class="action-card">
                    <div class="action-icon">👥</div>
                    <div class="action-text">Manage Users</div>
                </a>
                <a href="adminreports.php" class="action-card">
                    <div class="action-icon">📊</div>
                    <div class="action-text">View Reports</div>
                </a>
            </div>
        </div>
    </div>
</body>
</html>
<?php $conn->close(); ?>