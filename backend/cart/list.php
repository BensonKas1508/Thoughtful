<?php
header("Content-Type: application/json");
require_once "../config/db.php";

$user_id = isset($_GET['user_id']) ? (int) $_GET['user_id'] : 0;
if (!$user_id) {
    echo json_encode(['status'=>'error','message'=>'user_id required']);
    exit;
}

try {
    $stmt = $pdo->prepare("
        SELECT ci.id as cart_item_id, ci.quantity, ci.price_at_add,
               p.id as product_id, p.name, p.price, p.stock,
               (SELECT url FROM product_images WHERE product_id = p.id LIMIT 1) AS image
        FROM carts c
        JOIN cart_items ci ON ci.cart_id = c.id
        JOIN products p ON p.id = ci.product_id
        WHERE c.user_id = ?
    ");
    $stmt->execute([$user_id]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // compute subtotal field
    foreach ($items as &$it) {
        $it['unit_price'] = $it['price_at_add'] ?: $it['price'];
        $it['subtotal'] = $it['unit_price'] * $it['quantity'];
    }

    echo json_encode(['status'=>'success','items'=>$items]);
} catch (Exception $e) {
    echo json_encode(['status'=>'error','message'=>$e->getMessage()]);
}
