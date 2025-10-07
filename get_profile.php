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

$user_id = 0;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
}

if ($user_id <= 0) {
    echo json_encode(['error' => 'user_id is required']);
    exit;
}

// ✅ Fetch user details
$stmt = $conn->prepare("
    SELECT user_id, username, email, phone_number, role, created_at 
    FROM auth 
    WHERE user_id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    // ✅ Fetch orders for this user (using canteen_name directly from orders table)
    $order_stmt = $conn->prepare("
        SELECT order_id, food_name, quantity, total_price, order_status, pickup_time, order_date, canteen_name
        FROM orders
        WHERE user_id = ?
        ORDER BY order_date DESC
    ");
    $order_stmt->bind_param("i", $user_id);
    $order_stmt->execute();
    $order_result = $order_stmt->get_result();

    $orders = [];
    while ($row = $order_result->fetch_assoc()) {
        $orders[] = [
            "order_id" => $row["order_id"],
            "food_name" => $row["food_name"],
            "quantity" => $row["quantity"],
            "total_price" => $row["total_price"],
            "order_status" => $row["order_status"],
            "pickup_time" => $row["pickup_time"],
            "order_date" => $row["order_date"],
            "canteen_name" => $row["canteen_name"]
        ];
    }
    $order_stmt->close();

    // ✅ Final response
    $response = [
        "user" => $user,
        "orders" => $orders
    ];

    echo json_encode($response, JSON_PRETTY_PRINT);

} else {
    echo json_encode(['error' => 'User not found']);
}

$stmt->close();
$conn->close();
?>
