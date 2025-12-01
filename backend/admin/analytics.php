<?php
header("Content-Type: application/json");
include "../config/db.php";

try {
    // Total users
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
    $total_users = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

    // Total orders - check if orders table exists
    try {
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM orders");
        $total_orders = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    } catch (Exception $e) {
        $total_orders = 0;
    }

    // Total revenue
    try {
        $stmt = $pdo->query("SELECT COALESCE(SUM(total), 0) as revenue FROM orders WHERE status != 'cancelled'");
        $total_revenue = $stmt->fetch(PDO::FETCH_ASSOC)['revenue'];
    } catch (Exception $e) {
        $total_revenue = 0;
    }

    // Total products - check all possible status values
    try {
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM products");
        $total_products = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    } catch (Exception $e) {
        $total_products = 0;
    }

    // Pending orders
    try {
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM orders WHERE status = 'pending'");
        $pending_orders = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    } catch (Exception $e) {
        $pending_orders = 0;
    }

    // Monthly revenue (last 6 months)
    try {
        $stmt = $pdo->query("
            SELECT 
                DATE_FORMAT(created_at, '%b %Y') as month,
                COALESCE(SUM(total), 0) as revenue
            FROM orders
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
            GROUP BY DATE_FORMAT(created_at, '%Y-%m')
            ORDER BY created_at ASC
        ");
        $monthly_revenue = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        $monthly_revenue = [];
    }

    echo json_encode([
        "status" => "success",
        "analytics" => [
            "total_users" => (int)$total_users,
            "total_orders" => (int)$total_orders,
            "total_revenue" => (float)$total_revenue,
            "total_products" => (int)$total_products,
            "pending_orders" => (int)$pending_orders,
            "monthly_revenue" => $monthly_revenue
        ]
    ]);
} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage(),
        "analytics" => [
            "total_users" => 0,
            "total_orders" => 0,
            "total_revenue" => 0,
            "total_products" => 0,
            "pending_orders" => 0,
            "monthly_revenue" => []
        ]
    ]);
}
?>