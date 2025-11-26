<?php
require_once "../../config/db.php";
require_once "../../helpers/response.php";
require_once "../auth_check.php";

$admin_id = $_POST["admin_id"] ?? null;
requireAdmin($pdo, $admin_id);

$stmt = $pdo->query("
    SELECT 
        p.id, 
        p.name, 
        p.price, 
        p.stock,
        p.status,
        p.created_at,
        c.name AS category_name,
        v.business_name AS vendor_name
    FROM products p
    LEFT JOIN categories c ON c.id = p.category_id
    LEFT JOIN vendors v ON v.id = p.vendor_id
    ORDER BY p.created_at DESC
");

jsonResponse([
    "status" => "success",
    "products" => $stmt->fetchAll()
]);
?>
