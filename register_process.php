<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Simple registration process (you should add proper validation and database insertion)
if ($_POST) {
    $full_name = $_POST['full_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Simple validation
    if (!empty($full_name) && !empty($email) && !empty($password)) {
        // In a real application, you would:
        // 1. Hash the password
        // 2. Insert into database
        // 3. Send confirmation email
        
        // For demo purposes, just set session and redirect
        $_SESSION['user_id'] = 1;
        $_SESSION['user_name'] = $full_name;
        $_SESSION['user_email'] = $email;
        
        header('Location: index.php');
        exit();
    } else {
        header('Location: register.php?error=1');
        exit();
    }
} else {
    header('Location: register.php');
    exit();
}
?>