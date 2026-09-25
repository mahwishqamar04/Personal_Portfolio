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

/* Load current profile */
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

/* Upload new profile picture */
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

                    /* Remove previous profile image only if it belongs
                       to the portfolio profile-image directory. */
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

.crop-section {
    display: none;
    margin-top: 25px;
    padding-top: 25px;
    border-top: 1px solid #eee;
}

.crop-section.active {
    display: block;
}

.crop-area {
    width: 320px;
    height: 320px;
    margin: 20px auto;
    position: relative;
    overflow: hidden;
    background: #ddd;
    border-radius: 50%;
    border: 5px solid #222;
}

.crop-area img {
    position: absolute;
    max-width: none;
    user-select: none;
    -webkit-user-drag: none;
    cursor: grab;
}

.crop-area img:active {
    cursor: grabbing;
}

.crop-overlay {
    position: absolute;
    inset: 0;
    border-radius: 50%;
    box-shadow: inset 0 0 0 9999px rgba(0,0,0,.12);
    pointer-events: none;
}

.controls {
    max-width: 500px;
    margin: 0 auto;
}

.control-row {
    margin: 15px 0;
}

.control-row label {
    display: flex;
    justify-content: space-between;
    margin-bottom: 7px;
    font-weight: 600;
}

input[type="range"] {
    width: 100%;
}

input[type="file"] {
    width: 100%;
    box-sizing: border-box;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 6px;
}

.button-row {
    display: flex;
    gap: 10px;
    justify-content: center;
    flex-wrap: wrap;
    margin-top: 20px;
}

.submit-button,
.reset-button {
    padding: 11px 18px;
    border: 0;
    border-radius: 6px;
    color: #fff;
    cursor: pointer;
}

.submit-button {
    background: #222;
}

.reset-button {
    background: #666;
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

.crop-info {
    text-align: center;
    color: #666;
    font-size: 14px;
    line-height: 1.5;
}
</style>
</head>

<body>

<div class="container">

<h1>Manage Profile Picture</h1>

<p class="subtitle">
    Upload your profile picture and adjust the position and zoom before saving.
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
    alt="Current Profile Picture">

<?php else: ?>

<p>No profile picture is currently set.</p>

<?php endif; ?>
</div>

<form
    method="POST"
    enctype="multipart/form-data"
    id="profileForm">

<label for="profile_image">
    Choose New Profile Picture
</label>

<input
    type="file"
    id="profile_image"
    name="profile_image"
    accept=".jpg,.jpeg,.png,.webp"
    required>

<p class="info">
    Maximum size: 5 MB. Allowed formats: JPG, JPEG, PNG and WebP.
</p>

<div class="crop-section" id="cropSection">

<h2>Adjust Your Picture</h2>

<p class="crop-info">
    Drag the image inside the circle to change its position.
    Use the zoom slider to control how much of the image is visible.
</p>

<div class="crop-area" id="cropArea">

<img id="cropImage" src="" alt="Crop Preview">

<div class="crop-overlay"></div>

</div>

<div class="controls">

<div class="control-row">
<label for="zoom">
    <span>Zoom</span>
    <span id="zoomValue">100%</span>
</label>

<input
    type="range"
    id="zoom"
    min="100"
    max="300"
    value="100"
    step="1">
</div>

</div>

<div class="button-row">

<button
    type="button"
    class="reset-button"
    id="resetCrop">
    Reset Position
</button>

<button
    type="submit"
    class="submit-button"
    id="saveButton">
    Save Adjusted Picture
</button>

</div>

</div>

</form>

</div>

</div>

<script>
const fileInput = document.getElementById('profile_image');
const cropSection = document.getElementById('cropSection');
const cropArea = document.getElementById('cropArea');
const cropImage = document.getElementById('cropImage');
const zoomSlider = document.getElementById('zoom');
const zoomValue = document.getElementById('zoomValue');
const resetCrop = document.getElementById('resetCrop');
const profileForm = document.getElementById('profileForm');

let imageWidth = 0;
let imageHeight = 0;

let baseWidth = 0;
let baseHeight = 0;

let scale = 1;
let posX = 0;
let posY = 0;

let startX = 0;
let startY = 0;
let dragging = false;

const cropSize = 320;

/* Select image */
fileInput.addEventListener('change', function () {

    const file = this.files[0];

    if (!file) {
        cropSection.classList.remove('active');
        return;
    }

    if (file.size > 5 * 1024 * 1024) {
        alert('Profile picture must be 5 MB or smaller.');
        this.value = '';
        cropSection.classList.remove('active');
        return;
    }

    const reader = new FileReader();

    reader.onload = function (event) {

        cropImage.onload = function () {

            imageWidth = cropImage.naturalWidth;
            imageHeight = cropImage.naturalHeight;

            /* Cover the circular crop area */
            const coverScale = Math.max(
                cropSize / imageWidth,
                cropSize / imageHeight
            );

            baseWidth = imageWidth * coverScale;
            baseHeight = imageHeight * coverScale;

            scale = 1;

            posX = (cropSize - baseWidth) / 2;
            posY = (cropSize - baseHeight) / 2;

            zoomSlider.value = 100;
            zoomValue.textContent = '100%';

            updateImage();

            cropSection.classList.add('active');
        };

        cropImage.src = event.target.result;
    };

    reader.readAsDataURL(file);
});

/* Update image position and zoom */
function updateImage() {

    const width = baseWidth * scale;
    const height = baseHeight * scale;

    cropImage.style.width = width + 'px';
    cropImage.style.height = height + 'px';

    cropImage.style.left = posX + 'px';
    cropImage.style.top = posY + 'px';
}

/* Zoom */
zoomSlider.addEventListener('input', function () {

    const oldScale = scale;

    scale = Number(this.value) / 100;

    const centerX = cropSize / 2;
    const centerY = cropSize / 2;

    const oldWidth = baseWidth * oldScale;
    const oldHeight = baseHeight * oldScale;

    const imageCenterX = posX + oldWidth / 2;
    const imageCenterY = posY + oldHeight / 2;

    const relativeX = imageCenterX - centerX;
    const relativeY = imageCenterY - centerY;

    posX = centerX + relativeX * (scale / oldScale) - (baseWidth * scale) / 2;
    posY = centerY + relativeY * (scale / oldScale) - (baseHeight * scale) / 2;

    zoomValue.textContent = this.value + '%';

    keepImageInsideCrop();
    updateImage();
});

/* Mouse dragging */
cropImage.addEventListener('mousedown', function (event) {

    event.preventDefault();

    dragging = true;

    startX = event.clientX - posX;
    startY = event.clientY - posY;
});

document.addEventListener('mousemove', function (event) {

    if (!dragging) {
        return;
    }

    posX = event.clientX - startX;
    posY = event.clientY - startY;

    keepImageInsideCrop();
    updateImage();
});

document.addEventListener('mouseup', function () {
    dragging = false;
});

/* Touch dragging */
cropImage.addEventListener('touchstart', function (event) {

    const touch = event.touches[0];

    dragging = true;

    startX = touch.clientX - posX;
    startY = touch.clientY - posY;
}, { passive: true });

document.addEventListener('touchmove', function (event) {

    if (!dragging) {
        return;
    }

    const touch = event.touches[0];

    posX = touch.clientX - startX;
    posY = touch.clientY - startY;

    keepImageInsideCrop();
    updateImage();

}, { passive: true });

document.addEventListener('touchend', function () {
    dragging = false;
});

/* Prevent empty space inside crop */
function keepImageInsideCrop() {

    const width = baseWidth * scale;
    const height = baseHeight * scale;

    if (width <= cropSize) {
        posX = (cropSize - width) / 2;
    } else {
        if (posX > 0) {
            posX = 0;
        }

        if (posX + width < cropSize) {
            posX = cropSize - width;
        }
    }

    if (height <= cropSize) {
        posY = (cropSize - height) / 2;
    } else {
        if (posY > 0) {
            posY = 0;
        }

        if (posY + height < cropSize) {
            posY = cropSize - height;
        }
    }
}

/* Reset */
resetCrop.addEventListener('click', function () {

    scale = 1;

    posX = (cropSize - baseWidth) / 2;
    posY = (cropSize - baseHeight) / 2;

    zoomSlider.value = 100;
    zoomValue.textContent = '100%';

    updateImage();
});

/* Create final cropped image before submitting */
profileForm.addEventListener('submit', function (event) {

    if (!fileInput.files.length) {
        return;
    }

    event.preventDefault();

    const canvas = document.createElement('canvas');

    canvas.width = 800;
    canvas.height = 800;

    const ctx = canvas.getContext('2d');

    const finalScale = canvas.width / cropSize;

    const drawX = posX * finalScale;
    const drawY = posY * finalScale;

    const drawWidth = baseWidth * scale * finalScale;
    const drawHeight = baseHeight * scale * finalScale;

    ctx.clearRect(0, 0, canvas.width, canvas.height);

    ctx.drawImage(
        cropImage,
        drawX,
        drawY,
        drawWidth,
        drawHeight
    );

    canvas.toBlob(function (blob) {

        if (!blob) {
            alert('Unable to prepare the adjusted image.');
            return;
        }

        const croppedFile = new File(
            [blob],
            'profile-adjusted.jpg',
            {
                type: 'image/jpeg',
                lastModified: Date.now()
            }
        );

        const dataTransfer = new DataTransfer();

        dataTransfer.items.add(croppedFile);

        fileInput.files = dataTransfer.files;

        profileForm.submit();

    }, 'image/jpeg', 0.92);
});
</script>

</body>
</html>
