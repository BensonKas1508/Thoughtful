<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../login.php");
    exit;
}

$product_id = $_POST['product_id'] ?? null;
$name = trim($_POST['name']);
$description = trim($_POST['description']);
$price = floatval($_POST['price']);
$category_id = intval($_POST['category_id']);
$vendor_id = intval($_POST['vendor_id']);
$stock = intval($_POST['stock']);
$image_url = trim($_POST['image_url']);
$status = $_POST['status'];

$api_url = "http://169.239.251.102:442/~benson.vorsah/backend/admin/products/" . ($product_id ? "update.php" : "create.php");

$data = [
    "name" => $name,
    "description" => $description,
    "price" => $price,
    "category_id" => $category_id,
    "vendor_id" => $vendor_id,
    "stock" => $stock,
    "image_url" => $image_url,
    "status" => $status
];

if ($product_id) {
    $data['product_id'] = $product_id;
}

$options = [
    "http" => [
        "header"  => "Content-Type: application/json\r\n",
        "method"  => "POST",
        "content" => json_encode($data)
    ]
];

$context = stream_context_create($options);
$response = file_get_contents($api_url, false, $context);
$result = json_decode($response, true);

if ($result['status'] === 'success') {
    header("Location: ../products.php?msg=" . urlencode($product_id ? "Product updated" : "Product created"));
} else {
    header("Location: ../products.php?error=" . urlencode($result['message'] ?? 'Operation failed'));
}
exit;
?>