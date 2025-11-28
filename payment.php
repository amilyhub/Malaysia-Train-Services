<?php
session_start();

// Check if form was submitted from book_tickets.php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['passenger_name'])) {
    // This is from book_tickets.php - Store booking data
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
    
    // Refresh page to show payment form
    header('Location: payment.php');
    exit();
    
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['card_number'])) {
    // This is payment form submitted - Process payment and redirect to success
    $_SESSION['payment_details'] = [
        'payment_method' => $_POST['payment_method'],
        'card_last_four' => substr($_POST['card_number'], -4),
        'payment_date' => date('Y-m-d H:i:s'),
        'transaction_id' => 'TXN' . date('YmdHis') . rand(1000, 9999)
    ];
    
    // Redirect to success page
    header('Location: payment_success.php');
    exit();
    
} elseif (!isset($_SESSION['booking_data'])) {
    // Redirect back if no booking data
    header('Location: book_tickets.php');
    exit();
}

$booking = $_SESSION['booking_data'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - Malaysia Train Services</title>
    <style>
        /* SAME CSS AS BEFORE - TIDAK PERUBAHAN */
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
        }

        .payment-container {
            background: var(--white);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(255, 133, 162, 0.15);
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            border: 1px solid var(--border-light);
            position: relative;
            overflow: hidden;
        }

        .payment-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--pink-accent) 0%, var(--green-accent) 100%);
        }

        .company-header {
            text-align: center;
            margin-bottom: 32px;
            padding-bottom: 24px;
            border-bottom: 2px solid var(--pastel-pink);
            padding: 40px 35px 0;
        }

        .company-header h1 {
            color: var(--text-dark);
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 8px;
            background: linear-gradient(135deg, var(--pink-accent) 0%, var(--green-accent) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .company-header .tagline {
            color: var(--text-light);
            font-size: 18px;
            font-weight: 400;
        }

        .navigation {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 40px;
            flex-wrap: wrap;
            padding: 0 35px;
        }

        .nav-btn {
            padding: 12px 24px;
            background: linear-gradient(135deg, var(--pink-accent) 0%, var(--green-accent) 100%);
            color: var(--white);
            border: none;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            text-align: center;
        }

        .nav-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 133, 162, 0.3);
        }

        .nav-btn.active {
            background: var(--pink-accent);
        }

        .payment-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            padding: 0 35px 40px;
        }

        .booking-summary {
            background: linear-gradient(135deg, var(--pastel-pink) 0%, var(--pastel-green) 100%);
            padding: 25px;
            border-radius: 15px;
            border: 1px solid var(--border-light);
        }

        .booking-summary h2 {
            color: var(--text-dark);
            margin-bottom: 20px;
            text-align: center;
            font-size: 22px;
        }

        .summary-section {
            background: var(--white);
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .summary-section h3 {
            color: var(--text-dark);
            margin-bottom: 15px;
            font-size: 18px;
            border-bottom: 2px solid var(--pastel-pink);
            padding-bottom: 8px;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .summary-total {
            display: flex;
            justify-content: space-between;
            font-size: 20px;
            font-weight: 700;
            color: var(--text-dark);
            margin-top: 15px;
            padding-top: 15px;
            border-top: 3px solid var(--border-light);
        }

        .train-details {
            background: var(--pastel-green);
            padding: 15px;
            border-radius: 10px;
            margin-top: 15px;
            border-left: 4px solid var(--green-accent);
        }

        .payment-form-section {
            background: var(--white);
            padding: 25px;
            border-radius: 15px;
            border: 1px solid var(--border-light);
        }

        .payment-form-section h2 {
            color: var(--text-dark);
            margin-bottom: 20px;
            text-align: center;
            font-size: 22px;
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

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .payment-methods {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 20px;
        }

        .payment-method {
            background: var(--pastel-pink);
            border: 2px solid var(--border-light);
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .payment-method:hover {
            border-color: var(--pink-accent);
            transform: translateY(-2px);
        }

        .payment-method.selected {
            border-color: var(--green-accent);
            background: var(--pastel-green);
        }

        .payment-method i {
            font-size: 24px;
            margin-bottom: 8px;
            display: block;
        }

        .btn-pay {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, var(--pink-accent) 0%, var(--green-accent) 100%);
            color: var(--white);
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .btn-pay:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 133, 162, 0.3);
        }

        .security-notice {
            background: var(--pastel-green);
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            margin-top: 20px;
            border-left: 4px solid var(--green-accent);
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
            .payment-content {
                grid-template-columns: 1fr;
            }
            
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .payment-methods {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="payment-container">
        <!-- Header -->
        <div class="company-header">
            <h1>Malaysia Train Services</h1>
            <p class="tagline">Secure Payment Gateway</p>
        </div>

        <!-- Navigation -->
        <div class="navigation">
            <a href="index.php" class="nav-btn">Home</a>
            <a href="trainschedule.php" class="nav-btn">Train Schedule</a>
            <a href="book_tickets.php" class="nav-btn">Book Tickets</a>
            <a href="payment.php" class="nav-btn active">Payment</a>
            <a href="login.php" class="nav-btn">Login</a>
        </div>

        <div class="payment-content">
            <!-- Left Side - Booking Summary -->
            <div class="booking-summary">
                <h2>Booking Summary</h2>
                
                <div class="summary-section">
                    <h3>Passenger Details</h3>
                    <div class="summary-item">
                        <span>Name:</span>
                        <span><?php echo htmlspecialchars($booking['passenger_name']); ?></span>
                    </div>
                    <div class="summary-item">
                        <span>Email:</span>
                        <span><?php echo htmlspecialchars($booking['passenger_email']); ?></span>
                    </div>
                    <div class="summary-item">
                        <span>Phone:</span>
                        <span><?php echo htmlspecialchars($booking['passenger_phone']); ?></span>
                    </div>
                    <div class="summary-item">
                        <span>Ticket Type:</span>
                        <span><?php echo ucfirst($booking['ticket_type']); ?></span>
                    </div>
                    <div class="summary-item">
                        <span>Passengers:</span>
                        <span><?php echo $booking['passenger_count']; ?></span>
                    </div>
                    <div class="summary-item">
                        <span>Travel Date:</span>
                        <span><?php echo $booking['travel_date']; ?></span>
                    </div>
                </div>

                <div class="summary-section">
                    <h3>Train Details</h3>
                    <div class="train-details">
                        <div class="summary-item">
                            <span>Train:</span>
                            <span><?php echo $booking['train_data']['train_class'] . ' - ' . $booking['train_data']['train_name']; ?></span>
                        </div>
                        <div class="summary-item">
                            <span>Route:</span>
                            <span><?php echo $booking['train_data']['departure_station'] . ' → ' . $booking['train_data']['arrival_station']; ?></span>
                        </div>
                        <div class="summary-item">
                            <span>Time:</span>
                            <span><?php echo $booking['train_data']['departure_time'] . ' - ' . $booking['train_data']['arrival_time']; ?></span>
                        </div>
                        <div class="summary-item">
                            <span>Duration:</span>
                            <span><?php echo $booking['train_data']['duration']; ?></span>
                        </div>
                    </div>
                </div>

                <div class="summary-section">
                    <h3>Payment Summary</h3>
                    <div class="summary-item">
                        <span>Ticket Price:</span>
                        <span>RM <?php echo number_format($booking['train_data']['price'] * $booking['passenger_count'], 2); ?></span>
                    </div>
                    <div class="summary-item">
                        <span>Service Fee:</span>
                        <span>RM 2.00</span>
                    </div>
                    <div class="summary-total">
                        <span>Total Amount:</span>
                        <span>RM <?php echo number_format($booking['total_amount'], 2); ?></span>
                    </div>
                </div>
            </div>

            <!-- Right Side - Payment Form -->
            <div class="payment-form-section">
                <h2>Payment Details</h2>
                
                <form id="paymentForm" method="POST" action="payment.php">
                    <div class="form-group">
                        <label>Payment Method</label>
                        <div class="payment-methods">
                            <div class="payment-method selected" onclick="selectPaymentMethod('credit')">
                                <i>💳</i>
                                <span>Credit Card</span>
                            </div>
                            <div class="payment-method" onclick="selectPaymentMethod('debit')">
                                <i>🏦</i>
                                <span>Debit Card</span>
                            </div>
                        </div>
                        <input type="hidden" id="payment-method" name="payment_method" value="credit" required>
                    </div>

                    <div class="form-group">
                        <label for="card-number">Card Number</label>
                        <input type="text" class="form-control" id="card-number" name="card_number" 
                               placeholder="1234 5678 9012 3456" required maxlength="19">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="expiry-date">Expiry Date</label>
                            <input type="text" class="form-control" id="expiry-date" name="expiry_date" 
                                   placeholder="MM/YY" required maxlength="5">
                        </div>
                        <div class="form-group">
                            <label for="cvv">CVV</label>
                            <input type="text" class="form-control" id="cvv" name="cvv" 
                                   placeholder="123" required maxlength="3">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="card-name">Name on Card</label>
                        <input type="text" class="form-control" id="card-name" name="card_name" 
                               placeholder="John Doe" required>
                    </div>

                    <div class="security-notice">
                        🔒 Your payment information is secure and encrypted
                    </div>

                    <button type="submit" class="btn-pay">
                        Pay RM <?php echo number_format($booking['total_amount'], 2); ?>
                    </button>
                </form>
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
        // Payment method selection
        function selectPaymentMethod(method) {
            document.querySelectorAll('.payment-method').forEach(el => {
                el.classList.remove('selected');
            });
            event.target.closest('.payment-method').classList.add('selected');
            document.getElementById('payment-method').value = method;
        }

        // Card number formatting
        document.getElementById('card-number').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
            let formattedValue = value.match(/.{1,4}/g)?.join(' ');
            e.target.value = formattedValue || value;
        });

        // Expiry date formatting
        document.getElementById('expiry-date').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\//g, '');
            if (value.length >= 2) {
                value = value.substring(0, 2) + '/' + value.substring(2);
            }
            e.target.value = value;
        });

        // CVV number only
        document.getElementById('cvv').addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/[^0-9]/g, '');
        });

        // Form validation
        document.getElementById('paymentForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Simple validation
            const cardNumber = document.getElementById('card-number').value.replace(/\s/g, '');
            const expiryDate = document.getElementById('expiry-date').value;
            const cvv = document.getElementById('cvv').value;
            const cardName = document.getElementById('card-name').value;

            if (cardNumber.length !== 16) {
                alert('Please enter a valid 16-digit card number');
                return;
            }

            if (!expiryDate.match(/^\d{2}\/\d{2}$/)) {
                alert('Please enter a valid expiry date (MM/YY)');
                return;
            }

            if (cvv.length !== 3) {
                alert('Please enter a valid 3-digit CVV');
                return;
            }

            if (cardName.trim().length < 2) {
                alert('Please enter the name on your card');
                return;
            }

            // Show processing message
            const payButton = document.querySelector('.btn-pay');
            const originalText = payButton.textContent;
            payButton.textContent = 'Processing...';
            payButton.disabled = true;

            // Simulate payment processing
            setTimeout(() => {
                // Submit the form
                this.submit();
            }, 2000);
        });

        // Form interactions
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                this.style.transform = 'scale(1.02)';
            });
            
            input.addEventListener('blur', function() {
                this.style.transform = 'scale(1)';
            });
        });
    </script>
</body>
</html>