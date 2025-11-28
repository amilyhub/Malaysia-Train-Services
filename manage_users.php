<?php
// manage_users.php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: adminlogin.php");
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "train_booking");

// Handle actions
if (isset($_GET['action'])) {
    if ($_GET['action'] == 'delete' && isset($_GET['id'])) {
        $conn->query("DELETE FROM users WHERE id = " . intval($_GET['id']));
        header("Location: manage_users.php");
        exit();
    }
}

// Handle edit form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_user'])) {
    $user_id = intval($_POST['user_id']);
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    
    $update_query = "UPDATE users SET 
        name = '$name',
        email = '$email', 
        phone = '$phone'
        WHERE id = $user_id";
    
    if ($conn->query($update_query)) {
        $success_message = "User updated successfully!";
    } else {
        $error_message = "Error updating user: " . $conn->error;
    }
}

// Create sample data jika tiada
$check_users = $conn->query("SELECT COUNT(*) as count FROM users");
if ($check_users->fetch_assoc()['count'] == 0) {
    $conn->query("INSERT INTO users (name, email, password, phone, created_at) VALUES
        ('Lily Qistina', 'lily@email.com', 'password123', '0123456781', NOW()),
        ('Aya Inara', 'aya@email.com', 'password123', '0123456782', NOW()),
        ('Aiman Abid', 'aiman@email.com', 'password123', '0123456783', NOW()),
        ('Amir Ahnaf', 'amir@email.com', 'password123', '0123456784', NOW()),
        ('Monisha', 'monisha@email.com', 'password123', '0123456787', NOW()),
        ('Kritikha', 'kritikha@email.com', 'password123', '0123456788', NOW())");
}

// Get user for edit
$edit_user = null;
if (isset($_GET['edit_id'])) {
    $edit_id = intval($_GET['edit_id']);
    $edit_user = $conn->query("SELECT * FROM users WHERE id = $edit_id")->fetch_assoc();
}

// Query users
$users = $conn->query("SELECT * FROM users ORDER BY created_at DESC");
$total_users = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
$active_users = $conn->query("SELECT COUNT(*) as count FROM users WHERE email_verified IS NOT NULL")->fetch_assoc()['count'];
$new_users_today = $conn->query("SELECT COUNT(*) as count FROM users WHERE DATE(created_at) = CURDATE()")->fetch_assoc()['count'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Malaysia Train Services Admin</title>
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
        }

        .admin-container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            background: var(--white);
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
            padding: 20px 0;
            display: flex;
            flex-direction: column;
        }

        .company-header {
            text-align: center;
            margin-bottom: 32px;
            padding: 0 20px 24px;
            border-bottom: 2px solid var(--pastel-pink);
        }

        .company-header h1 {
            color: var(--text-dark);
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 8px;
            background: linear-gradient(135deg, var(--pink-accent) 0%, var(--green-accent) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .company-header .tagline {
            color: var(--text-light);
            font-size: 14px;
            font-weight: 400;
        }

        .admin-nav {
            flex: 1;
            padding: 0 20px;
        }

        .admin-nav ul {
            list-style: none;
        }

        .admin-nav li {
            margin-bottom: 8px;
        }

        .admin-nav a {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            color: var(--text-dark);
            text-decoration: none;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .admin-nav a:hover, .admin-nav a.active {
            background: linear-gradient(135deg, var(--pastel-pink) 0%, var(--pastel-green) 100%);
            color: var(--text-dark);
        }

        .admin-nav a i {
            margin-right: 10px;
            font-size: 18px;
        }

        .admin-footer {
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: var(--text-light);
            border-top: 1px solid var(--pastel-pink);
        }

        /* Main Content Styles */
        .main-content {
            flex: 1;
            padding: 30px;
            overflow-y: auto;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid var(--pastel-pink);
        }

        .page-title h2 {
            font-size: 28px;
            font-weight: 700;
            color: var(--text-dark);
        }

        .page-title p {
            color: var(--text-light);
            font-size: 16px;
        }

        .admin-actions {
            display: flex;
            gap: 15px;
        }

        .btn {
            padding: 10px 20px;
            border-radius: 10px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--pink-accent) 0%, var(--green-accent) 100%);
            color: var(--white);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 133, 162, 0.3);
        }

        .btn-secondary {
            background: var(--white);
            color: var(--text-dark);
            border: 1px solid var(--border-light);
        }

        .btn-secondary:hover {
            background: var(--pastel-pink);
        }

        /* Stats Cards */
        .stats-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: var(--white);
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            text-align: center;
            border-left: 4px solid var(--pink-accent);
        }

        .stat-card.active {
            border-left-color: var(--green-accent);
        }

        .stat-number {
            font-size: 32px;
            font-weight: 700;
            color: var(--pink-accent);
            margin-bottom: 8px;
        }

        .stat-card.active .stat-number {
            color: var(--green-accent);
        }

        .stat-label {
            color: var(--text-light);
            font-size: 14px;
            font-weight: 500;
        }

        /* Table Styles */
        .users-table-container {
            background: var(--white);
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            margin-bottom: 30px;
        }

        .table-header {
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--pastel-pink);
        }

        .table-title {
            font-size: 20px;
            font-weight: 600;
        }

        .table-controls {
            display: flex;
            gap: 15px;
        }

        .search-box {
            position: relative;
        }

        .search-box input {
            padding: 10px 15px 10px 40px;
            border: 1px solid var(--border-light);
            border-radius: 10px;
            width: 250px;
            font-size: 14px;
        }

        .search-box i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
        }

        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: linear-gradient(135deg, var(--pastel-pink) 0%, var(--pastel-green) 100%);
        }

        th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: var(--text-dark);
        }

        td {
            padding: 15px;
            border-bottom: 1px solid var(--pastel-pink);
        }

        tbody tr:hover {
            background-color: rgba(255, 214, 231, 0.2);
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .btn-action {
            padding: 6px 12px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-size: 12px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .btn-edit {
            background: var(--pastel-green);
            color: var(--text-dark);
        }

        .btn-edit:hover {
            background: var(--green-accent);
            color: var(--white);
        }

        .btn-delete {
            background: #ffd6e7;
            color: var(--text-dark);
        }

        .btn-delete:hover {
            background: #ff85a2;
            color: var(--white);
        }

        .checkbox-cell {
            width: 40px;
            text-align: center;
        }

        .bulk-actions {
            padding: 15px 20px;
            background: var(--pastel-pink);
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .bulk-actions select {
            padding: 8px 15px;
            border-radius: 8px;
            border: 1px solid var(--border-light);
            background: var(--white);
        }

        .pagination {
            display: flex;
            justify-content: center;
            padding: 20px;
            gap: 10px;
        }

        .pagination button {
            padding: 8px 15px;
            border: 1px solid var(--border-light);
            background: var(--white);
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .pagination button.active {
            background: linear-gradient(135deg, var(--pink-accent) 0%, var(--green-accent) 100%);
            color: var(--white);
            border-color: transparent;
        }

        .pagination button:hover:not(.active) {
            background: var(--pastel-pink);
        }

        /* Status Badges */
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-verified {
            background: var(--pastel-green);
            color: var(--green-accent);
        }

        .status-pending {
            background: #fff0f5;
            color: #ff85a2;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: var(--white);
            border-radius: 15px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .modal-header {
            padding: 20px;
            background: linear-gradient(135deg, var(--pastel-pink) 0%, var(--pastel-green) 100%);
            color: var(--text-dark);
            font-weight: 600;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            color: var(--text-dark);
        }

        .modal-body {
            padding: 20px;
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
            padding: 12px 15px;
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
            background-color: #fffdfe;
        }

        .modal-footer {
            padding: 15px 20px;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            border-top: 1px solid var(--pastel-pink);
        }

        /* Alert Messages */
        .alert {
            padding: 15px 20px;
            margin-bottom: 20px;
            border-radius: 10px;
            font-weight: 500;
        }

        .alert-success {
            background: var(--pastel-green);
            color: var(--green-accent);
            border: 1px solid var(--green-accent);
        }

        .alert-error {
            background: #ffd6e7;
            color: var(--pink-accent);
            border: 1px solid var(--pink-accent);
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .admin-container {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
                padding: 15px;
            }
            
            .admin-nav ul {
                display: flex;
                overflow-x: auto;
                padding-bottom: 10px;
            }
            
            .admin-nav li {
                margin-right: 10px;
                margin-bottom: 0;
            }
            
            .admin-nav a {
                white-space: nowrap;
            }
        }

        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .admin-actions {
                width: 100%;
                justify-content: space-between;
            }
            
            .table-header {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }
            
            .search-box input {
                width: 100%;
            }
            
            .table-controls {
                width: 100%;
                justify-content: space-between;
            }
            
            .stats-cards {
                grid-template-columns: 1fr;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="company-header">
                <h1>Malaysia Train Services</h1>
                <p class="tagline">Admin Dashboard</p>
            </div>
            
            <nav class="admin-nav">
                <ul>
                    <li><a href="admindashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
                    <li><a href="manage_users.php" class="active"><i class="fas fa-users"></i> Manage Users</a></li>
                    <li><a href="manage_trainschedule.php"><i class="fas fa-train"></i> Train Schedule</a></li>
                    <li><a href="manage_bookings.php"><i class="fas fa-ticket-alt"></i> Bookings</a></li>
                </ul>
            </nav>
            
            <div class="admin-footer">
                <p>© 2025 Malaysia Train Services</p>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <div class="page-header">
                <div class="page-title">
                    <h2>Manage Users</h2>
                    <p>View and manage user accounts</p>
                </div>
                
                <!-- Dua button Export dan Add New User telah dibuang -->
            </div>

            <!-- Statistics Cards -->
            <div class="stats-cards">
                <div class="stat-card">
                    <div class="stat-number"><?php echo $total_users; ?></div>
                    <div class="stat-label">Total Users</div>
                </div>
                <div class="stat-card active">
                    <div class="stat-number"><?php echo $active_users; ?></div>
                    <div class="stat-label">Active Users</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $new_users_today; ?></div>
                    <div class="stat-label">New Today</div>
                </div>
            </div>

            <!-- Success/Error Messages -->
            <?php if (isset($success_message)): ?>
                <div class="alert alert-success"><?php echo $success_message; ?></div>
            <?php endif; ?>
            
            <?php if (isset($error_message)): ?>
                <div class="alert alert-error"><?php echo $error_message; ?></div>
            <?php endif; ?>
            
            <div class="users-table-container">
                <div class="table-header">
                    <div class="table-title">User Accounts</div>
                    
                    <div class="table-controls">
                        <div class="search-box">
                            <i class="fas fa-search"></i>
                            <input type="text" placeholder="Search users...">
                        </div>
                        
                        <select>
                            <option>All Users</option>
                            <option>Verified</option>
                            <option>Unverified</option>
                        </select>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th class="checkbox-cell">
                                    <input type="checkbox" id="selectAll">
                                </th>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Created At</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($user = $users->fetch_assoc()): ?>
                            <tr>
                                <td class="checkbox-cell">
                                    <input type="checkbox" class="user-checkbox" value="<?php echo $user['id']; ?>">
                                </td>
                                <td><?php echo $user['id']; ?></td>
                                <td>
                                    <div style="font-weight: 600; color: var(--text-dark);"><?php echo htmlspecialchars($user['name']); ?></div>
                                </td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td><?php echo htmlspecialchars($user['phone']); ?></td>
                                <td>
                                    <div style="font-size: 12px;">
                                        <div><?php echo date('M j, Y', strtotime($user['created_at'])); ?></div>
                                        <div style="color: var(--text-light);"><?php echo date('H:i', strtotime($user['created_at'])); ?></div>
                                    </div>
                                </td>
                                <td>
                                    <?php 
                                    $status_class = $user['email_verified'] ? 'status-badge status-verified' : 'status-badge status-pending';
                                    $status_text = $user['email_verified'] ? 'Verified' : 'Pending';
                                    ?>
                                    <span class="<?php echo $status_class; ?>">
                                        <?php echo $status_text; ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="manage_users.php?edit_id=<?php echo $user['id']; ?>" 
                                           class="btn-action btn-edit">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <a href="manage_users.php?action=delete&id=<?php echo $user['id']; ?>" 
                                           class="btn-action btn-delete"
                                           onclick="return confirm('Are you sure you want to delete user <?php echo htmlspecialchars($user['name']); ?>?')">
                                            <i class="fas fa-trash"></i> Delete
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="bulk-actions">
                    <input type="checkbox" id="bulkSelect">
                    <label for="bulkSelect">Select all</label>
                    
                    <select>
                        <option>With selected:</option>
                        <option>Edit</option>
                        <option>Delete</option>
                        <option>Export</option>
                    </select>
                    
                    <button class="btn-action">Apply</button>
                </div>
                
                <div class="pagination">
                    <button class="active">1</button>
                    <button>2</button>
                    <button>3</button>
                    <button>Next</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Edit User Modal -->
    <?php if ($edit_user): ?>
    <div class="modal" id="userModal" style="display: flex;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Edit User</h3>
                <button class="modal-close" onclick="closeModal()">&times;</button>
            </div>
            
            <div class="modal-body">
                <form method="POST" action="manage_users.php">
                    <input type="hidden" name="user_id" value="<?php echo $edit_user['id']; ?>">
                    <input type="hidden" name="update_user" value="1">
                    
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($edit_user['name']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($edit_user['email']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($edit_user['phone']); ?>">
                    </div>
                    
                    <div class="modal-footer">
                        <a href="manage_users.php" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <script>
        function closeModal() {
            document.getElementById('userModal').style.display = 'none';
            window.location.href = 'manage_users.php';
        }

        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            const modal = document.getElementById('userModal');
            if (event.target === modal) {
                closeModal();
            }
        });

        // Select all checkbox functionality
        document.getElementById('selectAll').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.user-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        // Add animation to table rows
        document.addEventListener('DOMContentLoaded', function() {
            const tableRows = document.querySelectorAll('tbody tr');
            tableRows.forEach((row, index) => {
                row.style.animationDelay = `${index * 0.1}s`;
                row.style.opacity = '0';
                row.style.animation = 'fadeInUp 0.5s ease-out forwards';
            });
        });

        // Add CSS animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>
<?php $conn->close(); ?>