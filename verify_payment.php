<?php
require('razorpay-php/Razorpay.php');
use Razorpay\Api\Api;

include 'dbconn.php'; // ✅ reuse db connection

$keyId = "rzp_test_TqVsYNPoGcZowj";      // Your Razorpay Key ID
$keySecret = "jP3Wwr88IRT9nQTpTDsKJ5fn"; 

$api = new Api($keyId, $keySecret);

$input = json_decode(file_get_contents("php://input"), true);

$payment_id   = $input['razorpay_payment_id'];
$order_id     = $input['razorpay_order_id'];
$signature    = $input['razorpay_signature'];
$user_id      = $input['user_id'];
$food_name    = $input['food_name'];
$quantity     = $input['quantity'];
$total_price  = $input['total_price'];
$pickup_time  = $input['pickup_time'];

try {
    // Verify signature
    $attributes = [
        'razorpay_order_id' => $order_id,
        'razorpay_payment_id' => $payment_id,
        'razorpay_signature' => $signature
    ];
    $api->utility->verifyPaymentSignature($attributes);

    // Insert order in DB
    $sql = "INSERT INTO orders (user_id, food_name, quantity, total_price, order_date, order_status, pickup_time, payment_id)
            VALUES ('$user_id', '$food_name', '$quantity', '$total_price', NOW(), 'Paid', '$pickup_time', '$payment_id')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["success" => true, "message" => "Payment verified & order saved"]);
    } else {
        echo json_encode(["success" => false, "message" => "DB Error: " . $conn->error]);
    }

} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Payment Verification Failed"]);
}
?>
