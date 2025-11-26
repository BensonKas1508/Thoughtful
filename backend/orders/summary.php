<?php
require_once "../config/db.php";
require_once "../helpers/response.php";

// Validate
if (!isset($_GET["user_id"])) {
    jsonResponse([
        "status" => "error",
        "message" => "user_id is required"
    ], 400);
}

$user_id = (int) $_GET["user_id"];

// 1. FETCH USER CART
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
        p.delivery_type,
        
        v.business_name AS vendor_name
    FROM cart_items ci
    JOIN products p ON ci.product_id = p.id
    JOIN vendors v ON p.vendor_id = v.id
    WHERE ci.cart_id = ?
";

$stmt = $pdo->prepare($sql);
$stmt->execute([$cart_id]);
$items = $stmt->fetchAll();

if (!$items) {
    jsonResponse(["status" => "error", "message" => "Your cart is empty"], 400);
}

// 3. VERIFY STOCK BEFORE CHECKOUT
foreach ($items as $item) {
    if ($item["quantity"] > $item["stock"]) {
        jsonResponse([
            "status" => "error",
            "message" => "One or more items exceed stock",
            "product_id" => $item["product_id"],
            "available_stock" => $item["stock"]
        ], 400);
    }
}

// 4. FETCH USER ADDRESSES
$addressQuery = $pdo->prepare("SELECT * FROM addresses WHERE user_id = ?");
$addressQuery->execute([$user_id]);
$addresses = $addressQuery->fetchAll();

// 5. CALCULATE TOTALS
$subtotal = 0;

foreach ($items as $item) {
    $subtotal += $item["quantity"] * $item["price_at_add"];
}

$delivery_fee = 15; // Placeholder
$commission = $subtotal * 0.15;
$total = $subtotal + $delivery_fee + $commission;

// RESPONSE
jsonResponse([
    "status" => "success",
    "cart_id" => $cart_id,
    "items" => $items,
    "addresses" => $addresses,
    "summary" => [
        "subtotal" => $subtotal,
        "delivery_fee" => $delivery_fee,
        "commission" => $commission,
        "total" => $total
    ]
]);
?>
