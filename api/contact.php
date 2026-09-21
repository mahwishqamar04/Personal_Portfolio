<?php
// Prevent PHP warnings/notices from corrupting JSON response
error_log('Contact API request received');
ini_set('display_errors', '0');
ob_start();

// Return clean JSON even on fatal errors
register_shutdown_function(function() {
    $error = error_get_last();
    if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        while (ob_get_level()) ob_end_clean();
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Server error. Please try again later.']);
    }
});

/**
 * Clean any buffered PHP output and send a JSON response.
 * Ensures warnings/notices never corrupt the JSON body.
 */
function respondJson($data, $exit = true) {
    while (ob_get_level()) ob_end_clean();
    header('Content-Type: application/json');
    echo json_encode($data);
    if ($exit) exit;
}

/**
 * Contact Form Handler
 * Processes contact form submissions and stores them in the database.
 */

session_start();

header('Content-Type: application/json');
header('X-Content-Type-Options: nosniff');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respondJson(['success' => false, 'message' => 'Invalid request method.']);
}

// CSRF token validation
$token = isset($_POST['csrf_token']) ? $_POST['csrf_token'] : (isset($_SERVER['HTTP_X_CSRF_TOKEN']) ? $_SERVER['HTTP_X_CSRF_TOKEN'] : '');

if (empty($token) || !isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
    respondJson(['success' => false, 'message' => 'CSRF token validation failed. Unauthorized request.']);
}

require_once __DIR__ . '/../includes/db.php';

// Sanitize and validate input
$name = isset($_POST['name']) ? trim(htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8')) : '';
$email = isset($_POST['email']) ? trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL)) : '';
$subject = isset($_POST['subject']) ? trim(htmlspecialchars($_POST['subject'], ENT_QUOTES, 'UTF-8')) : '';
$message = isset($_POST['message']) ? trim(htmlspecialchars($_POST['message'], ENT_QUOTES, 'UTF-8')) : '';

if (empty($name) || empty($email) || empty($subject) || empty($message)) {
    respondJson(['success' => false, 'message' => 'Please fill in all required fields.']);
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    respondJson(['success' => false, 'message' => 'Please enter a valid email address.']);
}

if (strlen($name) > 100 || strlen($subject) > 200 || strlen($message) > 2000) {
    respondJson(['success' => false, 'message' => 'Input exceeds maximum allowed length.']);
}

$conn = getDBConnection();

if (!$conn) {
    respondJson(['success' => false, 'message' => 'Server error. Please try again later.']);
}

$stmt = $conn->prepare('INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)');

if (!$stmt) {
    $conn->close();
    respondJson(['success' => false, 'message' => 'Server error. Please try again later.']);
}

$stmt->bind_param('ssss', $name, $email, $subject, $message);

if ($stmt->execute()) {
    respondJson(['success' => true, 'message' => 'Thank you! Your message has been sent successfully. Mehwish Qamar will get back to you soon.']);
} else {
    respondJson(['success' => false, 'message' => 'Failed to send message. Please try again later.']);
}

$stmt->close();
$conn->close();
