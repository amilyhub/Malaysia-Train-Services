<?php
include 'config.php';

header('Content-Type: application/json');

if(isset($_GET['id'])) {
    // Get single train by ID
    $id = $conn->real_escape_string($_GET['id']);
    $sql = "SELECT * FROM trains WHERE id = $id";
    $result = $conn->query($sql);
    
    if($result->num_rows > 0) {
        echo json_encode($result->fetch_assoc());
    } else {
        echo json_encode(["error" => "Train not found"]);
    }
} else {
    // Get ALL trains (no ID provided)
    $sql = "SELECT * FROM trains";
    $result = $conn->query($sql);
    
    $trains = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $trains[] = $row;
        }
    }
    
    echo json_encode($trains);
}

$conn->close();
?>