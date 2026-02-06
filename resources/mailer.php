<?php
session_start();
require_once __DIR__ . "/../admin/include/classes/config.php";

// Set JSON header
header('Content-Type: application/json');

// Only process POST requests
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    http_response_code(403);
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method.'
    ]);
    exit;
}

// Sanitize inputs
$fname   = trim(strip_tags($_POST["fname"] ?? ''));
$lname   = trim(strip_tags($_POST["lname"] ?? ''));
$email   = filter_var(trim($_POST["email"] ?? ''), FILTER_SANITIZE_EMAIL);
$subject = trim(strip_tags($_POST["subject"] ?? ''));
$message = trim(strip_tags($_POST["message"] ?? ''));
$rcs     = isset($_POST["rcs_agree"]) ? 1 : 0;

// Validation array
$errors = [];

if (empty($fname)) {
    $errors[] = 'First name is required.';
}

if (empty($lname)) {
    $errors[] = 'Last name is required.';
}

if (empty($email)) {
    $errors[] = 'Email is required.';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Please enter a valid email address.';
}

if (empty($subject)) {
    $errors[] = 'Subject is required.';
}

if (empty($message)) {
    $errors[] = 'Message is required.';
}

if ($rcs != 1) {
    $errors[] = 'You must agree to receive messages via RCS.';
}

// If there are validation errors, return them
if (!empty($errors)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Please correct the following errors: ' . implode(' ', $errors),
        'errors' => $errors
    ]);
    exit;
}

// Insert into database
$stmt = $conn->prepare("
    INSERT INTO contact_messages
    (fname, lname, email, subject, message, rcs_agree, created_at)
    VALUES (?, ?, ?, ?, ?, ?, NOW())
");

if ($stmt) {
    $stmt->bind_param(
        "sssssi",
        $fname,
        $lname,
        $email,
        $subject,
        $message,
        $rcs
    );

    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'message' => 'Thank you for contacting us! Your message has been submitted successfully. We will get back to you soon.'
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Failed to save your message. Please try again later.'
        ]);
    }

    $stmt->close();
} else {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error. Please contact administrator.'
    ]);
}

$conn->close();
