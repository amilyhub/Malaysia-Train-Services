<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Database configuration
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

try {
    // Connect to database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get POST data
    $input = json_decode(file_get_contents('php://input'), true);
    
    $full_name = $input['full_name'] ?? '';
    $email = $input['email'] ?? '';
    $phone = $input['phone'] ?? '';
    $password = $input['password'] ?? '';
    $terms_accepted = $input['terms_accepted'] ?? false;

    // Validation
    if (empty($full_name) || empty($email) || empty($phone) || empty($password)) {
        throw new Exception('All fields are required');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email format');
    }

    if (!$terms_accepted) {
        throw new Exception('Please accept terms and conditions');
    }

    // Check if email already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        throw new Exception('Email already registered');
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert into database
    $stmt = $pdo->prepare("INSERT INTO users (full_name, email, phone, password, created_at) VALUES (?, ?, ?, ?, NOW())");
    $stmt->execute([$full_name, $email, $phone, $hashed_password]);

    // Success response
    echo json_encode([
        'success' => true,
        'message' => 'Registration successful!',
        'user_id' => $pdo->lastInsertId()
    ]);

} catch (Exception $e) {
    // Error response
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>