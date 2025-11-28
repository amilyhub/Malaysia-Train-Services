<?php
session_start();

// Check if payment form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Store booking data in session
    $_SESSION['booking_data'] = [
        'passenger_name' => $_POST['passenger_name'],
        'passenger_email' => $_POST['passenger_email'],
        'passenger_phone' => $_POST['passenger_phone'],
        'ticket_type' => $_POST['ticket_type'],
        'passenger_count' => $_POST['passenger_count'],
        'travel_date' => $_POST['travel_date'],
        'train_id' => $_POST['train_id'],
        'train_data' => json_decode($_POST['train_data'], true),
        'total_amount' => $_POST['total_amount']
    ];
    
    // Store payment details
    $_SESSION['payment_details'] = [
        'payment_method' => $_POST['payment_method'],
        'card_last_four' => substr($_POST['card_number'], -4),
        'payment_date' => date('Y-m-d H:i:s'),
        'transaction_id' => 'TXN' . date('YmdHis') . rand(1000, 9999)
    ];
}

// Check if we have the required data
if (!isset($_SESSION['booking_data']) || !isset($_SESSION['payment_details'])) {
    header('Location: book_tickets.php');
    exit();
}

$booking = $_SESSION['booking_data'];
$payment = $_SESSION['payment_details'];

// Generate booking reference
$booking_ref = 'MTS' . date('Ymd') . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);

// Clear session data after displaying (optional)
// unset($_SESSION['booking_data']);
// unset($_SESSION['payment_details']);
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
            padding: 20px;
            color: var(--text-dark);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .success-container {
            background: var(--white);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(255, 133, 162, 0.15);
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            border: 1px solid var(--border-light);
            position: relative;
            overflow: hidden;
            text-align: center;
        }

        .success-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--pink-accent) 0%, var(--green-accent) 100%);
        }

        .success-header {
            background: linear-gradient(135deg, var(--pastel-pink) 0%, var(--pastel-green) 100%);
            padding: 40px 35px;
            border-bottom: 1px solid var(--border-light);
        }

        .success-icon {
            font-size: 80px;
            margin-bottom: 20px;
        }

        .success-header h1 {
            color: var(--text-dark);
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .success-header p {
            color: var(--text-light);
            font-size: 18px;
            font-weight: 400;
        }

        .booking-details {
            padding: 40px 35px;
        }

        .booking-ref {
            background: var(--pastel-green);
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 30px;
            border-left: 4px solid var(--green-accent);
        }

        .booking-ref h3 {
            color: var(--text-dark);
            margin-bottom: 10px;
            font-size: 18px;
        }

        .ref-number {
            font-size: 24px;
            font-weight: 700;
            color: var(--pink-accent);
            letter-spacing: 2px;
        }

        .details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }

        .detail-section {
            background: var(--pastel-pink);
            padding: 25px;
            border-radius: 15px;
            border: 1px solid var(--border-light);
        }

        .detail-section h3 {
            color: var(--text-dark);
            margin-bottom: 15px;
            font-size: 18px;
            border-bottom: 2px solid var(--pink-accent);
            padding-bottom: 8px;
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .total-section {
            background: var(--pastel-green);
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
            border-left: 4px solid var(--green-accent);
        }

        .total-section h3 {
            color: var(--text-dark);
            margin-bottom: 15px;
            font-size: 20px;
        }

        .total-amount {
            font-size: 32px;
            font-weight: 700;
            color: var(--pink-accent);
        }

        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-bottom: 30px;
        }

        .btn {
            padding: 15px 30px;
            background: linear-gradient(135deg, var(--pink-accent) 0%, var(--green-accent) 100%);
            color: var(--white);
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 133, 162, 0.3);
        }

        .btn-outline {
            background: transparent;
            border: 2px solid var(--pink-accent);
            color: var(--pink-accent);
        }

        .btn-outline:hover {
            background: var(--pastel-pink);
        }

        .footer {
            text-align: center;
            padding: 25px;
            border-top: 1px solid var(--pastel-pink);
            color: var(--text-light);
            font-size: 14px;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 10px;
        }

        .footer-links a {
            color: var(--pink-accent);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-links a:hover {
            color: var(--green-accent);
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .details-grid {
                grid-template-columns: 1fr;
            }
            
            .action-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .btn {
                width: 100%;
                max-width: 300px;
            }
        }
    </style>
</head>
<body>
    <div class="success-container">
        <!-- Success Header -->
        <div class="success-header">
            <div class="success-icon">🎉</div>
            <h1>Payment Successful!</h1>
            <p>Your train tickets have been booked successfully</p>
        </div>

        <!-- Booking Details -->
        <div class="booking-details">
            <div class="booking-ref">
                <h3>Booking Reference Number</h3>
                <div class="ref-number"><?php echo $booking_ref; ?></div>
                <p style="margin-top: 10px; color: var(--text-light); font-size: 14px;">
                    Please save this number for your records
                </p>
            </div>

            <div class="details-grid">
                <!-- Passenger Details -->
                <div class="detail-section">
                    <h3>Passenger Details</h3>
                    <div class="detail-item">
                        <span>Name:</span>
                        <span><?php echo htmlspecialchars($booking['passenger_name']); ?></span>
                    </div>
                    <div class="detail-item">
                        <span>Email:</span>
                        <span><?php echo htmlspecialchars($booking['passenger_email']); ?></span>
                    </div>
                    <div class="detail-item">
                        <span>Phone:</span>
                        <span><?php echo htmlspecialchars($booking['passenger_phone']); ?></span>
                    </div>
                    <div class="detail-item">
                        <span>Ticket Type:</span>
                        <span><?php echo ucfirst($booking['ticket_type']); ?></span>
                    </div>
                    <div class="detail-item">
                        <span>Passengers:</span>
                        <span><?php echo $booking['passenger_count']; ?></span>
                    </div>
                </div>

                <!-- Journey Details -->
                <div class="detail-section">
                    <h3>Journey Details</h3>
                    <div class="detail-item">
                        <span>Train:</span>
                        <span><?php echo $booking['train_data']['train_class'] . ' - ' . $booking['train_data']['train_name']; ?></span>
                    </div>
                    <div class="detail-item">
                        <span>Route:</span>
                        <span><?php echo $booking['train_data']['departure_station'] . ' → ' . $booking['train_data']['arrival_station']; ?></span>
                    </div>
                    <div class="detail-item">
                        <span>Date:</span>
                        <span><?php echo $booking['travel_date']; ?></span>
                    </div>
                    <div class="detail-item">
                        <span>Time:</span>
                        <span><?php echo $booking['train_data']['departure_time'] . ' - ' . $booking['train_data']['arrival_time']; ?></span>
                    </div>
                    <div class="detail-item">
                        <span>Duration:</span>
                        <span><?php echo $booking['train_data']['duration']; ?></span>
                    </div>
                </div>
            </div>

            <!-- Payment Details -->
            <div class="detail-section">
                <h3>Payment Details</h3>
                <div class="detail-item">
                    <span>Transaction ID:</span>
                    <span><?php echo $payment['transaction_id']; ?></span>
                </div>
                <div class="detail-item">
                    <span>Payment Method:</span>
                    <span><?php echo ucfirst($payment['payment_method']) . ' Card'; ?></span>
                </div>
                <div class="detail-item">
                    <span>Card Ending:</span>
                    <span>**** <?php echo $payment['card_last_four']; ?></span>
                </div>
                <div class="detail-item">
                    <span>Payment Date:</span>
                    <span><?php echo $payment['payment_date']; ?></span>
                </div>
            </div>

            <!-- Total Amount -->
            <div class="total-section">
                <h3>Total Amount Paid</h3>
                <div class="total-amount">RM <?php echo number_format($booking['total_amount'], 2); ?></div>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <a href="index.php" class="btn">Back to Home</a>
                <a href="book_tickets.php" class="btn btn-outline">Book More Tickets</a>
            </div>

            <!-- Important Notes -->
            <div style="background: var(--pastel-pink); padding: 20px; border-radius: 10px; text-align: left;">
                <h4 style="color: var(--text-dark); margin-bottom: 10px;">📋 Important Information</h4>
                <ul style="color: var(--text-light); font-size: 14px; line-height: 1.6;">
                    <li>Please arrive at the station at least 30 minutes before departure</li>
                    <li>Bring a valid ID and this booking reference for verification</li>
                    <li>E-ticket will be sent to your email within 24 hours</li>
                    <li>For any changes, contact our support team with your booking reference</li>
                </ul>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>&copy; 2024 Malaysia Train Services. All rights reserved.</p>
            <div class="footer-links">
                <a href="about.php">About Us</a>
                <a href="support.php">Support</a>
                <a href="#">Privacy Policy</a>
            </div>
        </div>
    </div>

    <script>
        // Print booking confirmation
        function printConfirmation() {
            window.print();
        }

        // Auto-print after 2 seconds (optional)
        setTimeout(() => {
            // Uncomment the line below if you want auto-print
            // printConfirmation();
        }, 2000);
    </script>
</body>
</html>