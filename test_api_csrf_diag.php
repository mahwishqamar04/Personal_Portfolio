<?php
// Diagnostic: Test API with valid CSRF token to see what happens at DB connection step
echo "=== API WITH VALID CSRF TOKEN ===\n";

// Step 1: Get a valid CSRF token by loading the main page
$ch = curl_init('http://localhost/Personal_Portfolio/index.php');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt');
$page = curl_exec($ch);
curl_close($ch);

// Extract CSRF token from meta tag
if (preg_match('/<meta\s+name="csrf-token"\s+content="([^"]+)"/', $page, $matches)) {
    $csrfToken = $matches[1];
    echo "CSRF Token obtained: " . substr($csrfToken, 0, 20) . "...\n";
} else {
    echo "ERROR: Could not extract CSRF token from page\n";
    // Try to find any session-related info
    echo "Page length: " . strlen($page) . "\n";
    echo "First 500 chars: " . substr($page, 0, 500) . "\n";
    exit(1);
}

// Step 2: Submit contact form with valid CSRF token
$ch = curl_init('http://localhost/Personal_Portfolio/api/contact.php');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt');
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'csrf_token' => $csrfToken,
    'name' => 'Diagnostic Test',
    'email' => 'diag@test.com',
    'subject' => 'Diagnostic Test',
    'message' => 'This is a diagnostic test submission'
]));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'X-CSRF-TOKEN: ' . $csrfToken
]);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$error = curl_error($ch);
curl_close($ch);

echo "\nHTTP Status Code: $httpCode\n";

if ($error) {
    echo "CURL ERROR: $error\n";
} else {
    $headers = substr($response, 0, $headerSize);
    $body = substr($response, $headerSize);
    
    echo "\nRESPONSE HEADERS:\n$headers\n";
    echo "RESPONSE BODY (raw):\n$body\n";
    echo "\nBODY LENGTH: " . strlen($body) . " bytes\n";
    
    // Check for BOM
    $bom = bin2hex(substr($body, 0, 3));
    if ($bom === 'efbbbf') {
        echo "WARNING: UTF-8 BOM detected at start of response!\n";
    }
    
    // Try JSON parse
    $json = json_decode($body, true);
    if ($json !== null) {
        echo "\nJSON PARSE: SUCCESS\n";
        echo "success: " . ($json['success'] ? 'true' : 'false') . "\n";
        echo "message: " . $json['message'] . "\n";
    } else {
        echo "\nJSON PARSE: FAILED - " . json_last_error_msg() . "\n";
        echo "HEX (first 100 bytes): " . bin2hex(substr($body, 0, 100)) . "\n";
    }
}

// Clean up cookie file
@unlink('cookie.txt');

echo "\n=== END API CSRF TEST ===\n";
