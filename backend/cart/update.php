<?php
header("Content-Type: application/json");
include "../config/db.php";

$input = json_decode(file_get_contents("php://input"), true);
$cart_item_id = $input["cart_item_id"] ?? null;
$quantity = max(1, (int)($input["quantity"] ?? 1));

if (!$cart_item_id) {
    echo json_encode(["status" => "error", "message" => "Cart item ID required"]);
    exit;
}

$stmt = $pdo->prepare("UPDATE cart_items SET quantity = ? WHERE id = ?");
$stmt->execute([$quantity, $cart_item_id]);

echo json_encode(["status" => "success", "message" => "Cart updated"]);
?>