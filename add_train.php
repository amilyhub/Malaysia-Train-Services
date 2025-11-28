<?php
include 'config.php';

header('Content-Type: application/json');

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $train_name = $conn->real_escape_string($_POST['train_name']);
    $departure_station = $conn->real_escape_string($_POST['departure_station']);
    $arrival_station = $conn->real_escape_string($_POST['arrival_station']);
    
    $sql = "INSERT INTO trains (train_name, departure_station, arrival_sta) 
            VALUES ('$train_name', '$departure_station', '$arrival_station')";
    
    if($conn->query($sql) === TRUE) {
        echo json_encode(["success" => "Train added successfully"]);
    } else {
        echo json_encode(["error" => "Error adding train: " . $conn->error]);
    }
} else {
    echo json_encode(["error" => "Invalid request method"]);
}

$conn->close();
?>