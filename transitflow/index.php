<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TransitFlow: Schedule & Alerts</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        /* Layout para magkatabi ang Search/Alerts at ang Map */
        .main-container { display: flex; flex-wrap: wrap; gap: 20px; padding: 20px; max-width: 1200px; margin: auto; }
        .side-panel { flex: 1; min-width: 300px; }
        .map-panel { flex: 1.5; min-width: 350px; }
        .card { background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); margin-bottom: 20px; }
        #map { height: 450px; width: 100%; border-radius: 10px; }
        
        /* Alert Box styling */
        .alert-item { background: #fff4e5; border-left: 5px solid #ffa500; padding: 15px; border-radius: 4px; }
    </style>
</head>
<body>
    <main class="main-container">
        <div class="side-panel">
            <section class="card">
                <h2>📍 Find Your Trip</h2>
                <form action="search.php" method="GET">
                    <label>Departure Station</label>
                    <select name="origin_id" style="width:100%; padding:10px; margin:10px 0;" required>
                        <?php
                        $stops = $conn->query("SELECT * FROM stops");
                        while($row = $stops->fetch_assoc()) {
                            echo "<option value='".$row['stop_id']."'>".$row['stop_name']."</option>";
                        }
                        ?>
                    </select>
                    <label>Arrival Station</label>
                    <select name="destination_id" style="width:100%; padding:10px; margin:10px 0;" required>
                        <?php $stops->data_seek(0); while($row = $stops->fetch_assoc()) { echo "<option value='".$row['stop_id']."'>".$row['stop_name']."</option>"; } ?>
                    </select>
                    <button type="submit" style="width:100%; padding:12px; background:#27ae60; color:white; border:none; border-radius:5px; cursor:pointer;">Check Available Buses</button>
                </form>
            </section>

            <section class="card">
                <h3>⚠️ Service Alerts</h3>
                <div class="alert-item">
                    <strong>Normal Service:</strong> No major delays or closures reported at this time.
                </div>
            </section>
        </div>

        <div class="map-panel">
            <section class="card">
                <h3>🗺️ Local Route Map</h3>
                <div id="map"></div>
                <p style="font-size: 0.85rem; color: #64748b; margin-top: 10px;">Interactive map showing active routes and stop locations.</p>
            </section>
        </div>
    </main>

    <footer style="text-align: center; padding: 20px;">
        &copy; 2026 TransitFlow Project | <a href="admin_add_route.php">Admin Dashboard</a>
    </footer>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // I-set ang view sa Tacloban/Campetic
        var map = L.map('map').setView([11.2421, 125.0037], 13); 
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        // Marker para sa Campetic at Tacloban
        L.marker([11.2052, 124.9961]).addTo(map).bindPopup('Campetic Station');
        L.marker([11.2421, 125.0037]).addTo(map).bindPopup('Tacloban Terminal');
    </script>
</body>
</html>