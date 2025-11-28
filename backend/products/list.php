<?php
require_once "../config/db.php";
require_once "../helpers/response.php";

// QUERY PARAMETERS
$search = $_GET["search"] ?? null;
$category = $_GET["category"] ?? null;
$vendor = $_GET["vendor"] ?? null;

$page = isset($_GET["page"]) ? (int)$_GET["page"] : 1;
$limit = 12;
$offset = ($page - 1) * $limit;

$category = isset($_GET['category']) ? intval($_GET['category']) : 0;

// Base query
$query = "
    SELECT p.*, vendors.business_name, categories.name AS category_name,
        (SELECT url FROM product_images WHERE product_id = p.id LIMIT 1) AS image
    FROM products p
    LEFT JOIN vendors ON vendors.id = p.vendor_id
    LEFT JOIN categories ON categories.id = p.category_id
    WHERE p.status = 'active'
";

if ($category > 0) {
    $query .= " AND p.category_id = :cat";
}

$query .= " ORDER BY p.id DESC LIMIT :offset, :limit";

if ($category > 0) {
    $stmt->bindValue(':cat', $category, PDO::PARAM_INT);
}

$params = [];

// FILTERS
if ($search) {
    $sql .= " AND p.name LIKE :search";
    $params[':search'] = '%' . $search . '%';
}

if ($category) {
    $sql .= " AND p.category_id = :category";
    $params[':category'] = $category;
}

if ($vendor) {
    $sql .= " AND p.vendor_id = :vendor";
    $params[':vendor'] = $vendor;
}

// PAGINATION
$sql .= " ORDER BY p.created_at DESC LIMIT :limit OFFSET :offset";

$stmt = $pdo->prepare($sql);

// BIND DYNAMIC VALUES
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}

$stmt->bindValue(":limit", (int)$limit, PDO::PARAM_INT);
$stmt->bindValue(":offset", (int)$offset, PDO::PARAM_INT);

$stmt->execute();
$products = $stmt->fetchAll();

// GET IMAGES FOR EACH PRODUCT
foreach ($products as $key => $product) {
    $imgQuery = $pdo->prepare("SELECT url FROM product_images WHERE product_id = ? LIMIT 1");
    $imgQuery->execute([$product['id']]);
    $image = $imgQuery->fetch();

    $products[$key]["image"] = $image ? $image["url"] : null;
}

// RESPONSE
jsonResponse([
    "status" => "success",
    "page" => $page,
    "results" => count($products),
    "products" => $products
]);
?>
