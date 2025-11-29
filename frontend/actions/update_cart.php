<?php
session_start();

$cart_item_id = $_POST['cart_item_id'] ?? '';
$quantity = max(1, (int)($_POST['quantity'] ?? 1));

if (strpos($cart_item_id, 's-') === 0) {
    // session item
    $pid = (int) substr($cart_item_id, 2);
    if (isset($_SESSION['cart'][$pid])) {
        $_SESSION['cart'][$pid]['quantity'] = $quantity;
    }
    header("Location: ../cart.php?msg=Cart+updated");
    exit;
}

// else assume DB id
if (!empty($_SESSION['user_id'])) {
    $api = "http://169.239.251.102:442/~benson.vorsah/backend/cart/update.php";
    $payload = ["cart_item_id" => $cart_item_id, "quantity" => $quantity];
    $opts = ["http"=>["header"=>"Content-Type: application/json\r\n","method"=>"POST","content"=>json_encode($payload),"ignore_errors"=>true]];
    $ctx = stream_context_create($opts);
    file_get_contents($api, false, $ctx);
    header("Location: ../cart.php?msg=Cart+updated");
    exit;
}

// fallback
header("Location: ../cart.php");
exit;
