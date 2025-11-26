<?php
require_once "../config/db.php";
require_once "../helpers/response.php";

// Validate product ID
if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    jsonResponse(["status" => "error", "message" => "Product ID is required"], 400);
}

$product_id = (int) $_GET["id"];
$include_reviews = isset($_GET["with_reviews"]) && $_GET["with_reviews"] == "1";

// 1. Fetch main product details
$sql = "
    SELECT 
        p.id,
        p.name,
        p.description,
        p.price,
        p.stock,
        p.status,
        p.delivery_type,
        p.created_at,
        
        v.id AS vendor_id,
        v.business_name AS vendor_name,
        v.address AS vendor_address,
        
        c.name AS category_name
    FROM products p
    JOIN vendors v ON p.vendor_id = v.id
    LEFT JOIN categories c ON p.category_id = c.id
    WHERE p.id = :id
    LIMIT 1
";

$stmt = $pdo->prepare($sql);
$stmt->execute([":id" => $product_id]);
$product = $stmt->fetch();

if (!$product) {
    jsonResponse(["status" => "error", "message" => "Product not found"], 404);
}

// 2. Fetch product images
$imgQuery = $pdo->prepare("SELECT url FROM product_images WHERE product_id = ?");
$imgQuery->execute([$product_id]);
$images = $imgQuery->fetchAll();

// 3. Average rating
$ratingQuery = $pdo->prepare("
    SELECT 
        ROUND(AVG(rating), 1) AS avg_rating,
        COUNT(*) AS total_reviews
    FROM reviews
    WHERE product_id = ?
");
$ratingQuery->execute([$product_id]);
$ratingData = $ratingQuery->fetch();

$product["avg_rating"] = $ratingData["avg_rating"] ?? 0;
$product["total_reviews"] = $ratingData["total_reviews"] ?? 0;

// 4. Fetch reviews (optional)
$reviews = [];

if ($include_reviews) {
    $reviewQuery = $pdo->prepare("
        SELECT 
            r.id,
            r.rating,
            r.comment,
            r.created_at,
            u.name AS customer_name
        FROM reviews r
        JOIN users u ON r.user_id = u.id
        WHERE r.product_id = ?
        ORDER BY r.created_at DESC
    ");
    
    $reviewQuery->execute([$product_id]);
    $reviews = $reviewQuery->fetchAll();
}

// 5. Final response
jsonResponse([
    "status" => "success",
    "product" => $product,
    "images" => $images,
    "reviews" => $reviews
]);
?>
