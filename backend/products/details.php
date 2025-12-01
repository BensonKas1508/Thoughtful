<?php
header("Content-Type: application/json");
include "../config/db.php";

$product_id = $_GET["id"] ?? null;

if (!$product_id) {
    echo json_encode(["status" => "error", "message" => "Product ID required"]);
    exit;
}

try {
    $stmt = $pdo->prepare("
        SELECT 
            p.*,
            COALESCE(pi.url, 'https://via.placeholder.com/500') as image
        FROM products p
        LEFT JOIN (
            SELECT product_id, url 
            FROM product_images 
            WHERE id IN (
                SELECT MIN(id) 
                FROM product_images 
                GROUP BY product_id
            )
        ) pi ON p.id = pi.product_id
        WHERE p.id = ?
    ");
    
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        echo json_encode(["status" => "error", "message" => "Product not found"]);
        exit;
    }

    echo json_encode($product);
} catch (Exception $e) {
    echo json_encode([
        "status" => "error", 
        "message" => "Database error: " . $e->getMessage()
    ]);
}
?>