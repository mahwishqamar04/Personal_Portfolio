<?php
// Diagnostic: Test various MySQL connection scenarios
echo "=== MYSQL CONNECTION TESTS ===\n";

mysqli_report(MYSQLI_REPORT_OFF);

// Test 1: root with empty password (what .env says)
echo "\nTest 1: root / (empty password)\n";
$c1 = new mysqli('localhost', 'root', '', 'portfolio_db');
if ($c1->connect_error) {
    echo "  FAILED: " . $c1->connect_error . "\n";
} else {
    echo "  SUCCESS - connected to: " . $c1->server_info . "\n";
    $c1->close();
}

// Test 2: root with common XAMPP passwords
$passwords = ['', 'root', 'password', 'mysql', '12345', 'xampp'];
foreach ($passwords as $pw) {
    $label = $pw === '' ? '(empty)' : $pw;
    echo "\nTest: root / $label\n";
    $c = new mysqli('localhost', 'root', $pw);
    if ($c->connect_error) {
        echo "  FAILED: " . $c->connect_error . "\n";
    } else {
        echo "  SUCCESS - MySQL version: " . $c->server_info . "\n";
        // Check if portfolio_db exists
        $r = $c->query("SHOW DATABASES LIKE 'portfolio_db'");
        echo "  portfolio_db exists: " . ($r && $r->num_rows > 0 ? 'YES' : 'NO') . "\n";
        if ($r && $r->num_rows > 0) {
            $c->select_db('portfolio_db');
            $tables = $c->query("SHOW TABLES FROM portfolio_db");
            echo "  Tables in portfolio_db: ";
            if ($tables && $tables->num_rows > 0) {
                $tableNames = [];
                while ($t = $tables->fetch_row()) {
                    $tableNames[] = $t[0];
                }
                echo implode(', ', $tableNames) . "\n";
                
                // Check contact_messages columns
                $cols = $c->query("DESCRIBE contact_messages");
                if ($cols) {
                    echo "  contact_messages columns: ";
                    $colNames = [];
                    while ($col = $cols->fetch_assoc()) {
                        $colNames[] = $col['Field'] . '(' . $col['Type'] . ')';
                    }
                    echo implode(', ', $colNames) . "\n";
                } else {
                    echo "  contact_messages: TABLE DOES NOT EXIST\n";
                }
            } else {
                echo "(no tables)\n";
            }
        }
        $c->close();
        break; // Stop after first success
    }
}

echo "\n=== END MYSQL TESTS ===\n";
