<?php
include 'db.php'; 

// 1. Get Origin and Destination IDs from the URL
$origin_id = (int)($_GET['origin_id'] ?? 0);
$destination_id = (int)($_GET['destination_id'] ?? 0);

$results = [];
$origin_name = "Selected Origin";
$destination_name = "Selected Destination";
$current_time = date('H:i:s');
$current_day = date('l'); 

// --- Check for Same Origin and Destination ---
$is_same_location = ($origin_id > 0 && $origin_id == $destination_id);

// --- Fetch Stop Names for Display (only if not a same-location error) ---
if (!$is_same_location) {
    $stop_names_query = "SELECT stop_id, stop_name FROM Stops WHERE stop_id IN ($origin_id, $destination_id)";
    $stop_names_result = $conn->query($stop_names_query);
    if ($stop_names_result) {
        while ($row = $stop_names_result->fetch_assoc()) {
            if ($row['stop_id'] == $origin_id) {
                $origin_name = htmlspecialchars($row['stop_name']);
            }
            if ($row['stop_id'] == $destination_id) {
                $destination_name = htmlspecialchars($row['stop_name']);
            }
        }
    }
}

// --- 2. Advanced SQL Query: Find the Next Available Trip (Only runs if locations are different) ---
if (!$is_same_location) {
    $sql = "
    SELECT 
        r.route_name,
        t.departure_time,
        rso1.estimated_time AS origin_time,
        rso2.estimated_time AS destination_time
    FROM Routes r
    JOIN Route_Stop_Order rso1 ON r.route_id = rso1.route_id
    JOIN Route_Stop_Order rso2 ON r.route_id = rso2.route_id
    JOIN Trips t ON r.route_id = t.route_id
    WHERE 
        rso1.stop_id = $origin_id AND rso2.stop_id = $destination_id
        AND rso1.stop_order < rso2.stop_order
        AND t.departure_time > '$current_time'
        AND (
            t.day_of_week = 'Daily' 
            OR (t.day_of_week = 'Weekdays' AND DAYOFWEEK(CURDATE()) BETWEEN 2 AND 6)
            OR (t.day_of_week = 'Weekend' AND DAYOFWEEK(CURDATE()) IN (1, 7))
            OR (t.day_of_week = '$current_day')
        )
    ORDER BY t.departure_time ASC
    LIMIT 5;
    ";

    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $results[] = $row;
        }
    }
}

$conn->close();

// Helper function to calculate estimated arrival time
function calculateArrivalTime($departureTime, $destinationTime) {
    $dep_timestamp = strtotime($departureTime);
    $arr_timestamp = strtotime("+" . $destinationTime . " minutes", $dep_timestamp);
    return date("h:i A", $arr_timestamp);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TransitFlow Search Results</title>
    <link rel="stylesheet" href="style.css"> 
    <style>
        /* Specific Result Card Styling */
        .results-section {
            grid-column: 1 / -1; 
        }
        .result-card { 
            /* ... (keep your existing result-card styling from previous turns) ... */
            background: #ffffff; 
            padding: 25px; 
            border-radius: 12px;
            border-left: 6px solid #007bff; 
            margin-bottom: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: transform 0.2s;
        }
        .no-results, .error-message {
            padding: 40px;
            background-color: #f8d7da; 
            color: #721c24;
            border: 1px solid #f5c6cb;
            border-radius: 8px;
            text-align: center;
            font-size: 1.1em;
            margin-bottom: 30px;
        }
        /* Specific style for the same-location error */
        .error-message.same-location {
            background-color: #fff3cd; /* Yellow tint */
            color: #856404; /* Darker yellow/brown text */
            border-left: 5px solid #ffc107;
        }
    </style>
</head>
<body>
    <header class="header">
        <h1>🔍 Schedule Search</h1>
        <p>Results for **<?php echo $origin_name; ?>** to **<?php echo $destination_name; ?>**</p>
    </header>

    <main class="container">
        
        <section class="results-section">

            <?php if ($is_same_location): ?>
                
                <div class="error-message same-location">
                    <h2>⚠️ Cannot Search Trip</h2>
                    <p>The **Origin** and **Destination** stops cannot be the same. Please select a different destination to proceed with your search.</p>
                </div>

            <?php elseif (!empty($results)): ?>
                
                <h2>Next 5 Available Trips Today:</h2>
                
                <?php foreach ($results as $trip): ?>
                    <?php 
                        $formatted_departure = date("h:i A", strtotime($trip['departure_time']));
                        $estimated_arrival = calculateArrivalTime($trip['departure_time'], $trip['destination_time']);
                        $diff = strtotime($trip['departure_time']) - time();
                        $status_class = ($diff > 0 && $diff < 900) ? 'immediate' : ''; 
                        $status_text = ($diff > 0 && $diff < 900) ? 'DEPARTING SOON' : 'UPCOMING';
                    ?>
                    <div class="result-card">
                        <div class="result-info">
                            <h3>
                                🚌 <?php echo htmlspecialchars($trip['route_name']); ?>
                                <span class="status-badge <?php echo $status_class; ?>">
                                    <?php echo $status_text; ?>
                                </span>
                            </h3>
                            <p>⏳ Estimated Travel Time: ~<?php echo $trip['destination_time']; ?> minutes</p>
                        </div>
                        <div class="time-box">
                            <div class="departure-label">Next Departure</div>
                            <div class="departure"><?php echo $formatted_departure; ?></div>
                            <div class="arrival">Arrival: <?php echo $estimated_arrival; ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>

            <?php else: ?>
                <div class="no-results">
                    <h2>😞 No Available Trips Found</h2>
                    <p>There are no scheduled trips for this route today, or the route flow has not been defined yet in the admin panel.</p>
                </div>
            <?php endif; ?>

            <div style="margin-top: 30px;">
                <a href="index.php" class="search-button" style="background-color: #6c757d;">
                    ← Start New Search
                </a>
            </div>

        </section>

    </main>
    
    <footer class="footer">
        &copy; <?php echo date('Y'); ?> TransitFlow Project
        <div class="admin-link">
            <a href="admin_add_route.php">Admin Panel</a>
        </div>
    </footer>
</body>
</html>