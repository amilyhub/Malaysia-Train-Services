<?php
session_start();
include '../../db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if we have confirmation
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['confirm_payment'])) {
    header("Location: confirm_payment.php");
    exit();
}

// Check if payment data exists in session
if (!isset($_SESSION['payment_data'])) {
    header("Location: payment.php");
    exit();
}

$payment_data = $_SESSION['payment_data'];
$user_id = $_SESSION['user_id'];

try {
    $conn->begin_transaction();
    
    // Generate booking reference
    $booking_reference = 'BK' . date('YmdHis') . rand(100, 999);
    
    // 1. Insert into bookings table
    $booking_sql = "INSERT INTO bookings (
        user_id, train_id, passenger_name, passenger_email, passenger_phone, 
        travel_date, number_of_seats, total_fare, payment_method, 
        from_station, to_station, class, booking_status, booking_reference
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'confirmed', ?)";
    
    $booking_stmt = $conn->prepare($booking_sql);
    $booking_stmt->bind_param(
        "iissssidsssss", 
        $user_id,
        $payment_data['train_id'],
        $payment_data['passenger_name'],
        $payment_data['passenger_email'],
        $payment_data['passenger_phone'],
        $payment_data['travel_date'],
        $payment_data['number_of_seats'],
        $payment_data['total_fare'],
        $payment_data['payment_method'],
        $payment_data['from_station'],
        $payment_data['to_station'],
        $payment_data['train_class'],
        $booking_reference
    );
    $booking_stmt->execute();
    $booking_id = $conn->insert_id;
    
    // 2. Insert into payments table
    $payment_status = 'completed';
    $transaction_id = 'TXN' . date('YmdHis') . rand(1000, 9999);
    
    $payment_sql = "INSERT INTO payments (
        booking_id, amount, payment_method, payment_status, transaction_id, payment_date
    ) VALUES (?, ?, ?, ?, ?, NOW())";
    
    $payment_stmt = $conn->prepare($payment_sql);
    $payment_stmt->bind_param(
        "idsss",
        $booking_id,
        $payment_data['total_fare'],
        $payment_data['payment_method'],
        $payment_status,
        $transaction_id
    );
    $payment_stmt->execute();
    $payment_id = $conn->insert_id;
    
    // 3. Update booking with payment_id
    $update_booking_sql = "UPDATE bookings SET payment_id = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_booking_sql);
    $update_stmt->bind_param("ii", $payment_id, $booking_id);
    $update_stmt->execute();
    
    // 4. Update available seats in trains table
    $update_seats_sql = "UPDATE trains SET available_seats = available_seats - ? WHERE id = ?";
    $update_seats_stmt = $conn->prepare($update_seats_sql);
    $update_seats_stmt->bind_param("ii", $payment_data['number_of_seats'], $payment_data['train_id']);
    $update_seats_stmt->execute();
    
    $conn->commit();
    
    // Store success data in session
    $_SESSION['payment_success'] = true;
    $_SESSION['booking_reference'] = $booking_reference;
    $_SESSION['transaction_id'] = $transaction_id;
    $_SESSION['booking_id'] = $booking_id;
    
    // Clear payment data
    unset($_SESSION['payment_data']);
    
    // Redirect to success page
    header("Location: payment_success.php");
    exit();
    
} catch (Exception $e) {
    $conn->rollback();
    
    // Store error in session and redirect back
    $_SESSION['payment_error'] = "Payment failed: " . $e->getMessage();
    header("Location: confirm_payment.php");
    exit();
}

$conn->close();
?>