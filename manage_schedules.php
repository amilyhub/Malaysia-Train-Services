<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: adminlogin.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "train_booking");

// Handle actions
if (isset($_GET['action'])) {
    if ($_GET['action'] == 'delete' && isset($_GET['id'])) {
        $conn->query("DELETE FROM train_schedules WHERE id = " . $_GET['id']);
        header("Location: manage_schedules.php");
        exit();
    }
}

$schedules = $conn->query("SELECT * FROM train_schedules ORDER BY departure_time");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Schedules - Malaysia Train Services</title>
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

        .dashboard-header {
            background: var(--white);
            padding: 20px 40px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border-bottom: 4px solid var(--pink-accent);
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1400px;
            margin: 0 auto;
        }

        .logo h1 {
            background: linear-gradient(135deg, var(--pink-accent) 0%, var(--green-accent) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-size: 28px;
        }

        .nav-links {
            display: flex;
            gap: 20px;
        }

        .nav-btn {
            background: linear-gradient(135deg, var(--pastel-pink) 0%, var(--pastel-green) 100%);
            color: var(--text-dark);
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .nav-btn:hover {
            background: linear-gradient(135deg, var(--pink-accent) 0%, var(--green-accent) 100%);
            color: var(--white);
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 30px 20px;
        }

        .page-header {
            display: flex;
            justify-content: between;
            align-items: center;
            margin-bottom: 30px;
        }

        .page-title {
            color: var(--text-dark);
            font-size: 32px;
            font-weight: 700;
        }

        .add-btn {
            background: linear-gradient(135deg, var(--pink-accent) 0%, var(--green-accent) 100%);
            color: var(--white);
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: transform 0.3s ease;
        }

        .add-btn:hover {
            transform: translateY(-2px);
        }

        .table-container {
            background: var(--white);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(255, 133, 162, 0.15);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1000px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid var(--border-light);
        }

        th {
            background: linear-gradient(135deg, var(--pastel-pink) 0%, var(--pastel-green) 100%);
            color: var(--text-dark);
            font-weight: 600;
        }

        tr:hover {
            background-color: #fafafa;
        }

        .action-btn {
            padding: 6px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 12px;
            margin: 2px;
            transition: all 0.3s ease;
        }

        .edit-btn {
            background: var(--pastel-green);
            color: var(--text-dark);
        }

        .delete-btn {
            background: #ffd6e7;
            color: #ff85a2;
        }

        .action-btn:hover {
            transform: translateY(-1px);
        }

        .status-active {
            background: var(--pastel-green);
            color: var(--green-accent);
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="dashboard-header">
        <div class="header-content">
            <div class="logo">
                <h1>Malaysia Train Services</h1>
            </div>
            <div class="nav-links">
                <a href="admin_dashboard.php" class="nav-btn">Dashboard</a>
                <a href="manage_users.php" class="nav-btn">Users</a>
                <a href="manage_schedules.php" class="nav-btn">Schedules</a>
                <a href="manage_bookings.php" class="nav-btn">Bookings</a>
                <a href="logout.php" class="nav-btn">Logout</a>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="page-header">
            <h1 class="page-title">Manage Train Schedules</h1>
            <a href="#" class="add-btn" onclick="alert('Add Schedule feature coming soon!')">+ Add New Schedule</a>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Train Name</th>
                        <th>Class</th>
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
                    <?php while($schedule = $schedules->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $schedule['train_name']; ?></td>
                        <td><?php echo $schedule['train_class']; ?></td>
                        <td><?php echo $schedule['service_type']; ?></td>
                        <td><?php echo $schedule['departure_station']; ?></td>
                        <td><?php echo $schedule['arrival_station']; ?></td>
                        <td><?php echo date('H:i', strtotime($schedule['departure_time'])); ?></td>
                        <td><?php echo date('H:i', strtotime($schedule['arrival_time'])); ?></td>
                        <td><?php echo $schedule['duration']; ?></td>
                        <td>RM <?php echo $schedule['price']; ?></td>
                        <td><?php echo $schedule['available_seats']; ?></td>
                        <td><span class="status-active"><?php echo $schedule['status']; ?></span></td>
                        <td>
                            <button class="action-btn edit-btn" onclick="alert('Edit feature coming soon!')">Edit</button>
                            <a href="manage_schedules.php?action=delete&id=<?php echo $schedule['id']; ?>" 
                               class="action-btn delete-btn"
                               onclick="return confirm('Are you sure you want to delete this schedule?')">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
<?php $conn->close(); ?>