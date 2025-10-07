<?php
header('Content-Type: application/json');

$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'campusbite';
$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "DB connection failed"]);
    exit;
}

$email = trim($_POST['email'] ?? '');
$new_password = trim($_POST['password'] ?? '');

if (empty($email) || empty($new_password)) {
    echo json_encode(["status" => "error", "message" => "All fields required"]);
    exit;
}

// Store password in readable/plain text form (NOT SECURE!)
$stmt = $conn->prepare("UPDATE auth SET password = ? WHERE email = ?");
$stmt->bind_param("ss", $new_password, $email);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Password reset successful"]);
} else {
    echo json_encode(["status" => "error", "message" => "Password reset failed"]);
}

$conn->close();
