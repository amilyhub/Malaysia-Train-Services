<?php
session_start();
include 'db_connection.php';

$trains = $conn->query("SELECT * FROM trains ORDER BY departure_time");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Train Schedule</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="container">
            <div class="header-content">
                <div class="logo">Train<span>Booking</span></div>
                <nav>
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="booking.php">Book Tickets</a></li>
                        <li><a href="trains.php" class="active">Train Schedule</a></li>
                        <li><a href="login.php">Login</a></li>
                        <li><a href="admin/admin_login.php">Admin</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <main>
        <div class="container">
            <h2 style="text-align: center; color: #ff85a2;">Train Schedule</h2>
            
            <div class="schedule-grid">
                <?php while($train = $trains->fetch_assoc()): ?>
                    <div class="schedule-card">
                        <h4><?= $train['train_name'] ?></h4>
                        <div class="route"><?= $train['source_station'] ?> → <?= $train['destination_station'] ?></div>
                        <p>Departure: <?= date('h:i A', strtotime($train['departure_time'])) ?></p>
                        <p>Arrival: <?= date('h:i A', strtotime($train['arrival_time'])) ?></p>
                        <p>Fare: RM <?= number_format($train['fare'], 2) ?></p>
                        <p>Available: <?= $train['available_seats'] ?> seats</p>
                        <a href="booking.php" class="btn">Book Now</a>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </main>

    <footer>
        <div class="container">
            <div class="copyright">
                <p>&copy; 2025 Online Train Booking System</p>
            </div>
        </div>
    </footer>
</body>
</html>
<?php $conn->close(); ?>