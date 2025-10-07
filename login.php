<?php

// DB connection
require 'dbconn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Use raw input if app sends JSON
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    // Fallback to $_POST if form-encoded
    $email = isset($data['email']) ? trim($data['email']) : trim($_POST['email'] ?? '');
    $password = isset($data['password']) ? trim($data['password']) : trim($_POST['password'] ?? '');

    if (empty($email) || empty($password)) {
        echo json_encode([
            "status" => "error",
            "message" => "Email and password required"
        ]);
        exit;
    }

    $stmt = $conn->prepare("SELECT user_id, username, role, password, email FROM auth WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($user_id, $username, $role, $stored_password, $fetched_email);
        $stmt->fetch();

        // Compare passwords (NOTE: Use password_verify() if hashed)
        if ($password === $stored_password) {
            echo json_encode([
                "status" => "success",
                "message" => "Login successful",
                "user" => [
                    "user_id" => $user_id,
                    "username" => $username,
                    "email" => $fetched_email,
                    "role" => $role
                ]
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Invalid credentials"
            ]);
        }
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Email not found"
        ]);
    }

    $stmt->close();
}

$conn->close();
?>
