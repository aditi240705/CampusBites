<?php
header("Content-Type: application/json");

$host = "localhost";
$dbname = "campusbite";
$username = 'root';
$password = '';

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Connection failed"]);
    exit;
}
$conn->set_charset('utf8mb4');

function get_cart($conn, $user_id) {
    $stmt = $conn->prepare("SELECT food_name, quantity, price, total_price FROM cart WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $res = $stmt->get_result();

    $items = [];
    $grand_total = 0.0;

    while ($row = $res->fetch_assoc()) {
        $items[] = [
            "food_name"   => $row["food_name"],
            "quantity"    => (int)$row["quantity"],
            "price"       => (float)$row["price"],
            "total_price" => (float)$row["total_price"]
        ];
        $grand_total += (float)$row["total_price"];
    }

    return ["cart_items" => $items, "grand_total" => $grand_total];
}

/**
 * GET → list cart with ?user_id=1   
 */
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $user_id = isset($_GET["user_id"]) ? (int)$_GET["user_id"] : 0;
    if (!$user_id) {
        http_response_code(400);
        echo json_encode(["error" => "Missing user_id"]);
        exit;
    }
    echo json_encode(get_cart($conn, $user_id));
    exit;
}

/**
 * POST → add/update/delete/decrease/clear based on available keys
 */
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id   = isset($_POST["user_id"]) ? (int)$_POST["user_id"] : 0;
    $food_name = trim($_POST["food_name"] ?? "");
    $qty       = isset($_POST["quantity"]) ? (int)$_POST["quantity"] : 1;

    if (!$user_id) {
        http_response_code(400);
        echo json_encode(["error" => "Missing user_id"]);
        exit;
    }

    // ✅ Clear cart
    if (isset($_POST["clear"])) {
        $cs = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
        $cs->bind_param("i", $user_id);
        $cs->execute();
        echo json_encode(["message" => "Cart cleared"] + get_cart($conn, $user_id));
        exit;
    }

    // ✅ Delete item
    if (isset($_POST["delete"]) && $food_name !== "") {
        $ds = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND food_name = ?");
        $ds->bind_param("is", $user_id, $food_name);
        $ds->execute();
        echo json_encode(["message" => "Item deleted"] + get_cart($conn, $user_id));
        exit;
    }

    // ✅ Decrease item quantity
    if (isset($_POST["decrease"]) && $food_name !== "") {
        $cs = $conn->prepare("SELECT quantity, price FROM cart WHERE user_id = ? AND food_name = ?");
        $cs->bind_param("is", $user_id, $food_name);
        $cs->execute();
        $cr = $cs->get_result();

        if ($crow = $cr->fetch_assoc()) {
            $current_qty = (int)$crow["quantity"];
            $unit_price  = (float)$crow["price"];
            $new_qty     = max(1, $current_qty - $qty);
            $new_total   = $new_qty * $unit_price;

            $us = $conn->prepare("UPDATE cart SET quantity = ?, total_price = ? WHERE user_id = ? AND food_name = ?");
            $us->bind_param("idis", $new_qty, $new_total, $user_id, $food_name);
            $us->execute();

            echo json_encode(["message" => "Quantity decreased"] + get_cart($conn, $user_id));
            exit;
        } else {
            echo json_encode(["error" => "Item not found"]);
            exit;
        }
    }

    // ✅ Add or update item
    if ($food_name !== "") {
        $ms = $conn->prepare("SELECT price FROM menu WHERE food_name = ?");
        $ms->bind_param("s", $food_name);
        $ms->execute();
        $mr = $ms->get_result();
        if (!($mrow = $mr->fetch_assoc())) {
            echo json_encode(["error" => "Food item not found in menu"]);
            exit;
        }
        $unit_price = (float)$mrow["price"];

        $cs = $conn->prepare("SELECT quantity FROM cart WHERE user_id = ? AND food_name = ?");
        $cs->bind_param("is", $user_id, $food_name);
        $cs->execute();
        $cr = $cs->get_result();

        if ($crow = $cr->fetch_assoc()) {
            $new_qty = (int)$crow["quantity"] + $qty;
            $new_total = $new_qty * $unit_price;

            $us = $conn->prepare("UPDATE cart SET quantity = ?, price = ?, total_price = ? WHERE user_id = ? AND food_name = ?");
            $us->bind_param("iddis", $new_qty, $unit_price, $new_total, $user_id, $food_name);
            $us->execute();
        } else {
            $total = $qty * $unit_price;
            $is = $conn->prepare("INSERT INTO cart (user_id, food_name, quantity, price, total_price, added_at) VALUES (?, ?, ?, ?, ?, NOW())");
            $is->bind_param("isidd", $user_id, $food_name, $qty, $unit_price, $total);
            $is->execute();
        }

        echo json_encode(["message" => "Item added/updated"] + get_cart($conn, $user_id));
        exit;
    }

    // ✅ Just list cart
    echo json_encode(get_cart($conn, $user_id));
    exit;
}

http_response_code(405);
echo json_encode(["error" => "Only GET and POST allowed"]);
exit;
