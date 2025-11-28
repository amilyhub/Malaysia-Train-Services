<?php
include 'db_connection.php';

class TrainSchedule {
    private $conn;
    
    public function __construct($connection) {
        $this->conn = $connection;
    }
    
    public function getAllSchedules() {
        $sql = "SELECT * FROM train_schedules WHERE status = 'active' ORDER BY service_type, departure_time";
        $result = $this->conn->query($sql);
        
        $schedules = [];
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $schedules[] = $row;
            }
        }
        return $schedules;
    }
    
    public function getSchedulesByService($service_type) {
        $stmt = $this->conn->prepare("SELECT * FROM train_schedules WHERE service_type = ? AND status = 'active' ORDER BY departure_time");
        $stmt->bind_param("s", $service_type);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $schedules = [];
        while($row = $result->fetch_assoc()) {
            $schedules[] = $row;
        }
        return $schedules;
    }
    
    public function searchSchedules($departure, $arrival) {
        $sql = "SELECT * FROM train_schedules 
                WHERE departure_station LIKE ? 
                AND arrival_station LIKE ? 
                AND status = 'active' 
                ORDER BY departure_time";
        
        $stmt = $this->conn->prepare($sql);
        $departure = "%$departure%";
        $arrival = "%$arrival%";
        $stmt->bind_param("ss", $departure, $arrival);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $schedules = [];
        while($row = $result->fetch_assoc()) {
            $schedules[] = $row;
        }
        return $schedules;
    }
    
    public function getServiceTypes() {
        $sql = "SELECT DISTINCT service_type FROM train_schedules WHERE status = 'active'";
        $result = $this->conn->query($sql);
        
        $services = [];
        while($row = $result->fetch_assoc()) {
            $services[] = $row['service_type'];
        }
        return $services;
    }
}

$trainSchedule = new TrainSchedule($conn);
?>