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

// Get current user data
$conn = new mysqli($host, $username, $password, $database);
$user_data = [];
if (!$conn->connect_error) {
    $stmt = $conn->prepare("SELECT name, email, phone FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_data = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    
    if (empty($name)) {
        $error = 'Name is required';
    } else {
        $update_sql = "UPDATE users SET name = ?, phone = ? WHERE id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("ssi", $name, $phone, $_SESSION['user_id']);
        
        if ($stmt->execute()) {
            $_SESSION['user_name'] = $name;
            $success = 'Profile updated successfully!';
            // Refresh user data
            $user_data['name'] = $name;
            $user_data['phone'] = $phone;
        } else {
            $error = 'Failed to update profile: ' . $conn->error;
        }
        $stmt->close();
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - Malaysia Train Services</title>
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

        .edit-container {
            background: var(--white);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(255, 133, 162, 0.15);
            padding: 40px;
            width: 100%;
            max-width: 500px;
            border: 1px solid var(--border-light);
            position: relative;
        }

        .edit-container::before {
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

        .header .tagline {
            color: var(--text-light);
            font-size: 16px;
        }

        .edit-form {
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

        @media (max-width: 480px) {
            .edit-container {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="edit-container">
        <div class="header">
            <h1>Edit Profile</h1>
            <p class="tagline">Update your personal information</p>
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

        <form class="edit-form" method="POST" action="">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input 
                    type="text" 
                    class="form-control" 
                    id="name" 
                    name="name"
                    value="<?php echo htmlspecialchars($user_data['name'] ?? ''); ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input 
                    type="email" 
                    class="form-control" 
                    id="email" 
                    value="<?php echo htmlspecialchars($user_data['email'] ?? ''); ?>"
                    disabled
                    style="background-color: #f8f9fa;"
                >
                <small style="color: var(--text-light); font-size: 12px; margin-top: 5px; display: block;">
                    Email cannot be changed
                </small>
            </div>

            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input 
                    type="tel" 
                    class="form-control" 
                    id="phone" 
                    name="phone"
                    value="<?php echo htmlspecialchars($user_data['phone'] ?? ''); ?>"
                    placeholder="Enter your phone number"
                >
            </div>

            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <button type="submit" class="btn btn-primary">
                    💾 Save Changes
                </button>
                <a href="profile.php" class="btn btn-secondary">
                    ← Cancel
                </a>
            </div>
        </form>
    </div>
</body>
</html>