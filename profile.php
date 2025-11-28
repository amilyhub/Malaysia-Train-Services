<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'train_booking';

// Get user data from database
$conn = new mysqli($host, $username, $password, $database);
$user_data = [];
if (!$conn->connect_error) {
    $stmt = $conn->prepare("SELECT name, email, phone, created_at FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_data = $result->fetch_assoc();
    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Malaysia Train Services</title>
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

        .profile-container {
            max-width: 800px;
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

        .header .tagline {
            color: var(--text-light);
            font-size: 16px;
        }

        .profile-card {
            background: var(--white);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(255, 133, 162, 0.15);
            padding: 40px;
            margin-bottom: 30px;
            border: 1px solid var(--border-light);
        }

        .profile-header {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid var(--pastel-pink);
        }

        .profile-avatar {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--pink-accent) 0%, var(--green-accent) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            color: var(--white);
            margin-right: 20px;
        }

        .profile-info h2 {
            color: var(--text-dark);
            margin-bottom: 5px;
            font-size: 24px;
        }

        .profile-info p {
            color: var(--text-light);
            font-size: 14px;
        }

        .details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .detail-card {
            background: var(--pastel-pink);
            padding: 20px;
            border-radius: 15px;
            border-left: 4px solid var(--pink-accent);
        }

        .detail-card.green {
            background: var(--pastel-green);
            border-left-color: var(--green-accent);
        }

        .detail-label {

            font-size: 12px;
            color: var(--text-light);
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .detail-value {
            font-size: 16px;
            color: var(--text-dark);
            font-weight: 500;
        }

        .action-buttons {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
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

        .stats-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .stat-card {
            background: var(--white);
            padding: 20px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(255, 133, 162, 0.1);
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
            .profile-container {
                padding: 20px 15px;
            }
            
            .profile-header {
                flex-direction: column;
                text-align: center;
            }
            
            .profile-avatar {
                margin-right: 0;
                margin-bottom: 15px;
            }
            
            .action-buttons {
                justify-content: center;
            }
            
            .details-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <div class="header">
            <h1>Malaysia Train Services</h1>
            <p class="tagline">Your Profile Information</p>
        </div>

        <div class="profile-card">
            <div class="profile-header">
                <div class="profile-avatar">
                    👤
                </div>
                <div class="profile-info">
                    <h2><?php echo htmlspecialchars($user_data['name'] ?? $_SESSION['user_name']); ?></h2>
                    <p>Member since <?php echo date('F Y', strtotime($user_data['created_at'] ?? 'now')); ?></p>
                </div>
            </div>

            <div class="details-grid">
                <div class="detail-card">
                    <div class="detail-label">Full Name</div>
                    <div class="detail-value"><?php echo htmlspecialchars($user_data['name'] ?? $_SESSION['user_name']); ?></div>
                </div>
                
                <div class="detail-card green">
                    <div class="detail-label">Email Address</div>
                    <div class="detail-value"><?php echo htmlspecialchars($user_data['email'] ?? $_SESSION['user_email']); ?></div>
                </div>
                
                <div class="detail-card">
                    <div class="detail-label">Phone Number</div>
                    <div class="detail-value"><?php echo htmlspecialchars($user_data['phone'] ?? 'Not provided'); ?></div>
                </div>
                
                <div class="detail-card green">
                    <div class="detail-label">User ID</div>
                    <div class="detail-value">#<?php echo $_SESSION['user_id']; ?></div>
                </div>
            </div>

            <div class="action-buttons">
                <a href="edit_profile.php" class="btn btn-primary">
                    ✏️ Edit Profile
                </a>
                <a href="change_password.php" class="btn btn-secondary">
                    🔒 Change Password
                </a>
                <a href="dashboard.php" class="btn btn-secondary">
                    ← Back to Dashboard
                </a>
                <a href="logout.php" class="btn btn-danger">
                    🚪 Logout
                </a>
            </div>
        </div>

        <div class="stats-section">
            <div class="stat-card">
                <div class="stat-number">0</div>
                <div class="stat-label">Total Bookings</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">0</div>
                <div class="stat-label">Upcoming Trips</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">0</div>
                <div class="stat-label">Loyalty Points</div>
            </div>
        </div>
    </div>
</body>
</html>