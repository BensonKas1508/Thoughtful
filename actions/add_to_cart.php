<?php
session_start();

// If user not logged in → send to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: do_login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$product_id = $_POST['product_id'];
$qty = $_POST['quantity'];

// URL to backend (UPDATE USERNAME)
$api_url = "http://169.239.251.102:442/~benson.vorsah/backend/cart/list.php
";

$data = [
    "user_id" => $user_id,
    "product_id" => $product_id,
    "quantity" => $qty
];

// Setup request
$options = [
    "http" => [
        "header"  => "Content-Type: application/json",
        "method"  => "POST",
        "content" => json_encode($data)
    ]
];

// Call backend
$response = file_get_contents($api_url, false, stream_context_create($options));

// Redirect to cart
header("Location: ../cart.php");
exit;
