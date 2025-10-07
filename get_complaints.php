<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");  // allow Android to call
header("Access-Control-Allow-Methods: GET, POST");

// Database connection
$conn = new mysqli("localhost", "root", "", "campusbite");

// Check DB connection
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["success" => false, "error" => "Database connection failed"]);
    exit;
}

// Fetch complaints
$sql = "SELECT id, transaction_id, issue_type, issue_details, status, created_at, updated_at, canteen_name, user_id 
        FROM complaints 
        ORDER BY created_at DESC";

$result = $conn->query($sql);

$complaints = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $complaints[] = $row;
    }
}

echo json_encode([
    "success" => true,
    "complaints" => $complaints
], JSON_PRETTY_PRINT);

$conn->close();
exit;
?>
