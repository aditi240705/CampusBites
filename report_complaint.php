<?php
header('Content-Type: application/json');

// Database connection
$conn = new mysqli("localhost", "root", "", "campusbite");

// Check DB connection
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

// Collect inputs
$user_id        = isset($_POST['user_id']) ? trim($_POST['user_id']) : '';
$transaction_id = isset($_POST['transaction_id']) ? trim($_POST['transaction_id']) : '';
$issue_type     = isset($_POST['issue_type']) ? trim($_POST['issue_type']) : '';
$issue_details  = isset($_POST['issue_details']) ? trim($_POST['issue_details']) : '';
$canteen_name   = isset($_POST['canteen_name']) ? trim($_POST['canteen_name']) : '';

// Validate input
if (empty($user_id) || empty($transaction_id) || empty($issue_type) || empty($canteen_name)) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid input. 'user_id', 'transaction_id', 'issue_type' and 'canteen_name' are required."]);
    exit;
}

// Insert complaint
$stmt = $conn->prepare("INSERT INTO complaints (user_id, transaction_id, issue_type, issue_details, canteen_name) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $user_id, $transaction_id, $issue_type, $issue_details, $canteen_name);

if ($stmt->execute()) {
    echo json_encode([
        "message" => "Complaint Submitted Successfully",
        "resolution_eta" => "2-3 business days"
    ]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Failed to submit complaint."]);
}

$stmt->close();
$conn->close();
?>
