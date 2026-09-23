<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/includes/protect.php';
require_once dirname(__DIR__) . '/includes/db.php';

$db = getDBConnection();

$message = '';
$error = '';

$uploadDir = dirname(__DIR__, 2) . '/assets/images/profile/';
$uploadWebPath = 'assets/images/profile/';

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

/*
|--------------------------------------------------------------------------
| Load current profile
|--------------------------------------------------------------------------
*/
$profile = null;

$result = $db->query(
    "SELECT id, full_name, headline, bio, education, experience,
            profile_image, linkedin_url, github_url, upwork_url
     FROM profile
     ORDER BY id ASC
     LIMIT 1"
);

if ($result) {
    $profile = $result->fetch_assoc();
}

if (!$profile) {
    $error = 'No profile record was found in the database.';
}

/*
|--------------------------------------------------------------------------
| Upload new profile picture
|--------------------------------------------------------------------------
*/
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $profile) {

    if (
        !isset($_FILES['profile_image']) ||
        $_FILES['profile_image']['error'] === UPLOAD_ERR_NO_FILE
    ) {
        $error = 'Please select a profile picture.';
    } elseif ($_FILES['profile_image']['error'] !== UPLOAD_ERR_OK) {
        $error = 'Profile picture upload failed.';
    } elseif ($_FILES['profile_image']['size'] > 5 * 1024 * 1024) {
        $error = 'Profile picture must be 5 MB or smaller.';
    } else {

        $tmpName = $_FILES['profile_image']['tmp_name'];
        $originalName = $_FILES['profile_image']['name'];

        $extension = strtolower(
            pathinfo($originalName, PATHINFO_EXTENSION)
        );

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array($extension, $allowedExtensions, true)) {
            $error = 'Only JPG, JPEG, PNG and WebP images are allowed.';
        } elseif (@getimagesize($tmpName) === false) {
            $error = 'The selected file is not a valid image.';
        } else {

            try {
                $randomName = bin2hex(random_bytes(6));
            } catch (Throwable $e) {
                $randomName = uniqid();
            }

            $fileName =
                'profile_' .
                date('Ymd_His') .
                '_' .
                $randomName .
                '.' .
                $extension;

            $destination = $uploadDir . $fileName;

            if (move_uploaded_file($tmpName, $destination)) {

                $newImagePath = $uploadWebPath . $fileName;

                $stmt = $db->prepare(
                    "UPDATE profile SET profile_image = ? WHERE id = ?"
                );

                $stmt->bind_param(
                    'si',
                    $newImagePath,
                    $profile['id']
                );

                if ($stmt->execute()) {

                    /*
                    |--------------------------------------------------------------------------
                    | Remove previous profile image only if it was inside
                    | the portfolio profile-image directory.
                    |--------------------------------------------------------------------------
                    */
                    $oldImage = (string) ($profile['profile_image'] ?? '');

                    if (
                        $oldImage !== '' &&
                        strpos($oldImage, 'assets/images/profile/') === 0
                    ) {
                        $oldFile = dirname(__DIR__, 2) . '/' . $oldImage;

                        if (
                            is_file($oldFile) &&
                            realpath($oldFile) !== realpath($destination)
                        ) {
                            unlink($oldFile);
                        }
                    }

                    $profile['profile_image'] = $newImagePath;
                    $message = 'Profile picture updated successfully.';

                } else {

                    if (is_file($destination)) {
                        unlink($destination);
                    }

                    $error = 'Unable to update the profile picture in the database.';
                }

                $stmt->close();

            } else {
                $error = 'Unable to save the uploaded profile picture.';
            }
        }
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

<title>Manage Profile Picture</title>

<style>
body {
    margin: 0;
    padding: 30px;
    font-family: Arial, sans-serif;
    background: #f5f7fb;
    color: #222;
}

.container {
    max-width: 850px;
    margin: 0 auto;
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
    padding: 25px;
    margin-bottom: 25px;
    box-shadow: 0 3px 15px rgba(0,0,0,.08);
}

.current-image {
    text-align: center;
    margin: 20px 0 25px;
}

.current-image img {
    width: 220px;
    height: 220px;
    object-fit: cover;
    border-radius: 50%;
    border: 5px solid #eee;
}

label {
    display: block;
    font-weight: 600;
    margin-bottom: 8px;
}

input[type="file"] {
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

.info {
    color: #666;
    font-size: 14px;
    margin-top: 10px;
}
</style>
</head>

<body>

<div class="container">

<h1>Manage Profile Picture</h1>

<p class="subtitle">
Change the profile picture displayed on your portfolio.
</p>

<div class="top-actions">
    <a class="button" href="../dashboard.php">Dashboard</a>
    <a class="button" href="../logout.php">Logout</a>
</div>

<?php if ($message !== ''): ?>
<div class="message">
    <?= e($message) ?>
</div>
<?php endif; ?>

<?php if ($error !== ''): ?>
<div class="error">
    <?= e($error) ?>
</div>
<?php endif; ?>

<div class="card">

<h2>Current Profile Picture</h2>

<div class="current-image">

<?php if (!empty($profile['profile_image'])): ?>

<img
    src="../../<?= e($profile['profile_image']) ?>"
    alt="Current Profile Picture"
>

<?php else: ?>

<p>No profile picture is currently set.</p>

<?php endif; ?>

</div>

<form method="POST" enctype="multipart/form-data">

<label for="profile_image">
Choose New Profile Picture
</label>

<input
    type="file"
    id="profile_image"
    name="profile_image"
    accept=".jpg,.jpeg,.png,.webp"
    required
>

<p class="info">
Maximum size: 5 MB. Allowed formats: JPG, JPEG, PNG and WebP.
</p>

<button type="submit" class="submit-button">
Update Profile Picture
</button>

</form>

</div>

</div>

</body>
</html>
