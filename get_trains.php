<?php
include 'config.php';

// Set headers
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");

// Start timing for performance measurement
$start_time = microtime(true);

try {
    // Optimized query - only select needed columns
    $sql = "SELECT 
                id, 
                train_name, 
                train_number, 
                source_station, 
                destination_station,
                TIME_FORMAT(departure_time, '%H:%i') as departure_time,
                TIME_FORMAT(arrival_time, '%H:%i') as arrival_time,
                total_seats,
                available_seats,
                fare,
                status,
                DATE_FORMAT(created_at, '%Y-%m-%d %H:%i') as created_at
            FROM trains 
            WHERE status = 'active' 
            ORDER BY departure_time, train_name";

    $result = $conn->query($sql);
    
    if (!$result) {
        throw new Exception("Query failed: " . $conn->error);
    }
    
    $trains = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $trains[] = $row;
        }
    }
    
    // Calculate execution time
    $end_time = microtime(true);
    $execution_time = round(($end_time - $start_time) * 1000, 2);
    
    // Log performance
    error_log("get_trains.php executed in: {$execution_time}ms, returned: " . count($trains) . " trains");
    
    // Success response
    $response = [
        'success' => true,
        'data' => $trains,
        'performance' => [
            'execution_time_ms' => $execution_time,
            'records_count' => count($trains),
            'timestamp' => date('Y-m-d H:i:s')
        ]
    ];
    
    echo json_encode($response);
    
} catch(Exception $e) {
    // Error response
    $end_time = microtime(true);
    $execution_time = round(($end_time - $start_time) * 1000, 2);
    
    error_log("❌ get_trains.php ERROR: " . $e->getMessage());
    
    echo json_encode([
        'success' => false,
        'error' => 'Failed to fetch trains',
        'message' => $e->getMessage(),
        'performance' => [
            'execution_time_ms' => $execution_time,
            'timestamp' => date('Y-m-d H:i:s')
        ]
    ]);
}

$conn->close();
?>