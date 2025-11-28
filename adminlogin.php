<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // Koneksi database
    $conn = new mysqli("localhost", "root", "", "train_booking");
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $sql = "SELECT * FROM admin WHERE email = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_email'] = $admin['email'];
        $_SESSION['role'] = $admin['role'];
        
        header("Location: admindashboard.php");
        exit();
    } else {
        $error = "Invalid admin email or password!";
    }
    
    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Malaysia Train Services</title>
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

        .login-container {
            background: var(--white);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(255, 133, 162, 0.15);
            padding: 40px 35px;
            width: 100%;
            max-width: 420px;
            border: 1px solid var(--border-light);
            position: relative;
            overflow: hidden;
        }

        .login-container::before {
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

        .admin-badge {
            background: linear-gradient(135deg, var(--pastel-pink) 0%, var(--pastel-green) 100%);
            color: var(--text-dark);
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            display: inline-block;
            margin-top: 8px;
            border: 1px solid var(--border-light);
        }

        .login-form {
            margin-bottom: 0;
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

        .btn-login {
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
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 133, 162, 0.3);
        }

        .error-message {
            background: #fff5f7;
            color: #ff6b8b;
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            border-left: 4px solid #ff6b8b;
            font-size: 14px;
            font-weight: 500;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="company-header">
            <h1>Malaysia Train Services</h1>
            <p class="tagline">Admin Panel Login</p>
            <div class="admin-badge">Administrator Access</div>
        </div>

        <?php if (isset($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>

        <form class="login-form" method="POST" action="">
            <div class="form-group">
                <label for="email">Admin Email</label>
                <input 
                    type="email" 
                    class="form-control" 
                    id="email" 
                    name="email"
                    value="admin@mts.com"
                    required
                >
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input 
                    type="password" 
                    class="form-control" 
                    id="password" 
                    name="password"
                    value="admin123"
                    required
                >
            </div>
            
            <button type="submit" class="btn-login">Login to Admin Panel</button>
        </form>
    </div>
</body>
</html>