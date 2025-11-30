<?php
header("Content-Type: application/json");
include "../config/db.php";

$input = json_decode(file_get_contents("php://input"), true);

$user_id = $input["user_id"] ?? null;
$product_id = $input["product_id"] ?? null;
$quantity = max(1, (int)($input["quantity"] ?? 1));

if (!$user_id || !$product_id) {
    echo json_encode(["status" => "error", "message" => "Missing fields"]);
    exit;
}

// Check if item already in cart
$check = $pdo->prepare("SELECT id, quantity FROM cart_items WHERE user_id = ? AND product_id = ?");
$check->execute([$user_id, $product_id]);
$existing = $check->fetch();

if ($existing) {
    // Update quantity
    $new_qty = $existing['quantity'] + $quantity;
    $update = $pdo->prepare("UPDATE cart_items SET quantity = ? WHERE id = ?");
    $update->execute([$new_qty, $existing['id']]);
    
    echo json_encode(["status" => "success", "message" => "Cart updated"]);
} else {
    // Insert new item
    $insert = $pdo->prepare("INSERT INTO cart_items (user_id, product_id, quantity) VALUES (?, ?, ?)");
    $insert->execute([$user_id, $product_id, $quantity]);
    
    echo json_encode(["status" => "success", "message" => "Item added to cart"]);
}
?>