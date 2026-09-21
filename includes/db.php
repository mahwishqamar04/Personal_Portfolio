<?php
/**
 * Database Configuration
 * Centralized database connection for the portfolio.
 *
 * Uses mysqli with prepared statements for security.
 *
 * Credential resolution order:
 * 1. Server environment variables
 * 2. .env file in project root
 * 3. Local XAMPP fallback values
 */

/**
 * Load environment variables from .env file if present.
 */
function loadEnvFile($path)
{
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

        // Skip invalid lines
        if (strpos($line, '=') === false) {
            continue;
        }

        list($key, $value) = explode('=', $line, 2);

        $key = trim($key);
        $value = trim($value);

        // Remove optional quotes around value
        if (
            strlen($value) >= 2 &&
            (
                ($value[0] === '"' && $value[strlen($value) - 1] === '"') ||
                ($value[0] === "'" && $value[strlen($value) - 1] === "'")
            )
        ) {
            $value = substr($value, 1, -1);
        }

        // Only set if not already defined by server environment
        if (!isset($_ENV[$key]) && !isset($_SERVER[$key])) {
            $_ENV[$key] = $value;
            $_SERVER[$key] = $value;
        }
    }
}

// Load .env from project root
loadEnvFile(__DIR__ . '/../.env');

// Database settings
define(
    'DB_HOST',
    isset($_ENV['DB_HOST'])
        ? $_ENV['DB_HOST']
        : (isset($_SERVER['DB_HOST']) ? $_SERVER['DB_HOST'] : 'localhost')
);

define(
    'DB_USER',
    isset($_ENV['DB_USER'])
        ? $_ENV['DB_USER']
        : (isset($_SERVER['DB_USER']) ? $_SERVER['DB_USER'] : 'root')
);

define(
    'DB_PASS',
    isset($_ENV['DB_PASS'])
        ? $_ENV['DB_PASS']
        : (isset($_SERVER['DB_PASS']) ? $_SERVER['DB_PASS'] : '')
);

define(
    'DB_NAME',
    isset($_ENV['DB_NAME'])
        ? $_ENV['DB_NAME']
        : (isset($_SERVER['DB_NAME']) ? $_SERVER['DB_NAME'] : 'portfolio_db')
);

define(
    'DB_PORT',
    isset($_ENV['DB_PORT'])
        ? (int) $_ENV['DB_PORT']
        : (isset($_SERVER['DB_PORT']) ? (int) $_SERVER['DB_PORT'] : 3307)
);

/**
 * Get a database connection instance.
 *
 * Returns mysqli connection or null on failure.
 */
function getDBConnection()
{
    static $conn = null;

    if ($conn !== null) {
        return $conn;
    }

    mysqli_report(MYSQLI_REPORT_OFF);

    $conn = new mysqli(
        DB_HOST,
        DB_USER,
        DB_PASS,
        DB_NAME,
        DB_PORT
    );

    if ($conn->connect_error) {
        error_log('Database connection failed: ' . $conn->connect_error);
        $conn = null;
        return null;
    }

    $conn->set_charset('utf8mb4');

    return $conn;
}
?>