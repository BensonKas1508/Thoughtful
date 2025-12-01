<?php
header("Content-Type: application/json");
include "../../config/db.php";

$input = json_decode(file_get_contents("php://input"), true);

// Insert product
$stmt = $pdo->prepare("
    INSERT INTO products (name, description, price, category_id, vendor_id, stock, status) 
    VALUES (?, ?, ?, ?, ?, ?, ?)
");
$stmt->execute([
    $input['name'],
    $input['description'],
    $input['price'],
    $input['category_id'],
    $input['vendor_id'],
    $input['stock'],
    $input['status']
]);

$product_id = $pdo->lastInsertId();

// Insert product image
if (!empty($input['image_url'])) {
    $stmt = $pdo->prepare("INSERT INTO product_images (product_id, url) VALUES (?, ?)");
    $stmt->execute([$product_id, $input['image_url']]);
}

echo json_encode(["status" => "success", "message" => "Product created"]);
?>