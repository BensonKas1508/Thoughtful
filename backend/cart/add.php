<?php
require_once "../config/db.php";
require_once "../helpers/response.php";

// VALIDATE INPUT
if (!isset($_POST["user_id"]) || !isset($_POST["product_id"]) || !isset($_POST["quantity"])) {
    jsonResponse(["status" => "error", "message" => "Missing required fields"], 400);
}

$user_id = (int) $_POST["user_id"];
$product_id = (int) $_POST["product_id"];
$quantity = (int) $_POST["quantity"];

if ($quantity <= 0) {
    jsonResponse(["status" => "error", "message" => "Quantity must be at least 1"], 400);
}

// 1. VERIFY PRODUCT
$productQuery = $pdo->prepare("SELECT id, price, stock FROM products WHERE id = ?");
$productQuery->execute([$product_id]);
$product = $productQuery->fetch();

if (!$product) {
    jsonResponse(["status" => "error", "message" => "Product not found"], 404);
}

if ($product["stock"] < $quantity) {
    jsonResponse(["status" => "error", "message" => "Not enough stock"], 400);
}

// 2. GET OR CREATE CART
$cartQuery = $pdo->prepare("SELECT id FROM carts WHERE user_id = ?");
$cartQuery->execute([$user_id]);
$cart = $cartQuery->fetch();

if (!$cart) {
    // Create new cart
    $createCart = $pdo->prepare("INSERT INTO carts (user_id) VALUES (?)");
    $createCart->execute([$user_id]);
    $cart_id = $pdo->lastInsertId();
} else {
    $cart_id = $cart["id"];
}

// 3. CHECK IF ITEM EXISTS IN CART
$itemQuery = $pdo->prepare("
    SELECT id, quantity 
    FROM cart_items 
    WHERE cart_id = ? AND product_id = ?
");
$itemQuery->execute([$cart_id, $product_id]);
$existingItem = $itemQuery->fetch();

if ($existingItem) {
    // Update quantity
    $newQty = $existingItem["quantity"] + $quantity;

    if ($newQty > $product["stock"]) {
        jsonResponse(["status" => "error", "message" => "Exceeds available stock"], 400);
    }

    $updateItem = $pdo->prepare("
        UPDATE cart_items 
        SET quantity = ? 
        WHERE id = ?
    ");
    $updateItem->execute([$newQty, $existingItem["id"]]);

} else {
    // Add new item with price captured at this time
    $addItem = $pdo->prepare("
        INSERT INTO cart_items (cart_id, product_id, quantity, price_at_add)
        VALUES (?, ?, ?, ?)
    ");
    $addItem->execute([$cart_id, $product_id, $quantity, $product["price"]]);
}

// 4. SUCCESS
jsonResponse([
    "status" => "success",
    "message" => "Item added to cart",
    "cart_id" => $cart_id
]);
?>
