<?php
/**
 * Database Configuration
 * Centralized database connection for the portfolio.
 * Uses mysqli with prepared statements for security.
 *
 * CREDENTIAL RESOLUTION ORDER:
 *   1. Server environment variables (set in hosting control panel)
 *   2. .env file in project root (parsed by loadEnvFile below)
 *   3. Fallback defaults at the bottom (only suitable for local XAMPP)
 *
 * TO CHANGE CREDENTIALS (for any host):
 *   Edit the .env file in the project root. No other files need changing.
 *
 * INFINITYFREE SETUP:
 *   1. Create a MySQL database in VistaPanel.
 *   2. Edit .env with the host, username, password, and database name
 *      provided by InfinityFree.
 *   3. Upload the updated .env file to the project root.
 */

/**
 * Load environment variables from .env file if present.
 * Environment variables already set on the server take priority.
 */
function loadEnvFile($path) {
    if (!file_exists($path) || !is_readable($path)) {
        return;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        // Skip comments and empty lines
        if ($line === '' || strpos($line, '#') === 0) {
            continue;
        }
        // Only set if not already defined in server environment
        if (strpos($line, '=') === false) {
            continue;
        }
        list($key, $value) = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);
        if (!isset($_ENV[$key]) && !isset($_SERVER[$key])) {
            $_ENV[$key] = $value;
            $_SERVER[$key] = $value;
        }
    }
}

// Load .env from project root (one level above /includes)
loadEnvFile(__DIR__ . '/../.env');

// -----------------------------------------------------------------------
// Resolve credentials: server env vars → .env file → fallback defaults.
// The fallback defaults below are for LOCAL XAMPP ONLY.
// On InfinityFree (or any other host), the .env file MUST contain the
// correct DB_HOST, DB_USER, DB_PASS, and DB_NAME values.
// -----------------------------------------------------------------------
define('DB_HOST', isset($_ENV['DB_HOST']) ? $_ENV['DB_HOST'] : (isset($_SERVER['DB_HOST']) ? $_SERVER['DB_HOST'] : 'localhost'));
define('DB_USER', isset($_ENV['DB_USER']) ? $_ENV['DB_USER'] : (isset($_SERVER['DB_USER']) ? $_SERVER['DB_USER'] : 'root'));
define('DB_PASS', isset($_ENV['DB_PASS']) ? $_ENV['DB_PASS'] : (isset($_SERVER['DB_PASS']) ? $_SERVER['DB_PASS'] : ''));
define('DB_NAME', isset($_ENV['DB_NAME']) ? $_ENV['DB_NAME'] : (isset($_SERVER['DB_NAME']) ? $_SERVER['DB_NAME'] : 'portfolio_db'));

/**
 * Get a database connection instance.
 * Returns mysqli connection or null on failure.
 * Detailed errors are logged server-side only; callers receive null.
 */
function getDBConnection() {
    static $conn = null;
    
    if ($conn !== null) {
        return $conn;
    }
    
    mysqli_report(MYSQLI_REPORT_OFF);
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if ($conn->connect_error) {
        error_log('Database connection failed: ' . $conn->connect_error);
        $conn = null;
        return null;
    }
    
    $conn->set_charset('utf8mb4');
    return $conn;
}
?>
