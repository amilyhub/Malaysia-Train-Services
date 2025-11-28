<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include 'train_functions.php';

// Get train details if train_id is provided
$train = null;
if (isset($_GET['train_id'])) {
    $train = $trainSchedule->getScheduleById($_GET['train_id']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Tickets - Malaysia Train Services</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #ffe6f0 0%, #e6fff0 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .pastel-container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(255, 102, 163, 0.2);
            border: 2px solid #ffb3d9;
            overflow: hidden;
        }

        .pastel-header {
            background: linear-gradient(135deg, #ff66a3, #ff99c8);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .pastel-header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
        }

        .nav-menu {
            display: flex;
            justify-content: center;
            gap: 15px;
            padding: 20px;
            background: white;
            border-bottom: 2px solid #ffb3d9;
            flex-wrap: wrap;
        }

        .nav-btn {
            padding: 12px 25px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .nav-btn.pink {
            background: #ffb3d9;
            color: #ff66a3;
        }

        .nav-btn.green {
            background: #99ffbb;
            color: #66cc99;
        }

        .nav-btn:hover {
            transform: translateY(-2px);
        }

        .pastel-card {
            background: white;
            padding: 30px;
            margin: 20px;
            border-radius: 15px;
            border: 2px solid #99ffbb;
            box-shadow: 0 4px 15px rgba(102, 255, 153, 0.2);
        }

        .text-pink {
            color: #ff66a3;
        }

        .text-green {
            color: #66cc99;
        }

        .text-center {
            text-align: center;
        }

        .booking-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-top: 20px;
        }

        @media (max-width: 768px) {
            .booking-grid {
                grid-template-columns: 1fr;
            }
        }

        .train-details {
            background: rgba(255, 102, 163, 0.1);
            padding: 25px;
            border-radius: 12px;
            border-left: 5px solid #ff66a3;
        }

        .passenger-form {
            background: rgba(102, 204, 153, 0.1);
            padding: 25px;
            border-radius: 12px;
            border-left: 5px solid #66cc99;
        }

        .pastel-form-group {
            margin-bottom: 20px;
        }

        .pastel-form-group label {
            display: block;
            margin-bottom: 8px;
            color: #ff66a3;
            font-weight: bold;
        }

        .pastel-form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #ffb3d9;
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .pastel-form-control:focus {
            outline: none;
            border-color: #66cc99;
            box-shadow: 0 0 10px rgba(102, 255, 153, 0.3);
        }

        .pastel-btn-green {
            background: linear-gradient(135deg, #66cc99, #66ff99);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 12px;
            cursor: pointer;
            font-weight: bold;
            font-size: 1.1em;
            width: 100%;
            margin-top: 20px;
            transition: all 0.3s ease;
        }

        .pastel-btn-green:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 255, 153, 0.4);
        }

        .detail-row {
            display: flex;
            justify-content: between;
            margin-bottom: 15px;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }

        .detail-label {
            font-weight: bold;
            color: #ff66a3;
            width: 120px;
        }

        .detail-value {
            color: #666;
            flex: 1;
        }

        .price-display {
            text-align: center;
            padding: 20px;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 12px;
            margin-top: 20px;
        }

        .price-amount {
            font-size: 2.5em;
            font-weight: bold;
            color: #66cc99;
        }
    </style>
</head>
<body>
    <div class="pastel-container">
        <div class="pastel-header">
            <h1>🎫 Book Your Train Ticket</h1>
            <p>Secure Your Journey with Malaysia Train Services</p>
        </div>

        <div class="nav-menu">
            <a href="index.php" class="nav-btn green">🏠 Home</a>
            <a href="trainschedule.php" class="nav-btn pink">🚆 Schedule</a>
            <a href="logout.php" class="nav-btn green">🚪 Logout</a>
        </div>

        <?php if ($train): ?>
        <div class="pastel-card">
            <h2 class="text-green text-center">Train & Passenger Details</h2>
            
            <div class="booking-grid">
                <div class="train-details">
                    <h3 class="text-pink">🚆 Train Information</h3>
                    <div class="detail-row">
                        <span class="detail-label">Train:</span>
                        <span class="detail-value"><?= htmlspecialchars($train['train_name']) ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Class:</span>
                        <span class="detail-value"><?= htmlspecialchars($train['train_class']) ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Route:</span>
                        <span class="detail-value"><?= htmlspecialchars($train['departure_station']) ?> to <?= htmlspecialchars($train['arrival_station']) ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Departure:</span>
                        <span class="detail-value"><?= date('h:i A', strtotime($train['departure_time'])) ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Arrival:</span>
                        <span class="detail-value"><?= date('h:i A', strtotime($train['arrival_time'])) ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Duration:</span>
                        <span class="detail-value"><?= htmlspecialchars($train['duration']) ?></span>
                    </div>
                    
                    <div class="price-display">
                        <div>Total Price</div>
                        <div class="price-amount">RM <?= number_format($train['price'], 2) ?></div>
                        <div style="color: #666; font-size: 0.9em;">per passenger</div>
                    </div>
                </div>

                <div class="passenger-form">
                    <h3 class="text-green">👤 Passenger Information</h3>
                    <form method="POST" action="booking_process.php">
                        <input type="hidden" name="train_id" value="<?= $train['id'] ?>">
                        
                        <div class="pastel-form-group">
                            <label>Full Name (as per IC)</label>
                            <input type="text" class="pastel-form-control" name="passenger_name" required placeholder="Enter passenger full name">
                        </div>

                        <div class="pastel-form-group">
                            <label>IC/Passport Number</label>
                            <input type="text" class="pastel-form-control" name="ic_number" required placeholder="Enter IC or passport number">
                        </div>

                        <div class="pastel-form-group">
                            <label>Email Address</label>
                            <input type="email" class="pastel-form-control" name="passenger_email" required placeholder="Enter email for ticket">
                        </div>

                        <div class="pastel-form-group">
                            <label>Phone Number</label>
                            <input type="tel" class="pastel-form-control" name="passenger_phone" required placeholder="Enter phone number">
                        </div>

                        <div class="pastel-form-group">
                            <label>Number of Tickets</label>
                            <select class="pastel-form-control" name="ticket_quantity" required>
                                <option value="1">1 Ticket</option>
                                <option value="2">2 Tickets</option>
                                <option value="3">3 Tickets</option>
                                <option value="4">4 Tickets</option>
                                <option value="5">5 Tickets</option>
                            </select>
                        </div>

                        <button type="submit" class="pastel-btn-green">🎫 Confirm & Pay Now</button>
                    </form>
                </div>
            </div>
        </div>
        <?php else: ?>
        <div class="pastel-card text-center">
            <h2 class="text-pink">🚆 Select a Train First</h2>
            <p style="font-size: 1.1em; margin: 20px 0;">Please go to the train schedule and select a train to book.</p>
            <a href="trainschedule.php" class="nav-btn green" style="font-size: 1.1em; padding: 15px 30px; display: inline-block;">View Train Schedule</a>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>