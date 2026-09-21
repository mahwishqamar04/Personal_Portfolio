<?php
// Final diagnostic: Confirm port 3307 is the fix
echo "=== PORT DIAGNOSTIC ===\n";

mysqli_report(MYSQLI_REPORT_OFF);

// Test 1: Default port 3306 (what the code currently uses)
echo "\nTest 1: localhost:3306 (default) with empty password\n";
$c1 = @new mysqli('localhost', 'root', '');
if ($c1->connect_error) {
    echo "  FAILED (errno " . $c1->connect_errno . "): " . $c1->connect_error . "\n";
} else {
    echo "  SUCCESS on port 3306\n";
    $c1->close();
}

// Test 2: Explicit port 3307 (what my.ini says)
echo "\nTest 2: localhost:3307 with empty password\n";
$c2 = @new mysqli('localhost', 'root', '', '', 3307);
if ($c2->connect_error) {
    echo "  FAILED (errno " . $c2->connect_errno . "): " . $c2->connect_error . "\n";
} else {
    echo "  SUCCESS on port 3307!\n";
    echo "  MySQL version: " . $c2->server_info . "\n";
    
    // Check databases
    $r = $c2->query("SHOW DATABASES LIKE 'portfolio_db'");
    echo "  portfolio_db exists: " . ($r && $r->num_rows > 0 ? 'YES' : 'NO') . "\n";
    
    if ($r && $r->num_rows > 0) {
        $c2->select_db('portfolio_db');
        $tables = $c2->query("SHOW TABLES FROM portfolio_db");
        if ($tables && $tables->num_rows > 0) {
            $tableNames = [];
            while ($t = $tables->fetch_row()) $tableNames[] = $t[0];
            echo "  Tables: " . implode(', ', $tableNames) . "\n";
            
            $cols = $c2->query("DESCRIBE contact_messages");
            if ($cols) {
                echo "  contact_messages columns:\n";
                while ($col = $cols->fetch_assoc()) {
                    echo "    - " . $col['Field'] . " (" . $col['Type'] . ")\n";
                }
            } else {
                echo "  contact_messages: DOES NOT EXIST\n";
            }
        } else {
            echo "  portfolio_db has NO tables\n";
        }
    }
    $c2->close();
}

// Test 3: Using "localhost:3307" in host string (how db.php could parse it)
echo "\nTest 3: 'localhost:3307' as host string with mysqli\n";
$c3 = @new mysqli('localhost:3307', 'root', '');
if ($c3->connect_error) {
    echo "  FAILED: " . $c3->connect_error . "\n";
} else {
    echo "  SUCCESS - host:port in string works!\n";
    $c3->close();
}

echo "\n=== END PORT DIAGNOSTIC ===\n";
