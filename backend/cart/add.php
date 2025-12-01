<?php
header("Content-Type: application/json");
include "../config/db.php";

$input = json_decode(file_get_contents("php://input"), true);

$user_id = $input["user_id"] ?? null;
$product_id = $input["product_id"] ?? null;
$quantity = max(1, (int)($input["quantity"] ?? 1));

if (!$user_id || !$product_id) {
    echo json_encode(["status" => "error", "message" => "Missing user_id or product_id"]);
    exit;
}

try {
    // Get or create cart for user
    $cart_stmt = $pdo->prepare("SELECT id FROM carts WHERE user_id = ?");
    $cart_stmt->execute([$user_id]);
    $cart = $cart_stmt->fetch();
    
    if (!$cart) {
        // Create new cart
        $create_cart = $pdo->prepare("INSERT INTO carts (user_id) VALUES (?)");
        $create_cart->execute([$user_id]);
        $cart_id = $pdo->lastInsertId();
    } else {
        $cart_id = $cart['id'];
    }
    
    // Check if item already in cart
    $check = $pdo->prepare("SELECT id, quantity FROM cart_items WHERE cart_id = ? AND product_id = ?");
    $check->execute([$cart_id, $product_id]);
    $existing = $check->fetch();

    if ($existing) {
        // Update quantity
        $new_qty = $existing['quantity'] + $quantity;
        $update = $pdo->prepare("UPDATE cart_items SET quantity = ? WHERE id = ?");
        $update->execute([$new_qty, $existing['id']]);
        
        echo json_encode([
            "status" => "success", 
            "message" => "Cart updated"
        ]);
    } else {
        // Get product price
        $price_stmt = $pdo->prepare("SELECT price FROM products WHERE id = ?");
        $price_stmt->execute([$product_id]);
        $product = $price_stmt->fetch();
        $price = $product['price'] ?? 0;
        
        // Insert new item
        $insert = $pdo->prepare("INSERT INTO cart_items (cart_id, product_id, quantity, price_at_add) VALUES (?, ?, ?, ?)");
        $insert->execute([$cart_id, $product_id, $quantity, $price]);
        
        echo json_encode([
            "status" => "success", 
            "message" => "Item added to cart"
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        "status" => "error", 
        "message" => "Database error: " . $e->getMessage()
    ]);
}
?>