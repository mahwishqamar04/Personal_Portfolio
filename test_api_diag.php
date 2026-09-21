<?php
// Diagnostic test - API endpoint direct HTTP test
echo "=== API ENDPOINT DIAGNOSTIC ===\n";

// Test 1: Can we reach the API endpoint?
$url = 'http://localhost/Personal_Portfolio/api/contact.php';

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'csrf_token' => 'test_token_123',
    'name' => 'Test User',
    'email' => 'test@example.com',
    'subject' => 'Test Subject',
    'message' => 'Test message body'
]));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'X-CSRF-TOKEN: test_token_123'
]);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$error = curl_error($ch);

curl_close($ch);

echo "URL: $url\n";
echo "HTTP Status Code: $httpCode\n";

if ($error) {
    echo "CURL ERROR: $error\n";
} else {
    $headers = substr($response, 0, $headerSize);
    $body = substr($response, $headerSize);
    
    echo "\nRESPONSE HEADERS:\n$headers\n";
    echo "RESPONSE BODY:\n$body\n";
    
    // Try to parse as JSON
    $json = json_decode($body, true);
    if ($json !== null) {
        echo "\nJSON PARSE: SUCCESS\n";
        echo "JSON DATA: " . print_r($json, true) . "\n";
    } else {
        echo "\nJSON PARSE: FAILED - " . json_last_error_msg() . "\n";
        echo "RAW BODY LENGTH: " . strlen($body) . " bytes\n";
        echo "RAW BODY HEX (first 200 bytes): " . bin2hex(substr($body, 0, 200)) . "\n";
    }
}

echo "\n=== END API DIAGNOSTIC ===\n";
