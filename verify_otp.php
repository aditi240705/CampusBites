<?php
header('Content-Type: application/json');

$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'campusbite';
$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    echo json_encode(["status" => false, "message" => "DB connection failed"]);
    exit;
}

$email = trim($_POST['email'] ?? '');
$otp = trim($_POST['otp'] ?? '');

if (empty($email) || empty($otp)) {
    echo json_encode(["status" => false, "message" => "Email and OTP required"]);
    exit;
}

$stmt = $conn->prepare("SELECT token, expires_at FROM password_resets WHERE email = ? AND token = ?");
$stmt->bind_param("ss", $email, $otp);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["status" => false, "message" => "Invalid OTP"]);
    exit;
}

$row = $result->fetch_assoc();
if (strtotime($row['expires_at']) < time()) {
    echo json_encode(["status" => false, "message" => "OTP expired"]);
} else {
    echo json_encode(["status" => true, "message" => "OTP verified"]);
}

$conn->close();
