<?php
header('Content-Type: application/json');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include Composer autoload
require __DIR__ . '/vendor/autoload.php';

// Database configuration
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'campusbite';

// Connect to database
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "DB connection failed"]);
    exit;
}

// Get email from POST request
$email = trim($_POST['email'] ?? '');
if (empty($email)) {
    echo json_encode(["status" => "error", "message" => "Email is required"]);
    exit;
}

// Check if user exists
$stmt = $conn->prepare("SELECT user_id FROM auth WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["status" => "error", "message" => "No account found with that email"]);
    exit;
}

// Generate OTP
$otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
$expires_at = date('Y-m-d H:i:s', strtotime('+15 minutes'));

// Delete existing OTPs
$deleteStmt = $conn->prepare("DELETE FROM password_resets WHERE email = ?");
$deleteStmt->bind_param("s", $email);
$deleteStmt->execute();

// Insert new OTP
$insertStmt = $conn->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)");
$insertStmt->bind_param("sss", $email, $otp, $expires_at);
$insertStmt->execute();

// Send OTP email using PHPMailer
try {
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 's.shreyaaditi11a@gmail.com';       // Your Gmail
    $mail->Password = 'hdaw lsww hsnn necj';   // Your Gmail App Password (16 chars)
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('yourgmail@gmail.com', 'CampusBite');
    $mail->addAddress($email);
    $mail->isHTML(true);
    $mail->Subject = 'CampusBite - Password Reset OTP';
    $mail->Body    = "<p>Your OTP is: <strong>$otp</strong><br>It will expire in 15 minutes.</p>";

    $mail->send();

    echo json_encode(["status" => "success", "message" => "OTP sent to your email"]);
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => "Mailer Error: " . $mail->ErrorInfo]);
}

$conn->close();
