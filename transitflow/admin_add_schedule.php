<?php
include 'db.php'; 
$routes = [];
$message = null;

// Fetch existing routes for the dropdown
$routes_query = "SELECT route_id, route_name FROM Routes ORDER BY route_name ASC";
$routes_result = $conn->query($routes_query);
if ($routes_result) {
    while($row = $routes_result->fetch_assoc()) { $routes[] = $row; }
}

// Handle form submission
if (isset($_POST['submit_trip'])) {
    $route_id = (int)$_POST['route_id'];
    $departure_time = $conn->real_escape_string($_POST['departure_time']);
    $day_of_week = $conn->real_escape_string($_POST['day_of_week']);

    // SQL INSERT statement for the Trips table
    $sql = "INSERT INTO Trips (route_id, departure_time, day_of_week) 
            VALUES ($route_id, '$departure_time', '$day_of_week')";

    if ($conn->query($sql) === TRUE) {
        $message = "<h3 style='color:green;'>✅ New Trip Schedule added! Route ID: $route_id at $departure_time.</h3>";
    } else {
        $message = "<h3 style='color:red;'>❌ Error adding trip: " . $conn->error . "</h3>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TransitFlow Admin - Add Schedule</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body>

    <header class="header">
        <h1>🛠️ Admin Dashboard</h1>
        <p>Trip Schedule Definition</p>
    </header>

    <main class="admin-container">
        <aside class="admin-sidebar">
            <h3>📂 Management Menu</h3>
            <ul>
                <li><a href="admin_add_route.php">Add Route</a></li>
                <li><a href="admin_add_stop.php">Add Stop</a></li>
                <li><a href="admin_define_route.php">Define Route Flow</a></li>
                <li><a href="admin_add_schedule.php" class="active">Add Schedule</a></li>
                <li><a href="admin_add_alert.php">Add Alert</a></li>
                <li><a href="index.php" class="exit-link">⬅ Exit to Main Page</a></li>
            </ul>
        </aside>
        
        <div class="admin-content">
            <section class="map-container" style="background: white; padding: 2rem; border-radius: 12px; box-shadow: var(--card-shadow);">
                <h3>⏰ Add Trip Schedule</h3>
                <form method="POST" action="admin_add_schedule.php">

                    <div class="input-group">
                        <label for="route_id">Select Route</label>
                        <select id="route_id" name="route_id" required>
                            <option value="">-- Select a Route --</option>
                            <?php foreach ($routes as $route): ?>
                                <option value="<?php echo $route['route_id']; ?>">
                                    <?php echo htmlspecialchars($route['route_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="input-group">
                        <label for="departure_time">Departure Time</label>
                        <input type="time" id="departure_time" name="departure_time" required>
                    </div>

                    <div class="input-group">
                        <label for="day_of_week">Days Active</label>
                        <select id="day_of_week" name="day_of_week" required>
                            <option value="Daily">Daily</option>
                            <option value="Weekdays">Weekdays (Mon-Fri)</option>
                            <option value="Weekend">Weekend (Sat-Sun)</option>
                            <option value="Monday">Monday Only</option>
                            <option value="Tuesday">Tuesday Only</option>
                            <option value="Wednesday">Wednesday Only</option>
                            <option value="Thursday">Thursday Only</option>
                            <option value="Friday">Friday Only</option>
                            <option value="Saturday">Saturday Only</option>
                            <option value="Sunday">Sunday Only</option>
                        </select>
                    </div>
                    
                    <button type="submit" name="submit_trip" class="search-button" style="background-color: var(--success-green);">Save Schedule</button>
                </form>
            </section>
        </div>
    </main>    <footer class="footer">
        &copy; <?php echo date('Y'); ?> TransitFlow Project
    </footer>

</body>
</html>