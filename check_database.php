<?php
$host = 'localhost';
$dbname = 'mts_database';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    echo "✅ Database connection SUCCESS!";
    
    // Check if table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
    if ($stmt->rowCount() > 0) {
        echo "✅ Table 'users' EXISTS!";
    } else {
        echo "❌ Table 'users' NOT FOUND!";
    }
    
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>