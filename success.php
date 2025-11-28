<?php
session_start();
include 'payment/db_connection.php';

if (!isset($_SESSION['payment_success']) || !$_SESSION['payment_success']) {
    header("Location: book_tickets.php");
    exit();
}

$booking_reference = $_SESSION['booking_reference'] ?? '';
$transaction_id = $_SESSION['transaction_id'] ?? '';
$booking_id = $_SESSION['booking_id'] ?? '';

// Fetch booking details from database
$booking_sql = "SELECT b.*, t.train_name, t.train_number 
                FROM bookings b 
                JOIN trains t ON b.train_id = t.id 
                WHERE b.id = ?";
$booking_stmt = $conn->prepare($booking_sql);
$booking_stmt->bind_param("i", $booking_id);
$booking_stmt->execute();
$booking_result = $booking_stmt->get_result();
$booking_details = $booking_result->fetch_assoc();

// Clear success session
unset($_SESSION['payment_success']);
unset($_SESSION['booking_reference']); 
unset($_SESSION['transaction_id']);
unset($_SESSION['booking_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful - Malaysia Train Services</title>
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
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .success-container {
            background: var(--white);
            border-radius: 20px;
            padding: 50px;
            text-align: center;
            box-shadow: 0 20px 40px rgba(255, 133, 162, 0.2);
            border: 1px solid var(--border-light);
            max-width: 600px;
            width: 100%;
            border-top: 5px solid var(--green-accent);
        }
        
        .success-icon {
            font-size: 80px;
            color: var(--green-accent);
            margin-bottom: 25px;
        }
        
        h1 {
            color: var(--green-accent);
            margin-bottom: 15px;
            font-size: 32px;
        }
        
        .booking-info {
            background: var(--pastel-green);
            padding: 25px;
            border-radius: 12px;
            margin: 25px 0;
            border: 2px solid var(--green-accent);
            text-align: left;
        }
        
        .info-item {
            margin-bottom: 12px;
            display: flex;
            justify-content: space-between;
        }
        
        .info-label {
            font-weight: 600;
            color: var(--text-dark);
        }
        
        .info-value {
            font-weight: 700;
            color: var(--pink-accent);
        }
        
        .btn {
            padding: 15px 30px;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            margin: 8px;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }
        
        .btn-download {
            background: linear-gradient(135deg, var(--pink-accent) 0%, var(--green-accent) 100%);
            color: var(--white);
        }
        
        .btn-home {
            background: var(--pastel-pink);
            color: var(--text-dark);
            border: 1px solid var(--border-light);
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 133, 162, 0.3);
        }
        
        .confirmation-message {
            color: var(--text-light);
            margin-bottom: 25px;
            line-height: 1.6;
        }

        .ticket-details {
            background: var(--pastel-pink);
            padding: 20px;
            border-radius: 12px;
            margin: 20px 0;
            border: 2px solid var(--pink-accent);
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="success-container">
        <div class="success-icon">✅</div>
        <h1>Payment Successful!</h1>
        
        <p class="confirmation-message">
            Your booking has been confirmed and payment has been processed successfully. 
            A confirmation email has been sent to your registered email address.
        </p>
        
        <div class="booking-info">
            <div class="info-item">
                <span class="info-label">Booking Reference:</span>
                <span class="info-value"><?php echo htmlspecialchars($booking_reference); ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Transaction ID:</span>
                <span class="info-value"><?php echo htmlspecialchars($transaction_id); ?></span>
            </div>
            <?php if ($booking_details): ?>
            <div class="info-item">
                <span class="info-label">Train:</span>
                <span class="info-value"><?php echo htmlspecialchars($booking_details['train_name']); ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Route:</span>
                <span class="info-value"><?php echo htmlspecialchars($booking_details['from_station']); ?> to <?php echo htmlspecialchars($booking_details['to_station']); ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Travel Date:</span>
                <span class="info-value"><?php echo htmlspecialchars($booking_details['travel_date']); ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Seats:</span>
                <span class="info-value"><?php echo $booking_details['number_of_seats']; ?> seat(s)</span>
            </div>
            <div class="info-item">
                <span class="info-label">Total Paid:</span>
                <span class="info-value">RM <?php echo number_format($booking_details['total_fare'], 2); ?></span>
            </div>
            <?php endif; ?>
            <div class="info-item">
                <span class="info-label">Status:</span>
                <span class="info-value" style="color: var(--green-accent);">Confirmed</span>
            </div>
        </div>
        
        <div style="margin-top: 30px;">
            <a href="payment/download_ticket.php?ref=<?php echo $booking_reference; ?>" class="btn btn-download">
                📄 Download Ticket
            </a>
            <a href="payment/my_bookings.php" class="btn btn-home">
                📋 My Bookings
            </a>
            <a href="payment/book_tickets.php" class="btn btn-home">
                🚆 Book Another Ticket
            </a>
        </div>
        
        <p style="margin-top: 25px; color: var(--text-light); font-size: 14px;">
            Thank you for choosing Malaysia Train Services!
        </p>
    </div>
</body>
</html>

<?php $conn->close(); ?>