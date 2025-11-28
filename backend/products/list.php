<?php
header("Content-Type: application/json");
include "../config/db.php";

$category = $_GET["category"] ?? null;

$sql = "
    SELECT p.*, 
        v.business_name,
        c.name AS category_name,
        (SELECT url FROM product_images WHERE product_id = p.id LIMIT 1) AS image
    FROM products p
    LEFT JOIN vendors v ON v.id = p.vendor_id
    LEFT JOIN categories c ON c.id = p.category_id
    WHERE p.status = 'active'
";

$params = [];

// filter by category
if ($category) {
    $sql .= " AND p.category_id = :cat";
    $params[':cat'] = $category;
}

$sql .= " ORDER BY p.id DESC";

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    "status" => "success",
    "products" => $items
]);
