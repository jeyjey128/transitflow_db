<?php
include 'db.php'; 
$routes = [];
$stops = [];
$message = null;

// Fetch existing routes and stops for the dropdowns
$routes_query = "SELECT route_id, route_name FROM Routes ORDER BY route_name ASC";
$routes_result = $conn->query($routes_query);
if ($routes_result) {
    while($row = $routes_result->fetch_assoc()) { $routes[] = $row; }
}

$stops_query = "SELECT stop_id, stop_name FROM Stops ORDER BY stop_name ASC";
$stops_result = $conn->query($stops_query);
if ($stops_result) {
    while($row = $stops_result->fetch_assoc()) { $stops[] = $row; }
}

// Handle form submission
if (isset($_POST['submit_route_flow'])) {
    $route_id = (int)$_POST['route_id'];
    $stop_id = (int)$_POST['stop_id'];
    $stop_order = (int)$_POST['stop_order'];
    $estimated_time = empty($_POST['estimated_time']) ? 0 : (int)$_POST['estimated_time']; 

    // INSERT into Route_Stop_Order table
    $sql_insert = "INSERT INTO Route_Stop_Order (route_id, stop_id, stop_order, estimated_time) 
                   VALUES ($route_id, $stop_id, $stop_order, $estimated_time)";

    if ($conn->query($sql_insert) === TRUE) {
        $message = "<h3 style='color:green;'>✅ Stop added to Route Flow! Order #$stop_order defined.</h3>";
    } else {
        $message = "<h3 style='color:red;'>❌ Error: Stop already exists or Order conflict. " . $conn->error . "</h3>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TransitFlow Admin - Define Route Flow</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body>

    <header class="header">
        <h1>🛠️ Admin Dashboard</h1>
        <p>Route Flow Definition</p>
    </header>

    <main class="admin-container">
        <aside class="admin-sidebar">
            <h3>📂 Management Menu</h3>
            <ul>
                <li><a href="admin_add_route.php">Add Route</a></li>
                <li><a href="admin_add_stop.php">Add Stop</a></li>
                <li><a href="admin_define_route.php" class="active">Define Route Flow</a></li>
                <li><a href="admin_add_schedule.php">Add Schedule</a></li>
                <li><a href="admin_add_alert.php">Add Alert</a></li>
                <li><a href="index.php" class="exit-link">⬅ Exit to Main Page</a></li>
            </ul>
        </aside>
        
        <div class="admin-content">
            <section class="map-container" style="background: white; padding: 2rem; border-radius: 12px; box-shadow: var(--card-shadow);">
                <h3>🔄 Define Route Flow</h3>
                <form method="POST" action="admin_define_route.php">

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
                        <label for="stop_id">Select Stop</label>
                        <select id="stop_id" name="stop_id" required>
                            <option value="">-- Select a Stop --</option>
                            <?php foreach ($stops as $stop): ?>
                                <option value="<?php echo $stop['stop_id']; ?>">
                                    <?php echo htmlspecialchars($stop['stop_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="input-group">
                        <label for="stop_order">Stop Order</label>
                        <input type="number" id="stop_order" name="stop_order" min="1" required>
                    </div>

                    <div class="input-group">
                        <label for="estimated_time">Estimated Time (Minutes)</label>
                        <input type="number" id="estimated_time" name="estimated_time" min="0" value="0">
                    </div>
                    
                    <button type="submit" name="submit_route_flow" class="search-button" style="background-color: var(--success-green);">Add to Route Flow</button>
                </form>
            </section>
        </div>
    </main>
    
    <footer class="footer">
        &copy; <?php echo date('Y'); ?> TransitFlow Project
    </footer>

</body>
</html>