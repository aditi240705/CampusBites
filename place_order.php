<?php
header('Content-Type: application/json');

$host = 'localhost';
$user = 'root';
$pass = '';  // Your MySQL password
$dbname = 'campusbite';

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get POST inputs
    $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
    $food_name = isset($_POST['food_name']) ? trim($_POST['food_name']) : '';
    $canteen_name = isset($_POST['canteen_name']) ? trim($_POST['canteen_name']) : '';
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 0;
    $pickup_time = isset($_POST['pickup_time']) ? trim($_POST['pickup_time']) : '';

    // Basic validation
    if ($user_id <= 0 || empty($food_name) || empty($canteen_name) || $quantity <= 0 || empty($pickup_time)) {
        http_response_code(400);
        echo json_encode(['error' => 'All fields are required and quantity must be > 0']);
        exit();
    }

    $price_sql = "SELECT price, discount FROM vendor WHERE LOWER(food_name) = LOWER(?) AND LOWER(canteen_name) = LOWER(?) LIMIT 1";
    $price_stmt = $conn->prepare($price_sql);
    $price_stmt->bind_param("ss", $food_name, $canteen_name);
    $price_stmt->execute();
    $result = $price_stmt->get_result();

    if ($result->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['error' => 'Food item not found in vendor table']);
        exit();
    }

    $row = $result->fetch_assoc();
    $original_price = floatval($row['price']);
    $discount_percent = floatval($row['discount']);

    // ✅ Step 2: Calculate discounted price
    $discounted_price_per_item = $original_price - ($original_price * ($discount_percent / 100));
    $total_price = $discounted_price_per_item * $quantity;

    // ✅ Step 3: Insert into orders table
    $insert_stmt = $conn->prepare("INSERT INTO orders (user_id, food_name, quantity, total_price, order_date, order_status, pickup_time, canteen_name) VALUES (?, ?, ?, ?, NOW(), 'pending', ?, ?)");
    $insert_stmt->bind_param("isidss", $user_id, $food_name, $quantity, $total_price, $pickup_time, $canteen_name);

    if ($insert_stmt->execute()) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Order placed successfully with discount',
            'original_price_per_item' => $original_price,
            'discount_percent' => $discount_percent,
            'discounted_price_per_item' => round($discounted_price_per_item, 2),
            'total_price' => round($total_price, 2)
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to place order']);
    }

    $insert_stmt->close();
    $price_stmt->close();
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Invalid request method']);
}

$conn->close();
?>
