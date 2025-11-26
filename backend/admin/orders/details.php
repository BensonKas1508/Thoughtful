<?php
require_once "../../config/db.php";
require_once "../../helpers/response.php";
require_once "../auth_check.php";

$admin_id = $_POST["admin_id"] ?? null;
$order_id = $_POST["order_id"] ?? null;

requireAdmin($pdo, $admin_id);

$stmt = $pdo->prepare("
    SELECT o.*, u.name AS customer_name, u.email AS customer_email
    FROM orders o
    LEFT JOIN users u ON u.id = o.user_id
    WHERE o.id = ?
");
$stmt->execute([$order_id]);
$order = $stmt->fetch();

if (!$order) {
    jsonResponse(["status"=>"error","message"=>"Order not found"],404);
}

// Order items
$itemStmt = $pdo->prepare("
    SELECT 
        oi.*,
        p.name AS product_name,
        v.business_name AS vendor_name
    FROM order_items oi
    LEFT JOIN products p ON p.id = oi.product_id
    LEFT JOIN vendors v ON v.id = oi.vendor_id
    WHERE oi.order_id = ?
");
$itemStmt->execute([$order_id]);
$items = $itemStmt->fetchAll();

jsonResponse([
    "status" => "success",
    "order"  => $order,
    "items"  => $items
]);
?>
