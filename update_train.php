<?php
include 'config.php';

header('Content-Type: application/json');

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $conn->real_escape_string($_POST['id']);
    $train_name = $conn->real_escape_string($_POST['train_name']);
    $departure_station = $conn->real_escape_string($_POST['departure_station']);
    $arrival_station = $conn->real_escape_string($_POST['arrival_station']);
    
    $sql = "UPDATE trains SET 
            train_name = '$train_name',
            departure_station = '$departure_station',
            arrival_sta = '$arrival_station'
            WHERE id = $id";
    
    if($conn->query($sql) === TRUE) {
        echo json_encode(["success" => "Train updated successfully"]);
    } else {
        echo json_encode(["error" => "Error updating train: " . $conn->error]);
    }
} else {
    echo json_encode(["error" => "Invalid request method"]);
}

$conn->close();
?>