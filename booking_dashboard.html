<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Management - Malaysia Train Services</title>
    <style>
        :root {
            --pastel-pink: #ffd6e7;       /* Soft pink */
            --pastel-green: #d6f5e3;      /* Soft green */
            --pink-accent: #ff85a2;       /* Pink untuk button & accent */
            --green-accent: #7ecf9b;      /* Green untuk hover state */
            --text-dark: #4a4a4a;         /* Text color */
            --text-light: #888888;        /* Text secondary */
            --border-light: #e8d4e0;      /* Border color */
            --white: #ffffff;
            --background: #fef7fa;        /* Light pink background */
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
            padding: 20px;
        }

        /* Header */
        .dashboard-header {
            background: var(--white);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(255, 133, 162, 0.15);
            padding: 30px;
            margin-bottom: 25px;
            border: 1px solid var(--border-light);
            position: relative;
            overflow: hidden;
        }

        .dashboard-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--pink-accent) 0%, var(--green-accent) 100%);
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
            font-size: 16px;
            font-weight: 400;
        }

        /* Stats Cards */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
        }

        .stat-card {
            background: var(--white);
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(255, 133, 162, 0.1);
            border: 1px solid var(--border-light);
            text-align: center;
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-number {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 8px;
            background: linear-gradient(135deg, var(--pink-accent) 0%, var(--green-accent) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .stat-label {
            color: var(--text-light);
            font-size: 14px;
            font-weight: 500;
        }

        /* Filter Section */
        .filter-section {
            background: var(--white);
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 5px 15px rgba(255, 133, 162, 0.1);
            border: 1px solid var(--border-light);
        }

        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
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

        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, var(--pink-accent) 0%, var(--green-accent) 100%);
            color: var(--white);
            border: none;
            border-radius: 10px;
            padding: 12px 24px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 133, 162, 0.3);
        }

        .btn-outline {
            background: transparent;
            color: var(--pink-accent);
            border: 2px solid var(--pink-accent);
            border-radius: 10px;
            padding: 10px 20px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-outline:hover {
            background: var(--pink-accent);
            color: var(--white);
        }

        /* Table */
        .table-container {
            background: var(--white);
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(255, 133, 162, 0.1);
            border: 1px solid var(--border-light);
            overflow: hidden;
        }

        .table-header {
            display: flex;
            justify-content: between;
            align-items: center;
            margin-bottom: 20px;
        }

        .table-title {
            font-size: 20px;
            font-weight: 600;
            color: var(--text-dark);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: linear-gradient(135deg, var(--pastel-pink) 0%, var(--pastel-green) 100%);
            color: var(--text-dark);
            font-weight: 600;
            padding: 15px;
            text-align: left;
            font-size: 14px;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid var(--border-light);
            font-size: 14px;
        }

        tr:hover {
            background-color: var(--pastel-pink);
        }

        /* Status Badges */
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-confirmed {
            background: #d1edff;
            color: #0c5460;
        }

        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .btn-sm {
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 12px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-view {
            background: var(--pastel-green);
            color: var(--text-dark);
        }

        .btn-edit {
            background: var(--pastel-pink);
            color: var(--text-dark);
        }

        .btn-cancel {
            background: #f8d7da;
            color: #721c24;
        }

        /* Quick Actions */
        .quick-actions {
            margin-top: 25px;
            padding: 25px;
            background: linear-gradient(135deg, var(--pastel-pink) 0%, var(--pastel-green) 100%);
            border-radius: 15px;
            border: 1px solid var(--border-light);
        }

        .quick-actions h3 {
            color: var(--text-dark);
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 16px;
            text-align: center;
        }

        .action-links {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 12px;
        }

        .action-links a {
            color: var(--text-dark);
            text-decoration: none;
            font-size: 14px;
            padding: 12px;
            background: var(--white);
            border-radius: 10px;
            transition: all 0.3s ease;
            border: 1px solid transparent;
            text-align: center;
            font-weight: 500;
        }

        .action-links a:hover {
            background: var(--pastel-pink);
            border-color: var(--pink-accent);
            transform: translateY(-2px);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .stats-container {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .filter-grid {
                grid-template-columns: 1fr;
            }
            
            .table-container {
                overflow-x: auto;
            }
            
            .action-buttons {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="dashboard-header">
        <div class="company-header">
            <h1>Malaysia Train Services</h1>
            <p class="tagline">Booking Management Dashboard</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-container">
        <div class="stat-card">
            <div class="stat-number">156</div>
            <div class="stat-label">Total Bookings</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">124</div>
            <div class="stat-label">Confirmed</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">18</div>
            <div class="stat-label">Pending</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">14</div>
            <div class="stat-label">Cancelled</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">RM 12,458</div>
            <div class="stat-label">Total Revenue</div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <div class="filter-grid">
            <div class="form-group">
                <label>Booking Reference</label>
                <input type="text" class="form-control" placeholder="Search reference...">
            </div>
            <div class="form-group">
                <label>Passenger Name</label>
                <input type="text" class="form-control" placeholder="Search passenger...">
            </div>
            <div class="form-group">
                <label>Status</label>
                <select class="form-control">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
            <div class="form-group">
                <label>From Date</label>
                <input type="date" class="form-control">
            </div>
            <div class="form-group">
                <label>To Date</label>
                <input type="date" class="form-control">
            </div>
            <div class="form-group">
                <button class="btn-primary" style="width: 100%">
                    <i class="fas fa-search"></i> Filter
                </button>
            </div>
        </div>
    </div>

    <!-- Bookings Table -->
    <div class="table-container">
        <div class="table-header">
            <div class="table-title">Recent Bookings</div>
            <button class="btn-primary">+ New Booking</button>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Booking Ref</th>
                    <th>Passenger</th>
                    <th>Route</th>
                    <th>Travel Date</th>
                    <th>Seats</th>
                    <th>Class</th>
                    <th>Fare</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>TR20241201ABC</strong></td>
                    <td>
                        <div>Ahmad bin Ali</div>
                        <small style="color: var(--text-light)">ahmad@email.com</small>
                    </td>
                    <td>KL Sentral → Butterworth</td>
                    <td>15 Dec 2024</td>
                    <td>2</td>
                    <td>Economy</td>
                    <td><strong>RM 120.00</strong></td>
                    <td><span class="status-badge status-confirmed">Confirmed</span></td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn-sm btn-view">View</button>
                            <button class="btn-sm btn-edit">Edit</button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><strong>TR20241201DEF</strong></td>
                    <td>
                        <div>Siti Nurhaliza</div>
                        <small style="color: var(--text-light)">siti@email.com</small>
                    </td>
                    <td>JB Sentral → KL Sentral</td>
                    <td>18 Dec 2024</td>
                    <td>1</td>
                    <td>First Class</td>
                    <td><strong>RM 85.00</strong></td>
                    <td><span class="status-badge status-pending">Pending</span></td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn-sm btn-view">View</button>
                            <button class="btn-sm btn-edit">Edit</button>
                            <button class="btn-sm btn-cancel">Cancel</button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><strong>TR20241130XYZ</strong></td>
                    <td>
                        <div>Raj Kumar</div>
                        <small style="color: var(--text-light)">raj@email.com</small>
                    </td>
                    <td>Ipoh → Penang</td>
                    <td>12 Dec 2024</td>
                    <td>4</td>
                    <td>Business</td>
                    <td><strong>RM 320.00</strong></td>
                    <td><span class="status-badge status-cancelled">Cancelled</span></td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn-sm btn-view">View</button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions">
        <h3>Quick Actions</h3>
        <div class="action-links">
            <a href="new_booking.html">Create New Booking</a>
            <a href="schedule.html">View Train Schedule</a>
            <a href="reports.html">Generate Reports</a>
            <a href="support.html">Customer Support</a>
        </div>
    </div>

    <script>
        // Add some interactive effects
        document.querySelectorAll('.stat-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });

        // Simple filter functionality
        document.querySelectorAll('.btn-sm').forEach(button => {
            button.addEventListener('click', function() {
                const action = this.textContent.toLowerCase();
                const bookingRef = this.closest('tr').querySelector('strong').textContent;
                alert(`🎀 ${action} booking: ${bookingRef}`);
            });
        });

        // New booking button
        document.querySelector('.btn-primary').addEventListener('click', function() {
            alert('🎀 Opening new booking form...');
        });
    </script>
</body>
</html>