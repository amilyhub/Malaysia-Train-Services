<?php
include 'config.php';

header('Content-Type: application/json');

if(isset($_GET['id'])) {
    $id = $conn->real_escape_string($_GET['id']);
    $sql = "DELETE FROM trains WHERE id = $id";
    
    if($conn->query($sql) === TRUE) {
        echo json_encode(["success" => "Train deleted successfully"]);
    } else {
        echo json_encode(["error" => "Error deleting train: " . $conn->error]);
    }
} else {
    echo json_encode(["error" => "No ID provided"]);
}

$conn->close();
?>