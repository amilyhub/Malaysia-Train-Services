<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'train_booking';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = new mysqli($host, $username, $password, $database);
    
    if (!$conn->connect_error) {
        $current_password = $_POST['current_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        
        // Get current password hash
        $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        
        if (!$user || !password_verify($current_password, $user['password'])) {
            $error = 'Current password is incorrect';
        } elseif (strlen($new_password) < 8) {
            $error = 'New password must be at least 8 characters long';
        } elseif ($new_password !== $confirm_password) {
            $error = 'New passwords do not match';
        } else {
            // Update password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $update_stmt->bind_param("si", $hashed_password, $_SESSION['user_id']);
            
            if ($update_stmt->execute()) {
                $success = 'Password changed successfully!';
            } else {
                $error = 'Failed to change password: ' . $conn->error;
            }
            $update_stmt->close();
        }
        $stmt->close();
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password - Malaysia Train Services</title>
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

        .password-container {
            background: var(--white);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(255, 133, 162, 0.15);
            padding: 40px;
            width: 100%;
            max-width: 500px;
            border: 1px solid var(--border-light);
            position: relative;
        }

        .password-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--pink-accent) 0%, var(--green-accent) 100%);
        }

        .header {
            text-align: center;
            margin-bottom: 32px;
            padding-bottom: 24px;
            border-bottom: 2px solid var(--pastel-pink);
        }

        .header h1 {
            color: var(--text-dark);
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
            background: linear-gradient(135deg, var(--pink-accent) 0%, var(--green-accent) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .password-form {
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
        }

        .btn {
            padding: 14px 24px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            margin-right: 10px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--pink-accent) 0%, var(--green-accent) 100%);
            color: white;
        }

        .btn-secondary {
            background: var(--pastel-pink);
            color: var(--text-dark);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .success-message {
            background: linear-gradient(135deg, var(--pastel-green) 0%, #e8f5e8 100%);
            color: var(--text-dark);
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            margin-bottom: 20px;
            border: 1px solid var(--green-accent);
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

        .password-strength {
            height: 4px;
            background: var(--pastel-pink);
            border-radius: 2px;
            margin-top: 5px;
            overflow: hidden;
        }

        .password-strength-fill {
            height: 100%;
            width: 0%;
            background: var(--green-accent);
            transition: all 0.3s ease;
        }

        @media (max-width: 480px) {
            .password-container {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="password-container">
        <div class="header">
            <h1>Change Password</h1>
            <p class="tagline">Secure your account</p>
        </div>

        <?php if ($success): ?>
            <div class="success-message">
                ✅ <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="error-message">
                ❌ <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form class="password-form" method="POST" action="">
            <div class="form-group">
                <label for="current_password">Current Password</label>
                <input 
                    type="password" 
                    class="form-control" 
                    id="current_password" 
                    name="current_password"
                    required
                >
            </div>

            <div class="form-group">
                <label for="new_password">New Password</label>
                <input 
                    type="password" 
                    class="form-control" 
                    id="new_password" 
                    name="new_password"
                    required
                >
                <div class="password-strength">
                    <div class="password-strength-fill" id="passwordStrength"></div>
                </div>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm New Password</label>
                <input 
                    type="password" 
                    class="form-control" 
                    id="confirm_password" 
                    name="confirm_password"
                    required
                >
            </div>

            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <button type="submit" class="btn btn-primary">
                    🔒 Change Password
                </button>
                <a href="profile.php" class="btn btn-secondary">
                    ← Back to Profile
                </a>
            </div>
        </form>
    </div>

    <script>
        // Password strength indicator
        document.getElementById('new_password').addEventListener('input', function(e) {
            const password = e.target.value;
            const strengthFill = document.getElementById('passwordStrength');
            let strength = 0;
            
            if (password.length >= 8) strength += 25;
            if (/[A-Z]/.test(password)) strength += 25;
            if (/[0-9]/.test(password)) strength += 25;
            if (/[^A-Za-z0-9]/.test(password)) strength += 25;
            
            strengthFill.style.width = strength + '%';
            strengthFill.style.background = strength < 50 ? '#ff6b8b' : strength < 75 ? '#ffa726' : '#7ecf9b';
        });
    </script>
</body>
</html>