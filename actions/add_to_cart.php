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

// Validate
if (!$product_id) {
    header("Location: ../cart.php?error=Invalid+product");
    exit;
}

// Correct API URL
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

// Debug: Save response
file_put_contents("debug_add_cart_response.txt", "REQUEST: " . json_encode($data) . "\n\nRESPONSE: " . $response);

// Check if response is valid
if ($response === false) {
    header("Location: ../cart.php?error=Server+error");
    exit;
}

$result = json_decode($response, true);

// Check if addition was successful
if (isset($result['status']) && $result['status'] === 'success') {
    header("Location: ../cart.php?msg=Item+added+to+cart");
} else {
    $error_msg = $result['message'] ?? 'Failed+to+add+item';
    header("Location: ../cart.php?error=" . urlencode($error_msg));
}
exit;
?>