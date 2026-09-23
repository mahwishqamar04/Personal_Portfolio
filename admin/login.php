<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';

if (isAdminLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim((string)($_POST['username'] ?? ''));
    $password = (string)($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        $error = 'Please enter username and password.';
    } else {
        $stmt = $db->prepare(
            "SELECT id, username, password_hash, status
             FROM admin_users
             WHERE username = ?
             LIMIT 1"
        );

        if ($stmt) {
            $stmt->bind_param('s', $username);
            $stmt->execute();

            $result = $stmt->get_result();
            $admin = $result->fetch_assoc();

            if (
                $admin &&
                $admin['status'] === 'active' &&
                password_verify($password, $admin['password_hash'])
            ) {
                session_regenerate_id(true);

                $_SESSION['admin_id'] = (int)$admin['id'];
                $_SESSION['admin_username'] = $admin['username'];

                $update = $db->prepare(
                    "UPDATE admin_users
                     SET last_login = NOW()
                     WHERE id = ?"
                );

                if ($update) {
                    $update->bind_param('i', $admin['id']);
                    $update->execute();
                    $update->close();
                }

                $stmt->close();

                header('Location: dashboard.php');
                exit;
            }

            $stmt->close();
        }

        $error = 'Invalid username or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Mehwish Qamar</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: Arial, sans-serif;
            background: #f4f6f9;
        }

        .login-box {
            width: 100%;
            max-width: 420px;
            margin: 20px;
            padding: 32px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        }

        h1 {
            margin: 0 0 8px;
            font-size: 26px;
        }

        .subtitle {
            margin: 0 0 25px;
            color: #666;
        }

        label {
            display: block;
            margin: 15px 0 7px;
            font-weight: 600;
        }

        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #d5d9df;
            border-radius: 7px;
            font-size: 15px;
        }

        button {
            width: 100%;
            margin-top: 22px;
            padding: 12px;
            border: 0;
            border-radius: 7px;
            background: #222;
            color: #fff;
            font-size: 15px;
            cursor: pointer;
        }

        .error {
            padding: 10px 12px;
            margin-bottom: 15px;
            border-radius: 7px;
            background: #ffe8e8;
            color: #a00000;
        }
    </style>
</head>
<body>

<div class="login-box">
    <h1>Admin Login</h1>
    <p class="subtitle">Mehwish Qamar Portfolio</p>

    <?php if ($error !== ''): ?>
        <div class="error">
            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="username">Username</label>
        <input
            type="text"
            id="username"
            name="username"
            autocomplete="username"
            required
        >

        <label for="password">Password</label>
        <input
            type="password"
            id="password"
            name="password"
            autocomplete="current-password"
            required
        >

        <button type="submit">Login</button>
    </form>
</div>

</body>
</html>
