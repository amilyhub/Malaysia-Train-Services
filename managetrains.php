<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: adminlogin.php");
    exit();
}

// Sample trains data - SAMA SEPERTI BOOK_TICKET.PHP (15 TRAINS)
$trains = [
    ['id' => 'T001', 'name' => 'ETS Gold', 'route' => 'KL Sentral - Butterworth', 'departure' => '08:00', 'arrival' => '12:30', 'seats' => 45, 'price' => 85, 'class' => 'First Class', 'status' => 'Active'],
    ['id' => 'T002', 'name' => 'ETS Platinum', 'route' => 'KL Sentral - Johor Bahru', 'departure' => '09:15', 'arrival' => '14:45', 'seats' => 32, 'price' => 95, 'class' => 'Business Class', 'status' => 'Active'],
    ['id' => 'T003', 'name' => 'ETS Express', 'route' => 'KL Sentral - Kota Bharu', 'departure' => '07:30', 'arrival' => '16:20', 'seats' => 28, 'price' => 120, 'class' => 'First Class', 'status' => 'Active'],
    ['id' => 'T004', 'name' => 'Intercity', 'route' => 'KL Sentral - Ipoh', 'departure' => '10:00', 'arrival' => '12:00', 'seats' => 60, 'price' => 35, 'class' => 'Economy Class', 'status' => 'Active'],
    ['id' => 'T005', 'name' => 'ETS Gold', 'route' => 'KL Sentral - Kuantan', 'departure' => '11:30', 'arrival' => '15:45', 'seats' => 42, 'price' => 75, 'class' => 'First Class', 'status' => 'Active'],
    ['id' => 'T006', 'name' => 'ETS Platinum', 'route' => 'KL Sentral - Alor Setar', 'departure' => '14:00', 'arrival' => '19:30', 'seats' => 38, 'price' => 110, 'class' => 'Business Class', 'status' => 'Active'],
    ['id' => 'T007', 'name' => 'Intercity', 'route' => 'KL Sentral - Melaka', 'departure' => '13:15', 'arrival' => '15:30', 'seats' => 55, 'price' => 25, 'class' => 'Economy Class', 'status' => 'Active'],
    ['id' => 'T008', 'name' => 'ETS Gold', 'route' => 'KL Sentral - Kuching', 'departure' => '20:00', 'arrival' => '06:00', 'seats' => 36, 'price' => 180, 'class' => 'First Class', 'status' => 'Active'],
    ['id' => 'T009', 'name' => 'ETS Platinum', 'route' => 'KL Sentral - Kota Kinabalu', 'departure' => '19:30', 'arrival' => '08:30', 'seats' => 30, 'price' => 200, 'class' => 'Business Class', 'status' => 'Active'],
    ['id' => 'T010', 'name' => 'Intercity', 'route' => 'Butterworth - Padang Besar', 'departure' => '06:45', 'arrival' => '08:15', 'seats' => 40, 'price' => 28, 'class' => 'Economy Class', 'status' => 'Active'],
    ['id' => 'T011', 'name' => 'ETS Express', 'route' => 'JB Sentral - Woodlands', 'departure' => '07:00', 'arrival' => '07:45', 'seats' => 44, 'price' => 40, 'class' => 'First Class', 'status' => 'Active'],
    ['id' => 'T012', 'name' => 'Intercity', 'route' => 'KL Sentral - Seremban', 'departure' => '16:30', 'arrival' => '17:45', 'seats' => 50, 'price' => 18, 'class' => 'Economy Class', 'status' => 'Active'],
    ['id' => 'T013', 'name' => 'ETS Gold', 'route' => 'KL Sentral - Taiping', 'departure' => '12:00', 'arrival' => '14:45', 'seats' => 42, 'price' => 65, 'class' => 'First Class', 'status' => 'Active'],
    ['id' => 'T014', 'name' => 'ETS Platinum', 'route' => 'KL Sentral - Sungai Petani', 'departure' => '08:45', 'arrival' => '12:15', 'seats' => 35, 'price' => 80, 'class' => 'Business Class', 'status' => 'Active'],
    ['id' => 'T015', 'name' => 'Intercity', 'route' => 'KL Sentral - Teluk Intan', 'departure' => '13:00', 'arrival' => '15:45', 'seats' => 48, 'price' => 22, 'class' => 'Economy Class', 'status' => 'Active']
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Trains - Malaysia Train Services</title>
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
            --card-shadow: 0 8px 25px rgba(255, 133, 162, 0.1);
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

        .sidebar {
            width: 260px;
            background: var(--white);
            box-shadow: var(--card-shadow);
            padding: 0;
            position: fixed;
            height: 100vh;
            border-right: 1px solid var(--border-light);
        }

        .sidebar-header {
            padding: 30px 25px;
            border-bottom: 2px solid var(--pastel-pink);
            background: linear-gradient(135deg, var(--pastel-pink) 0%, var(--pastel-green) 100%);
        }

        .sidebar-header h1 {
            color: var(--text-dark);
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .sidebar-header .tagline {
            color: var(--text-light);
            font-size: 14px;
        }

        .sidebar-nav {
            padding: 20px 0;
        }

        .nav-item {
            padding: 15px 25px;
            text-decoration: none;
            color: var(--text-dark);
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .nav-item:hover {
            background: var(--pastel-pink);
            border-left-color: var(--pink-accent);
            padding-left: 30px;
        }

        .nav-item.active {
            background: var(--pastel-green);
            border-left-color: var(--green-accent);
            font-weight: 600;
        }

        .main-content {
            flex: 1;
            margin-left: 260px;
            padding: 30px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid var(--pastel-pink);
        }

        .header h2 {
            font-size: 28px;
            font-weight: 700;
            background: linear-gradient(135deg, var(--pink-accent) 0%, var(--green-accent) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--pink-accent) 0%, var(--green-accent) 100%);
            color: var(--white);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 133, 162, 0.3);
        }

        /* Trains Grid - SAMA SEPERTI BOOK_TICKET.PHP */
        .trains-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .train-card {
            background: var(--white);
            border-radius: 15px;
            padding: 25px;
            box-shadow: var(--card-shadow);
            border: 1px solid var(--border-light);
            transition: all 0.3s ease;
            position: relative;
        }

        .train-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--pink-accent) 0%, var(--green-accent) 100%);
            border-radius: 15px 15px 0 0;
        }

        .train-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(255, 133, 162, 0.2);
        }

        .train-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .train-id {
            background: var(--pastel-pink);
            padding: 5px 10px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
        }

        .train-price {
            font-size: 20px;
            font-weight: 700;
            color: var(--pink-accent);
        }

        .train-status {
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-active {
            background: var(--pastel-green);
            color: var(--green-accent);
        }

        .status-maintenance {
            background: #fff3cd;
            color: #856404;
        }

        .train-name {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 10px;
            color: var(--text-dark);
        }

        .train-route {
            color: var(--text-light);
            margin-bottom: 15px;
            font-size: 14px;
        }

        .train-class {
            background: var(--pink-accent);
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 15px;
        }

        .train-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 20px;
        }

        .detail-item {
            display: flex;
            flex-direction: column;
        }

        .detail-label {
            font-size: 12px;
            color: var(--text-light);
            margin-bottom: 5px;
        }

        .detail-value {
            font-weight: 600;
            color: var(--text-dark);
        }

        .train-actions {
            display: flex;
            gap: 10px;
        }

        .action-btn {
            flex: 1;
            padding: 8px 12px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-edit {
            background: var(--pastel-pink);
            color: var(--text-dark);
        }

        .btn-delete {
            background: #ffe6e6;
            color: #ff6b6b;
        }

        .action-btn:hover {
            transform: translateY(-2px);
        }

        .stats-overview {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: var(--white);
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            box-shadow: var(--card-shadow);
            border: 1px solid var(--border-light);
        }

        .stat-number {
            font-size: 32px;
            font-weight: 700;
            color: var(--pink-accent);
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 14px;
            color: var(--text-light);
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .trains-grid {
                grid-template-columns: 1fr;
            }
            
            .stats-overview {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <h1>Malaysia Train Services</h1>
                <p class="tagline">Admin Management System</p>
            </div>
            
            <nav class="sidebar-nav">
                <a href="admindashboard.php" class="nav-item">
                    📊 Dashboard
                </a>
                <a href="manageusers.php" class="nav-item">
                    👥 Manage Users
                </a>
                <a href="managetrains.php" class="nav-item active">
                    🚆 Manage Trains
                </a>
                <a href="managebookings.php" class="nav-item">
                    📋 Manage Bookings
                </a>
                <a href="adminreports.php" class="nav-item">
                    📈 Reports & Analytics
                </a>
                <a href="adminlogout.php" class="nav-item">
                    🚪 Logout
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h2>🚆 Manage Trains</h2>
                <div>
                    <button class="btn btn-primary">
                        🚆 Add New Train
                    </button>
                </div>
            </div>

            <!-- Statistics Overview -->
            <div class="stats-overview">
                <div class="stat-card">
                    <div class="stat-number">15</div>
                    <div class="stat-label">Total Trains</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">14</div>
                    <div class="stat-label">Active Trains</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">1</div>
                    <div class="stat-label">Under Maintenance</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">583</div>
                    <div class="stat-label">Total Seats</div>
                </div>
            </div>

            <!-- Trains Grid -->
            <div class="trains-grid">
                <?php foreach ($trains as $train): ?>
                <div class="train-card">
                    <div class="train-header">
                        <span class="train-id"><?php echo $train['id']; ?></span>
                        <div>
                            <span class="train-price">RM <?php echo $train['price']; ?></span>
                            <span class="train-status status-<?php echo strtolower($train['status']); ?>">
                                <?php echo $train['status']; ?>
                            </span>
                        </div>
                    </div>
                    
                    <div class="train-name"><?php echo $train['name']; ?></div>
                    <div class="train-route">📍 <?php echo $train['route']; ?></div>
                    <span class="train-class"><?php echo $train['class']; ?></span>
                    
                    <div class="train-details">
                        <div class="detail-item">
                            <span class="detail-label">Departure</span>
                            <span class="detail-value">🕗 <?php echo $train['departure']; ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Arrival</span>
                            <span class="detail-value">🕓 <?php echo $train['arrival']; ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Available Seats</span>
                            <span class="detail-value">💺 <?php echo $train['seats']; ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Duration</span>
                            <span class="detail-value">⏱️ 
                                <?php 
                                $departure = strtotime($train['departure']);
                                $arrival = strtotime($train['arrival']);
                                $duration = ($arrival - $departure) / 3600;
                                echo floor($duration) . 'h ' . (($duration - floor($duration)) * 60) . 'm';
                                ?>
                            </span>
                        </div>
                    </div>
                    
                    <div class="train-actions">
                        <button class="action-btn btn-edit">✏️ Edit</button>
                        <button class="action-btn btn-delete">🗑️ Delete</button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <script>
        // Add interactive effects
        document.querySelectorAll('.train-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });

        // Action buttons functionality
        document.querySelectorAll('.btn-edit').forEach(btn => {
            btn.addEventListener('click', function() {
                const trainCard = this.closest('.train-card');
                const trainId = trainCard.querySelector('.train-id').textContent;
                const trainName = trainCard.querySelector('.train-name').textContent;
                alert(`Edit train: ${trainName} (${trainId})`);
            });
        });

        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', function() {
                const trainCard = this.closest('.train-card');
                const trainId = trainCard.querySelector('.train-id').textContent;
                const trainName = trainCard.querySelector('.train-name').textContent;
                
                if (confirm(`Are you sure you want to delete ${trainName} (${trainId})?`)) {
                    alert(`Train ${trainName} deleted successfully!`);
                }
            });
        });
    </script>
</body>
</html>