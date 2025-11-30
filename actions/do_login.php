<?php
session_start();

// Backend login API URL
$api_url = "http://169.239.251.102:442/~benson.vorsah/backend/auth/login.php";

$data = [
    "email" => $_POST['email'] ?? '',
    "password" => $_POST['password'] ?? ''
];

// Validate required fields
if (empty($data["email"]) || empty($data["password"])) {
    header("Location: ../login.php?error=Email+and+password+required");
    exit;
}

// Send JSON to backend
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

if ($response === false) {
    header("Location: ../login.php?error=Server+error");
    exit;
}

$result = json_decode($response, true);

if (!$result || ($result["status"] ?? '') !== "success") {
    $msg = $result["message"] ?? "Login failed";
    header("Location: ../login.php?error=" . urlencode($msg));
    exit;
}

// SUCCESS — Set all session variables
$_SESSION["user_id"] = $result["user"]["id"];
$_SESSION["name"] = $result["user"]["name"];  // THIS LINE IS CRITICAL
$_SESSION["role"] = $result["user"]["role"];

// Merge guest cart with user cart (if exists)
if (!empty($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $pid => $entry) {
        $payload = [
            "user_id" => $_SESSION['user_id'],
            "product_id" => (int)$entry['product_id'],
            "quantity" => (int)$entry['quantity']
        ];
        $cart_api = "http://169.239.251.102:442/~benson.vorsah/backend/cart/add.php";
        $cart_opts = [
            "http" => [
                "header" => "Content-Type: application/json\r\n",
                "method" => "POST",
                "content" => json_encode($payload),
                "ignore_errors" => true
            ]
        ];
        $cart_ctx = stream_context_create($cart_opts);
        @file_get_contents($cart_api, false, $cart_ctx);
    }
    unset($_SESSION['cart']);
}

header("Location: ../home.php");
exit;
?>