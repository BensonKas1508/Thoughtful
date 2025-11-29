<?php
header("Content-Type: application/json");
require_once "../config/db.php";

// Accept JSON body
$input = json_decode(file_get_contents("php://input"), true);

$user_id = $input['user_id'] ?? null;
$product_id = (int) ($input['product_id'] ?? 0);
$quantity = max(1, (int) ($input['quantity'] ?? 1));

if (!$user_id || !$product_id) {
    echo json_encode(['status'=>'error','message'=>'user_id and product_id required']);
    exit;
}

// check existing cart for user
try {
    // find user cart
    $stmt = $pdo->prepare("SELECT id FROM carts WHERE user_id = ? LIMIT 1");
    $stmt->execute([$user_id]);
    $cart = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$cart) {
        $ins = $pdo->prepare("INSERT INTO carts (user_id, created_at) VALUES (?, NOW())");
        $ins->execute([$user_id]);
        $cart_id = $pdo->lastInsertId();
    } else {
        $cart_id = $cart['id'];
    }

    // check if product already in cart
    $ci = $pdo->prepare("SELECT id, quantity FROM cart_items WHERE cart_id = ? AND product_id = ? LIMIT 1");
    $ci->execute([$cart_id, $product_id]);
    $existing = $ci->fetch(PDO::FETCH_ASSOC);

    if ($existing) {
        $newQty = $existing['quantity'] + $quantity;
        $u = $pdo->prepare("UPDATE cart_items SET quantity = ? WHERE id = ?");
        $u->execute([$newQty, $existing['id']]);
    } else {
        $i = $pdo->prepare("INSERT INTO cart_items (cart_id, product_id, quantity, price_at_add, created_at) VALUES (?, ?, ?, ?, NOW())");
        // get current price
        $pstmt = $pdo->prepare("SELECT price FROM products WHERE id = ? LIMIT 1");
        $pstmt->execute([$product_id]);
        $price = $pstmt->fetchColumn() ?: 0;
        $i->execute([$cart_id, $product_id, $quantity, $price]);
    }

    echo json_encode(['status'=>'success','message'=>'Added to cart']);
    exit;
} catch (Exception $e) {
    echo json_encode(['status'=>'error','message'=>$e->getMessage()]);
    exit;
}
