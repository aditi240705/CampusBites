<?php
require('razorpay-php/Razorpay.php'); // download from https://github.com/razorpay/razorpay-php
use Razorpay\Api\Api;

<?php
require('razorpay-php/Razorpay.php'); 
use Razorpay\Api\Api;

include 'dbconn.php'; // ✅ reuse your connection

$keyId = "rzp_test_TqVsYNPoGcZowj";      // Your Razorpay Key ID
$keySecret = "jP3Wwr88IRT9nQTpTDsKJ5fn"; 

$api = new Api($keyId, $keySecret);

// Get data from Android app
$input = json_decode(file_get_contents("php://input"), true);
$amount = $input['amount'];  
$currency = "INR";

// Create order in Razorpay
$order = $api->order->create([
    'receipt' => 'order_rcptid_' . rand(1000,9999),
    'amount' => $amount * 100,  // Razorpay accepts paise
    'currency' => $currency
]);

echo json_encode([
    "order_id" => $order['id'],
    "amount" => $order['amount'],
    "currency" => $order['currency'],
    "key" => $keyId
]);
?>
 // Your Razorpay Secret

$api = new Api($keyId, $keySecret);

// Get data from app (amount in INR, user_id, etc.)
$input = json_decode(file_get_contents("php://input"), true);

$amount = $input['amount'];  // e.g. 250
$currency = "INR";

// Create Razorpay order
$order = $api->order->create([
    'receipt' => 'order_rcptid_' . rand(1000,9999),
    'amount' => $amount * 100,   // amount in paise
    'currency' => $currency
]);

// Return order details to Android app
echo json_encode([
    "order_id" => $order['id'],
    "amount" => $order['amount'],
    "currency" => $order['currency'],
    "key" => $keyId
]);
?>
