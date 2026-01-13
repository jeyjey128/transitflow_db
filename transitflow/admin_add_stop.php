<?php
include 'db.php'; 
$message = null;

if (isset($_POST['submit_stop'])) {
    $stopName = $conn->real_escape_string($_POST['stop_name']);
    $lat = $conn->real_escape_string($_POST['latitude']);
    $lng = $conn->real_escape_string($_POST['longitude']);

    // SQL INSERT statement for the Stops table
    $sql = "INSERT INTO Stops (stop_name, latitude, longitude) 
            VALUES ('$stopName', '$lat', '$lng')";

    if ($conn->query($sql) === TRUE) {
        $message = "<h3 style='color:green;'>✅ Stop '$stopName' added successfully! ID: " . $conn->insert_id . "</h3>";
    } else {
        $message = "<h3 style='color:red;'>❌ Error: " . $conn->error . "</h3>";
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TransitFlow Admin - Add Stop</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body>

    <header class="header">
        <h1>🛠️ Admin Dashboard</h1>
        <p>Stop Location Management</p>
    </header>

    <main class="admin-container">
        <aside class="admin-sidebar">
            <h3>📂 Management Menu</h3>
            <ul>
                <li><a href="admin_add_route.php">Add Route</a></li>
                <li><a href="admin_add_stop.php" class="active">Add Stop</a></li>
                <li><a href="admin_define_route.php">Define Route Flow</a></li>
                <li><a href="admin_add_schedule.php">Add Schedule</a></li>
                <li><a href="admin_add_alert.php">Add Alert</a></li>
                <li><a href="index.php" class="exit-link">⬅ Exit to Main Page</a></li>
            </ul>
        </aside>
        
        <div class="admin-content">
            <section class="map-container" style="background: white; padding: 2rem; border-radius: 12px; box-shadow: var(--card-shadow);">
                <h3>📍 Add New Stop</h3>
                <form method="POST" action="admin_add_stop.php">
                    <div class="input-group">
                        <label for="stop_name">Stop Name</label>
                        <input type="text" id="stop_name" name="stop_name" required>
                    </div>
                    
                    <div class="input-group">
                        <label for="latitude">Latitude</label>
                        <input type="text" id="latitude" name="latitude" required>
                    </div>

                    <div class="input-group">
                        <label for="longitude">Longitude</label>
                        <input type="text" id="longitude" name="longitude" required>
                    </div>
                    
                    <button type="submit" name="submit_stop" class="search-button" style="background-color: var(--success-green);">Save Stop</button>
                </form>
            </section>
        </div>
    </main>
    
    <footer class="footer">
        &copy; <?php echo date('Y'); ?> TransitFlow Project
    </footer>

</body>
</html>