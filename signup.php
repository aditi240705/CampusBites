<?php
header("Content-Type: application/json");

// Database connection
$conn = new mysqli("localhost", "root", "", "campusbite");

if ($conn->connect_error) {
    echo json_encode(["message" => "Database connection failed"]);
    exit;
}

// Get form values
$username = $_POST['username'] ?? '';
$email = $_POST['email'] ?? '';
$phone_number = $_POST['phone_number'] ?? '';
$password = $_POST['password'] ?? '';
$role = $_POST['role'] ?? '';

// Basic validation
if (empty($username) || empty($email) || empty($phone_number) || empty($password) || empty($role)) {
    echo json_encode(["message" => "All fields are required"]);
    exit;
}

// Allow only specific roles (user, vendor, admin)
$allowed_roles = ["user", "vendor", "admin"];
if (!in_array($role, $allowed_roles)) {
    echo json_encode(["message" => "Invalid role"]);
    exit;
}

// Check if user already exists
$check = $conn->prepare("SELECT * FROM auth WHERE email = ?");
$check->bind_param("s", $email);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
    echo json_encode(["message" => "Email already exists"]);
    exit;
}

// Insert user
$stmt = $conn->prepare("INSERT INTO auth (username, email, phone_number, password, role) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $username, $email, $phone_number, $password, $role);

if ($stmt->execute()) {
    echo json_encode(["message" => "Signup successful"]);
} else {
    echo json_encode(["message" => "Signup failed"]);
}

$stmt->close();
$conn->close();
?>