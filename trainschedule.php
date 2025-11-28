<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Train Schedule - Malaysia Train Services</title>
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

        .schedule-container {
            background: var(--white);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(255, 133, 162, 0.15);
            padding: 40px 35px;
            width: 100%;
            max-width: 1400px;
            margin: 0 auto;
            border: 1px solid var(--border-light);
            position: relative;
            overflow: hidden;
        }

        .schedule-container::before {
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

        /* Search Section */
        .search-section {
            background: linear-gradient(135deg, var(--pastel-pink) 0%, var(--pastel-green) 100%);
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            border: 1px solid var(--border-light);
        }

        .search-section h2 {
            color: var(--text-dark);
            margin-bottom: 20px;
            text-align: center;
            font-size: 24px;
        }

        .search-form {
            display: grid;
            grid-template-columns: 1fr 1fr auto;
            gap: 15px;
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
            padding: 14px 16px;
            border: 2px solid var(--pastel-pink);
            border-radius: 12px;
            font-size: 15px;
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
            padding: 14px 28px;
            background: linear-gradient(135deg, var(--pink-accent) 0%, var(--green-accent) 100%);
            color: var(--white);
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-search:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 133, 162, 0.3);
        }

        /* Schedule Table */
        .schedule-table {
            background: var(--white);
            border-radius: 15px;
            overflow: hidden;
            border: 1px solid var(--border-light);
            margin-bottom: 30px;
        }

        .table-header {
            background: linear-gradient(135deg, var(--pastel-pink) 0%, var(--pastel-green) 100%);
            padding: 20px;
            text-align: center;
        }

        .table-header h2 {
            color: var(--text-dark);
            font-size: 24px;
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: linear-gradient(135deg, var(--pink-accent) 0%, var(--green-accent) 100%);
            color: var(--white);
            padding: 15px;
            text-align: left;
            font-weight: 600;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid var(--border-light);
            color: var(--text-dark);
        }

        tr:hover {
            background-color: rgba(255, 214, 231, 0.2);
        }

        .train-number {
            font-weight: 600;
            color: var(--pink-accent);
        }

        .btn-book {
            padding: 8px 16px;
            background: linear-gradient(135deg, var(--pink-accent) 0%, var(--green-accent) 100%);
            color: var(--white);
            border: none;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-book:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 133, 162, 0.3);
        }

        .status-active {
            color: var(--green-accent);
            font-weight: 600;
        }

        .status-inactive {
            color: #ff6b8b;
            font-weight: 600;
        }

        /* Footer */
        .footer {
            text-align: center;
            padding-top: 25px;
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
            .schedule-container {
                padding: 30px 20px;
            }
            
            .search-form {
                grid-template-columns: 1fr;
            }
            
            .navigation {
                gap: 10px;
            }
            
            .nav-btn {
                padding: 10px 15px;
                font-size: 12px;
            }
            
            table {
                font-size: 14px;
                display: block;
                overflow-x: auto;
            }
            
            th, td {
                padding: 10px 8px;
            }
        }
    </style>
</head>
<body>
    <div class="schedule-container">
        <!-- Header -->
        <div class="company-header">
            <h1>Malaysia Train Services</h1>
            <p class="tagline">Your Journey Begins With Us</p>
        </div>

        <!-- Navigation -->
        <div class="navigation">
            <a href="index.php" class="nav-btn">Home</a>
            <a href="trainschedule.php" class="nav-btn">Train Schedule</a>
            <a href="login.php" class="nav-btn">Login</a>
            <a href="register.php" class="nav-btn">Register</a>
            <a href="about.php" class="nav-btn">About</a>
            <a href="support.php" class="nav-btn">Support</a>
        </div>

        <!-- Search Section -->
        <div class="search-section">
            <h2>Find Your Train</h2>
            <div class="search-form">
                <div class="form-group">
                    <label for="from-station">From Station</label>
                    <select class="form-control" id="from-station">
                        <option value="">Select departure station</option>
                        <option value="KL Sentral">Kuala Lumpur Sentral</option>
                        <option value="Butterworth">Butterworth</option>
                        <option value="Johor Bahru">Johor Bahru</option>
                        <option value="Ipoh">Ipoh</option>
                        <option value="Melaka">Melaka</option>
                        <option value="Kota Bharu">Kota Bharu</option>
                        <option value="JB Sentral">JB Sentral</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="to-station">To Station</label>
                    <select class="form-control" id="to-station">
                        <option value="">Select arrival station</option>
                        <option value="KL Sentral">Kuala Lumpur Sentral</option>
                        <option value="Butterworth">Butterworth</option>
                        <option value="Johor Bahru">Johor Bahru</option>
                        <option value="Ipoh">Ipoh</option>
                        <option value="Melaka">Melaka</option>
                        <option value="Kota Bharu">Kota Bharu</option>
                        <option value="JB Sentral">JB Sentral</option>
                    </select>
                </div>
                <button class="btn-search" onclick="searchTrains()">Search Trains</button>
            </div>
        </div>

        <!-- Schedule Table -->
        <div class="schedule-table">
            <div class="table-header">
                <h2>Train Schedule - All Available Trains</h2>
            </div>
            
            <?php
            // Database connection
            $servername = "localhost";
            $username = "root";      
            $password = "";          
            $dbname = "train_booking";

            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Check connection
            if ($conn->connect_error) {
                die("<div style='text-align: center; color: red; padding: 20px;'>Connection failed: " . $conn->connect_error . "</div>");
            }

            // Get all trains from database
            $sql = "SELECT * FROM trains ORDER BY id";
            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                echo "<table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Train Name</th>
                            <th>Train No.</th>
                            <th>From</th>
                            <th>To</th>
                            <th>Departure</th>
                            <th>Arrival</th>
                            <th>Total Seats</th>
                            <th>Available</th>
                            <th>Fare</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>";

                // Output data of each row - ID DISPLAY DARI 1-15
                $counter = 1;
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . $counter . "</td>
                            <td><strong>" . $row["train_name"] . "</strong></td>
                            <td class='train-number'>" . $row["train_number"] . "</td>
                            <td>" . $row["source_station"] . "</td>
                            <td>" . $row["destination_station"] . "</td>
                            <td>" . $row["departure_time"] . "</td>
                            <td>" . $row["arrival_time"] . "</td>
                            <td>" . $row["total_seats"] . "</td>
                            <td>" . $row["available_seats"] . "</td>
                            <td><strong>" . $row["fare"] . "</strong></td>
                            <td class='status-active'>" . $row["status"] . "</td>
                            <td><button class='btn-book' onclick='bookTrain(" . $row["id"] . ")'>Book Now</button></td>
                          </tr>";
                    $counter++;
                }
                echo "</tbody></table>";
            } else {
                echo "<div style='text-align: center; padding: 40px; color: var(--text-light);'>No trains available at the moment.</div>";
            }

            $conn->close();
            ?>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>&copy; 2024 Malaysia Train Services. All rights reserved.</p>
            <div class="footer-links">
                <a href="about.php">About Us</a>
                <a href="support.php">Support</a>
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
            </div>
        </div>
    </div>

    <script>
        // Search functionality
        function searchTrains() {
            const fromStation = document.getElementById('from-station').value;
            const toStation = document.getElementById('to-station').value;
            
            if (!fromStation || !toStation) {
                alert('Please select both departure and arrival stations.');
                return;
            }
            
            // Filter trains based on selection
            const rows = document.querySelectorAll('tbody tr');
            let found = false;
            
            rows.forEach(row => {
                const fromCell = row.cells[3].textContent; // From station column
                const toCell = row.cells[4].textContent;   // To station column
                
                if (fromCell === fromStation && toCell === toStation) {
                    row.style.display = '';
                    found = true;
                } else {
                    row.style.display = 'none';
                }
            });
            
            if (!found) {
                alert('No trains found for the selected route.');
                // Show all rows again
                rows.forEach(row => row.style.display = '');
            }
        }

        // Book button functionality
        function bookTrain(trainId) {
            alert('Please login to book train ID: ' + trainId);
            window.location.href = 'login.php?train=' + trainId;
        }

        // Form interactions
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                this.style.transform = 'scale(1.02)';
            });
            
            input.addEventListener('blur', function() {
                this.style.transform = 'scale(1)';
            });
        });

        // Reset filter when page loads
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('from-station').value = '';
            document.getElementById('to-station').value = '';
        });
    </script>
</body>
</html>