<?php
header("Content-Type: application/json");
require_once "../config/db.php";

$input = json_decode(file_get_contents("php://input"), true);
$cart_item_id = $input['cart_item_id'] ?? 0;
$quantity = max(1, (int)($input['quantity'] ?? 1));

if (!$cart_item_id) {
    echo json_encode(['status'=>'error','message'=>'cart_item_id required']);
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE cart_items SET quantity = ? WHERE id = ?");
    $stmt->execute([$quantity, $cart_item_id]);
    echo json_encode(['status'=>'success']);
} catch (Exception $e) {
    echo json_encode(['status'=>'error','message'=>$e->getMessage()]);
}
