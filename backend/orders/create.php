<?php
require_once "../config/db.php";
require_once "../helpers/response.php";

// VALIDATE INPUT
if (!isset($_POST["user_id"]) || !isset($_POST["address_id"]) || !isset($_POST["payment_method"])) {
    jsonResponse([
        "status" => "error",
        "message" => "Missing required fields"
    ], 400);
}

$user_id = (int) $_POST["user_id"];
$address_id = (int) $_POST["address_id"];
$payment_method = $_POST["payment_method"];

// Only allow these methods
if (!in_array($payment_method, ["paystack", "cash_on_delivery"])) {
    jsonResponse(["status" => "error", "message" => "Invalid payment method"], 400);
}

// 1. GET USER CART
$cartQuery = $pdo->prepare("SELECT id FROM carts WHERE user_id = ?");
$cartQuery->execute([$user_id]);
$cart = $cartQuery->fetch();

if (!$cart) {
    jsonResponse(["status" => "error", "message" => "Cart is empty"], 400);
}

$cart_id = $cart["id"];

// 2. FETCH CART ITEMS
$sql = "
    SELECT 
        ci.id AS cart_item_id,
        ci.quantity,
        ci.price_at_add,
        
        p.id AS product_id,
        p.name,
        p.stock,
        p.vendor_id,
        p.price AS current_price
    FROM cart_items ci
    JOIN products p ON ci.product_id = p.id
    WHERE ci.cart_id = ?
";
$stmt = $pdo->prepare($sql);
$stmt->execute([$cart_id]);
$items = $stmt->fetchAll();

if (!$items) {
    jsonResponse(["status" => "error", "message" => "Your cart is empty"], 400);
}

// 3. VERIFY STOCK
foreach ($items as $item) {
    if ($item["quantity"] > $item["stock"]) {
        jsonResponse([
            "status" => "error",
            "message" => "Not enough stock for product: " . $item["name"]
        ], 400);
    }
}

// 4. CALCULATE TOTALS
$subtotal = 0;

foreach ($items as $item) {
    $subtotal += $item["quantity"] * $item["price_at_add"];
}

$delivery_fee = 15;
$commission_amount = $subtotal * 0.15;
$total_amount = $subtotal + $delivery_fee + $commission_amount;

// 5. GENERATE ORDER NUMBER
$order_number = "THF-" . strtoupper(uniqid());

// 6. INSERT ORDER
$orderSql = "
    INSERT INTO orders (
        order_number, user_id, address_id, total_amount,
        delivery_fee, commission_amount, payment_method, payment_status, order_status
    ) VALUES (?, ?, ?, ?, ?, ?, ?, 'pending', 'pending')
";

$orderStmt = $pdo->prepare($orderSql);
$orderStmt->execute([
    $order_number,
    $user_id,
    $address_id,
    $total_amount,
    $delivery_fee,
    $commission_amount,
    $payment_method
]);

$order_id = $pdo->lastInsertId();

// 7. INSERT ORDER ITEMS + DEDUCT STOCK
foreach ($items as $item) {

    // Insert item
    $itemStmt = $pdo->prepare("
        INSERT INTO order_items (order_id, product_id, vendor_id, quantity, unit_price, subtotal)
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    $subtotal_row = $item["quantity"] * $item["price_at_add"];

    $itemStmt->execute([
        $order_id,
        $item["product_id"],
        $item["vendor_id"],
        $item["quantity"],
        $item["price_at_add"],
        $subtotal_row
    ]);

    // Deduct stock
    $stockStmt = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
    $stockStmt->execute([$item["quantity"], $item["product_id"]]);
}

// 8. CLEAR CART (optional but recommended)
$clearItems = $pdo->prepare("DELETE FROM cart_items WHERE cart_id = ?");
$clearItems->execute([$cart_id]);

// 9. IF PAYSTACK → INITIATE PAYMENT
if ($payment_method === "paystack") {

    $callback_url = "C:\Users\benso\Desktop\Thoughful\backend\orders\paystack_callback.php";

    $fields = [
        'email' => "customer{$user_id}@thoughtful.com",
        'amount' => $total_amount * 100,  // Paystack uses pesewas
        'reference' => $order_number,
        'callback_url' => $callback_url
    ];

    $fields_string = http_build_query($fields);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.paystack.co/transaction/initialize");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer " . $paystack_secret_key,
        "Cache-Control: no-cache",
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $result = curl_exec($ch);
    $response = json_decode($result, true);

    if (!$response || !$response["status"]) {
        jsonResponse(["status" => "error", "message" => "Failed to initialize payment"]);
    }

    // Return paystack URL
    jsonResponse([
        "status" => "success",
        "message" => "Order created. Proceed to payment.",
        "order_id" => $order_id,
        "order_number" => $order_number,
        "payment_url" => $response["data"]["authorization_url"]
    ]);
}

// 10. IF CASH → order confirmed immediately
jsonResponse([
    "status" => "success",
    "message" => "Order placed successfully (Cash on Delivery)",
    "order_id" => $order_id,
    "order_number" => $order_number
]);
?>
