<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "train_booking");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];

// FIX: Check jika query success
$sql = "SELECT * FROM bookings WHERE user_id = '$user_id' ORDER BY booking_date DESC";
$result = $conn->query($sql);

if ($result) {
    $total_bookings = $result->num_rows;
    $bookings = [];
    if ($total_bookings > 0) {
        while($row = $result->fetch_assoc()) {
            $bookings[] = $row;
        }
    }
} else {
    // Jika query fail
    $total_bookings = 0;
    $bookings = [];
    // Remove the error display for production
    // echo "<!-- Debug: Query failed: " . $conn->error . " -->";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings - Malaysia Train Services</title>
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
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .page-header {
            background: var(--white);
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            text-align: center;
        }

        .page-title {
            font-size: 32px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 10px;
        }

        .bookings-count {
            font-size: 18px;
            color: var(--text-light);
        }

        .bookings-container {
            background: var(--white);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .no-bookings {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-light);
        }

        .no-bookings-icon {
            font-size: 64px;
            margin-bottom: 20px;
        }

        .no-bookings h3 {
            font-size: 24px;
            margin-bottom: 15px;
            color: var(--text-dark);
        }

        .no-bookings p {
            margin-bottom: 25px;
            font-size: 16px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--pink-accent) 0%, var(--green-accent) 100%);
            color: var(--white);
            border: none;
            padding: 12px 30px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: transform 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
        }

        /* Table Styles */
        .bookings-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .bookings-table th {
            background: linear-gradient(135deg, var(--pastel-pink) 0%, var(--pastel-green) 100%);
            color: var(--text-dark);
            font-weight: 600;
            padding: 15px;
            text-align: left;
        }

        .bookings-table td {
            padding: 15px;
            border-bottom: 1px solid var(--border-light);
        }

        .bookings-table tr:hover {
            background-color: var(--pastel-pink);
        }

        .status-confirmed {
            background: var(--pastel-green);
            color: var(--green-accent);
            padding: 6px 12px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: 500;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
            padding: 6px 12px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: 500;
        }

        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }
            
            .page-header {
                padding: 20px;
            }
            
            .bookings-container {
                padding: 20px;
            }
            
            .bookings-table {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Page Header - SIMPLE VERSION -->
        <div class="page-header">
            <h1 class="page-title">My Bookings</h1>
            <div class="bookings-count">Total: <?php echo $total_bookings; ?> bookings</div>
        </div>

        <!-- Bookings Content -->
        <div class="bookings-container">
            <?php if ($total_bookings > 0): ?>
                <!-- Table for existing bookings -->
                <table class="bookings-table">
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>Train</th>
                            <th>Route</th>
                            <th>Travel Date</th>
                            <th>Seats</th>
                            <th>Total Price</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bookings as $booking): ?>
                        <tr>
                            <td><strong><?php echo $booking['booking_id']; ?></strong></td>
                            <td><?php echo $booking['train_name']; ?></td>
                            <td><?php echo $booking['departure_station']; ?> → <?php echo $booking['arrival_station']; ?></td>
                            <td><?php echo date('M j, Y', strtotime($booking['travel_date'])); ?></td>
                            <td><?php echo $booking['seats']; ?></td>
                            <td><strong>RM <?php echo number_format($booking['total_price'], 2); ?></strong></td>
                            <td>
                                <span class="status-<?php echo strtolower($booking['status']); ?>">
                                    <?php echo ucfirst($booking['status']); ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <!-- No bookings message -->
                <div class="no-bookings">
                    <div class="no-bookings-icon">🚆</div>
                    <h3>No bookings yet</h3>
                    <p>You haven't made any bookings yet. Start your journey now!</p>
                    <a href="book_ticket.php" class="btn-primary">Book Your First Ticket</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

<?php $conn->close(); ?>