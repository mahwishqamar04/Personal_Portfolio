<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/includes/protect.php';
require_once dirname(__DIR__) . '/includes/db.php';

$error = '';
$success = '';

$title = '';
$slug = '';
$shortDescription = '';
$detailedDescription = '';
$technologies = '';
$category = '';
$thumbnail = '';
$image = '';
$liveDemoUrl = '';
$projectUrl = '';
$githubUrl = '';
$status = 'active';
$displayOrder = 0;

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

        $stmt = $db->prepare(
            "INSERT INTO projects
            (
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
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );

        if (!$stmt) {
            $error = 'Unable to prepare database request.';
        } else {

            $stmt->bind_param(
                'sssssssissssss',
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
                $githubUrl
            );

            if ($stmt->execute()) {
                $success = 'Project added successfully.';

                $title = '';
                $slug = '';
                $shortDescription = '';
                $detailedDescription = '';
                $technologies = '';
                $category = '';
                $thumbnail = '';
                $image = '';
                $liveDemoUrl = '';
                $projectUrl = '';
                $githubUrl = '';
                $status = 'active';
                $displayOrder = 0;
            } else {
                $error = 'Unable to add project. The slug may already exist.';
            }

            $stmt->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Project | Admin</title>

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
            max-width: 1000px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .form-box {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.06);
        }

        h1 {
            margin-top: 0;
        }

        .field {
            margin-bottom: 18px;
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

        .row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }

        button {
            padding: 12px 20px;
            border: 0;
            border-radius: 7px;
            background: #222;
            color: #fff;
            cursor: pointer;
            font-size: 15px;
        }

        .message {
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 7px;
        }

        .error {
            background: #ffe8e8;
            color: #a00000;
        }

        .success {
            background: #e8f8ec;
            color: #176b2c;
        }

        .hint {
            margin-top: 5px;
            font-size: 13px;
            color: #777;
        }

        @media (max-width: 700px) {
            .row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

<header class="topbar">
    <strong>Mehwish Qamar â€” Admin</strong>

    <nav>
        <a href="index.php">Projects</a>
        <a href="../dashboard.php">Dashboard</a>
        <a href="../logout.php">Logout</a>
    </nav>
</header>

<main class="container">

    <div class="form-box">

        <h1>Add Project</h1>

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
                    placeholder="example-project"
                >
                <div class="hint">
                    Leave empty to generate automatically from the title.
                </div>
            </div>

            <div class="row">

                <div class="field">
                    <label for="category">Category</label>
                    <input
                        type="text"
                        id="category"
                        name="category"
                        value="<?= htmlspecialchars($category, ENT_QUOTES, 'UTF-8') ?>"
                        placeholder="AI Web Development"
                    >
                </div>

                <div class="field">
                    <label for="technologies">Technologies</label>
                    <input
                        type="text"
                        id="technologies"
                        name="technologies"
                        value="<?= htmlspecialchars($technologies, ENT_QUOTES, 'UTF-8') ?>"
                        placeholder="PHP, MySQL, JavaScript"
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
                    placeholder="assets/images/projects/example.png"
                >
            </div>

            <div class="field">
                <label for="image">Main Image Path</label>
                <input
                    type="text"
                    id="image"
                    name="image"
                    value="<?= htmlspecialchars($image, ENT_QUOTES, 'UTF-8') ?>"
                    placeholder="assets/images/projects/example.png"
                >
            </div>

            <div class="row">

                <div class="field">
                    <label for="live_demo_url">Live Demo URL</label>
                    <input
                        type="url"
                        id="live_demo_url"
                        name="live_demo_url"
                        value="<?= htmlspecialchars($liveDemoUrl, ENT_QUOTES, 'UTF-8') ?>"
                        placeholder="https://example.com"
                    >
                </div>

                <div class="field">
                    <label for="github_url">GitHub Repository URL</label>
                    <input
                        type="url"
                        id="github_url"
                        name="github_url"
                        value="<?= htmlspecialchars($githubUrl, ENT_QUOTES, 'UTF-8') ?>"
                        placeholder="https://github.com/username/repository"
                    >
                </div>

            </div>

            <div class="row">

                <div class="field">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="active" <?= $status === 'active' ? 'selected' : '' ?>>
                            Active
                        </option>
                        <option value="inactive" <?= $status === 'inactive' ? 'selected' : '' ?>>
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

            <button type="submit">Add Project</button>

        </form>

    </div>

</main>

</body>
</html>
