<?php
require_once "../../config/db.php";
require_once "../../helpers/response.php";
require_once "../auth_check.php";

$admin_id = $_POST["admin_id"] ?? null;
$product_id = $_POST["product_id"] ?? null;

requireAdmin($pdo, $admin_id);

if (!$product_id) {
    jsonResponse(["status" => "error", "message" => "Product ID required"], 400);
}

// Get product info
$stmt = $pdo->prepare("
    SELECT p.*, 
           c.name AS category_name,
           v.business_name AS vendor_name
    FROM products p
    LEFT JOIN categories c ON c.id = p.category_id
    LEFT JOIN vendors v ON v.id = p.vendor_id
    WHERE p.id = ?
    LIMIT 1
");
$stmt->execute([$product_id]);
$product = $stmt->fetch();

if (!$product) {
    jsonResponse(["status" => "error", "message" => "Product not found"], 404);
}

// Get images
$imgStmt = $pdo->prepare("SELECT id, url FROM product_images WHERE product_id = ?");
$imgStmt->execute([$product_id]);
$images = $imgStmt->fetchAll();

jsonResponse([
    "status" => "success",
    "product" => $product,
    "images" => $images
]);
?>
