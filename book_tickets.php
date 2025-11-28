<?php
session_start();

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

// Handle form submission - SIMPAN DATA DALAM SESSION
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['train_id'])) {
    $_SESSION['booking_data'] = [
        'train_id' => $_POST['train_id'],
        'passenger_name' => $_POST['passenger_name'],
        'passenger_email' => $_POST['passenger_email'],
        'passenger_phone' => $_POST['passenger_phone'],
        'ticket_type' => $_POST['ticket_type'],
        'passenger_count' => $_POST['passenger_count'],
        'travel_date' => $_POST['travel_date'],
        'total_amount' => $_POST['total_amount'],
        'train_data' => json_decode($_POST['train_data'], true)
    ];
    
    // Redirect ke payment page
    header("Location: payment.php");
    exit;
}

// Fetch trains data from database - ORDER BY ID untuk susunan sama seperti gambar
$sql = "SELECT * FROM train_schedules WHERE status = 'active' ORDER BY id ASC";
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
    <title>Book Tickets - Malaysia Train Services</title>
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
        }

        .booking-container {
            background: var(--white);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(255, 133, 162, 0.15);
            width: 100%;
            max-width: 1400px;
            margin: 0 auto;
            border: 1px solid var(--border-light);
            position: relative;
            overflow: hidden;
            display: flex;
            min-height: 80vh;
        }

        .booking-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--pink-accent) 0%, var(--green-accent) 100%);
        }

        /* Left Side - Train Selection */
        .trains-section {
            flex: 1;
            padding: 30px;
            overflow-y: auto;
            max-height: 80vh;
        }

        /* Right Side - Booking Form */
        .booking-form-section {
            width: 400px;
            background: linear-gradient(135deg, var(--pastel-pink) 0%, var(--pastel-green) 100%);
            padding: 30px;
            border-left: 1px solid var(--border-light);
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
        }

        .company-header {
            text-align: center;
            margin-bottom: 32px;
            padding-bottom: 24px;
            border-bottom: 2px solid var(--pastel-pink);
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

        /* Search Section */
        .search-section {
            background: linear-gradient(135deg, var(--pastel-pink) 0%, var(--pastel-green) 100%);
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
            border: 1px solid var(--border-light);
        }

        .search-section h2 {
            color: var(--text-dark);
            margin-bottom: 20px;
            text-align: center;
            font-size: 22px;
        }

        .search-form {
            display: grid;
            grid-template-columns: 1fr 1fr auto;
            gap: 12px;
            align-items: end;
        }

        .form-group {
            margin-bottom: 0;
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

        .btn-search {
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

        .btn-search:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 133, 162, 0.3);
        }

        .btn-search:disabled {
            background: var(--text-light);
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        /* Train Cards */
        .trains-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .train-card {
            background: var(--white);
            border-radius: 15px;
            padding: 20px;
            border: 2px solid var(--border-light);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .train-card:hover {
            transform: translateY(-5px);
            border-color: var(--pink-accent);
            box-shadow: 0 10px 25px rgba(255, 133, 162, 0.2);
        }

        .train-card.selected {
            border-color: var(--green-accent);
            background: linear-gradient(135deg, #f8fffe 0%, #f0fff8 100%);
        }

        .train-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .train-number {
            font-weight: 700;
            color: var(--pink-accent);
            font-size: 18px;
        }

        .train-class {
            background: var(--pastel-green);
            color: var(--green-accent);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .train-route {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 10px;
            color: var(--text-dark);
        }

        .train-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 15px;
        }

        .detail-item {
            display: flex;
            flex-direction: column;
        }

        .detail-label {
            font-size: 12px;
            color: var(--text-light);
            margin-bottom: 4px;
        }

        .detail-value {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-dark);
        }

        .train-price {
            text-align: center;
            font-size: 24px;
            font-weight: 700;
            color: var(--pink-accent);
            margin: 15px 0;
        }

        .btn-select {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, var(--pink-accent) 0%, var(--green-accent) 100%);
            color: var(--white);
            border: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-select:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 133, 162, 0.3);
        }

        .btn-select:disabled {
            background: var(--text-light);
            cursor: not-allowed;
        }

        /* Booking Form */
        .booking-form {
            background: var(--white);
            padding: 25px;
            border-radius: 15px;
            border: 1px solid var(--border-light);
        }

        .booking-form h3 {
            color: var(--text-dark);
            margin-bottom: 20px;
            text-align: center;
            font-size: 20px;
        }

        .selected-train-info {
            background: var(--pastel-pink);
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            border-left: 4px solid var(--pink-accent);
        }

        .selected-train-info h4 {
            color: var(--text-dark);
            margin-bottom: 8px;
            font-size: 16px;
        }

        .selected-train-details {
            font-size: 14px;
            color: var(--text-light);
        }

        .form-group-full {
            margin-bottom: 15px;
        }

        .form-group-full label {
            display: block;
            margin-bottom: 8px;
            color: var(--text-dark);
            font-weight: 500;
            font-size: 14px;
        }

        .booking-summary {
            background: var(--pastel-green);
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
            border-left: 4px solid var(--green-accent);
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .summary-total {
            display: flex;
            justify-content: space-between;
            font-size: 18px;
            font-weight: 700;
            color: var(--text-dark);
            margin-top: 10px;
            padding-top: 10px;
            border-top: 2px solid var(--border-light);
        }

        .btn-proceed {
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

        .btn-proceed:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 133, 162, 0.3);
        }

        .btn-proceed:disabled {
            background: var(--text-light);
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .no-trains {
            text-align: center;
            padding: 40px;
            color: var(--text-light);
            font-size: 18px;
            grid-column: 1 / -1;
        }

        /* Footer */
        .footer {
            text-align: center;
            padding-top: 25px;
            border-top: 1px solid var(--pastel-pink);
            color: var(--text-light);
            font-size: 14px;
            margin-top: 30px;
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

        /* Responsive */
        @media (max-width: 1024px) {
            .booking-container {
                flex-direction: column;
            }
            
            .booking-form-section {
                width: 100%;
                height: auto;
                position: static;
            }
            
            .trains-section {
                max-height: none;
            }
        }

        @media (max-width: 768px) {
            .search-form {
                grid-template-columns: 1fr;
            }
            
            .trains-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="booking-container">
        <!-- Left Side - Train Selection -->
        <div class="trains-section">
            <!-- Header -->
            <div class="company-header">
                <h1>Malaysia Train Services</h1>
                <p class="tagline">Book Your Journey With Us</p>
            </div>

            <!-- Navigation -->
            <div class="navigation">
                <a href="index.php" class="nav-btn">Home</a>
                <a href="trainschedule.php" class="nav-btn">Train Schedule</a>
                <a href="searchtrains.php" class="nav-btn">Search Trains</a>
                <a href="book_tickets.php" class="nav-btn active">Book Tickets</a>
                <a href="login.php" class="nav-btn">Login</a>
                <a href="register.php" class="nav-btn">Register</a>
            </div>

            <!-- Search Section -->
            <div class="search-section">
                <h2>Find Your Perfect Train</h2>
                <div class="search-form">
                    <div class="form-group">
                        <label for="from-station">From Station</label>
                        <select class="form-control" id="from-station">
                            <option value="">Select departure station</option>
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
                        <label for="to-station">To Station</label>
                        <select class="form-control" id="to-station">
                            <option value="">Select arrival station</option>
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
                    <button class="btn-search" id="search-btn">Search Trains</button>
                </div>
            </div>

            <!-- Train Cards Grid - SUSUNAN SAMA SEPERTI GAMBAR ANDA -->
            <div class="trains-grid" id="trains-grid">
                <?php foreach ($trains as $train): ?>
                <div class="train-card" data-train-id="<?php echo $train['id']; ?>">
                    <div class="train-header">
                        <div class="train-number"><?php echo $train['train_class']; ?></div>
                        <div class="train-class"><?php echo $train['train_name']; ?></div>
                    </div>
                    <div class="train-route"><?php echo $train['departure_station']; ?> → <?php echo $train['arrival_station']; ?></div>
                    <div class="train-details">
                        <div class="detail-item">
                            <span class="detail-label">Departure</span>
                            <span class="detail-value"><?php echo date('H:i', strtotime($train['departure_time'])); ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Arrival</span>
                            <span class="detail-value"><?php echo date('H:i', strtotime($train['arrival_time'])); ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Duration</span>
                            <span class="detail-value"><?php echo $train['duration']; ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Available Seats</span>
                            <span class="detail-value"><?php echo $train['available_seats']; ?></span>
                        </div>
                    </div>
                    <div class="train-price">RM <?php echo number_format($train['price'], 2); ?></div>
                    <button type="button" class="btn-select" onclick="selectTrain(this, <?php echo $train['id']; ?>)" 
                            <?php echo ($train['available_seats'] == 0) ? 'disabled' : ''; ?>>
                        <?php echo ($train['available_seats'] == 0) ? 'Sold Out' : 'Select Train'; ?>
                    </button>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Right Side - Booking Form -->
        <div class="booking-form-section">
            <div class="booking-form">
                <h3>Complete Your Booking</h3>
                
                <div class="selected-train-info" id="selected-train-info" style="display: none;">
                    <h4 id="selected-train-name">No train selected</h4>
                    <div class="selected-train-details" id="selected-train-details">
                        Please select a train from the left
                    </div>
                </div>

                <!-- FORM ACTION KE FILE SENDIRI UNTUK PROCESS SESSION -->
                <form id="bookingForm" method="POST" action="book_tickets.php">
                    <div class="form-group-full">
                        <label for="passenger-name">Full Name</label>
                        <input type="text" class="form-control" id="passenger-name" name="passenger_name" required placeholder="Enter your full name">
                    </div>

                    <div class="form-group-full">
                        <label for="passenger-email">Email Address</label>
                        <input type="email" class="form-control" id="passenger-email" name="passenger_email" required placeholder="Enter your email">
                    </div>

                    <div class="form-group-full">
                        <label for="passenger-phone">Phone Number</label>
                        <input type="tel" class="form-control" id="passenger-phone" name="passenger_phone" required placeholder="Enter your phone number">
                    </div>

                    <div class="form-group-full">
                        <label for="ticket-type">Ticket Type</label>
                        <select class="form-control" id="ticket-type" name="ticket_type" required>
                            <option value="">Select ticket type</option>
                            <option value="adult">Adult</option>
                            <option value="child">Child (3-12 years)</option>
                            <option value="senior">Senior (60+ years)</option>
                            <option value="student">Student</option>
                        </select>
                    </div>

                    <div class="form-group-full">
                        <label for="passenger-count">Number of Passengers</label>
                        <select class="form-control" id="passenger-count" name="passenger_count" required>
                            <option value="1">1 Passenger</option>
                            <option value="2">2 Passengers</option>
                            <option value="3">3 Passengers</option>
                            <option value="4">4 Passengers</option>
                            <option value="5">5 Passengers</option>
                            <option value="6">6 Passengers</option>
                        </select>
                    </div>

                    <div class="form-group-full">
                        <label for="travel-date">Travel Date</label>
                        <input type="date" class="form-control" id="travel-date" name="travel_date" required>
                    </div>

                    <!-- Hidden fields for train data -->
                    <input type="hidden" id="train_id" name="train_id">
                    <input type="hidden" id="train_data" name="train_data">
                    <input type="hidden" id="total_amount" name="total_amount">

                    <div class="booking-summary" id="booking-summary" style="display: none;">
                        <h4>Booking Summary</h4>
                        <div class="summary-item">
                            <span>Ticket Price:</span>
                            <span id="summary-price">RM 0.00</span>
                        </div>
                        <div class="summary-item">
                            <span>Passengers:</span>
                            <span id="summary-passengers">0</span>
                        </div>
                        <div class="summary-item">
                            <span>Service Fee:</span>
                            <span>RM 2.00</span>
                        </div>
                        <div class="summary-total">
                            <span>Total Amount:</span>
                            <span id="summary-total">RM 0.00</span>
                        </div>
                    </div>

                    <button type="submit" class="btn-proceed" id="proceed-btn" disabled>
                        Proceed to Payment
                    </button>
                </form>
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
    </div>

    <script>
        // Train data from PHP
        const trainData = <?php echo json_encode($trains); ?>;
        
        let selectedTrain = null;

        // Select train function
        function selectTrain(button, trainId) {
            // Remove selected class from all cards
            document.querySelectorAll('.train-card').forEach(card => {
                card.classList.remove('selected');
            });
            
            // Add selected class to clicked card
            const trainCard = button.closest('.train-card');
            trainCard.classList.add('selected');
            
            // Find selected train
            selectedTrain = trainData.find(train => train.id == trainId);
            
            if (selectedTrain) {
                // Update selected train info
                document.getElementById('selected-train-name').textContent = selectedTrain.train_class + ' - ' + selectedTrain.train_name;
                document.getElementById('selected-train-details').innerHTML = `
                    <strong>Route:</strong> ${selectedTrain.departure_station} → ${selectedTrain.arrival_station}<br>
                    <strong>Time:</strong> ${selectedTrain.departure_time} - ${selectedTrain.arrival_time}<br>
                    <strong>Duration:</strong> ${selectedTrain.duration}<br>
                    <strong>Price:</strong> RM ${parseFloat(selectedTrain.price).toFixed(2)}<br>
                    <strong>Available Seats:</strong> ${selectedTrain.available_seats}<br>
                    <strong>Service Type:</strong> ${selectedTrain.service_type}
                `;
                
                // Show selected train info and booking summary
                document.getElementById('selected-train-info').style.display = 'block';
                document.getElementById('booking-summary').style.display = 'block';
                
                // Enable proceed button
                document.getElementById('proceed-btn').disabled = false;
                
                // Set hidden fields
                document.getElementById('train_id').value = selectedTrain.id;
                document.getElementById('train_data').value = JSON.stringify(selectedTrain);
                
                // Update booking summary
                updateBookingSummary();
            }
        }

        // Update booking summary
        function updateBookingSummary() {
            if (!selectedTrain) return;
            
            const passengers = parseInt(document.getElementById('passenger-count').value);
            const ticketPrice = parseFloat(selectedTrain.price) * passengers;
            const totalAmount = ticketPrice + 2.00; // Service fee
            
            document.getElementById('summary-price').textContent = 'RM ' + ticketPrice.toFixed(2);
            document.getElementById('summary-passengers').textContent = passengers;
            document.getElementById('summary-total').textContent = 'RM ' + totalAmount.toFixed(2);
            document.getElementById('total_amount').value = totalAmount.toFixed(2);
        }

        // Update summary when passenger count changes
        document.getElementById('passenger-count').addEventListener('change', updateBookingSummary);

        // Form submission
        document.getElementById('bookingForm').addEventListener('submit', function(e) {
            if (!selectedTrain) {
                e.preventDefault();
                alert('Please select a train first.');
                return;
            }
            
            // Validate passenger count doesn't exceed available seats
            const passengers = parseInt(document.getElementById('passenger-count').value);
            if (passengers > selectedTrain.available_seats) {
                e.preventDefault();
                alert(`Sorry, only ${selectedTrain.available_seats} seats available for this train.`);
                return;
            }
            
            // Show loading state
            const proceedBtn = document.getElementById('proceed-btn');
            proceedBtn.innerHTML = 'Processing...';
            proceedBtn.disabled = true;
        });

        // Set minimum date to today
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('travel-date').min = today;

        // Initialize date field with tomorrow's date
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        document.getElementById('travel-date').value = tomorrow.toISOString().split('T')[0];

        // Form interactions
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                this.style.transform = 'scale(1.02)';
            });
            
            input.addEventListener('blur', function() {
                this.style.transform = 'scale(1)';
            });
        });

        // ========== SEARCH TRAINS FUNCTIONALITY ==========
        document.addEventListener('DOMContentLoaded', function() {
            // Get the search button
            const searchBtn = document.getElementById('search-btn');
            
            // Add click event listener to search button
            if (searchBtn) {
                searchBtn.addEventListener('click', function() {
                    searchTrains();
                });
            }
            
            // Search trains function
            function searchTrains() {
                const fromStation = document.getElementById('from-station').value;
                const toStation = document.getElementById('to-station').value;
                
                // Basic validation
                if (!fromStation || !toStation) {
                    alert('Please select both departure and arrival stations.');
                    return;
                }
                
                if (fromStation === toStation) {
                    alert('Departure and arrival stations cannot be the same.');
                    return;
                }
                
                // Show loading state
                searchBtn.innerHTML = 'Searching...';
                searchBtn.disabled = true;
                
                // Simulate search process
                setTimeout(function() {
                    // Filter trains based on selection
                    filterTrainsByRoute(fromStation, toStation);
                    
                    // Reset button
                    searchBtn.innerHTML = 'Search Trains';
                    searchBtn.disabled = false;
                }, 1000);
            }
            
            // Filter trains by route
            function filterTrainsByRoute(fromStation, toStation) {
                const trainCards = document.querySelectorAll('.train-card');
                let foundTrains = false;
                
                trainCards.forEach(card => {
                    const routeText = card.querySelector('.train-route').textContent;
                    const departureStation = routeText.split('→')[0].trim();
                    const arrivalStation = routeText.split('→')[1].trim();
                    
                    // Check if train matches the search criteria
                    if (departureStation === fromStation && arrivalStation === toStation) {
                        card.style.display = 'block';
                        foundTrains = true;
                    } else {
                        card.style.display = 'none';
                    }
                });
                
                // Show message if no trains found
                if (!foundTrains) {
                    const trainsGrid = document.getElementById('trains-grid');
                    const noResultsMsg = document.createElement('div');
                    noResultsMsg.className = 'no-trains';
                    noResultsMsg.innerHTML = `
                        <p>No trains found for route: ${fromStation} → ${toStation}</p>
                        <p>Please try different stations.</p>
                    `;
                    
                    // Remove existing message if any
                    const existingMsg = trainsGrid.querySelector('.no-trains');
                    if (existingMsg) {
                        existingMsg.remove();
                    }
                    
                    trainsGrid.appendChild(noResultsMsg);
                } else {
                    // Remove no results message if trains are found
                    const noResultsMsg = document.querySelector('.no-trains');
                    if (noResultsMsg) {
                        noResultsMsg.remove();
                    }
                }
            }
            
            // Add enter key functionality to form fields
            document.getElementById('from-station').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    searchTrains();
                }
            });
            
            document.getElementById('to-station').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    searchTrains();
                }
            });
        });
    </script>
</body>
</html>