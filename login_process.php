<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Simple login process (you should add proper authentication)
if ($_POST) {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Simple demo authentication
    if (!empty($email) && !empty($password)) {
        // In a real application, you would:
        // 1. Check against database
        // 2. Verify password hash
        // 3. Set proper user session
        
        $_SESSION['user_id'] = 1;
        $_SESSION['user_name'] = 'Demo User';
        $_SESSION['user_email'] = $email;
        
        header('Location: index.php');
        exit();
    } else {
        header('Location: login.php?error=1');
        exit();
    }
} else {
    header('Location: login.php');
    exit();
}
?>