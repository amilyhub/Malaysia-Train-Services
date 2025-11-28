<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'train_booking';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Create connection
    $conn = new mysqli($host, $username, $password, $database);
    
    // Check connection
    if ($conn->connect_error) {
        $error = "Database connection failed: " . $conn->connect_error;
    } else {
        // Get form data
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $user_password = 'password123';
        $phone = trim($_POST['phone'] ?? '');
        
        // Validation
        if (empty($name) || empty($email)) {
            $error = 'Name and email are required';
        } else {
            // Check if email already exists
            $check_sql = "SELECT id FROM users WHERE email = ?";
            $check_stmt = $conn->prepare($check_sql);
            $check_stmt->bind_param("s", $email);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();
            
            if ($check_result->num_rows > 0) {
                $error = 'Email already exists';
            } else {
                $sql = "INSERT INTO users (name, email, password, phone) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                
                if ($stmt) {
                    $stmt->bind_param("ssss", $name, $email, $user_password, $phone);
                    
                    if ($stmt->execute()) {
                        $success = 'Registration successful! Redirecting to login...';
                        echo "<script>
                            setTimeout(function() {
                                window.location.href = 'login.php';
                            }, 1500);
                        </script>";
                    } else {
                        $error = 'Registration failed: ' . $conn->error;
                    }
                    $stmt->close();
                } else {
                    $error = 'Database error: ' . $conn->error;
                }
            }
            $check_stmt->close();
        }
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Malaysia Train Services</title>
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
            color: var(--text-dark);
        }

        .register-container {
            background: var(--white);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(255, 133, 162, 0.15);
            padding: 40px 35px;
            width: 100%;
            max-width: 450px;
            border: 1px solid var(--border-light);
            position: relative;
            overflow: hidden;
        }

        .register-container::before {
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
            font-size: 28px;
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

        .login-form {
            margin-bottom: 24px;
        }

        .form-group {
            margin-bottom: 24px;
            position: relative;
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
            background-color: #fffdfe;
        }

        .form-control::placeholder {
            color: #c4a8b7;
            font-style: italic;
        }

        .btn-register {
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
            margin-top: 8px;
            position: relative;
            overflow: hidden;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 133, 162, 0.3);
        }

        .login-section {
            text-align: center;
            padding-top: 24px;
            border-top: 1px solid var(--pastel-pink);
            color: var(--text-light);
            font-size: 14px;
        }

        .login-section a {
            color: var(--pink-accent);
            text-decoration: none;
            font-weight: 600;
            margin-left: 4px;
            transition: color 0.3s ease;
        }

        .login-section a:hover {
            color: var(--green-accent);
            text-decoration: underline;
        }

        .quick-actions {
            margin-top: 32px;
            padding: 24px;
            background: linear-gradient(135deg, var(--pastel-pink) 0%, var(--pastel-green) 100%);
            border-radius: 15px;
            border: 1px solid var(--border-light);
        }

        .quick-actions h3 {
            color: var(--text-dark);
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 16px;
            text-align: center;
        }

        .action-links {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .action-links a {
            color: var(--text-dark);
            text-decoration: none;
            font-size: 14px;
            padding: 10px 16px;
            background: var(--white);
            border-radius: 10px;
            transition: all 0.3s ease;
            border: 1px solid transparent;
            text-align: center;
        }

        .action-links a:hover {
            background: var(--pastel-pink);
            border-color: var(--pink-accent);
            transform: translateX(5px);
        }

        .error-message {
            background: #ffe6e6;
            color: #ff6b8b;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #ff6b8b;
            margin-bottom: 20px;
            text-align: center;
            font-size: 14px;
        }

        .success-message {
            background: #e6ffe6;
            color: #4CAF50;
            padding: 15px;
            border-radius: 10px;
            border: 2px solid #4CAF50;
            margin-bottom: 20px;
            text-align: center;
            font-size: 16px;
            font-weight: 600;
        }

        @media (max-width: 480px) {
            .register-container {
                padding: 30px 20px;
                border-radius: 15px;
            }
            
            .company-header h1 {
                font-size: 24px;
            }
            
            .quick-actions {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="company-header">
            <h1>Malaysia Train Services</h1>
            <p class="tagline">Create Your Account</p>
        </div>

        <!-- Success Message -->
        <?php if ($success): ?>
            <div class="success-message">
                ✅ <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>

        <!-- Error Message -->
        <?php if ($error): ?>
            <div class="error-message">
                ❌ <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form class="login-form" method="POST" action="">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input 
                    type="text" 
                    class="form-control" 
                    id="name" 
                    name="name"
                    placeholder="Enter your full name"
                    value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>"
                    required
                >
            </div>
            
            <div class="form-group">
                <label for="email">Email Address</label>
                <input 
                    type="email" 
                    class="form-control" 
                    id="email" 
                    name="email"
                    placeholder="Enter your email"
                    value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                    required
                >
            </div>
            
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input 
                    type="tel" 
                    class="form-control" 
                    id="phone" 
                    name="phone"
                    placeholder="Enter your phone number"
                    value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>"
                >
            </div>
            
            <button type="submit" class="btn-register">Register</button>
        </form>

        <div class="login-section">
            Already have an account? 
            <a href="login.php">Login here</a>
        </div>

        <div class="quick-actions">
            <h3>Quick Actions</h3>
            <div class="action-links">
                <a href="trainschedule.php">View Train Schedule</a>
                <a href="login.php">Back to Login</a>
                <a href="support.php">Contact Support</a>
            </div>
        </div>
    </div>

    <script>
        document.querySelector('form').addEventListener('submit', function(e) {
            const registerBtn = document.querySelector('.btn-register');
            
            // Show loading state
            registerBtn.innerHTML = 'Creating Account...';
            registerBtn.disabled = true;
        });

        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });
    </script>
</body>
</html>