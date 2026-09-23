<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/includes/protect.php';
require_once dirname(__DIR__) . '/includes/db.php';

$error = '';
$success = '';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    die('Invalid project ID.');
}

$stmt = $db->prepare("
    SELECT
        id,
        slug,
        short_description,
        detailed_description,
        technologies,
        thumbnail,
        live_demo_url,
        status,
        display_order,
        title,
        description,
        category,
        image,
        project_url,
        github_url
    FROM projects
    WHERE id = ?
    LIMIT 1
");

$stmt->bind_param('i', $id);
$stmt->execute();

$result = $stmt->get_result();
$project = $result->fetch_assoc();

$stmt->close();

if (!$project) {
    die('Project not found.');
}

$title = (string)($project['title'] ?? '');
$slug = (string)($project['slug'] ?? '');
$shortDescription = (string)($project['short_description'] ?? '');
$detailedDescription = (string)($project['detailed_description'] ?? '');
$technologies = (string)($project['technologies'] ?? '');
$category = (string)($project['category'] ?? '');
$thumbnail = (string)($project['thumbnail'] ?? '');
$image = (string)($project['image'] ?? '');
$liveDemoUrl = (string)($project['live_demo_url'] ?? '');
$projectUrl = (string)($project['project_url'] ?? '');
$githubUrl = (string)($project['github_url'] ?? '');
$status = ($project['status'] ?? 'active') === 'inactive'
    ? 'inactive'
    : 'active';
$displayOrder = (int)($project['display_order'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = trim((string)($_POST['title'] ?? ''));
    $slug = trim((string)($_POST['slug'] ?? ''));
    $shortDescription = trim((string)($_POST['short_description'] ?? ''));
    $detailedDescription = trim((string)($_POST['detailed_description'] ?? ''));
    $technologies = trim((string)($_POST['technologies'] ?? ''));
    $category = trim((string)($_POST['category'] ?? ''));
    $thumbnail = trim((string)($_POST['thumbnail'] ?? ''));
    $image = trim((string)($_POST['image'] ?? ''));
    $liveDemoUrl = trim((string)($_POST['live_demo_url'] ?? ''));
    $projectUrl = trim((string)($_POST['project_url'] ?? ''));
    $githubUrl = trim((string)($_POST['github_url'] ?? ''));
    $status = ($_POST['status'] ?? 'active') === 'inactive'
        ? 'inactive'
        : 'active';
    $displayOrder = (int)($_POST['display_order'] ?? 0);

    if ($title === '') {
        $error = 'Project title is required.';
    } else {

        if ($slug === '') {
            $slug = strtolower(
                trim(
                    preg_replace('/[^a-z0-9]+/i', '-', $title),
                    '-'
                )
            );
        }

        $stmt = $db->prepare("
            UPDATE projects SET
                slug = ?,
                short_description = ?,
                detailed_description = ?,
                technologies = ?,
                thumbnail = ?,
                live_demo_url = ?,
                status = ?,
                display_order = ?,
                title = ?,
                description = ?,
                category = ?,
                image = ?,
                project_url = ?,
                github_url = ?
            WHERE id = ?
        ");

        $stmt->bind_param(
            'sssssssissssssi',
            $slug,
            $shortDescription,
            $detailedDescription,
            $technologies,
            $thumbnail,
            $liveDemoUrl,
            $status,
            $displayOrder,
            $title,
            $detailedDescription,
            $category,
            $image,
            $projectUrl,
            $githubUrl,
            $id
        );

        if ($stmt->execute()) {
            $success = 'Project updated successfully.';
        } else {
            $error = 'Failed to update project: ' . $stmt->error;
        }

        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Project | Mehwish Qamar</title>

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

        .container {
            width: min(1000px, calc(100% - 30px));
            margin: 40px auto;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            margin-bottom: 25px;
        }

        h1 {
            margin: 0;
        }

        .links {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .links a {
            text-decoration: none;
            color: #222;
            background: #fff;
            padding: 10px 14px;
            border-radius: 7px;
            border: 1px solid #ddd;
        }

        .card {
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.06);
        }

        .field {
            margin-bottom: 18px;
        }

        .row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }

        label {
            display: block;
            margin-bottom: 7px;
            font-weight: 600;
        }

        input,
        textarea,
        select {
            width: 100%;
            padding: 11px;
            border: 1px solid #d5d9df;
            border-radius: 7px;
            font-size: 15px;
            font-family: inherit;
        }

        textarea {
            min-height: 120px;
            resize: vertical;
        }

        .hint {
            margin-top: 6px;
            color: #777;
            font-size: 13px;
        }

        button {
            border: 0;
            border-radius: 7px;
            padding: 12px 18px;
            background: #222;
            color: #fff;
            cursor: pointer;
            font-size: 15px;
        }

        .message {
            padding: 12px 15px;
            border-radius: 7px;
            margin-bottom: 20px;
        }

        .success {
            background: #e7f7ed;
            color: #176b35;
        }

        .error {
            background: #ffe8e8;
            color: #a00000;
        }

        @media (max-width: 700px) {
            .row {
                grid-template-columns: 1fr;
            }

            .topbar {
                align-items: flex-start;
                flex-direction: column;
            }
        }
    </style>
</head>

<body>

<main class="container">

    <div class="topbar">
        <h1>Edit Project</h1>

        <div class="links">
            <a href="index.php">← Projects</a>
            <a href="../dashboard.php">Dashboard</a>
            <a href="../logout.php">Logout</a>
        </div>
    </div>

    <div class="card">

        <?php if ($error !== ''): ?>
            <div class="message error">
                <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <?php if ($success !== ''): ?>
            <div class="message success">
                <?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <form method="POST">

            <div class="field">
                <label for="title">Project Title *</label>

                <input
                    type="text"
                    id="title"
                    name="title"
                    value="<?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?>"
                    required
                >
            </div>

            <div class="field">
                <label for="slug">Slug</label>

                <input
                    type="text"
                    id="slug"
                    name="slug"
                    value="<?= htmlspecialchars($slug, ENT_QUOTES, 'UTF-8') ?>"
                >
            </div>

            <div class="row">

                <div class="field">
                    <label for="category">Category</label>

                    <input
                        type="text"
                        id="category"
                        name="category"
                        value="<?= htmlspecialchars($category, ENT_QUOTES, 'UTF-8') ?>"
                    >
                </div>

                <div class="field">
                    <label for="technologies">Technologies</label>

                    <input
                        type="text"
                        id="technologies"
                        name="technologies"
                        value="<?= htmlspecialchars($technologies, ENT_QUOTES, 'UTF-8') ?>"
                    >
                </div>

            </div>

            <div class="field">
                <label for="short_description">Short Description</label>

                <textarea
                    id="short_description"
                    name="short_description"
                ><?= htmlspecialchars($shortDescription, ENT_QUOTES, 'UTF-8') ?></textarea>
            </div>

            <div class="field">
                <label for="detailed_description">Detailed Description</label>

                <textarea
                    id="detailed_description"
                    name="detailed_description"
                ><?= htmlspecialchars($detailedDescription, ENT_QUOTES, 'UTF-8') ?></textarea>
            </div>

            <div class="field">
                <label for="thumbnail">Thumbnail Path</label>

                <input
                    type="text"
                    id="thumbnail"
                    name="thumbnail"
                    value="<?= htmlspecialchars($thumbnail, ENT_QUOTES, 'UTF-8') ?>"
                >
            </div>

            <div class="field">
                <label for="image">Image Path</label>

                <input
                    type="text"
                    id="image"
                    name="image"
                    value="<?= htmlspecialchars($image, ENT_QUOTES, 'UTF-8') ?>"
                >
            </div>

            <div class="field">
                <label for="live_demo_url">Live Demo URL</label>

                <input
                    type="url"
                    id="live_demo_url"
                    name="live_demo_url"
                    value="<?= htmlspecialchars($liveDemoUrl, ENT_QUOTES, 'UTF-8') ?>"
                >
            </div>

            <div class="field">
                <label for="project_url">Project URL</label>

                <input
                    type="url"
                    id="project_url"
                    name="project_url"
                    value="<?= htmlspecialchars($projectUrl, ENT_QUOTES, 'UTF-8') ?>"
                >
            </div>

            <div class="field">
                <label for="github_url">GitHub Repository URL</label>

                <input
                    type="url"
                    id="github_url"
                    name="github_url"
                    value="<?= htmlspecialchars($githubUrl, ENT_QUOTES, 'UTF-8') ?>"
                >
            </div>

            <div class="row">

                <div class="field">
                    <label for="status">Status</label>

                    <select id="status" name="status">

                        <option
                            value="active"
                            <?= $status === 'active' ? 'selected' : '' ?>
                        >
                            Active
                        </option>

                        <option
                            value="inactive"
                            <?= $status === 'inactive' ? 'selected' : '' ?>
                        >
                            Inactive
                        </option>

                    </select>
                </div>

                <div class="field">
                    <label for="display_order">Display Order</label>

                    <input
                        type="number"
                        id="display_order"
                        name="display_order"
                        value="<?= (int)$displayOrder ?>"
                        min="0"
                    >
                </div>

            </div>

            <button type="submit">Save Changes</button>

        </form>

    </div>

</main>

</body>
</html>