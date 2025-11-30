<?php
// debugging to see errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Content-Type: application/json");
include "../config/db.php";

$user_id = $_GET["user_id"] ?? null;

if (!$user_id) {
    echo json_encode(["status" => "error", "message" => "User ID required"]);
    exit;
}

$stmt = $pdo->prepare("
    SELECT 
        c.id as cart_item_id,
        c.product_id,
        c.quantity,
        p.name,
        p.price as unit_price,
        p.image,
        (p.price * c.quantity) as subtotal
    FROM cart_items c
    JOIN products p ON c.product_id = p.id
    WHERE c.user_id = ?
");
$stmt->execute([$user_id]);
$items = $stmt->fetchAll();

echo json_encode([
    "status" => "success",
    "items" => $items
]);
?>