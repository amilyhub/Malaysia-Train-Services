<?php
session_start();
require_once 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$user = [];
$bookings = [];

try {
    // Get user information
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Get booking history with train details
    $stmt = $pdo->prepare("
        SELECT b.*, t.train_name, t.departure_station, t.arrival_station, t.departure_time
        FROM bookings b 
        JOIN trains t ON b.train_id = t.id 
        WHERE b.user_id = ? 
        ORDER BY b.booking_date DESC
    ");
    $stmt->execute([$user_id]);
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $error = "Database error: " . $e->getMessage();
}

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    try {
        $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, phone = ? WHERE id = ?");
        $stmt->execute([$name, $email, $phone, $user_id]);
        $success = "Profile updated successfully!";
        
        // Update session
        $_SESSION['user_name'] = $name;
        $user['name'] = $name;
        $user['email'] = $email;
        $user['phone'] = $phone;
        
    } catch (PDOException $e) {
        $error = "Error updating profile: " . $e->getMessage();
    }
}
?>

<!-- KEEP THE EXISTING HTML/CSS -->