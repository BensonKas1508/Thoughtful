<?php
header("Content-Type: application/json");
include "../config/db.php";

$vendor_id = $_GET['vendor_id'] ?? null;

if (!$vendor_id) {
    echo json_encode(["status" => "error", "message" => "Vendor ID required"]);
    exit;
}

try {
    // Total products
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM products WHERE vendor_id = ?");
    $stmt->execute([$vendor_id]);
    $total_products = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

    // Total orders for vendor products
    $stmt = $pdo->prepare("
        SELECT COUNT(DISTINCT o.id) as count
        FROM orders o
        JOIN order_items oi ON o.id = oi.order_id
        JOIN products p ON oi.product_id = p.id
        WHERE p.vendor_id = ?
    ");
    $stmt->execute([$vendor_id]);
    $total_orders = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

    // Total revenue
    $stmt = $pdo->prepare("
        SELECT COALESCE(SUM(oi.price * oi.quantity), 0) as revenue
        FROM order_items oi
        JOIN products p ON oi.product_id = p.id
        JOIN orders o ON oi.order_id = o.id
        WHERE p.vendor_id = ? AND o.status != 'cancelled'
    ");
    $stmt->execute([$vendor_id]);
    $total_revenue = $stmt->fetch(PDO::FETCH_ASSOC)['revenue'];

    // Pending orders
    $stmt = $pdo->prepare("
        SELECT COUNT(DISTINCT o.id) as count
        FROM orders o
        JOIN order_items oi ON o.id = oi.order_id
        JOIN products p ON oi.product_id = p.id
        WHERE p.vendor_id = ? AND o.status = 'pending'
    ");
    $stmt->execute([$vendor_id]);
    $pending_orders = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

    // Monthly revenue
    $stmt = $pdo->prepare("
        SELECT 
            DATE_FORMAT(o.created_at, '%b %Y') as month,
            COALESCE(SUM(oi.price * oi.quantity), 0) as revenue
        FROM order_items oi
        JOIN products p ON oi.product_id = p.id
        JOIN orders o ON oi.order_id = o.id
        WHERE p.vendor_id = ? 
        AND o.status != 'cancelled'
        AND o.created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
        GROUP BY DATE_FORMAT(o.created_at, '%Y-%m')
        ORDER BY o.created_at ASC
    ");
    $stmt->execute([$vendor_id]);
    $monthly_revenue = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "status" => "success",
        "analytics" => [
            "total_products" => (int)$total_products,
            "total_orders" => (int)$total_orders,
            "total_revenue" => (float)$total_revenue,
            "pending_orders" => (int)$pending_orders,
            "monthly_revenue" => $monthly_revenue
        ]
    ]);
} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}
?>