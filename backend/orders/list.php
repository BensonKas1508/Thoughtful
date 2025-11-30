<?php
header("Content-Type: application/json");
include "../config/db.php";

$user_id = $_GET["user_id"] ?? null;

if (!$user_id) {
    echo json_encode(["status" => "error", "message" => "User ID required"]);
    exit;
}

$stmt = $pdo->prepare("
    SELECT 
        o.id,
        o.total,
        o.status,
        o.created_at
    FROM orders o
    WHERE o.user_id = ?
    ORDER BY o.created_at DESC
");
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// For each order, fetch its items
foreach ($orders as &$order) {
    $itemStmt = $pdo->prepare("
        SELECT 
            oi.quantity,
            p.name,
            pi.url as image
        FROM order_items oi
        JOIN products p ON oi.product_id = p.id
        LEFT JOIN product_images pi ON p.id = pi.product_id
        WHERE oi.order_id = ?
        GROUP BY oi.id
    ");
    $itemStmt->execute([$order['id']]);
    $order['items'] = $itemStmt->fetchAll(PDO::FETCH_ASSOC);
}

echo json_encode([
    "status" => "success",
    "orders" => $orders
]);
?>