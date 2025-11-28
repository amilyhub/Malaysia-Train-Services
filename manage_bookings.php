<?php
// manage_bookings.php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: adminlogin.php");
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "train_booking");

// Handle actions
if (isset($_GET['action'])) {
    if ($_GET['action'] == 'delete' && isset($_GET['id'])) {
        $conn->query("SET FOREIGN_KEY_CHECKS=0");
        $conn->query("DELETE FROM bookings WHERE id = " . intval($_GET['id']));
        $conn->query("SET FOREIGN_KEY_CHECKS=1");
        header("Location: manage_bookings.php");
        exit();
    }
}

// Handle edit form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_booking'])) {
    $booking_id = intval($_POST['booking_id']);
    $passenger_name = $conn->real_escape_string($_POST['passenger_name']);
    $passenger_email = $conn->real_escape_string($_POST['passenger_email']);
    $passenger_phone = $conn->real_escape_string($_POST['passenger_phone']);
    $travel_date = $conn->real_escape_string($_POST['travel_date']);
    $number_of_seats = intval($_POST['number_of_seats']);
    $total_fare = floatval($_POST['total_fare']);
    $class = $conn->real_escape_string($_POST['class']);
    $booking_status = $conn->real_escape_string($_POST['booking_status']);
    
    $update_query = "UPDATE bookings SET 
        passenger_name = '$passenger_name',
        passenger_email = '$passenger_email', 
        passenger_phone = '$passenger_phone',
        travel_date = '$travel_date',
        number_of_seats = $number_of_seats,
        total_fare = $total_fare,
        class = '$class',
        booking_status = '$booking_status'
        WHERE id = $booking_id";
    
    if ($conn->query($update_query)) {
        $success_message = "Booking updated successfully!";
    } else {
        $error_message = "Error updating booking: " . $conn->error;
    }
}

// Create sample data jika tiada
$check_bookings = $conn->query("SELECT COUNT(*) as count FROM bookings");
if ($check_bookings->fetch_assoc()['count'] == 0) {
    $conn->query("SET FOREIGN_KEY_CHECKS=0");
    $conn->query("INSERT INTO bookings (user_id, passenger_name, passenger_email, passenger_phone, travel_date, booking_date, number_of_seats, total_fare, class, booking_status, booking_reference, from_station, to_station) VALUES
        (1, 'Lily Qistina', 'lily@email.com', '0123456781', '2025-02-15', '2025-02-10 14:30:00', 2, 170.00, 'Gold', 'confirmed', 'BK001', 'KL Sentral', 'Butterworth'),
        (1, 'Aya Inara', 'aya@email.com', '0123456782', '2025-03-20', '2025-03-15 09:15:00', 1, 95.00, 'Platinum', 'pending', 'BK002', 'KL Sentral', 'Johor Bahru'),
        (1, 'Aiman Abid', 'aiman@email.com', '0123456783', '2025-04-25', '2025-04-20 16:45:00', 3, 360.00, 'Express', 'confirmed', 'BK003', 'KL Sentral', 'Kota Bharu')");
    $conn->query("SET FOREIGN_KEY_CHECKS=1");
}

// Get booking for edit
$edit_booking = null;
if (isset($_GET['edit_id'])) {
    $edit_id = intval($_GET['edit_id']);
    $edit_booking = $conn->query("SELECT * FROM bookings WHERE id = $edit_id")->fetch_assoc();
}

// Query bookings
$bookings = $conn->query("SELECT * FROM bookings ORDER BY travel_date DESC");
$total_bookings = $conn->query("SELECT COUNT(*) as count FROM bookings")->fetch_assoc()['count'];
$confirmed_bookings = $conn->query("SELECT COUNT(*) as count FROM bookings WHERE booking_status = 'confirmed'")->fetch_assoc()['count'];
$pending_bookings = $conn->query("SELECT COUNT(*) as count FROM bookings WHERE booking_status = 'pending'")->fetch_assoc()['count'];
$cancelled_bookings = $conn->query("SELECT COUNT(*) as count FROM bookings WHERE booking_status = 'cancelled'")->fetch_assoc()['count'];
$total_revenue = $conn->query("SELECT SUM(total_fare) as revenue FROM bookings WHERE booking_status = 'confirmed'")->fetch_assoc()['revenue'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings - Malaysia Train Services Admin</title>
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

        .admin-actions {
            display: flex;
            gap: 15px;
        }

        .btn {
            padding: 10px 20px;
            border-radius: 10px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--pink-accent) 0%, var(--green-accent) 100%);
            color: var(--white);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 133, 162, 0.3);
        }

        .btn-secondary {
            background: var(--white);
            color: var(--text-dark);
            border: 1px solid var(--border-light);
        }

        .btn-secondary:hover {
            background: var(--pastel-pink);
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

        /* Table Styles */
        .bookings-table-container {
            background: var(--white);
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            margin-bottom: 30px;
        }

        .table-header {
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--pastel-pink);
        }

        .table-title {
            font-size: 20px;
            font-weight: 600;
        }

        .table-controls {
            display: flex;
            gap: 15px;
        }

        .search-box {
            position: relative;
        }

        .search-box input {
            padding: 10px 15px 10px 40px;
            border: 1px solid var(--border-light);
            border-radius: 10px;
            width: 250px;
            font-size: 14px;
        }

        .search-box i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
        }

        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1200px;
        }

        thead {
            background: linear-gradient(135deg, var(--pastel-pink) 0%, var(--pastel-green) 100%);
        }

        th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: var(--text-dark);
        }

        td {
            padding: 15px;
            border-bottom: 1px solid var(--pastel-pink);
        }

        tbody tr:hover {
            background-color: rgba(255, 214, 231, 0.2);
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .btn-action {
            padding: 6px 12px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-size: 12px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .btn-edit {
            background: var(--pastel-green);
            color: var(--text-dark);
        }

        .btn-edit:hover {
            background: var(--green-accent);
            color: var(--white);
        }

        .btn-delete {
            background: #ffd6e7;
            color: var(--text-dark);
        }

        .btn-delete:hover {
            background: #ff85a2;
            color: var(--white);
        }

        .checkbox-cell {
            width: 40px;
            text-align: center;
        }

        .bulk-actions {
            padding: 15px 20px;
            background: var(--pastel-pink);
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .bulk-actions select {
            padding: 8px 15px;
            border-radius: 8px;
            border: 1px solid var(--border-light);
            background: var(--white);
        }

        .pagination {
            display: flex;
            justify-content: center;
            padding: 20px;
            gap: 10px;
        }

        .pagination button {
            padding: 8px 15px;
            border: 1px solid var(--border-light);
            background: var(--white);
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .pagination button.active {
            background: linear-gradient(135deg, var(--pink-accent) 0%, var(--green-accent) 100%);
            color: var(--white);
            border-color: transparent;
        }

        .pagination button:hover:not(.active) {
            background: var(--pastel-pink);
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

        /* Class Badges */
        .class-badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
        }

        .class-gold {
            background: linear-gradient(135deg, #fff9c4, #ffd700);
            color: #b8860b;
        }

        .class-platinum {
            background: linear-gradient(135deg, #f5f5f5, #e5e4e2);
            color: #696969;
        }

        .class-express {
            background: linear-gradient(135deg, #e3f2fd, #87ceeb);
            color: #1e88e5;
        }

        .class-intercity {
            background: linear-gradient(135deg, #e8f5e8, #7ecf9b);
            color: #2e7d32;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: var(--white);
            border-radius: 15px;
            width: 90%;
            max-width: 600px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .modal-header {
            padding: 20px;
            background: linear-gradient(135deg, var(--pastel-pink) 0%, var(--pastel-green) 100%);
            color: var(--text-dark);
            font-weight: 600;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            color: var(--text-dark);
        }




        .modal-body {
            padding: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--text-dark);
            font-weight: 500;
            font-size: 14px;
            padding-left: 8px;
            border-left: 3px solid var(--pink-accent);
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid var(--pastel-pink);
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s ease;
            background-color: var(--white);
            color: var(--text-dark);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--pink-accent);
            box-shadow: 0 0 0 4px rgba(255, 133, 162, 0.1);
            background-color: #fffdfe;
        }

        .modal-footer {
            padding: 15px 20px;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            border-top: 1px solid var(--pastel-pink);
        }

        /* Alert Messages */
        .alert {
            padding: 15px 20px;
            margin-bottom: 20px;
            border-radius: 10px;
            font-weight: 500;
        }

        .alert-success {
            background: var(--pastel-green);
            color: var(--green-accent);
            border: 1px solid var(--green-accent);
        }

        .alert-error {
            background: #ffd6e7;
            color: var(--pink-accent);
            border: 1px solid var(--pink-accent);
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
            
            .admin-actions {
                width: 100%;
                justify-content: space-between;
            }
            
            .table-header {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }
            
            .search-box input {
                width: 100%;
            }
            
            .table-controls {
                width: 100%;
                justify-content: space-between;
            }
            
            .stats-cards {
                grid-template-columns: 1fr;
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
                    <li><a href="admindashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
                    <li><a href="manage_users.php"><i class="fas fa-users"></i> Manage Users</a></li>
                    <li><a href="manage_schedules.php"><i class="fas fa-train"></i> Train Schedule</a></li>
                    <li><a href="manage_bookings.php" class="active"><i class="fas fa-ticket-alt"></i> Bookings</a></li>
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
                    <h2>Manage Bookings</h2>
                    <p>View and manage train bookings</p>
                </div>
                
                <div class="admin-actions">
                    <!-- Buttons Export dan Add New Booking telah dibuang -->
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
                    <div class="stat-label">Confirmed</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $pending_bookings; ?></div>
                    <div class="stat-label">Pending</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $cancelled_bookings; ?></div>
                    <div class="stat-label">Cancelled</div>
                </div>
                <div class="stat-card revenue">
                    <div class="stat-number">RM <?php echo number_format($total_revenue ?: 0, 2); ?></div>
                    <div class="stat-label">Total Revenue</div>
                </div>
            </div>

            <!-- Success/Error Messages -->
            <?php if (isset($success_message)): ?>
                <div class="alert alert-success"><?php echo $success_message; ?></div>
            <?php endif; ?>
            
            <?php if (isset($error_message)): ?>
                <div class="alert alert-error"><?php echo $error_message; ?></div>
            <?php endif; ?>
            
            <div class="bookings-table-container">
                <div class="table-header">
                    <div class="table-title">Booking Records</div>
                    
                    <div class="table-controls">
                        <div class="search-box">
                            <i class="fas fa-search"></i>
                            <input type="text" placeholder="Search bookings...">
                        </div>
                        
                        <select>
                            <option>All Bookings</option>
                            <option>Confirmed</option>
                            <option>Pending</option>
                            <option>Cancelled</option>
                        </select>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th class="checkbox-cell">
                                    <input type="checkbox" id="selectAll">
                                </th>
                                <th>Booking Ref</th>
                                <th>Passenger</th>
                                <th>Train & Route</th>
                                <th>Travel Date</th>
                                <th>Booking Date</th>
                                <th>Seats</th>
                                <th>Class</th>
                                <th>Total Price</th>
                                <th>Payment</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($booking = $bookings->fetch_assoc()): ?>
                            <tr>
                                <td class="checkbox-cell">
                                    <input type="checkbox" class="booking-checkbox" value="<?php echo $booking['id']; ?>">
                                </td>
                                <td>
                                    <span style="background: var(--pastel-pink); padding: 4px 8px; border-radius: 6px; font-family: monospace; font-size: 12px; color: var(--pink-accent); font-weight: 600;">
                                        <?php echo $booking['booking_reference']; ?>
                                    </span>
                                </td>
                                <td>
                                    <div style="font-size: 14px;">
                                        <div style="font-weight: 600; color: var(--text-dark);"><?php echo htmlspecialchars($booking['passenger_name']); ?></div>
                                        <div style="color: var(--text-light); font-size: 12px;"><?php echo htmlspecialchars($booking['passenger_email']); ?></div>
                                        <div style="color: var(--text-light); font-size: 12px;"><?php echo htmlspecialchars($booking['passenger_phone']); ?></div>
                                    </div>
                                </td>
                                <td>
                                    <div style="font-size: 14px;">
                                        <div style="font-weight: 600;">Train Service</div>
                                        <div style="font-weight: 600; color: var(--pink-accent);">
                                            <?php 
                                            $from = isset($booking['from_station']) ? $booking['from_station'] : 'KL Sentral';
                                            $to = isset($booking['to_station']) ? $booking['to_station'] : 'Destination';
                                            echo $from . ' → ' . $to; 
                                            ?>
                                        </div>
                                    </div>
                                </td>
                                <td><?php echo date('M j, Y', strtotime($booking['travel_date'])); ?></td>
                                <td>
                                    <div style="font-size: 12px;">
                                        <div><?php echo date('M j, Y', strtotime($booking['booking_date'])); ?></div>
                                        <div style="color: var(--text-light);"><?php echo date('H:i', strtotime($booking['booking_date'])); ?></div>
                                    </div>
                                </td>
                                <td><?php echo $booking['number_of_seats']; ?> seat(s)</td>
                                <td>
                                    <?php 
                                    $class = $booking['class'];
                                    $class_badge = 'class-badge ';
                                    if (strpos($class, 'Gold') !== false) $class_badge .= 'class-gold';
                                    elseif (strpos($class, 'Platinum') !== false) $class_badge .= 'class-platinum';
                                    elseif (strpos($class, 'Express') !== false) $class_badge .= 'class-express';
                                    elseif (strpos($class, 'Intercity') !== false) $class_badge .= 'class-intercity';
                                    else $class_badge .= 'class-gold';
                                    ?>
                                    <span class="<?php echo $class_badge; ?>"><?php echo $class; ?></span>
                                </td>
                                <td><strong>RM <?php echo $booking['total_fare']; ?></strong></td>
                                <td>
                                    <?php if (isset($booking['payment_method']) && $booking['payment_method']): ?>
                                        <span style="font-size: 12px; color: var(--text-light);">
                                            <?php echo strtoupper($booking['payment_method']); ?>
                                        </span>
                                        <?php if (isset($booking['cardholder']) && $booking['cardholder']): ?>
                                            <br><span style="font-size: 11px; color: var(--text-light);"><?php echo $booking['cardholder']; ?></span>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span style="font-size: 12px; color: var(--text-light);">N/A</span>
                                    <?php endif; ?>
                                </td>
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
                                    <div class="action-buttons">
                                        <a href="manage_bookings.php?edit_id=<?php echo $booking['id']; ?>" 
                                           class="btn-action btn-edit">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <a href="manage_bookings.php?action=delete&id=<?php echo $booking['id']; ?>" 
                                           class="btn-action btn-delete"
                                           onclick="return confirm('Are you sure you want to delete booking <?php echo $booking['booking_reference']; ?>?')">
                                            <i class="fas fa-trash"></i> Delete
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="bulk-actions">
                    <input type="checkbox" id="bulkSelect">
                    <label for="bulkSelect">Select all</label>
                    
                    <select>
                        <option>With selected:</option>
                        <option>Edit</option>
                        <option>Delete</option>
                        <option>Export</option>
                    </select>
                    
                    <button class="btn-action">Apply</button>
                </div>
                
                <div class="pagination">
                    <button class="active">1</button>
                    <button>2</button>
                    <button>3</button>
                    <button>Next</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Edit Booking Modal -->
    <?php if ($edit_booking): ?>
    <div class="modal" id="bookingModal" style="display: flex;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Edit Booking</h3>
                <button class="modal-close" onclick="closeModal()">&times;</button>
            </div>
            
            <div class="modal-body">
                <form method="POST" action="manage_bookings.php">
                    <input type="hidden" name="booking_id" value="<?php echo $edit_booking['id']; ?>">
                    <input type="hidden" name="update_booking" value="1">
                    
                    <div class="form-group">
                        <label for="passenger_name">Passenger Name</label>
                        <input type="text" class="form-control" id="passenger_name" name="passenger_name" value="<?php echo htmlspecialchars($edit_booking['passenger_name']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="passenger_email">Email</label>
                        <input type="email" class="form-control" id="passenger_email" name="passenger_email" value="<?php echo htmlspecialchars($edit_booking['passenger_email']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="passenger_phone">Phone</label>
                        <input type="text" class="form-control" id="passenger_phone" name="passenger_phone" value="<?php echo htmlspecialchars($edit_booking['passenger_phone']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="travel_date">Travel Date</label>
                        <input type="date" class="form-control" id="travel_date" name="travel_date" value="<?php echo $edit_booking['travel_date']; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="number_of_seats">Number of Seats</label>
                        <input type="number" class="form-control" id="number_of_seats" name="number_of_seats" value="<?php echo $edit_booking['number_of_seats']; ?>" min="1" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="total_fare">Total Fare (RM)</label>
                        <input type="number" class="form-control" id="total_fare" name="total_fare" step="0.01" value="<?php echo $edit_booking['total_fare']; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="class">Class</label>
                        <select class="form-control" id="class" name="class" required>
                            <option value="Gold" <?php echo $edit_booking['class'] == 'Gold' ? 'selected' : ''; ?>>Gold</option>
                            <option value="Platinum" <?php echo $edit_booking['class'] == 'Platinum' ? 'selected' : ''; ?>>Platinum</option>
                            <option value="Express" <?php echo $edit_booking['class'] == 'Express' ? 'selected' : ''; ?>>Express</option>
                            <option value="Intercity" <?php echo $edit_booking['class'] == 'Intercity' ? 'selected' : ''; ?>>Intercity</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="booking_status">Status</label>
                        <select class="form-control" id="booking_status" name="booking_status" required>
                            <option value="pending" <?php echo $edit_booking['booking_status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="confirmed" <?php echo $edit_booking['booking_status'] == 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                            <option value="cancelled" <?php echo $edit_booking['booking_status'] == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                        </select>
                    </div>
                    
                    <div class="modal-footer">
                        <a href="manage_bookings.php" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Booking</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <script>
        function closeModal() {
            document.getElementById('bookingModal').style.display = 'none';
            window.location.href = 'manage_bookings.php';
        }

        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            const modal = document.getElementById('bookingModal');
            if (event.target === modal) {
                closeModal();
            }
        });

        // Select all checkbox functionality
        document.getElementById('selectAll').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.booking-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        // Add animation to table rows
        document.addEventListener('DOMContentLoaded', function() {
            const tableRows = document.querySelectorAll('tbody tr');
            tableRows.forEach((row, index) => {
                row.style.animationDelay = `${index * 0.1}s`;
                row.style.opacity = '0';
                row.style.animation = 'fadeInUp 0.5s ease-out forwards';
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