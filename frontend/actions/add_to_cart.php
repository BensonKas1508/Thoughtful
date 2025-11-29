<?php
session_start();

// get POST
$product_id = (int) ($_POST['product_id'] ?? 0);
$quantity = max(1, (int) ($_POST['quantity'] ?? 1));

// simple validation
if ($product_id <= 0) {
    header("Location: /~benson.vorsah/home.php?error=Invalid+product");
    exit;
}

// if user logged in -> call backend to add to DB
if (!empty($_SESSION['user_id'])) {
    $api_url = "http://169.239.251.102:442/~benson.vorsah/backend/cart/add.php";

    $payload = [
        "user_id" => $_SESSION['user_id'],
        "product_id" => $product_id,
        "quantity" => $quantity
    ];

    $options = [
        "http" => [
            "header"  => "Content-Type: application/json\r\n",
            "method"  => "POST",
            "content" => json_encode($payload),
            "ignore_errors" => true
        ]
    ];

    $context = stream_context_create($options);
    $response = file_get_contents($api_url, false, $context);
    $res = json_decode($response, true);

    $msg = $res['message'] ?? 'Added to cart';
    header("Location: ../cart.php?msg=" . urlencode($msg));
    exit;
}

// --- guest: store in session cart
if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// key by product_id
$key = (string)$product_id;

if (isset($_SESSION['cart'][$key])) {
    $_SESSION['cart'][$key]['quantity'] += $quantity;
} else {
    $_SESSION['cart'][$key] = [
        'product_id' => $product_id,
        'quantity' => $quantity,
        'added_at' => time()
    ];
}

header("Location: ../cart.php?msg=" . urlencode("Added to cart (guest)"));
exit;
