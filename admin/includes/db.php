<?php
declare(strict_types=1);

/**
 * Admin database connection.
 * Reuses the existing project database connection.
 */

require_once dirname(__DIR__, 2) . '/includes/db.php';

$db = getDBConnection();

if (!$db) {
    die('Database connection failed.');
}

$db->set_charset('utf8mb4');
