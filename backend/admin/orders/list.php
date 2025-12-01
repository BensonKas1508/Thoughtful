<?php
header("Content-Type: application/json");
include "../../config/db.php";

$stmt = $pdo->query("
    SELECT 
        o.*,
        u.name as customer_name,
        u.email as customer_email
    FROM orders o
    JOIN users u ON o.user_id = u.id
    ORDER BY o.created_at DESC
");
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    "status" => "success",
    "orders" => $orders
]);
?>