<?php
require_once "../config/db.php";
require_once "../helpers/response.php";

// Validate user_id
if (!isset($_GET["user_id"])) {
    jsonResponse(["status" => "error", "message" => "user_id is required"], 400);
}

$user_id = (int) $_GET["user_id"];

// 1. GET USER CART
$cartQuery = $pdo->prepare("SELECT id FROM carts WHERE user_id = ?");
$cartQuery->execute([$user_id]);
$cart = $cartQuery->fetch();

if (!$cart) {
    jsonResponse([
        "status" => "success",
        "cart" => [],
        "subtotal" => 0,
        "delivery_fee" => 0,
        "commission" => 0,
        "total" => 0
    ]);
}

$cart_id = $cart["id"];

// 2. GET CART ITEMS
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

// 3. GET IMAGES
foreach ($items as $key => $item) {
    $imgQuery = $pdo->prepare("SELECT url FROM product_images WHERE product_id = ? LIMIT 1");
    $imgQuery->execute([$item["product_id"]]);
    $image = $imgQuery->fetch();
    $items[$key]["image"] = $image ? $image["url"] : null;
}

// 4. CALCULATE TOTALS
$subtotal = 0;

foreach ($items as $item) {
    $subtotal += $item["quantity"] * $item["price_at_add"];
}

$delivery_fee = 15; // flat fee for now (you can adjust later)
$commission = $subtotal * 0.15; // 15% platform commission
$total = $subtotal + $delivery_fee + $commission;

// 5. RETURN RESPONSE
jsonResponse([
    "status" => "success",
    "cart_id" => $cart_id,
    "items" => $items,
    "subtotal" => $subtotal,
    "delivery_fee" => $delivery_fee,
    "commission" => $commission,
    "total" => $total
]);
?>
