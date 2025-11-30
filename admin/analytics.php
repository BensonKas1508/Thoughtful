<?php
header("Content-Type: application/json");
include "../config/db.php";

// Total users
$stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
$total_users = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

// Total orders
$stmt = $pdo->query("SELECT COUNT(*) as count FROM orders");
$total_orders = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

// Total revenue
$stmt = $pdo->query("SELECT COALESCE(SUM(total), 0) as revenue FROM orders WHERE status != 'cancelled'");
$total_revenue = $stmt->fetch(PDO::FETCH_ASSOC)['revenue'];

// Total products
$stmt = $pdo->query("SELECT COUNT(*) as count FROM products WHERE status = 'active'");
$total_products = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

// Pending orders
$stmt = $pdo->query("SELECT COUNT(*) as count FROM orders WHERE status = 'pending'");
$pending_orders = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

// Monthly revenue (last 6 months)
$stmt = $pdo->query("
    SELECT 
        DATE_FORMAT(created_at, '%b %Y') as month,
        SUM(total) as revenue
    FROM orders
    WHERE status != 'cancelled'
    AND created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
    GROUP BY DATE_FORMAT(created_at, '%Y-%m')
    ORDER BY created_at ASC
");
$monthly_revenue = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    "status" => "success",
    "analytics" => [
        "total_users" => $total_users,
        "total_orders" => $total_orders,
        "total_revenue" => $total_revenue,
        "total_products" => $total_products,
        "pending_orders" => $pending_orders,
        "monthly_revenue" => $monthly_revenue
    ]
]);
?>