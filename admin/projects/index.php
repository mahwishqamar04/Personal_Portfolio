<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/includes/protect.php';
require_once dirname(__DIR__) . '/includes/db.php';

$projects = [];

$result = $db->query(
    "SELECT id, title, slug, category, status, github_url
     FROM projects
     ORDER BY id DESC"
);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $projects[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Projects | Admin</title>

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

        .topbar a {
            color: #fff;
            text-decoration: none;
            margin-left: 12px;
        }

        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }

        h1 {
            margin: 0;
        }

        .button {
            display: inline-block;
            padding: 10px 15px;
            background: #222;
            color: #fff;
            text-decoration: none;
            border-radius: 7px;
        }

        .table-wrap {
            background: #fff;
            border-radius: 10px;
            overflow-x: auto;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.06);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 13px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background: #f8f8f8;
        }

        .status {
            font-weight: 600;
        }

        .empty {
            padding: 30px;
            text-align: center;
            color: #666;
        }

        @media (max-width: 700px) {
            .header {
                align-items: flex-start;
                flex-direction: column;
            }
        }
    </style>
</head>

<body>

<header class="topbar">
    <strong>Mehwish Qamar — Admin</strong>

    <nav>
        <a href="../dashboard.php">Dashboard</a>
        <a href="../logout.php">Logout</a>
    </nav>
</header>

<main class="container">

    <div class="header">
        <h1>Manage Projects</h1>

        <a class="button" href="add.php">
            + Add Project
        </a>
    </div>

    <div class="table-wrap">

        <?php if (empty($projects)): ?>

            <div class="empty">
                No projects found.
            </div>

        <?php else: ?>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>GitHub</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>

                <?php foreach ($projects as $project): ?>

                    <tr>
                        <td>
                            <?= (int)$project['id'] ?>
                        </td>

                        <td>
                            <?= htmlspecialchars(
                                $project['title'],
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars(
                                (string)$project['category'],
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </td>

                        <td class="status">
                            <?= htmlspecialchars(
                                (string)$project['status'],
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </td>

                        <td>
                            <?php if (!empty($project['github_url'])): ?>
                                <a
                                    href="<?= htmlspecialchars(
                                        $project['github_url'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                >
                                    GitHub
                                </a>
                            <?php else: ?>
                                —
                            <?php endif; ?>
                        </td>

                        <td>
                            <a href="edit.php?id=<?= (int)$project['id'] ?>">
                                Edit
                            </a>
                        </td>
                    </tr>

                <?php endforeach; ?>

                </tbody>
            </table>

        <?php endif; ?>

    </div>

</main>

</body>
</html>
