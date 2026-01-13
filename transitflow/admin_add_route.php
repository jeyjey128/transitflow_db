<?php
include 'db.php'; 
$message = null;

if (isset($_POST['submit_route'])) {
    $routeName = $conn->real_escape_string($_POST['route_name']);
    $origin = $conn->real_escape_string($_POST['origin']);
    $destination = $conn->real_escape_string($_POST['destination']);

    // SQL INSERT statement for the Routes table
    $sql = "INSERT INTO Routes (route_name, origin, destination) 
            VALUES ('$routeName', '$origin', '$destination')";

    if ($conn->query($sql) === TRUE) {
        $message = "<h3 style='color:green;'>✅ Route '$routeName' added successfully! ID: " . $conn->insert_id . "</h3>";
    } else {
        // This usually happens if the route name already exists and you have a UNIQUE constraint
        $message = "<h3 style='color:red;'>❌ Error adding route: " . $conn->error . "</h3>";
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TransitFlow Admin - Add Route</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body>

    <header class="header">
        <h1>🛠️ Admin Dashboard</h1>
        <p>Route Management</p>
    </header>

    <main class="admin-container">
        <aside class="admin-sidebar">
            <h3>📂 Management Menu</h3>
            <ul>
                <li><a href="admin_add_route.php" class="active">Add Route</a></li>
                <li><a href="admin_add_stop.php">Add Stop</a></li>
                <li><a href="admin_define_route.php">Define Route Flow</a></li>
                <li><a href="admin_add_schedule.php">Add Schedule</a></li>
                <li><a href="admin_add_alert.php">Add Alert</a></li>
                <li><a href="index.php" class="exit-link">⬅ Exit to Main Page</a></li>
            </ul>
        </aside>
        
        <div class="admin-content">
            <section class="map-container" style="background: white; padding: 2rem; border-radius: 12px; box-shadow: var(--card-shadow);">
                <h3>🛤️ Add New Route</h3>
                <form method="POST" action="admin_add_route.php">
                    <div class="input-group">
                        <label for="route_name">Route Name</label>
                        <input type="text" id="route_name" name="route_name" required>
                    </div>
                    
                    <div class="input-group">
                        <label for="origin">Origin</label>
                        <input type="text" id="origin" name="origin" required>
                    </div>

                    <div class="input-group">
                        <label for="destination">Destination</label>
                        <input type="text" id="destination" name="destination" required>
                    </div>

                    <button type="submit" name="submit_route" class="search-button" style="background-color: var(--success-green);">Save Route</button>
                </form>
            </section>
        </div>
    </main>
    
    <footer class="footer">
        &copy; <?php echo date('Y'); ?> TransitFlow Project
    </footer>

</body>
</html>