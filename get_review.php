<?php
session_start();

// Database connection
$host = 'localhost';
$user = 'root';
$pass = ''; // add your MySQL password if needed
$dbname = 'campusbite';

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    echo json_encode([
        "status" => "error",
        "message" => "Connection failed: " . $conn->connect_error
    ]);
    exit;
}

// Accept inputs (optional)
$food_name = $_REQUEST["food_name"] ?? null;
$canteen_name = $_REQUEST["canteen_name"] ?? null;

// Base query (fetch everything by default)
$query = "SELECT food_name, canteen_name, rating, review, review_date FROM reviews";
$params = [];
$types = "";

// Add filtering only if inputs are provided
$conditions = [];
if (!empty($food_name)) {
    $conditions[] = "food_name = ?";
    $params[] = $food_name;
    $types .= "s";
}
if (!empty($canteen_name)) {
    $conditions[] = "canteen_name = ?";
    $params[] = $canteen_name;
    $types .= "s";
}
if (!empty($conditions)) {
    $query .= " WHERE " . implode(" AND ", $conditions);
}

// Always order latest first
$query .= " ORDER BY review_date DESC";

// Prepare statement
$stmt = $conn->prepare($query);
if ($stmt === false) {
    echo json_encode([
        "status" => "error",
        "message" => "Prepare failed: " . $conn->error
    ]);
    exit;
}

// Bind params if needed
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

// Execute
$stmt->execute();
$result = $stmt->get_result();

// Collect results
$reviews = [];
while ($row = $result->fetch_assoc()) {
    $reviews[] = $row;
}

// Output JSON
echo json_encode([
    "status" => "success",
    "results" => count($reviews),
    "data" => $reviews
]);

// Cleanup
$stmt->close();
$conn->close();
?>
