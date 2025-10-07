<?php
header('Content-Type: application/json');

// Database connection
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'campusbite';

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

// Accept canteen_name from POST or GET
$canteen_name = '';
if (isset($_POST['canteen_name'])) {
    $canteen_name = $_POST['canteen_name'];
} elseif (isset($_GET['canteen_name'])) {
    $canteen_name = $_GET['canteen_name'];
}

if (empty($canteen_name)) {
    echo json_encode(['error' => 'canteen_name is required']);
    exit;
}

// Query to fetch food details
$sql = "SELECT m.food_name, m.image, m.price, m.discount, c.canteen_id, c.canteen_name
        FROM menu m
        JOIN canteen_name c ON m.canteen_name = c.canteen_name
        WHERE c.canteen_name = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $canteen_name);
$stmt->execute();
$result = $stmt->get_result();

$response = [];
while ($row = $result->fetch_assoc()) {
    // discount is percentage
    $discounted_price = $row['price'] - ($row['price'] * ($row['discount'] / 100));

    $response['canteen'][] = [
        'canteen_id' => $row['canteen_id'],
        'canteen_name' => $row['canteen_name'],
        'food_name' => $row['food_name'],
        'photo' => $row['image'],
        'price' => (float)$row['price'],
        'discount_percent' => (float)$row['discount'],
        'discounted_price' => round($discounted_price, 2)
    ];
}

if (empty($response)) {
    echo json_encode(['error' => 'No data found']);
} else {
    echo json_encode($response, JSON_PRETTY_PRINT);
}

$conn->close();
?>
