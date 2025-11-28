<?php
session_start();

// Check if user is logged in as admin
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit;
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "train_booking";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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

// Fetch all train schedules
$sql = "SELECT * FROM train_schedules ORDER BY id ASC";
$result = $conn->query($sql);

$trains = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $trains[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Train Schedule - Malaysia Train Services</title>
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
            --success: #4CAF50;
            --warning: #FF9800;
            --error: #f44336;
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
            padding: 20px;
            color: var(--text-dark);
        }

        .admin-container {
            background: var(--white);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(255, 133, 162, 0.15);
            width: 100%;
            max-width: 1400px;
            margin: 0 auto;
            border: 1px solid var(--border-light);
            position: relative;
            overflow: hidden;
        }

        .admin-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--pink-accent) 0%, var(--green-accent) 100%);
        }

        /* Header */
        .admin-header {
            padding: 30px;
            border-bottom: 2px solid var(--pastel-pink);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .admin-header h1 {
            color: var(--text-dark);
            font-size: 32px;
            font-weight: 700;
            background: linear-gradient(135deg, var(--pink-accent) 0%, var(--green-accent) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .admin-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .admin-welcome {
            color: var(--text-light);
            font-size: 16px;
        }

        .btn-logout {
            padding: 10px 20px;
            background: linear-gradient(135deg, var(--pink-accent) 0%, var(--green-accent) 100%);
            color: var(--white);
            border: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-logout:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 133, 162, 0.3);
        }

        /* Navigation */
        .admin-nav {
            display: flex;
            gap: 10px;
            padding: 0 30px 20px;
            flex-wrap: wrap;
        }

        .nav-btn {
            padding: 12px 24px;
            background: var(--pastel-pink);
            color: var(--text-dark);
            border: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .nav-btn:hover {
            background: var(--pink-accent);
            color: var(--white);
        }

        .nav-btn.active {
            background: linear-gradient(135deg, var(--pink-accent) 0%, var(--green-accent) 100%);
            color: var(--white);
        }

        /* Message Alert */
        .alert {
            padding: 15px 20px;
            margin: 0 30px 20px;
            border-radius: 10px;
            font-weight: 500;
        }

        .alert-success {
            background: rgba(76, 175, 80, 0.1);
            color: var(--success);
            border: 1px solid rgba(76, 175, 80, 0.3);
        }

        .alert-error {
            background: rgba(244, 67, 54, 0.1);
            color: var(--error);
            border: 1px solid rgba(244, 67, 54, 0.3);
        }

        /* Main Content */
        .admin-content {
            padding: 30px;
        }

        /* Action Bar */
        .action-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .section-title {
            font-size: 24px;
            font-weight: 700;
            color: var(--text-dark);
        }

        .btn-add {
            padding: 12px 24px;
            background: linear-gradient(135deg, var(--pink-accent) 0%, var(--green-accent) 100%);
            color: var(--white);
            border: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-add:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 133, 162, 0.3);
        }

        /* Forms */
        .form-container {
            background: var(--pastel-pink);
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
            border: 1px solid var(--border-light);
            display: none;
        }

        .form-container.active {
            display: block;
        }

        .form-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 20px;
            color: var(--text-dark);
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--text-dark);
            font-weight: 500;
            font-size: 14px;
        }

        .form-control {
            width: 100%;
            padding: 12px 14px;
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
        }

        .form-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        .btn-submit {
            padding: 12px 24px;
            background: linear-gradient(135deg, var(--pink-accent) 0%, var(--green-accent) 100%);
            color: var(--white);
            border: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 133, 162, 0.3);
        }

        .btn-cancel {
            padding: 12px 24px;
            background: var(--text-light);
            color: var(--white);
            border: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-cancel:hover {
            background: var(--text-dark);
        }

        /* Train Schedule Table */
        .table-container {
            background: var(--white);
            border-radius: 15px;
            overflow: hidden;
            border: 1px solid var(--border-light);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .schedule-table {
            width: 100%;
            border-collapse: collapse;
        }

        .schedule-table th {
            background: linear-gradient(135deg, var(--pastel-pink) 0%, var(--pastel-green) 100%);
            padding: 15px 12px;
            text-align: left;
            font-weight: 600;
            color: var(--text-dark);
            font-size: 14px;
        }

        .schedule-table td {
            padding: 12px;
            border-bottom: 1px solid var(--border-light);
            font-size: 14px;
        }

        .schedule-table tr:hover {
            background: rgba(255, 214, 231, 0.1);
        }

        .status-active {
            background: rgba(126, 207, 155, 0.2);
            color: var(--green-accent);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        .status-inactive {
            background: rgba(255, 133, 162, 0.2);
            color: var(--pink-accent);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .btn-edit, .btn-delete, .btn-toggle {
            padding: 6px 12px;
            border: none;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-edit {
            background: var(--pastel-green);
            color: var(--green-accent);
        }

        .btn-edit:hover {
            background: var(--green-accent);
            color: var(--white);
        }

        .btn-delete {
            background: rgba(244, 67, 54, 0.1);
            color: var(--error);
        }

        .btn-delete:hover {
            background: var(--error);
            color: var(--white);
        }

        .btn-toggle {
            background: var(--pastel-pink);
            color: var(--pink-accent);
        }

        .btn-toggle:hover {
            background: var(--pink-accent);
            color: var(--white);
        }

        /* No Data Message */
        .no-data {
            text-align: center;
            padding: 40px;
            color: var(--text-light);
            font-size: 16px;
        }

        /* Bulk Actions */
        .bulk-actions {
            background: var(--pastel-green);
            padding: 15px 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            border-bottom: 1px solid var(--border-light);
        }

        .bulk-select {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .bulk-buttons {
            display: flex;
            gap: 10px;
        }

        .btn-bulk {
            padding: 8px 16px;
            background: var(--white);
            border: 1px solid var(--border-light);
            border-radius: 6px;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-bulk:hover {
            background: var(--pastel-pink);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .admin-header {
                flex-direction: column;
                text-align: center;
            }
            
            .admin-nav {
                justify-content: center;
            }
            
            .action-bar {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .schedule-table {
                display: block;
                overflow-x: auto;
            }
            
            .bulk-actions {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Header -->
        <div class="admin-header">
            <h1>Malaysia Train Services - Admin Panel</h1>
            <div class="admin-info">
                <span class="admin-welcome">Welcome, Admin!</span>
                <a href="admin_logout.php" class="btn-logout">Logout</a>
            </div>
        </div>

        <!-- Navigation -->
        <div class="admin-nav">
            <a href="admin_dashboard.php" class="nav-btn">Dashboard</a>
            <a href="admin_train_schedule.php" class="nav-btn active">Train Schedule</a>
            <a href="admin_bookings.php" class="nav-btn">Bookings</a>
            <a href="admin_users.php" class="nav-btn">Users</a>
            <a href="admin_reports.php" class="nav-btn">Reports</a>
        </div>

        <!-- Message Alert -->
        <?php if ($message): ?>
        <div class="alert alert-<?php echo $message_type; ?>">
            <?php echo $message; ?>
        </div>
        <?php endif; ?>

        <!-- Main Content -->
        <div class="admin-content">
            <!-- Action Bar -->
            <div class="action-bar">
                <h2 class="section-title">Train Schedule Management</h2>
                <button class="btn-add" onclick="toggleForm('addForm')">+ Add New Train</button>
            </div>

            <!-- Add Train Form -->
            <div id="addForm" class="form-container">
                <h3 class="form-title">Add New Train Schedule</h3>
                <form method="POST" action="">
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
                    </div>
                    <div class="form-actions">
                        <button type="button" class="btn-cancel" onclick="toggleForm('addForm')">Cancel</button>
                        <button type="submit" class="btn-submit" name="add_train">Add Train</button>
                    </div>
                </form>
            </div>

            <!-- Bulk Actions -->
            <div class="bulk-actions">
                <div class="bulk-select">
                    <input type="checkbox" id="select-all" onchange="toggleSelectAll()">
                    <label for="select-all">Check all</label>
                </div>
                <div class="bulk-buttons">
                    <button class="btn-bulk">Edit</button>
                    <button class="btn-bulk">Copy</button>
                    <button class="btn-bulk">Delete</button>
                    <button class="btn-bulk">Export</button>
                </div>
            </div>

            <!-- Train Schedule Table -->
            <div class="table-container">
                <?php if (count($trains) > 0): ?>
                <table class="schedule-table">
                    <thead>
                        <tr>
                            <th style="width: 30px;"></th>
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
                    <tbody>
                        <?php foreach ($trains as $train): ?>
                        <tr>
                            <td>
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
                                    <button class="btn-edit" onclick="editTrain(<?php echo $train['id']; ?>)">Edit</button>
                                    <a href="" class="btn-toggle">
                                        <?php echo $train['status'] == 'active' ? 'Deactivate' : 'Activate'; ?>
                                    </a>
                                    <a href="" class="btn-delete" onclick="return confirm('Are you sure you want to delete this train schedule?')">Delete</a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <div class="no-data">
                    <p>No train schedules found. Add your first train schedule!</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        // Toggle form visibility
        function toggleForm(formId) {
            const form = document.getElementById(formId);
            form.classList.toggle('active');
        }

        // Edit train function
        function editTrain(trainId) {
            // In a real implementation, this would open a modal or redirect to edit page
            alert('Edit functionality for train ID: ' + trainId + '\n\nIn a full implementation, this would open an edit form with pre-filled data.');
            // You can implement: window.location.href = 'edit_train.php?train_id=' + trainId;
        }

        // Select all functionality
        function toggleSelectAll() {
            const selectAll = document.getElementById('select-all');
            const checkboxes = document.querySelectorAll('.train-checkbox');
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAll.checked;
            });
        }

        // Auto-hide message after 5 seconds
        setTimeout(function() {
            const alert = document.querySelector('.alert');
            if (alert) {
                alert.style.display = 'none';
            }
        }, 5000);

        // Form validation
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    const departureStation = form.querySelector('#departure_station');
                    const arrivalStation = form.querySelector('#arrival_station');
                    
                    if (departureStation && arrivalStation && departureStation.value === arrivalStation.value) {
                        e.preventDefault();
                        alert('Departure and arrival stations cannot be the same!');
                        return false;
                    }
                    
                    const departureTime = form.querySelector('#departure_time');
                    const arrivalTime = form.querySelector('#arrival_time');
                    
                    if (departureTime && arrivalTime && departureTime.value >= arrivalTime.value) {
                        e.preventDefault();
                        alert('Arrival time must be after departure time!');
                        return false;
                    }
                });
            });
        });

        // Real-time form validation
        document.addEventListener('input', function(e) {
            if (e.target.id === 'departure_station' || e.target.id === 'arrival_station') {
                const departure = document.getElementById('departure_station');
                const arrival = document.getElementById('arrival_station');

                
                if (departure && arrival && departure.value === arrival.value) {
                    arrival.style.borderColor = 'var(--error)';
                } else {
                    arrival.style.borderColor = '';
                }
            }
        });
    </script>
</body>
</html>