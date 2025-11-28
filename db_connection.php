<?php
$host = "localhost";
$user = "root"; 
$password = "";
$database = "train_booking";

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>