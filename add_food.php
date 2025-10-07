<?php
header('Content-Type: application/json');

// Database connection
$conn = new mysqli("localhost", "root", "", "campusbite");
if ($conn->connect_error) {
    die(json_encode(["error" => "Database connection failed."]));
}

// Collect input data
$email      = $_POST['email'] ?? null;   // vendor email from request
$food_name  = $_POST['food_name'] ?? null;
$price      = $_POST['price'] ?? null;

// Validate required fields
if (!$email || !$food_name || !$price) {
    echo json_encode(["error" => "Required fields are missing"]);
    exit;
}

// Step 1: Find canteen name from vendor table
$stmt = $conn->prepare("SELECT canteen_name FROM canteen_name WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $canteen_name = $row['canteen_name'];

    // Step 2: Insert food item into menu
    $insert = $conn->prepare("INSERT INTO menu (canteen_name, food_name, price) VALUES (?, ?, ?)");
    $insert->bind_param("ssi", $canteen_name, $food_name, $price);

    if ($insert->execute()) {
        echo json_encode(["status" => "success", "message" => "Food item added successfully","canteen_name" => $canteen_name]);
    } else {
        echo json_encode(["error" => "Failed to add food item"]);
    }

    $insert->close();
} else {
    echo json_encode(["error" => "Vendor not found for the given email"]);
}

$stmt->close();
$conn->close();
?>
