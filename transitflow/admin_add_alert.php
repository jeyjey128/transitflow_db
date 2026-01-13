<?php
include 'db.php'; 
$routes = [];
$message = null;

// Fetch routes for the dropdown
$routes_query = "SELECT route_id, route_name FROM Routes ORDER BY route_name ASC";
$routes_result = $conn->query($routes_query);
if ($routes_result) {
    while($row = $routes_result->fetch_assoc()) { $routes[] = $row; }
}

// Handle alert submission
if (isset($_POST['submit_alert'])) {
    $route_id = (int)$_POST['route_id'];
    $alert_type = $conn->real_escape_string($_POST['alert_type']);
    $alert_message = $conn->real_escape_string($_POST['alert_message']);
    $start_time = $conn->real_escape_string($_POST['start_time']);
    $end_time = !empty($_POST['end_time']) ? "'" . $conn->real_escape_string($_POST['end_time']) . "'" : "NULL";

    $sql = "INSERT INTO Alerts (route_id, alert_type, alert_message, start_time, end_time) 
            VALUES ($route_id, '$alert_type', '$alert_message', '$start_time', $end_time)";

    if ($conn->query($sql) === TRUE) {
        $message = "✅ Alert issued successfully! Type: $alert_type.";
    } else {
        $message = "❌ Error: " . $conn->error;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TransitFlow Admin - Add Alert</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body>

    <header class="header">
        <h1>🛠️ Admin Dashboard</h1>
        <p>Service Alert & System Management</p>
    </header>

    <main class="admin-container">
        <aside class="admin-sidebar">
            <h3>📂 Management Menu</h3>
            <ul>
                <li><a href="admin_add_route.php">Add Route</a></li>
                <li><a href="admin_add_stop.php">Add Stop</a></li>
                <li><a href="admin_define_route.php">Define Route Flow</a></li>
                <li><a href="admin_add_schedule.php">Add Schedule</a></li>
                <li><a href="admin_add_alert.php" class="active">Add Alert</a></li>
                <li><a href="index.php" class="exit-link">⬅ Exit to Main Page</a></li>
            </ul>
        </aside>
        
        <div class="admin-content">
            <section class="map-container" style="background: white; padding: 2rem; border-radius: 12px; box-shadow: var(--card-shadow);">
                <h3>🚨 Issue Service Alert</h3>
                <form method="POST" action="admin_add_alert.php">
                    <div class="input-group">
                        <label for="route_id">Route Affected</label>
                        <select id="route_id" name="route_id">
                            <option value="0">General Alert (All Routes)</option>
                            <?php foreach ($routes as $route): ?>
                                <option value="<?php echo $route['route_id']; ?>">
                                    <?php echo htmlspecialchars($route['route_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="input-group">
                        <label for="alert_type">Alert Type</label>
                        <select id="alert_type" name="alert_type" required>
                            <option value="Delay">Delay</option>
                            <option value="Closure">Route Closure</option>
                            <option value="Emergency">Emergency</option>
                        </select>
                    </div>

                    <div class="input-group">
                        <label for="alert_message">Message</label>
                        <textarea id="alert_message" name="alert_message" rows="3" style="width: 100%; padding: 0.75rem; border-radius: 8px; border: 2px solid #e2e8f0;" required></textarea>
                    </div>

                    <div class="input-group">
                        <label for="start_time">Start Time</label>
                        <input type="datetime-local" id="start_time" name="start_time" style="width: 100%; padding: 0.75rem; border-radius: 8px; border: 2px solid #e2e8f0;" required>
                    </div>

                    <div class="input-group">
                        <label for="end_time">End Time</label>
                        <input type="datetime-local" id="end_time" name="end_time" style="width: 100%; padding: 0.75rem; border-radius: 8px; border: 2px solid #e2e8f0;">
                    </div>

                    <button type="submit" name="submit_alert" class="search-button" style="background-color: var(--danger-red);">Post Alert</button>
                </form>
            </section>
        </div>
    </main>
    
    <footer class="footer">
        &copy; <?php echo date('Y'); ?> TransitFlow Project
    </footer>

</body>
</html>