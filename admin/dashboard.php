<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/protect.php';
require_once __DIR__ . '/includes/db.php';

$projectCount = 0;
$certificateCount = 0;

$projectResult = $db->query("SELECT COUNT(*) AS total FROM projects");

if ($projectResult) {
    $row = $projectResult->fetch_assoc();
    $projectCount = (int)($row['total'] ?? 0);
}

$certificateTableExists = false;
$tableCheck = $db->query("SHOW TABLES LIKE 'certificates'");

if ($tableCheck && $tableCheck->num_rows > 0) {
    $certificateTableExists = true;

    $certificateResult = $db->query(
        "SELECT COUNT(*) AS total FROM certificates"
    );

    if ($certificateResult) {
        $row = $certificateResult->fetch_assoc();
        $certificateCount = (int)($row['total'] ?? 0);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Mehwish Qamar</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            color: #222;
        }

        .topbar {
            background: #222;
            color: #fff;
            padding: 16px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
        }

        .brand {
            font-size: 20px;
            font-weight: 700;
        }

        .top-links {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .top-links a {
            color: #fff;
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 6px;
            background: rgba(255, 255, 255, 0.12);
        }

        .container {
            max-width: 1100px;
            margin: 35px auto;
            padding: 0 20px;
        }

        .welcome {
            margin-bottom: 25px;
        }

        .welcome h1 {
            margin: 0 0 8px;
        }

        .welcome p {
            margin: 0;
            color: #666;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            background: #fff;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.06);
        }

        .card h2 {
            margin: 0 0 10px;
            font-size: 16px;
            color: #666;
        }

        .count {
            font-size: 34px;
            font-weight: 700;
        }

        .actions {
            background: #fff;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.06);
        }

        .actions h2 {
            margin-top: 0;
        }

        .buttons {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .button {
            display: inline-block;
            padding: 11px 16px;
            border-radius: 7px;
            text-decoration: none;
            background: #222;
            color: #fff;
        }

        .button.secondary {
            background: #666;
        }

        @media (max-width: 700px) {
            .topbar {
                align-items: flex-start;
                flex-direction: column;
            }

            .cards {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

<header class="topbar">
    <div class="brand">Mehwish Qamar — Admin</div>

    <div class="top-links">
        <a href="../index.php" target="_blank">View Portfolio</a>
        <a href="logout.php">Logout</a>
    </div>
</header>

<main class="container">

    <section class="welcome">
        <h1>Welcome, <?= htmlspecialchars($_SESSION['admin_username'], ENT_QUOTES, 'UTF-8') ?> 👋</h1>
        <p>Manage your portfolio projects, certificates and screenshots from here.</p>
    </section>

    <section class="cards">

        <div class="card">
            <h2>Total Projects</h2>
            <div class="count"><?= $projectCount ?></div>
        </div>

        <div class="card">
            <h2>Total Certificates</h2>
            <div class="count">
                <?= $certificateTableExists ? $certificateCount : '0' ?>
            </div>
        </div>

    </section>

    <section class="actions">
        <h2>Quick Actions</h2>

        <div class="buttons">
            <a class="button" href="projects/">Manage Projects</a>
            <a class="button" href="profile/">Manage Profile Picture</a>

            <a class="button" href="certificates/">
                Manage Certificates
            </a>

            <a class="button" href="screenshots/">
                Manage Screenshots
            </a>

            <a class="button secondary" href="../index.php" target="_blank">
                Open Portfolio
            </a>
        </div>
    </section>

</main>

</body>
</html>
