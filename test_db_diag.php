<?php
// Diagnostic test - DB connection and table check
echo "=== DATABASE DIAGNOSTIC ===\n";

// Test 1: Can we connect to MySQL?
mysqli_report(MYSQLI_REPORT_OFF);
$conn = new mysqli('localhost', 'root', '', 'portfolio_db');

if ($conn->connect_error) {
    echo "DB CONNECTION FAILED: " . $conn->connect_error . "\n";
    exit(1);
}

echo "DB CONNECTION: OK\n";
echo "DB NAME: " . $conn->server_info . "\n";

// Test 2: Does contact_messages table exist?
$result = $conn->query("SHOW TABLES LIKE 'contact_messages'");
if ($result && $result->num_rows > 0) {
    echo "TABLE contact_messages: EXISTS\n";
    
    // Test 3: Check columns
    $cols = $conn->query("DESCRIBE contact_messages");
    echo "COLUMNS:\n";
    while ($row = $cols->fetch_assoc()) {
        echo "  - " . $row['Field'] . " | " . $row['Type'] . " | Null:" . $row['Null'] . " | Key:" . $row['Key'] . " | Default:" . $row['Default'] . "\n";
    }
} else {
    echo "TABLE contact_messages: DOES NOT EXIST\n";
}

$conn->close();
echo "\n=== END DB DIAGNOSTIC ===\n";
