<?php
session_start();

$cart_item_id = $_POST['cart_item_id'] ?? '';

if (strpos($cart_item_id, 's-') === 0) {
    $pid = (int) substr($cart_item_id, 2);
    if (isset($_SESSION['cart'][$pid])) unset($_SESSION['cart'][$pid]);
    header("Location: ../cart.php?msg=Removed");
    exit;
}

if (!empty($_SESSION['user_id'])) {
    $api = "http://169.239.251.102:442/~benson.vorsah/backend/cart/remove.php";
    $payload = ["cart_item_id" => $cart_item_id];
    $opts = ["http"=>["header"=>"Content-Type: application/json\r\n","method"=>"POST","content"=>json_encode($payload),"ignore_errors"=>true]];
    $ctx = stream_context_create($opts);
    file_get_contents($api, false, $ctx);
    header("Location: ../cart.php?msg=Removed");
    exit;
}

header("Location: ../cart.php");
exit;
