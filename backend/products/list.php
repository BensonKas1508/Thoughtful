<?php
require_once "../config/db.php";
require_once "../helpers/response.php";

// Query parameters
$search   = $_GET["search"]   ?? null;
$category = $_GET["category"] ?? null;
$vendor   = $_GET["vendor"]   ?? null;

$page  = isset($_GET["page"]) ? (int)$_GET["page"] : 1;
$limit = 12;
$offset = ($page - 1) * $limit;

// Base query
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
        v.business_name,
        c.name AS category_name
    FROM products p
    JOIN vendors v ON p.vendor_id = v.id
    LEFT JOIN categories c ON p.category_id = c.id
    WHERE p.status = 'active'
";

$params = [];

// Filters
if (!empty($search)) {
    $sql .= " AND p.name LIKE :search";
    $params[':search'] = '%' . $search . '%';
}

if (!empty($category)) {
    $sql .= " AND p.category_id = :category";
    $params[':category'] = $category;
}

if (!empty($vendor)) {
    $sql .= " AND p.vendor_id = :vendor";
    $params[':vendor'] = $vendor;
}

// Pagination
$sql .= " ORDER BY p.id DESC LIMIT :limit OFFSET :offset";

$stmt = $pdo->prepare($sql);

// Bind dynamic values
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}

$stmt->bindValue(":limit", (int)$limit, PDO::PARAM_INT);
$stmt->bindValue(":offset", (int)$offset, PDO::PARAM_INT);

$stmt->execute();
$products = $stmt->fetchAll();

// Attach product images
foreach ($products as $key => $product) {
    $imgQuery = $pdo->prepare("SELECT url FROM product_images WHERE product_id = ? LIMIT 1");
    $imgQuery->execute([$product['id']]);
    $image = $imgQuery->fetch();

    $products[$key]["image"] = $image ? $image["url"] : null;
}

// Send JSON response
jsonResponse([
    "status"   => "success",
    "page"     => $page,
    "results"  => count($products),
    "products" => $products
]);
?>
