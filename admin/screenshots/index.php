<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/includes/protect.php';
require_once dirname(__DIR__) . '/includes/db.php';

$message = '';
$error = '';

$uploadDir = dirname(__DIR__, 2) . '/assets/images/projects/screenshots/';
$uploadWebPath = '../../assets/images/projects/screenshots/';

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

/* Delete screenshot */
if (isset($_GET['delete'])) {
    $id = filter_input(INPUT_GET, 'delete', FILTER_VALIDATE_INT);

    if ($id && $id > 0) {
        $stmt = $db->prepare("SELECT image_path FROM project_screenshots WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        if ($row) {
            $stmt = $db->prepare("DELETE FROM project_screenshots WHERE id = ?");
            $stmt->bind_param('i', $id);

            if ($stmt->execute()) {
                $imagePath = dirname(__DIR__, 2) . '/' . ltrim($row['image_path'], '/');

                if (is_file($imagePath)) {
                    unlink($imagePath);
                }

                $message = 'Screenshot deleted successfully.';
            } else {
                $error = 'Failed to delete screenshot.';
            }

            $stmt->close();
        }
    }
}

/* Add screenshot */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $projectId = filter_input(INPUT_POST, 'project_id', FILTER_VALIDATE_INT);
    $caption = trim($_POST['caption'] ?? '');
    $displayOrder = filter_input(INPUT_POST, 'display_order', FILTER_VALIDATE_INT);

    if (!$projectId || $projectId < 1) {
        $error = 'Please select a project.';
    } elseif (!isset($_FILES['screenshot']) || $_FILES['screenshot']['error'] !== UPLOAD_ERR_OK) {
        $error = 'Please select a screenshot image.';
    } else {
        $file = $_FILES['screenshot'];

        $allowedTypes = [
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/webp' => 'webp'
        ];

        $mime = mime_content_type($file['tmp_name']);
        $maxSize = 5 * 1024 * 1024;

        if (!isset($allowedTypes[$mime])) {
            $error = 'Only JPG, PNG and WebP images are allowed.';
        } elseif ($file['size'] > $maxSize) {
            $error = 'Image must be 5 MB or smaller.';
        } else {
            $extension = $allowedTypes[$mime];
            $filename = 'screenshot_' . $projectId . '_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $extension;
            $destination = $uploadDir . $filename;

            if (move_uploaded_file($file['tmp_name'], $destination)) {
                $dbPath = 'assets/images/projects/screenshots/' . $filename;
                $order = ($displayOrder !== false && $displayOrder !== null) ? $displayOrder : 0;

                $stmt = $db->prepare("
                    INSERT INTO project_screenshots
                    (project_id, image_path, caption, display_order)
                    VALUES (?, ?, ?, ?)
                ");

                $stmt->bind_param('issi', $projectId, $dbPath, $caption, $order);

                if ($stmt->execute()) {
                    $message = 'Screenshot added successfully.';
                } else {
                    unlink($destination);
                    $error = 'Database error: ' . $stmt->error;
                }

                $stmt->close();
            } else {
                $error = 'Failed to upload the image.';
            }
        }
    }
}

/* Projects */
$projects = [];
$result = $db->query("SELECT id, title FROM projects ORDER BY title ASC");

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $projects[] = $row;
    }
}

/* Screenshots */
$screenshots = [];
$result = $db->query("
    SELECT
        ps.id,
        ps.project_id,
        ps.image_path,
        ps.caption,
        ps.display_order,
        p.title AS project_title
    FROM project_screenshots ps
    LEFT JOIN projects p ON p.id = ps.project_id
    ORDER BY p.title ASC, ps.display_order ASC, ps.id DESC
");

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $screenshots[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Screenshots | Mehwish Qamar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f7fb;
            margin: 0;
            padding: 30px;
            color: #222;
        }

        .container {
            max-width: 1100px;
            margin: auto;
        }

        .top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            gap: 15px;
        }

        .top a {
            text-decoration: none;
        }

        .card {
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 25px;
            box-shadow: 0 2px 12px rgba(0,0,0,.08);
        }

        label {
            display: block;
            margin: 12px 0 6px;
            font-weight: 600;
        }

        input, select {
            width: 100%;
            box-sizing: border-box;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        button, .btn {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 16px;
            border: 0;
            border-radius: 6px;
            background: #222;
            color: #fff;
            cursor: pointer;
            text-decoration: none;
        }

        .success {
            background: #e8f7ed;
            color: #176b35;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 15px;
        }

        .error {
            background: #fdecec;
            color: #a32121;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 15px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(230px, 1fr));
            gap: 20px;
        }

        .item {
            border: 1px solid #ddd;
            border-radius: 10px;
            overflow: hidden;
            background: #fff;
        }

        .item img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            display: block;
        }

        .item-content {
            padding: 12px;
        }

        .delete {
            background: #b42318;
        }
    </style>
</head>

<body>

<div class="container">

    <div class="top">
        <div>
            <h1>Manage Screenshots</h1>
            <p>Add and remove screenshots for your portfolio projects.</p>
        </div>

        <div>
            <a class="btn" href="../dashboard.php">Dashboard</a>
            <a class="btn" href="../logout.php">Logout</a>
        </div>
    </div>

    <?php if ($message !== '') : ?>
        <div class="success"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <?php if ($error !== '') : ?>
        <div class="error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <div class="card">
        <h2>Add Screenshot</h2>

        <form method="post" enctype="multipart/form-data">

            <label for="project_id">Project</label>

            <select name="project_id" id="project_id" required>
                <option value="">Select project</option>

                <?php foreach ($projects as $project) : ?>
                    <option value="<?= (int)$project['id'] ?>">
                        <?= htmlspecialchars($project['title'], ENT_QUOTES, 'UTF-8') ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="screenshot">Screenshot</label>
            <input
                type="file"
                name="screenshot"
                id="screenshot"
                accept=".jpg,.jpeg,.png,.webp"
                required
            >

            <label for="caption">Caption</label>
            <input
                type="text"
                name="caption"
                id="caption"
                maxlength="200"
                placeholder="Optional screenshot caption"
            >

            <label for="display_order">Display Order</label>
            <input
                type="number"
                name="display_order"
                id="display_order"
                value="0"
                min="0"
            >

            <button type="submit">Add Screenshot</button>

        </form>
    </div>

    <div class="card">
        <h2>Existing Screenshots</h2>

        <?php if (empty($screenshots)) : ?>

            <p>No screenshots have been added yet.</p>

        <?php else : ?>

            <div class="grid">

                <?php foreach ($screenshots as $screenshot) : ?>

                    <div class="item">

                        <img
                            src="../../<?= htmlspecialchars($screenshot['image_path'], ENT_QUOTES, 'UTF-8') ?>"
                            alt="<?= htmlspecialchars($screenshot['caption'] ?: 'Project screenshot', ENT_QUOTES, 'UTF-8') ?>"
                        >

                        <div class="item-content">

                            <strong>
                                <?= htmlspecialchars($screenshot['project_title'] ?? 'Unknown Project', ENT_QUOTES, 'UTF-8') ?>
                            </strong>

                            <?php if ($screenshot['caption']) : ?>
                                <p>
                                    <?= htmlspecialchars($screenshot['caption'], ENT_QUOTES, 'UTF-8') ?>
                                </p>
                            <?php endif; ?>

                            <small>
                                Order: <?= (int)$screenshot['display_order'] ?>
                            </small>

                            <br>

                            <a
                                class="btn delete"
                                href="?delete=<?= (int)$screenshot['id'] ?>"
                                onclick="return confirm('Delete this screenshot?');"
                            >
                                Delete
                            </a>

                        </div>

                    </div>

                <?php endforeach; ?>

            </div>

        <?php endif; ?>

    </div>

</div>

</body>
</html>
