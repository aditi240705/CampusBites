<?php
// Show errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

// DB Connection
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "campusbite";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// Check action
$action = $_POST['action'] ?? '';

if ($action === 'view_live_orders') {
    $liveOrders = [];
    $result = $conn->query("SELECT * FROM orders WHERE order_status='Pending'");
    while ($row = $result->fetch_assoc()) {
        $liveOrders[] = $row;
    }
    echo json_encode($liveOrders);
}

elseif ($action === 'accept' && isset($_POST['order_id'])) {
    $orderId = intval($_POST['order_id']);
    $conn->query("UPDATE orders SET order_status='Accepted' WHERE order_id=$orderId");
    echo json_encode(["message" => "Order accepted"]);
}

elseif ($action === 'reject' && isset($_POST['order_id'])) {
    $orderId = intval($_POST['order_id']);
    $conn->query("UPDATE orders SET order_status='Rejected' WHERE order_id=$orderId");
    echo json_encode(["message" => "Order rejected"]);
}

elseif ($action === 'view_accepted_orders') {
    $acceptedOrders = [];
    $result = $conn->query("SELECT * FROM orders WHERE order_status='Accepted'");
    while ($row = $result->fetch_assoc()) {
        $acceptedOrders[] = $row;
    }
    echo json_encode($acceptedOrders);
}

elseif ($action === 'mark_ready' && isset($_POST['order_id'])) {
    $orderId = intval($_POST['order_id']);
    $conn->query("UPDATE orders SET order_status='Completed' WHERE order_id=$orderId");
    echo json_encode(["message" => "Order marked as Ready"]);
}

else {
    echo json_encode(["error" => "Invalid or missing action"]);
}

$conn->close();
?>
