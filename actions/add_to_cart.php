<?php
session_start();

// If user not logged in → send to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$product_id = $_POST['product_id'] ?? 0;
$qty = max(1, (int)($_POST['quantity'] ?? 1));

// Correct API URL - should be add.php, not list.php
$api_url = "http://169.239.251.102:442/~benson.vorsah/backend/cart/add.php";

$data = [
    "user_id" => $user_id,
    "product_id" => $product_id,
    "quantity" => $qty
];

// Setup request
$options = [
    "http" => [
        "header"  => "Content-Type: application/json\r\n",
        "method"  => "POST",
        "content" => json_encode($data),
        "ignore_errors" => true
    ]
];

$context = stream_context_create($options);
$response = file_get_contents($api_url, false, $context);

// Debug (remove in production)
// file_put_contents("debug_add_to_cart.txt", $response);

// Redirect to cart with success message
header("Location: ../cart.php?msg=Item+added+to+cart");
exit;
?>