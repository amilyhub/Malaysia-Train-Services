<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Trains - Malaysia Train Services</title>
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
            max-width: 1200px;
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

        .nav-btn.active {
            background: var(--pink-accent);
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
            display: none;
        }

        .schedule-table.active {
            display: block;
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

        .status-available {
            color: var(--green-accent);
            font-weight: 600;
        }

        .status-limited {
            color: #ffa500;
            font-weight: 600;
        }

        .no-results {
            padding: 40px;
            text-align: center;
            color: var(--text-light);
            font-size: 18px;
            display: none;
        }

        .no-results.active {
            display: block;
        }

        .loading {
            display: none;
            text-align: center;
            padding: 20px;
        }

        .loading.active {
            display: block;
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
            <a href="searchtrains.php" class="nav-btn active">Search Trains</a>
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
                        <option value="Kuala Lumpur Sentral">Kuala Lumpur Sentral</option>
                        <option value="Penang">Penang</option>
                        <option value="Johor Bahru">Johor Bahru</option>
                        <option value="Ipoh">Ipoh</option>
                        <option value="Melaka">Melaka</option>
                        <option value="Kota Bharu">Kota Bharu</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="to-station">To Station</label>
                    <select class="form-control" id="to-station">
                        <option value="">Select arrival station</option>
                        <option value="Kuala Lumpur Sentral">Kuala Lumpur Sentral</option>
                        <option value="Penang">Penang</option>
                        <option value="Johor Bahru">Johor Bahru</option>
                        <option value="Ipoh">Ipoh</option>
                        <option value="Melaka">Melaka</option>
                        <option value="Kota Bharu">Kota Bharu</option>
                    </select>
                </div>
                <button class="btn-search" id="search-btn">Search Trains</button>
            </div>
        </div>

        <!-- Loading -->
        <div class="loading" id="loading">
            <p>Searching for available trains...</p>
        </div>

        <!-- No Results -->
        <div class="no-results" id="no-results">
            <p>No trains found for the selected route.</p>
            <p>Please try different stations.</p>
        </div>

        <!-- Results Table -->
        <div class="schedule-table" id="results-table">
            <div class="table-header">
                <h2 id="results-title">Available Trains</h2>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Train No.</th>
                        <th>Route</th>
                        <th>Departure</th>
                        <th>Arrival</th>
                        <th>Duration</th>
                        <th>Fare</th>
                        <th>Seats</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="results-body">
                    <!-- Results will be populated here by JavaScript -->
                </tbody>
            </table>
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
        // Train data - sama seperti dalam trainschedule.php
        const trainData = [
            {
                number: "ETS 101",
                from: "Kuala Lumpur Sentral",
                to: "Penang",
                departure: "08:00 AM",
                arrival: "12:00 PM",
                duration: "4 hours",
                fare: "RM 35",
                seats: "Available",
                status: "available"
            },
            {
                number: "ETS 102",
                from: "Kuala Lumpur Sentral",
                to: "Johor Bahru",
                departure: "09:30 AM",
                arrival: "02:30 PM",
                duration: "5 hours",
                fare: "RM 45",
                seats: "Available",
                status: "available"
            },
            {
                number: "ETS 103",
                from: "Kuala Lumpur Sentral",
                to: "Ipoh",
                departure: "10:15 AM",
                arrival: "12:45 PM",
                duration: "2.5 hours",
                fare: "RM 25",
                seats: "Limited",
                status: "limited"
            },
            {
                number: "ETS 104",
                from: "Penang",
                to: "Kuala Lumpur Sentral",
                departure: "01:00 PM",
                arrival: "05:00 PM",
                duration: "4 hours",
                fare: "RM 35",
                seats: "Available",
                status: "available"
            },
            {
                number: "ETS 105",
                from: "Johor Bahru",
                to: "Kuala Lumpur Sentral",
                departure: "03:30 PM",
                arrival: "08:30 PM",
                duration: "5 hours",
                fare: "RM 45",
                seats: "Available",
                status: "available"
            },
            {
                number: "ETS 106",
                from: "Ipoh",
                to: "Penang",
                departure: "11:00 AM",
                arrival: "01:30 PM",
                duration: "2.5 hours",
                fare: "RM 20",
                seats: "Available",
                status: "available"
            },
            {
                number: "ETS 107",
                from: "Melaka",
                to: "Kuala Lumpur Sentral",
                departure: "07:30 AM",
                arrival: "09:30 AM",
                duration: "2 hours",
                fare: "RM 15",
                seats: "Limited",
                status: "limited"
            },
            {
                number: "ETS 108",
                from: "Kota Bharu",
                to: "Kuala Lumpur Sentral",

                departure: "06:00 AM",
                arrival: "02:00 PM",
                duration: "8 hours",
                fare: "RM 60",
                seats: "Available",
                status: "available"
            }
        ];

        // DOM Elements
        const fromStationSelect = document.getElementById('from-station');
        const toStationSelect = document.getElementById('to-station');
        const searchBtn = document.getElementById('search-btn');
        const loadingElement = document.getElementById('loading');
        const noResultsElement = document.getElementById('no-results');
        const resultsTable = document.getElementById('results-table');
        const resultsTitle = document.getElementById('results-title');
        const resultsBody = document.getElementById('results-body');

        // Search functionality - INSTANT tanpa loading delay
        searchBtn.addEventListener('click', function() {
            const fromStation = fromStationSelect.value;
            const toStation = toStationSelect.value;
            
            if (!fromStation || !toStation) {
                alert('Please select both departure and arrival stations.');
                return;
            }
            
            if (fromStation === toStation) {
                alert('Departure and arrival stations cannot be the same.');
                return;
            }
            
            // Hide all sections first
            loadingElement.classList.remove('active');
            noResultsElement.classList.remove('active');
            resultsTable.classList.remove('active');
            
            // Show loading briefly (optional - you can remove this for instant results)
            loadingElement.classList.add('active');
            
            // Simulate very short loading (100ms saja)
            setTimeout(() => {
                performSearch(fromStation, toStation);
            }, 100);
        });

        // Perform search INSTANT
        function performSearch(fromStation, toStation) {
            // Hide loading
            loadingElement.classList.remove('active');
            
            // Filter trains based on selected stations
            const filteredTrains = trainData.filter(train => 
                train.from === fromStation && train.to === toStation
            );
            
            if (filteredTrains.length > 0) {
                // Show results
                resultsTitle.textContent = `Available Trains from ${fromStation} to ${toStation}`;
                displayResults(filteredTrains);
                resultsTable.classList.add('active');
                noResultsElement.classList.remove('active');
            } else {
                // Show no results
                resultsTable.classList.remove('active');
                noResultsElement.classList.add('active');
            }
        }

        // Display results in table
        function displayResults(trains) {
            resultsBody.innerHTML = '';
            
            trains.forEach(train => {
                const row = document.createElement('tr');
                
                const statusClass = train.status === 'available' ? 'status-available' : 'status-limited';
                
                row.innerHTML = `
                    <td class="train-number">${train.number}</td>
                    <td>${train.from} → ${train.to}</td>
                    <td>${train.departure}</td>
                    <td>${train.arrival}</td>
                    <td>${train.duration}</td>
                    <td>${train.fare}</td>
                    <td class="${statusClass}">${train.seats}</td>
                    <td><button class="btn-book" onclick="bookTrain('${train.number}')">Book Now</button></td>
                `;
                
                resultsBody.appendChild(row);
            });
        }

        // Book button functionality
        function bookTrain(trainNumber) {
            alert(`Please login to book ${trainNumber}`);
            window.location.href = 'login.php';
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

        // Prevent same station selection
        function updateStationOptions() {
            const fromValue = fromStationSelect.value;
            const toValue = toStationSelect.value;
            
            // Enable all options first
            Array.from(fromStationSelect.options).forEach(option => option.disabled = false);
            Array.from(toStationSelect.options).forEach(option => option.disabled = false);
            
            // Disable selected values
            if (fromValue) {
                Array.from(toStationSelect.options).forEach(option => {
                    if (option.value === fromValue) {
                        option.disabled = true;
                    }
                });
            }
            
            if (toValue) {
                Array.from(fromStationSelect.options).forEach(option => {
                    if (option.value === toValue) {
                        option.disabled = true;
                    }
                });
            }
        }

        fromStationSelect.addEventListener('change', updateStationOptions);
        toStationSelect.addEventListener('change', updateStationOptions);

        // Initialize station options
        updateStationOptions();
    </script>
</body>
</html>