<?php
// admindashboard.php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: adminlogin.php");
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "train_booking");

// Get recent bookings data
$recent_bookings = $conn->query("
    SELECT booking_reference, passenger_name, total_fare, booking_status 
    FROM bookings 
    ORDER BY booking_date DESC 
    LIMIT 5
");

// Get statistics for dashboard
$total_bookings = $conn->query("SELECT COUNT(*) as count FROM bookings")->fetch_assoc()['count'];
$confirmed_bookings = $conn->query("SELECT COUNT(*) as count FROM bookings WHERE booking_status = 'confirmed'")->fetch_assoc()['count'];
$pending_bookings = $conn->query("SELECT COUNT(*) as count FROM bookings WHERE booking_status = 'pending'")->fetch_assoc()['count'];
$total_revenue = $conn->query("SELECT SUM(total_fare) as revenue FROM bookings WHERE booking_status = 'confirmed'")->fetch_assoc()['revenue'];
$total_users = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
$active_users = $conn->query("SELECT COUNT(*) as count FROM users WHERE email_verified IS NOT NULL")->fetch_assoc()['count'];
$new_users_today = $conn->query("SELECT COUNT(*) as count FROM users WHERE DATE(created_at) = CURDATE()")->fetch_assoc()['count'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Malaysia Train Services</title>
    <style>
        :root {
            --pastel-pink: #ffd6e7;       /* Soft pink */
            --pastel-green: #d6f5e3;      /* Soft green */
            --pink-accent: #ff85a2;       /* Pink untuk button & accent */
            --green-accent: #7ecf9b;      /* Green untuk hover state */
            --text-dark: #4a4a4a;         /* Text color */
            --text-light: #888888;        /* Text secondary */
            --border-light: #e8d4e0;      /* Border color */
            --white: #ffffff;
            --background: #fef7fa;        /* Light pink background */
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

        .admin-container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            background: var(--white);
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
            padding: 20px 0;
            display: flex;
            flex-direction: column;
        }

        .company-header {
            text-align: center;
            margin-bottom: 32px;
            padding: 0 20px 24px;
            border-bottom: 2px solid var(--pastel-pink);
        }

        .company-header h1 {
            color: var(--text-dark);
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 8px;
            background: linear-gradient(135deg, var(--pink-accent) 0%, var(--green-accent) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .company-header .tagline {
            color: var(--text-light);
            font-size: 14px;
            font-weight: 400;
        }

        .admin-nav {
            flex: 1;
            padding: 0 20px;
        }

        .admin-nav ul {
            list-style: none;
        }

        .admin-nav li {
            margin-bottom: 8px;
        }

        .admin-nav a {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            color: var(--text-dark);
            text-decoration: none;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .admin-nav a:hover, .admin-nav a.active {
            background: linear-gradient(135deg, var(--pastel-pink) 0%, var(--pastel-green) 100%);
            color: var(--text-dark);
        }

        .admin-nav a i {
            margin-right: 10px;
            font-size: 18px;
        }

        .admin-footer {
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: var(--text-light);
            border-top: 1px solid var(--pastel-pink);
        }

        /* Main Content Styles */
        .main-content {
            flex: 1;
            padding: 30px;
            overflow-y: auto;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid var(--pastel-pink);
        }

        .page-title h2 {
            font-size: 28px;
            font-weight: 700;
            color: var(--text-dark);
        }

        .page-title p {
            color: var(--text-light);
            font-size: 16px;
        }

        /* Stats Cards */
        .stats-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: var(--white);
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            text-align: center;
            border-left: 4px solid var(--pink-accent);
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card.revenue {
            border-left-color: var(--green-accent);
        }

        .stat-number {
            font-size: 32px;
            font-weight: 700;
            color: var(--pink-accent);
            margin-bottom: 8px;
        }

        .stat-card.revenue .stat-number {
            color: var(--green-accent);
        }

        .stat-label {
            color: var(--text-light);
            font-size: 14px;
            font-weight: 500;
        }

        /* Dashboard Sections */
        .dashboard-sections {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }

        @media (max-width: 1024px) {
            .dashboard-sections {
                grid-template-columns: 1fr;
            }
        }

        .dashboard-section {
            background: var(--white);
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .section-header {
            padding: 20px;
            background: linear-gradient(135deg, var(--pastel-pink) 0%, var(--pastel-green) 100%);
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--pastel-pink);
        }

        .section-header h3 {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-dark);
        }

        .view-all {
            color: var(--pink-accent);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .view-all:hover {
            color: var(--green-accent);
            text-decoration: underline;
        }

        /* Recent Bookings Table */
        .bookings-table {
            padding: 20px;
        }

        .bookings-table table {
            width: 100%;
            border-collapse: collapse;
        }

        .bookings-table th {
            padding: 12px 15px;
            text-align: left;
            font-weight: 600;
            color: var(--text-dark);
            border-bottom: 2px solid var(--pastel-pink);
            font-size: 14px;
        }

        .bookings-table td {
            padding: 12px 15px;
            border-bottom: 1px solid var(--pastel-pink);
            font-size: 14px;
        }

        .bookings-table tbody tr:hover {
            background-color: rgba(255, 214, 231, 0.2);
        }

        .booking-ref {
            background: var(--pastel-pink);
            padding: 4px 8px;
            border-radius: 6px;
            font-family: monospace;
            font-size: 12px;
            color: var(--pink-accent);
            font-weight: 600;
        }

        /* Status Badges */
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-confirmed {
            background: var(--pastel-green);
            color: var(--green-accent);
        }

        .status-pending {
            background: #fff0f5;
            color: #ff85a2;
        }

        .status-cancelled {
            background: #ffe6e6;
            color: #ff6b6b;
        }

        .btn-view {
            background: var(--pastel-green);
            color: var(--text-dark);
            border: none;
            padding: 6px 12px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 12px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .btn-view:hover {
            background: var(--green-accent);
            color: var(--white);
        }

        /* Quick Actions */
        .quick-actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            padding: 20px;
        }

        .action-card {
            background: linear-gradient(135deg, var(--pastel-pink) 0%, var(--pastel-green) 100%);
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            text-decoration: none;
            color: var(--text-dark);
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .action-card:hover {
            transform: translateY(-3px);
            border-color: var(--pink-accent);
            box-shadow: 0 8px 20px rgba(255, 133, 162, 0.2);
        }

        .action-card i {
            font-size: 24px;
            margin-bottom: 10px;
            color: var(--pink-accent);
        }

        .action-card span {
            font-weight: 600;
            font-size: 14px;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .admin-container {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
                padding: 15px;
            }
            
            .admin-nav ul {
                display: flex;
                overflow-x: auto;
                padding-bottom: 10px;
            }
            
            .admin-nav li {
                margin-right: 10px;
                margin-bottom: 0;
            }
            
            .admin-nav a {
                white-space: nowrap;
            }
        }

        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .stats-cards {
                grid-template-columns: 1fr;
            }
            
            .quick-actions-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .bookings-table {
                overflow-x: auto;
            }
            
            .bookings-table table {
                min-width: 600px;
            }
        }

        @media (max-width: 480px) {
            .quick-actions-grid {
                grid-template-columns: 1fr;
            }
            
            .main-content {
                padding: 15px;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="company-header">
                <h1>Malaysia Train Services</h1>
                <p class="tagline">Admin Dashboard</p>
            </div>
            
            <nav class="admin-nav">
                <ul>
                    <li><a href="admindashboard.php" class="active"><i class="fas fa-home"></i> Dashboard</a></li>
                    <li><a href="manage_users.php"><i class="fas fa-users"></i> Manage Users</a></li>
                    <li><a href="manage_schedules.php"><i class="fas fa-train"></i> Train Schedule</a></li>
                    <li><a href="manage_bookings.php"><i class="fas fa-ticket-alt"></i> Bookings</a></li>
                </ul>
            </nav>
            
            <div class="admin-footer">
                <p>© 2025 Malaysia Train Services</p>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <div class="page-header">
                <div class="page-title">
                    <h2>Dashboard Overview</h2>
                    <p>Welcome back! Here's what's happening with your train services today.</p>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="stats-cards">
                <div class="stat-card">
                    <div class="stat-number"><?php echo $total_bookings; ?></div>
                    <div class="stat-label">Total Bookings</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $confirmed_bookings; ?></div>
                    <div class="stat-label">Confirmed Bookings</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $pending_bookings; ?></div>
                    <div class="stat-label">Pending Bookings</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $total_users; ?></div>
                    <div class="stat-label">Total Users</div>
                </div>
                <div class="stat-card revenue">
                    <div class="stat-number">RM <?php echo number_format($total_revenue ?: 0, 2); ?></div>
                    <div class="stat-label">Total Revenue</div>
                </div>
            </div>

            <div class="dashboard-sections">
                <!-- Recent Bookings Section -->
                <div class="dashboard-section">
                    <div class="section-header">
                        <h3>Recent Bookings</h3>
                        <a href="manage_bookings.php" class="view-all">View All</a>
                    </div>
                    
                    <div class="bookings-table">
                        <table>
                            <thead>
                                <tr>
                                    <th>Booking ID</th>
                                    <th>User</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($recent_bookings->num_rows > 0): ?>
                                    <?php while($booking = $recent_bookings->fetch_assoc()): ?>
                                    <tr>
                                        <td>
                                            <span class="booking-ref"><?php echo $booking['booking_reference']; ?></span>
                                        </td>
                                        <td><?php echo htmlspecialchars($booking['passenger_name']); ?></td>
                                        <td><strong>RM <?php echo number_format($booking['total_fare'], 2); ?></strong></td>
                                        <td>
                                            <?php 
                                            $status_class = 'status-badge status-pending';
                                            if ($booking['booking_status'] == 'confirmed') {
                                                $status_class = 'status-badge status-confirmed';
                                            } elseif ($booking['booking_status'] == 'cancelled') {
                                                $status_class = 'status-badge status-cancelled';
                                            }
                                            ?>
                                            <span class="<?php echo $status_class; ?>">
                                                <?php echo ucfirst($booking['booking_status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button class="btn-view" onclick="viewBooking('<?php echo $booking['booking_reference']; ?>')">
                                                💷 View
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" style="text-align: center; color: var(--text-light); padding: 20px;">
                                            No bookings found
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Quick Actions Section -->
                <div class="dashboard-section">
                    <div class="section-header">
                        <h3>Quick Actions</h3>
                    </div>
                    
                    <div class="quick-actions-grid">
                        <a href="manage_bookings.php" class="action-card">
                            <i class="fas fa-ticket-alt"></i>
                            <span>Manage Bookings</span>
                        </a>
                        <a href="manage_users.php" class="action-card">
                            <i class="fas fa-users"></i>
                            <span>Manage Users</span>
                        </a>
                        <a href="manage_schedules.php" class="action-card">
                            <i class="fas fa-train"></i>
                            <span>Train Schedules</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Additional Stats Section -->
            <div class="dashboard-sections">
                <!-- User Statistics -->
                <div class="dashboard-section">
                    <div class="section-header">
                        <h3>User Statistics</h3>
                    </div>
                    
                    <div class="bookings-table">
                        <table>
                            <thead>
                                <tr>
                                    <th>Metric</th>
                                    <th>Count</th>
                                    <th>Percentage</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Total Users</td>
                                    <td><?php echo $total_users; ?></td>
                                    <td>100%</td>
                                </tr>
                                <tr>
                                    <td>Active Users</td>
                                    <td><?php echo $active_users; ?></td>
                                    <td><?php echo $total_users > 0 ? round(($active_users / $total_users) * 100, 1) : 0; ?>%</td>
                                </tr>
                                <tr>
                                    <td>New Users Today</td>
                                    <td><?php echo $new_users_today; ?></td>
                                    <td>-</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Booking Statistics -->
                <div class="dashboard-section">
                    <div class="section-header">
                        <h3>Booking Statistics</h3>
                    </div>
                    
                    <div class="bookings-table">
                        <table>
                            <thead>
                                <tr>
                                    <th>Status</th>
                                    <th>Count</th>
                                    <th>Percentage</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Total Bookings</td>
                                    <td><?php echo $total_bookings; ?></td>
                                    <td>100%</td>
                                </tr>
                                <tr>
                                    <td>Confirmed</td>
                                    <td><?php echo $confirmed_bookings; ?></td>
                                    <td><?php echo $total_bookings > 0 ? round(($confirmed_bookings / $total_bookings) * 100, 1) : 0; ?>%</td>
                                </tr>
                                <tr>
                                    <td>Pending</td>
                                    <td><?php echo $pending_bookings; ?></td>
                                    <td><?php echo $total_bookings > 0 ? round(($pending_bookings / $total_bookings) * 100, 1) : 0; ?>%</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function viewBooking(bookingRef) {
            alert('View booking details for: ' + bookingRef);
            // In actual implementation, this would redirect to booking details page
            // window.location.href = 'booking_details.php?ref=' + bookingRef;
        }

        // Add animation to cards and tables
        document.addEventListener('DOMContentLoaded', function() {
            // Animate stat cards
            const statCards = document.querySelectorAll('.stat-card');
            statCards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
                card.style.opacity = '0';
                card.style.animation = 'fadeInUp 0.5s ease-out forwards';
            });

            // Animate table rows
            const tableRows = document.querySelectorAll('tbody tr');
            tableRows.forEach((row, index) => {
                row.style.animationDelay = `${index * 0.05}s`;
                row.style.opacity = '0';
                row.style.animation = 'fadeInUp 0.5s ease-out forwards';
            });

            // Animate action cards
            const actionCards = document.querySelectorAll('.action-card');
            actionCards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
                card.style.opacity = '0';
                card.style.animation = 'fadeInUp 0.5s ease-out forwards';
            });
        });

        // Add CSS animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>
<?php $conn->close(); ?>