<?php
header('Content-Type: application/json');

$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'campusbite';

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

// Fetch all canteens
$sql = "SELECT canteen_id, canteen_name FROM canteen_name ";
$result = $conn->query($sql);

$canteens = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $canteens[] = $row;
    }
    echo json_encode(['canteens' => $canteens]);
} else {
    echo json_encode(['canteens' => []]);
}

$conn->close();
?>
