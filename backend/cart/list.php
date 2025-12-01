<?php
header("Content-Type: application/json");
include "../config/db.php";

$user_id = $_GET["user_id"] ?? null;

if (!$user_id) {
    echo json_encode(["status" => "error", "message" => "User ID required"]);
    exit;
}

try {
    $stmt = $pdo->prepare("
        SELECT 
            c.id as cart_item_id,
            c.product_id,
            c.quantity,
            p.name,
            p.price as unit_price,
            COALESCE(pi.url, '') as image,
            (p.price * c.quantity) as subtotal
        FROM cart_items c
        JOIN products p ON c.product_id = p.id
        LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_primary = 1
        WHERE c.user_id = ?
    ");
    $stmt->execute([$user_id]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "status" => "success",
        "items" => $items
    ]);
} catch (Exception $e) {
    echo json_encode([
        "status" => "error", 
        "message" => "Database error: " . $e->getMessage()
    ]);
}
?>