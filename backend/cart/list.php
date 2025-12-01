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
            ci.id as cart_item_id,
            ci.product_id,
            ci.quantity,
            p.name,
            p.price as unit_price,
            COALESCE(pi.url, 'https://via.placeholder.com/300') as image,
            (p.price * ci.quantity) as subtotal
        FROM carts c
        JOIN cart_items ci ON c.id = ci.cart_id
        JOIN products p ON ci.product_id = p.id
        LEFT JOIN (
            SELECT product_id, url 
            FROM product_images 
            WHERE id IN (
                SELECT MIN(id) 
                FROM product_images 
                GROUP BY product_id
            )
        ) pi ON p.id = pi.product_id
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