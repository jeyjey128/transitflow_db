<?php
include 'db.php';

if ($conn->connect_error) {
    echo '❌ Connection failed: ' . $conn->connect_error . PHP_EOL;
    exit(1);
}

echo '✅ Connected successfully to transitflow_db!' . PHP_EOL;
echo PHP_EOL;

// Show all tables
$result = $conn->query('SHOW TABLES');
if ($result && $result->num_rows > 0) {
    echo '📋 Tables in transitflow_db:' . PHP_EOL;
    while ($row = $result->fetch_array()) {
        echo '  • ' . $row[0] . PHP_EOL;
    }
    echo PHP_EOL;

    // Show structure for each table
    $result->data_seek(0); // Reset result pointer
    while ($row = $result->fetch_array()) {
        $tableName = $row[0];
        echo '📊 Table: ' . $tableName . PHP_EOL;

        // Get table structure
        $structureResult = $conn->query("DESCRIBE `$tableName`");
        if ($structureResult) {
            echo '  Columns:' . PHP_EOL;
            while ($col = $structureResult->fetch_assoc()) {
                $null = $col['Null'] === 'YES' ? 'NULL' : 'NOT NULL';
                $default = $col['Default'] !== NULL ? "DEFAULT '{$col['Default']}'" : '';
                $extra = $col['Extra'] ? $col['Extra'] : '';
                echo "    - {$col['Field']} {$col['Type']} {$null} {$default} {$extra}" . PHP_EOL;
            }
        }

        // Get row count
        $countResult = $conn->query("SELECT COUNT(*) as count FROM `$tableName`");
        if ($countResult) {
            $count = $countResult->fetch_assoc()['count'];
            echo '  Records: ' . $count . PHP_EOL;
        }

        echo PHP_EOL;
    }
} else {
    echo '📭 No tables found in the database.' . PHP_EOL;
    echo PHP_EOL;
    echo '💡 Tip: You may need to run your SQL file to create tables.' . PHP_EOL;
}

$conn->close();
?>