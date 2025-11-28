<?php
// manage_trainschedule.php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: adminlogin.php");
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "train_booking");

// Handle form actions
$message = '';
$message_type = '';

// Add new train schedule
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_train'])) {
    $train_name = $_POST['train_name'];
    $train_class = $_POST['train_class'];
    $service_type = $_POST['service_type'];
    $departure_station = $_POST['departure_station'];
    $arrival_station = $_POST['arrival_station'];
    $departure_time = $_POST['departure_time'];
    $arrival_time = $_POST['arrival_time'];
    $duration = $_POST['duration'];
    $price = $_POST['price'];
    $available_seats = $_POST['available_seats'];
    
    $sql = "INSERT INTO train_schedules (train_name, train_class, service_type, departure_station, arrival_station, departure_time, arrival_time, duration, price, available_seats, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'active')";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssdi", $train_name, $train_class, $service_type, $departure_station, $arrival_station, $departure_time, $arrival_time, $duration, $price, $available_seats);
    
    if ($stmt->execute()) {
        $message = "Train schedule added successfully!";
        $message_type = "success";
    } else {
        $message = "Error adding train schedule: " . $conn->error;
        $message_type = "error";
    }
    $stmt->close();
}

// Update train schedule
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_train'])) {
    $id = $_POST['train_id'];
    $train_name = $_POST['train_name'];
    $train_class = $_POST['train_class'];
    $service_type = $_POST['service_type'];
    $departure_station = $_POST['departure_station'];
    $arrival_station = $_POST['arrival_station'];
    $departure_time = $_POST['departure_time'];
    $arrival_time = $_POST['arrival_time'];
    $duration = $_POST['duration'];
    $price = $_POST['price'];
    $available_seats = $_POST['available_seats'];
    $status = $_POST['status'];
    
    $sql = "UPDATE train_schedules SET train_name=?, train_class=?, service_type=?, departure_station=?, arrival_station=?, departure_time=?, arrival_time=?, duration=?, price=?, available_seats=?, status=? WHERE id=?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssdisi", $train_name, $train_class, $service_type, $departure_station, $arrival_station, $departure_time, $arrival_time, $duration, $price, $available_seats, $status, $id);
    
    if ($stmt->execute()) {
        $message = "Train schedule updated successfully!";
        $message_type = "success";
    } else {
        $message = "Error updating train schedule: " . $conn->error;
        $message_type = "error";
    }
    $stmt->close();
}

// Delete train schedule
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    
    $sql = "DELETE FROM train_schedules WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $message = "Train schedule deleted successfully!";
        $message_type = "success";
    } else {
        $message = "Error deleting train schedule: " . $conn->error;
        $message_type = "error";
    }
    $stmt->close();
}

// Toggle train status
if (isset($_GET['toggle_status'])) {
    $id = $_GET['toggle_status'];
    
    // Get current status
    $sql = "SELECT status FROM train_schedules WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $train = $result->fetch_assoc();
    
    $new_status = ($train['status'] == 'active') ? 'inactive' : 'active';
    
    $sql = "UPDATE train_schedules SET status=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $new_status, $id);
    
    if ($stmt->execute()) {
        $message = "Train status updated successfully!";
        $message_type = "success";
    } else {
        $message = "Error updating train status: " . $conn->error;
        $message_type = "error";
    }
    $stmt->close();
}

// Bulk actions
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['bulk_action'])) {
    $bulk_action = $_POST['bulk_action'];
    $selected_trains = isset($_POST['selected_trains']) ? $_POST['selected_trains'] : [];
    
    if (empty($selected_trains)) {
        $message = "Please select at least one train schedule!";
        $message_type = "error";
    } else {
        $placeholders = implode(',', array_fill(0, count($selected_trains), '?'));
        $types = str_repeat('i', count($selected_trains));
        
        switch ($bulk_action) {
            case 'delete':
                $sql = "DELETE FROM train_schedules WHERE id IN ($placeholders)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param($types, ...$selected_trains);
                if ($stmt->execute()) {
                    $message = count($selected_trains) . " train schedule(s) deleted successfully!";
                    $message_type = "success";
                }
                break;
                
            case 'activate':
                $sql = "UPDATE train_schedules SET status='active' WHERE id IN ($placeholders)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param($types, ...$selected_trains);
                if ($stmt->execute()) {
                    $message = count($selected_trains) . " train schedule(s) activated successfully!";
                    $message_type = "success";
                }
                break;
                
            case 'deactivate':
                $sql = "UPDATE train_schedules SET status='inactive' WHERE id IN ($placeholders)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param($types, ...$selected_trains);
                if ($stmt->execute()) {
                    $message = count($selected_trains) . " train schedule(s) deactivated successfully!";
                    $message_type = "success";
                }
                break;
                
            case 'copy':
                // Copy selected trains
                $copied_count = 0;
                foreach ($selected_trains as $train_id) {
                    $sql = "INSERT INTO train_schedules (train_name, train_class, service_type, departure_station, arrival_station, departure_time, arrival_time, duration, price, available_seats, status)
                            SELECT CONCAT(train_name, ' (Copy)'), train_class, service_type, departure_station, arrival_station, departure_time, arrival_time, duration, price, available_seats, 'active'
                            FROM train_schedules WHERE id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $train_id);
                    if ($stmt->execute()) {
                        $copied_count++;
                    }
                    $stmt->close();
                }
                $message = $copied_count . " train schedule(s) copied successfully!";
                $message_type = "success";
                break;
        }
    }
}

// Export to CSV
if (isset($_GET['export'])) {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=train_schedules_' . date('Y-m-d') . '.csv');
    
    $output = fopen('php://output', 'w');
    
    // Add BOM to fix UTF-8 in Excel
    fputs($output, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));
    
    // Headers
    fputcsv($output, ['ID', 'Train Name', 'Train Class', 'Service Type', 'Departure Station', 'Arrival Station', 'Departure Time', 'Arrival Time', 'Duration', 'Price', 'Available Seats', 'Status']);
    
    // Data
    $sql = "SELECT * FROM train_schedules ORDER BY id ASC";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, [
            $row['id'],
            $row['train_name'],
            $row['train_class'],
            $row['service_type'],
            $row['departure_station'],
            $row['arrival_station'],
            $row['departure_time'],
            $row['arrival_time'],
            $row['duration'],
            $row['price'],
            $row['available_seats'],
            $row['status']
        ]);
    }
    fclose($output);
    exit;
}

// Fetch all train schedules
$sql = "SELECT * FROM train_schedules ORDER BY id ASC";
$result = $conn->query($sql);

$trains = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $trains[] = $row;
    }
}

// Get statistics
$total_trains = $conn->query("SELECT COUNT(*) as count FROM train_schedules")->fetch_assoc()['count'];
$active_trains = $conn->query("SELECT COUNT(*) as count FROM train_schedules WHERE status = 'active'")->fetch_assoc()['count'];
$inactive_trains = $conn->query("SELECT COUNT(*) as count FROM train_schedules WHERE status = 'inactive'")->fetch_assoc()['count'];
$total_seats = $conn->query("SELECT SUM(available_seats) as total FROM train_schedules WHERE status = 'active'")->fetch_assoc()['total'];
$total_revenue_potential = $conn->query("SELECT SUM(price * available_seats) as total FROM train_schedules WHERE status = 'active'")->fetch_assoc()['total'];

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Train Schedule - Malaysia Train Services Admin</title>
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

        /* Message Alert */
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

        /* Table Styles */
        .table-container {
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

        .btn-toggle {
            background: var(--pastel-pink);
            color: var(--text-dark);
        }

        .btn-toggle:hover {
            background: var(--pink-accent);
            color: var(--white);
        }

        .checkbox-cell {
            width: 40px;
            text-align: center;
        }

        .status-active {
            background: var(--pastel-green);
            color: var(--green-accent);
            padding: 6px 12px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: 500;
        }

        .status-inactive {
            background: #fff0f5;
            color: #ff85a2;
            padding: 6px 12px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: 500;
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
            max-height: 70vh;
            overflow-y: auto;
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

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .modal-footer {
            padding: 15px 20px;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            border-top: 1px solid var(--pastel-pink);
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
            
            .form-grid {
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
                    <li><a href="manage_trainschedule.php" class="active"><i class="fas fa-train"></i> Train Schedule</a></li>
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
                    <h2>Manage Train Schedule</h2>
                    <p>View and manage train schedules</p>
                </div>
                
                <div class="admin-actions">
                    <a href="?export=1" class="btn btn-secondary"><i class="fas fa-file-export"></i> Export</a>
                    <button class="btn btn-primary" id="addTrainBtn"><i class="fas fa-plus"></i> Add New Train</button>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="stats-cards">
                <div class="stat-card">
                    <div class="stat-number"><?php echo $total_trains; ?></div>
                    <div class="stat-label">Total Trains</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $active_trains; ?></div>
                    <div class="stat-label">Active Trains</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $inactive_trains; ?></div>
                    <div class="stat-label">Inactive Trains</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $total_seats ?: 0; ?></div>
                    <div class="stat-label">Available Seats</div>
                </div>
                <div class="stat-card revenue">
                    <div class="stat-number">RM <?php echo number_format($total_revenue_potential ?: 0, 2); ?></div>
                    <div class="stat-label">Revenue Potential</div>
                </div>
            </div>

            <!-- Message Alert -->
            <?php if ($message): ?>
            <div class="alert alert-<?php echo $message_type == 'success' ? 'success' : 'error'; ?>">
                <?php echo $message; ?>
            </div>
            <?php endif; ?>
            
            <div class="table-container">
                <div class="table-header">
                    <div class="table-title">Train Schedules</div>
                    
                    <div class="table-controls">
                        <div class="search-box">
                            <i class="fas fa-search"></i>
                            <input type="text" placeholder="Search trains..." id="searchInput">
                        </div>
                        
                        <select id="statusFilter">
                            <option value="">All Trains</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
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
                                <th>ID</th>
                                <th>Train Name</th>
                                <th>Train Class</th>
                                <th>Service Type</th>
                                <th>Departure Station</th>
                                <th>Arrival Station</th>
                                <th>Departure Time</th>
                                <th>Arrival Time</th>
                                <th>Duration</th>
                                <th>Price (RM)</th>
                                <th>Available Seats</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="trainsTableBody">
                            <?php foreach ($trains as $train): ?>
                            <tr>
                                <td class="checkbox-cell">
                                    <input type="checkbox" class="train-checkbox" value="<?php echo $train['id']; ?>">
                                </td>
                                <td><?php echo $train['id']; ?></td>
                                <td><?php echo $train['train_name']; ?></td>
                                <td><?php echo $train['train_class']; ?></td>
                                <td><?php echo $train['service_type']; ?></td>
                                <td><?php echo $train['departure_station']; ?></td>
                                <td><?php echo $train['arrival_station']; ?></td>
                                <td><?php echo date('H:i', strtotime($train['departure_time'])); ?></td>
                                <td><?php echo date('H:i', strtotime($train['arrival_time'])); ?></td>
                                <td><?php echo $train['duration']; ?></td>
                                <td>RM <?php echo number_format($train['price'], 2); ?></td>
                                <td><?php echo $train['available_seats']; ?></td>
                                <td>
                                    <span class="status-<?php echo $train['status']; ?>">
                                        <?php echo ucfirst($train['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn-action btn-edit" onclick="editTrain(<?php echo $train['id']; ?>)">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <a href="?toggle_status=<?php echo $train['id']; ?>" class="btn-action btn-toggle">
                                            <i class="fas fa-power-off"></i> <?php echo $train['status'] == 'active' ? 'Deactivate' : 'Activate'; ?>
                                        </a>
                                        <a href="?delete_id=<?php echo $train['id']; ?>" class="btn-action btn-delete" onclick="return confirm('Are you sure you want to delete this train schedule?')">
                                            <i class="fas fa-trash"></i> Delete
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Bulk Actions Form -->
                <form method="POST" action="" id="bulkActionForm">
                    <div class="bulk-actions">
                        <input type="checkbox" id="bulkSelect" onchange="toggleBulkSelect()">
                        <label for="bulkSelect">Select all</label>
                        
                        <select name="bulk_action" id="bulkActionSelect">
                            <option value="">With selected:</option>
                            <option value="copy">Copy</option>
                            <option value="activate">Activate</option>
                            <option value="deactivate">Deactivate</option>
                            <option value="delete">Delete</option>
                        </select>
                        
                        <button type="submit" class="btn-action">Apply</button>
                    </div>
                    <input type="hidden" name="selected_trains" id="selectedTrains" value="">
                </form>
                
                <div class="pagination">
                    <button class="active">1</button>
                    <button>2</button>
                    <button>3</button>
                    <button>Next</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Add/Edit Train Modal -->
    <div class="modal" id="trainModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitle">Add New Train</h3>
                <button class="modal-close" id="closeModal">&times;</button>
            </div>
            
            <div class="modal-body">
                <form id="trainForm" method="POST" action="">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="train_name">Train Name</label>
                            <input type="text" class="form-control" id="train_name" name="train_name" required placeholder="e.g., ETS Gold">
                        </div>
                        <div class="form-group">
                            <label for="train_class">Train Class/Code</label>
                            <input type="text" class="form-control" id="train_class" name="train_class" required placeholder="e.g., T001">
                        </div>
                        <div class="form-group">
                            <label for="service_type">Service Type</label>
                            <select class="form-control" id="service_type" name="service_type" required>
                                <option value="Express">Express</option>
                                <option value="Intercity">Intercity</option>
                                <option value="Premium">Premium</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="departure_station">Departure Station</label>
                            <select class="form-control" id="departure_station" name="departure_station" required>
                                <option value="">Select station</option>
                                <option value="KL Sentral">KL Sentral</option>
                                <option value="Butterworth">Butterworth</option>
                                <option value="JB Sentral">JB Sentral</option>
                                <option value="Ipoh">Ipoh</option>
                                <option value="Kuantan">Kuantan</option>
                                <option value="Alor Setar">Alor Setar</option>
                                <option value="Melaka">Melaka</option>
                                <option value="Kuching">Kuching</option>
                                <option value="Kota Kinabalu">Kota Kinabalu</option>
                                <option value="Padang Besar">Padang Besar</option>
                                <option value="Woodlands">Woodlands</option>
                                <option value="Seremban">Seremban</option>
                                <option value="Taiping">Taiping</option>
                                <option value="Sungai Petani">Sungai Petani</option>
                                <option value="Teluk Intan">Teluk Intan</option>
                                <option value="Kota Bharu">Kota Bharu</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="arrival_station">Arrival Station</label>
                            <select class="form-control" id="arrival_station" name="arrival_station" required>
                                <option value="">Select station</option>
                                <option value="KL Sentral">KL Sentral</option>
                                <option value="Butterworth">Butterworth</option>
                                <option value="Johor Bahru">Johor Bahru</option>
                                <option value="Kota Bharu">Kota Bharu</option>
                                <option value="Ipoh">Ipoh</option>
                                <option value="Kuantan">Kuantan</option>
                                <option value="Alor Setar">Alor Setar</option>
                                <option value="Melaka">Melaka</option>
                                <option value="Kuching">Kuching</option>
                                <option value="Kota Kinabalu">Kota Kinabalu</option>
                                <option value="Padang Besar">Padang Besar</option>
                                <option value="Woodlands">Woodlands</option>
                                <option value="Seremban">Seremban</option>
                                <option value="Taiping">Taiping</option>
                                <option value="Sungai Petani">Sungai Petani</option>
                                <option value="Teluk Intan">Teluk Intan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="departure_time">Departure Time</label>
                            <input type="time" class="form-control" id="departure_time" name="departure_time" required>
                        </div>
                        <div class="form-group">
                            <label for="arrival_time">Arrival Time</label>
                            <input type="time" class="form-control" id="arrival_time" name="arrival_time" required>
                        </div>
                        <div class="form-group">
                            <label for="duration">Duration</label>
                            <input type="text" class="form-control" id="duration" name="duration" required placeholder="e.g., 4h 30m">
                        </div>
                        <div class="form-group">
                            <label for="price">Price (RM)</label>
                            <input type="number" step="0.01" class="form-control" id="price" name="price" required placeholder="e.g., 85.00">
                        </div>
                        <div class="form-group">
                            <label for="available_seats">Available Seats</label>
                            <input type="number" class="form-control" id="available_seats" name="available_seats" required placeholder="e.g., 45">
                        </div>
                        <div class="form-group" id="statusField" style="display: none;">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <input type="hidden" id="train_id" name="train_id">
                </form>
            </div>
            
            <div class="modal-footer">
                <button class="btn btn-secondary" id="cancelBtn">Cancel</button>
                <button class="btn btn-primary" id="saveTrainBtn">Save Train</button>
            </div>
        </div>
    </div>

    <script>
        // DOM Elements
        const trainModal = document.getElementById('trainModal');
        const modalTitle = document.getElementById('modalTitle');
        const trainForm = document.getElementById('trainForm');
        const addTrainBtn = document.getElementById('addTrainBtn');
        const closeModal = document.getElementById('closeModal');
        const cancelBtn = document.getElementById('cancelBtn');
        const saveTrainBtn = document.getElementById('saveTrainBtn');
        const selectAllCheckbox = document.getElementById('selectAll');
        const bulkSelect = document.getElementById('bulkSelect');
        const bulkActionForm = document.getElementById('bulkActionForm');
        const selectedTrainsInput = document.getElementById('selectedTrains');
        const searchInput = document.getElementById('searchInput');
        const statusFilter = document.getElementById('statusFilter');

        // Open modal for adding a new train
        function openAddTrainModal() {
            modalTitle.textContent = 'Add New Train';
            trainForm.reset();
            document.getElementById('statusField').style.display = 'none';
            trainForm.removeAttribute('data-edit-id');
            trainModal.style.display = 'flex';
        }

        // Open modal for editing an existing train
        function editTrain(trainId) {
            // In real implementation, you would fetch train data from server
            // For now, we'll show a simple edit form
            modalTitle.textContent = 'Edit Train Schedule';
            document.getElementById('statusField').style.display = 'block';
            
            // Set form action for update
            trainForm.setAttribute('data-edit-id', trainId);
            trainModal.style.display = 'flex';
            
            // In real implementation, you would populate form with existing data
            // For demo purposes, we'll just show the ID
            document.getElementById('train_id').value = trainId;
        }

        // Save train (add or update)
        function saveTrain() {
            const trainId = trainForm.getAttribute('data-edit-id');
            
            if (trainId) {
                // Update existing train
                trainForm.action = '';
                trainForm.innerHTML += '<input type="hidden" name="update_train" value="1">';
            } else {
                // Add new train
                trainForm.action = '';
                trainForm.innerHTML += '<input type="hidden" name="add_train" value="1">';
            }
            
            trainForm.submit();
        }

        // Bulk select functionality
        function toggleBulkSelect() {
            const checkboxes = document.querySelectorAll('.train-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = bulkSelect.checked;
            });
            updateSelectedTrains();
        }

        // Update selected trains for bulk actions
        function updateSelectedTrains() {
            const selectedTrains = [];
            document.querySelectorAll('.train-checkbox:checked').forEach(checkbox => {
                selectedTrains.push(checkbox.value);
            });
            selectedTrainsInput.value = selectedTrains.join(',');
        }

        // Event Listeners
        addTrainBtn.addEventListener('click', openAddTrainModal);
        closeModal.addEventListener('click', () => trainModal.style.display = 'none');
        cancelBtn.addEventListener('click', () => trainModal.style.display = 'none');
        saveTrainBtn.addEventListener('click', saveTrain);

        // Close modal when clicking outside of it
        window.addEventListener('click', (event) => {
            if (event.target === trainModal) {
                trainModal.style.display = 'none';
            }
        });

        // Select all checkbox functionality
        selectAllCheckbox.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.train-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateSelectedTrains();
        });

        // Update selected trains when individual checkboxes change
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('train-checkbox')) {
                updateSelectedTrains();
            }
        });

        // Auto-hide message after 5 seconds
        setTimeout(function() {
            const alert = document.querySelector('.alert');
            if (alert) {
                alert.style.display = 'none';
            }
        }, 5000);

        // Search functionality
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('#trainsTableBody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });

        // Status filter functionality
        statusFilter.addEventListener('change', function() {
            const status = this.value;
            const rows = document.querySelectorAll('#trainsTableBody tr');
            
            rows.forEach(row => {
                if (!status) {
                    row.style.display = '';
                    return;
                }
                
                const statusCell = row.querySelector('.status-active, .status-inactive');
                if (statusCell) {
                    const rowStatus = statusCell.classList.contains('status-active') ? 'active' : 'inactive';
                    row.style.display = rowStatus === status ? '' : 'none';
                }
            });
        });

        // Form validation for time
        document.addEventListener('DOMContentLoaded', function() {
            const departureTime = document.getElementById('departure_time');
            const arrivalTime = document.getElementById('arrival_time');
            
            if (departureTime && arrivalTime) {
                departureTime.addEventListener('change', validateTimes);
                arrivalTime.addEventListener('change', validateTimes);
            }
            
            function validateTimes() {
                if (departureTime.value && arrivalTime.value && departureTime.value >= arrivalTime.value) {
                    alert('Arrival time must be after departure time!');
                    arrivalTime.value = '';
                }
            }
        });
    </script>
</body>
</html>