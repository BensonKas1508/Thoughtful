<?php
require_once "../config/db.php";
require_once "../helpers/response.php";
require_once "auth_check.php";

include "components/navbar.php";

// Get admin ID from request (in real world this comes from session or token)
$admin_id = $_POST["admin_id"] ?? null;

requireAdmin($pdo, $admin_id);

// Total users
$users = $pdo->query("SELECT COUNT(*) AS total FROM users")->fetch()['total'];

// Total vendors
$vendors = $pdo->query("SELECT COUNT(*) AS total FROM vendors")->fetch()['total'];

// Total orders
$orders = $pdo->query("SELECT COUNT(*) AS total FROM orders")->fetch()['total'];

// Revenue
$revenue = $pdo->query("
    SELECT COALESCE(SUM(total_amount), 0) AS revenue
    FROM orders 
    WHERE payment_status = 'paid'
")->fetch()['revenue'];

// Pending orders
$pending_orders = $pdo->query("
    SELECT COUNT(*) AS total 
    FROM orders 
    WHERE order_status = 'pending'
")->fetch()['total'];

// Active products
$active_products = $pdo->query("
    SELECT COUNT(*) AS total 
    FROM products 
    WHERE status = 'active'
")->fetch()['total'];

jsonResponse([
    "status" => "success",
    "data" => [
        "total_users" => $users,
        "total_vendors" => $vendors,
        "total_orders" => $orders,
        "revenue" => $revenue,
        "pending_orders" => $pending_orders,
        "active_products" => $active_products
    ]
]);
?>
