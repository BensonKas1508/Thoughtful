<?php
header("Content-Type: application/json");
include "../config/db.php";

$stmt = $pdo->query("
    SELECT 
        p.*,
        c.name as category_name,
        pi.url as image
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    LEFT JOIN product_images pi ON p.id = pi.product_id
    GROUP BY p.id
    ORDER BY p.created_at DESC
");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    "status" => "success",
    "products" => $products
]);
?>