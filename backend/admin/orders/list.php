<?php
require_once "../../config/db.php";
require_once "../../helpers/response.php";
require_once "../auth_check.php";

$admin_id = $_POST["admin_id"] ?? null;
requireAdmin($pdo, $admin_id);

$stmt = $pdo->query("
    SELECT 
        o.id,
        o.total_amount,
        o.payment_method,
        o.payment_status,
        o.order_status,
        o.created_at,
        u.name AS customer_name,
        u.email AS customer_email
    FROM orders o
    LEFT JOIN users u ON u.id = o.user_id
    ORDER BY o.created_at DESC
");

jsonResponse([
    "status" => "success",
    "orders" => $stmt->fetchAll()
]);
?>
