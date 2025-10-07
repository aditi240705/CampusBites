<?php
// Set response type
header('Content-Type: application/json');

// Database connection
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'campusbite';

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
    exit();
}

// Get input
$canteen_name = isset($_POST['canteen_name']) ? trim($_POST['canteen_name']) : '';

if (empty($canteen_name)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'canteen_name is required']);
    exit();
}

// Query menu items for the given canteen
$stmt = $conn->prepare("SELECT image,food_name, price, available, discount 
                        FROM menu 
                        WHERE canteen_name = ?");
$stmt->bind_param("s", $canteen_name);
$stmt->execute();
$result = $stmt->get_result();

$menu = [];
while ($row = $result->fetch_assoc()) {
    $menu[] = $row;
}

if (count($menu) > 0) {
    echo json_encode([
        'status' => 'success',
        'canteen' => $canteen_name,
        'menu' => $menu
    ]);
} else {
    echo json_encode([
        'status' => 'success',
        'canteen' => $canteen_name,
        'menu' => [],
        'message' => 'No dishes available in this canteen'
    ]);
}

$stmt->close();
$conn->close();
?>
