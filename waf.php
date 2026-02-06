<?php

// Set the target domain for proxying requests
$target_domain = 'https://ditrpindia.org';  // Replace this with your actual target domain

// Regular expressions for detecting SQL injection and XSS
$sql_injection_patterns = [
    "/(?i)UNION.SELECT./",
    "/(?i)SELECT.FROM./",
    "/(?i)DROP.TABLE./",
    "/(?i)INSERT.INTO./",
    "/(?i)UPDATE.SET./",
    "/(?i)DELETE.FROM./",
    "/(?i)OR.1.=.*1/"
];

$xss_patterns = [
    "/<script.?>.?<\/script.*?>/i",
    "/javascript:/i",
    "/<.?on.?>/i",
    "/<img.?src=.?onerror=.*?>/i",
    "/<iframe.?>.?<\/iframe.*?>/i",
    "/<object.?>.?<\/object.*?>/i",
    "/alert/i",
    "/prompt/i"
];

$file_inclusion_patterns = [
    "/\.\.\//i",  // Directory traversal
    "/file:\/\//i",  // File inclusion
    "/php:\/\//i",  // PHP wrappers
    "/data:\/\//i"  // Data URI schemes
];

$ssrf_patterns = [
    "/169\.254\.169\.254/",  // AWS metadata endpoint
    "/localhost/",  // Localhost address
    "/127\.0\.0\.1/",  // Loopback address
    "/::1/",  // IPv6 loopback
    "/0\.0\.0\.0/"  // Wildcard address
];

// Helper function to detect patterns
function detect_patterns($data, $patterns)
{
    foreach ($patterns as $pattern) {
        if (preg_match($pattern, $data)) {
            return true;
        }
    }
    return false;
}

// Function to sanitize input (removes < > characters to avoid XSS)
function sanitize_input($input)
{
    return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
}

// WAF protection function to check requests
function waf_protect()
{
    global $sql_injection_patterns, $xss_patterns, $file_inclusion_patterns, $ssrf_patterns;

    // Check GET parameters
    foreach ($_GET as $key => $value) {
        if (
            detect_patterns($value, $sql_injection_patterns) || detect_patterns($value, $xss_patterns) ||
            detect_patterns($value, $file_inclusion_patterns) || detect_patterns($value, $ssrf_patterns)
        ) {
            die(json_encode(["error" => "Malicious content detected in GET parameters!"]));
        }
    }

    // Check POST data
    foreach ($_POST as $key => $value) {
        if (
            detect_patterns($value, $sql_injection_patterns) || detect_patterns($value, $xss_patterns) ||
            detect_patterns($value, $file_inclusion_patterns) || detect_patterns($value, $ssrf_patterns)
        ) {
            die(json_encode(["error" => "Malicious content detected in POST data!"]));
        }
    }

    // Check headers for potential attacks
    foreach (getallheaders() as $key => $value) {
        if (
            detect_patterns($value, $sql_injection_patterns) || detect_patterns($value, $xss_patterns) ||
            detect_patterns($value, $file_inclusion_patterns) || detect_patterns($value, $ssrf_patterns)
        ) {
            die(json_encode(["error" => "Malicious content detected in headers!"]));
        }
    }

    // Check JSON payload (if any)
    if ($_SERVER['CONTENT_TYPE'] == 'application/json') {
        $data = json_decode(file_get_contents('php://input'), true);
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                if (is_string($value) && (detect_patterns($value, $sql_injection_patterns) ||
                    detect_patterns($value, $xss_patterns) ||
                    detect_patterns($value, $file_inclusion_patterns) ||
                    detect_patterns($value, $ssrf_patterns))) {
                    die(json_encode(["error" => "Malicious content detected in JSON payload!"]));
                }
            }
        }
    }
}

// Apply WAF protection
waf_protect();

// Function to proxy requests to the target domain
function proxy_request($path, $method, $data = null)
{
    global $target_domain;
    $url = rtrim($target_domain, '/') . '/' . ltrim($path, '/');

    $ch = curl_init($url);

    switch ($method) {
        case 'POST':
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            break;
        case 'GET':
            curl_setopt($ch, CURLOPT_HTTPGET, 1);
            break;
        case 'PUT':
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            break;
        case 'DELETE':
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
            break;
            // Add other methods if needed
    }

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}
