<?php
header("Content-Type: application/json");
include "../../config/db.php";

$input = json_decode(file_get_contents("php://input"), true);
$product_id = $input['product_id'] ?? null;

if ($product_id) {
    // Delete product images first
    $stmt = $pdo->prepare("DELETE FROM product_images WHERE product_id = ?");
    $stmt->execute([$product_id]);
    
    // Delete product
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    
    echo json_encode(["status" => "success", "message" => "Product deleted"]);
} else {
    echo json_encode(["status" => "error", "message" => "Product ID required"]);
}
?>