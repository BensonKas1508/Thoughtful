<?php
header("Content-Type: application/json");
include "../../config/db.php";

$input = json_decode(file_get_contents("php://input"), true);

// Update product
$stmt = $pdo->prepare("
    UPDATE products 
    SET name = ?, description = ?, price = ?, category_id = ?, vendor_id = ?, stock = ?, status = ? 
    WHERE id = ?
");
$stmt->execute([
    $input['name'],
    $input['description'],
    $input['price'],
    $input['category_id'],
    $input['vendor_id'],
    $input['stock'],
    $input['status'],
    $input['product_id']
]);

// Update product image
if (!empty($input['image_url'])) {
    $stmt = $pdo->prepare("UPDATE product_images SET url = ? WHERE product_id = ?");
    $affected = $stmt->execute([$input['image_url'], $input['product_id']]);
    
    // If no rows updated, insert new image
    if ($affected == 0) {
        $stmt = $pdo->prepare("INSERT INTO product_images (product_id, url) VALUES (?, ?)");
        $stmt->execute([$input['product_id'], $input['image_url']]);
    }
}

echo json_encode(["status" => "success", "message" => "Product updated"]);
?>