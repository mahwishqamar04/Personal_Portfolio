<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/includes/protect.php';
require_once dirname(__DIR__) . '/includes/db.php';

$db = getDBConnection();

$message = '';
$error = '';

$uploadDir = dirname(__DIR__, 2) . '/assets/images/certificates/';
$uploadWebPath = 'assets/images/certificates/';

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];

    if ($id > 0) {
        $stmt = $db->prepare("SELECT image FROM certificates WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $certificate = $result->fetch_assoc();
        $stmt->close();

        $stmt = $db->prepare("DELETE FROM certificates WHERE id = ?");
        $stmt->bind_param('i', $id);

        if ($stmt->execute()) {
            if (!empty($certificate['image'])) {
                $imagePath = dirname(__DIR__, 2) . '/' . ltrim($certificate['image'], '/');

                if (is_file($imagePath)) {
                    unlink($imagePath);
                }
            }

            $message = 'Certificate deleted successfully.';
        } else {
            $error = 'Unable to delete certificate.';
        }

        $stmt->close();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $organization = trim($_POST['organization'] ?? '');
    $certificateDate = trim($_POST['certificate_date'] ?? '');
    $certificateUrl = trim($_POST['certificate_url'] ?? '');

    if ($title === '') {
        $error = 'Certificate title is required.';
    } elseif (
        $certificateDate !== '' &&
        !preg_match('/^\d{4}-\d{2}-\d{2}$/', $certificateDate)
    ) {
        $error = 'Please enter a valid certificate date.';
    } elseif (
        $certificateUrl !== '' &&
        !filter_var($certificateUrl, FILTER_VALIDATE_URL)
    ) {
        $error = 'Please enter a valid certificate URL.';
    }

    $imagePath = '';

    if (
        $error === '' &&
        isset($_FILES['image']) &&
        $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE
    ) {
        if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            $error = 'Certificate image upload failed.';
        } elseif ($_FILES['image']['size'] > 5 * 1024 * 1024) {
            $error = 'Certificate image must be 5 MB or smaller.';
        } else {
            $tmpName = $_FILES['image']['tmp_name'];
            $originalName = $_FILES['image']['name'];

            $extension = strtolower(
                pathinfo($originalName, PATHINFO_EXTENSION)
            );

            $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];

            if (!in_array($extension, $allowedExtensions, true)) {
                $error = 'Only JPG, JPEG, PNG, and WebP images are allowed.';
            } elseif (@getimagesize($tmpName) === false) {
                $error = 'The uploaded file is not a valid image.';
            } else {
                try {
                    $randomName = bin2hex(random_bytes(6));
                } catch (Throwable $e) {
                    $randomName = uniqid();
                }

                $fileName =
                    'certificate_' .
                    date('Ymd_His') .
                    '_' .
                    $randomName .
                    '.' .
                    $extension;

                $destination = $uploadDir . $fileName;

                if (move_uploaded_file($tmpName, $destination)) {
                    $imagePath = $uploadWebPath . $fileName;
                } else {
                    $error = 'Unable to save the certificate image.';
                }
            }
        }
    }

    if ($error === '') {
        $stmt = $db->prepare(
            "INSERT INTO certificates
            (title, organization, certificate_date, certificate_url, image)
            VALUES (?, ?, NULLIF(?, ''), ?, ?)"
        );

        $stmt->bind_param(
            'sssss',
            $title,
            $organization,
            $certificateDate,
            $certificateUrl,
            $imagePath
        );

        if ($stmt->execute()) {
            $message = 'Certificate added successfully.';
            $title = '';
            $organization = '';
            $certificateDate = '';
            $certificateUrl = '';
        } else {
            if ($imagePath !== '') {
                $savedImage = dirname(__DIR__, 2) . '/' . $imagePath;

                if (is_file($savedImage)) {
                    unlink($savedImage);
                }
            }

            $error = 'Unable to add certificate.';
        }

        $stmt->close();
    }
}

$certificates = [];

$result = $db->query(
    "SELECT id, title, organization, certificate_date,
            certificate_url, image, created_at
     FROM certificates
     ORDER BY certificate_date DESC, id DESC"
);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $certificates[] = $row;
    }
}

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Certificates</title>

<style>
body {
    margin: 0;
    padding: 30px;
    font-family: Arial, sans-serif;
    background: #f5f7fb;
    color: #222;
}

.container {
    max-width: 1100px;
    margin: auto;
}

h1 {
    margin-bottom: 8px;
}

.subtitle {
    color: #666;
    margin-bottom: 25px;
}

.top-actions {
    margin-bottom: 20px;
}

.button {
    display: inline-block;
    padding: 10px 16px;
    margin-right: 8px;
    text-decoration: none;
    border-radius: 6px;
    background: #222;
    color: #fff;
}

.card {
    background: #fff;
    border-radius: 10px;
    padding: 22px;
    margin-bottom: 25px;
    box-shadow: 0 3px 15px rgba(0,0,0,.08);
}

label {
    display: block;
    font-weight: 600;
    margin-top: 14px;
    margin-bottom: 6px;
}

input {
    width: 100%;
    box-sizing: border-box;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 6px;
}

.submit-button {
    margin-top: 18px;
    padding: 11px 18px;
    border: 0;
    border-radius: 6px;
    background: #222;
    color: #fff;
    cursor: pointer;
}

.message {
    padding: 12px;
    border-radius: 6px;
    background: #e8f7e8;
    color: #216b21;
    margin-bottom: 15px;
}

.error {
    padding: 12px;
    border-radius: 6px;
    background: #fdeaea;
    color: #9b2226;
    margin-bottom: 15px;
}

.certificate-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 20px;
}

.certificate-card {
    border: 1px solid #ddd;
    border-radius: 8px;
    overflow: hidden;
    background: #fff;
}

.certificate-image {
    width: 100%;
    height: 180px;
    object-fit: cover;
    display: block;
    background: #eee;
}

.certificate-content {
    padding: 15px;
}

.certificate-content h3 {
    margin-top: 0;
}

.certificate-content p {
    margin: 6px 0;
    color: #555;
}

.delete-button {
    display: inline-block;
    margin-top: 10px;
    padding: 8px 12px;
    border-radius: 5px;
    background: #b42318;
    color: #fff;
    text-decoration: none;
}

.empty {
    color: #666;
}
</style>
</head>

<body>

<div class="container">

<h1>Manage Certificates</h1>

<p class="subtitle">
Add and manage your professional certificates.
</p>

<div class="top-actions">
    <a class="button" href="../dashboard.php">Dashboard</a>
    <a class="button" href="../logout.php">Logout</a>
</div>

<?php if ($message !== ''): ?>
<div class="message"><?= e($message) ?></div>
<?php endif; ?>

<?php if ($error !== ''): ?>
<div class="error"><?= e($error) ?></div>
<?php endif; ?>

<div class="card">

<h2>Add Certificate</h2>

<form method="POST" enctype="multipart/form-data">

<label for="title">Certificate Title *</label>
<input
    type="text"
    id="title"
    name="title"
    maxlength="200"
    value="<?= e($title ?? '') ?>"
    required
>

<label for="organization">Organization</label>
<input
    type="text"
    id="organization"
    name="organization"
    maxlength="150"
    value="<?= e($organization ?? '') ?>"
>

<label for="certificate_date">Certificate Date</label>
<input
    type="date"
    id="certificate_date"
    name="certificate_date"
    value="<?= e($certificateDate ?? '') ?>"
>

<label for="certificate_url">Certificate URL</label>
<input
    type="url"
    id="certificate_url"
    name="certificate_url"
    maxlength="255"
    value="<?= e($certificateUrl ?? '') ?>"
    placeholder="https://example.com"
>

<label for="image">Certificate Image</label>
<input
    type="file"
    id="image"
    name="image"
    accept=".jpg,.jpeg,.png,.webp"
>

<small>
Maximum size: 5 MB. Allowed: JPG, JPEG, PNG, WebP.
</small>

<br>

<button type="submit" class="submit-button">
Add Certificate
</button>

</form>

</div>

<div class="card">

<h2>Existing Certificates</h2>

<?php if (empty($certificates)): ?>

<p class="empty">No certificates have been added yet.</p>

<?php else: ?>

<div class="certificate-grid">

<?php foreach ($certificates as $certificate): ?>

<div class="certificate-card">

<?php if (!empty($certificate['image'])): ?>

<img
    class="certificate-image"
    src="../../<?= e($certificate['image']) ?>"
    alt="<?= e($certificate['title']) ?>"
>

<?php endif; ?>

<div class="certificate-content">

<h3><?= e($certificate['title']) ?></h3>

<?php if (!empty($certificate['organization'])): ?>
<p>
<strong>Organization:</strong>
<?= e($certificate['organization']) ?>
</p>
<?php endif; ?>

<?php if (!empty($certificate['certificate_date'])): ?>
<p>
<strong>Date:</strong>
<?= e($certificate['certificate_date']) ?>
</p>
<?php endif; ?>

<?php if (!empty($certificate['certificate_url'])): ?>
<p>
<a
    href="<?= e($certificate['certificate_url']) ?>"
    target="_blank"
    rel="noopener noreferrer"
>
View Certificate
</a>
</p>
<?php endif; ?>

<a
    class="delete-button"
    href="?delete=<?= (int) $certificate['id'] ?>"
    onclick="return confirm('Delete this certificate?');"
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
