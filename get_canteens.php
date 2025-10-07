<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header("Content-Type: application/json");

// DB connection
$conn = new mysqli("localhost", "root", "", "campusbite");
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => $conn->connect_error]));
}

// Fetch distinct canteen names from menu table
$sql = "SELECT DISTINCT canteen_name FROM menu";
$result = $conn->query($sql);

$canteens = [];
if ($result && $result->num_rows > 0) {
    $id = 1;
    while ($row = $result->fetch_assoc()) {
        $canteens[] = [
            "id" => $id,
            "name" => $row["canteen_name"]
        ];
        $id++;
    }
}

echo json_encode([
    "status" => "success",
    "canteens" => $canteens
]);

$conn->close();
?>
