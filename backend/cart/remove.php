<?php
header("Content-Type: application/json");
include "../config/db.php";

$input = json_decode(file_get_contents("php://input"), true);
$cart_item_id = $input["cart_item_id"] ?? null;

if (!$cart_item_id) {
    echo json_encode(["status" => "error", "message" => "Cart item ID required"]);
    exit;
}

$stmt = $pdo->prepare("DELETE FROM cart_items WHERE id = ?");
$stmt->execute([$cart_item_id]);

echo json_encode(["status" => "success", "message" => "Item removed"]);
?>