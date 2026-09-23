<?php
declare(strict_types=1);

/**
 * Admin session and authentication helpers.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_name('PORTFOLIO_ADMIN_SESSION');

    session_set_cookie_params([
        'httponly' => true,
        'samesite' => 'Lax',
        'secure' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    ]);

    session_start();
}

function isAdminLoggedIn(): bool
{
    return isset($_SESSION['admin_id'], $_SESSION['admin_username']);
}

function requireAdminLogin(): void
{
    if (!isAdminLoggedIn()) {
        header('Location: /Personal_Portfolio/admin/login.php');
        exit;
    }
}
