<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// API URL (UPDATE USERNAME)
$api_url = "http://169.239.251.102:442//~benson.vorsah/thoughtful/backend/cart/list.php?user_id=" . $user_id;

// Get cart items
$response = file_get_contents($api_url);
$cart = json_decode($response, true);

include "components/navbar.php";
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="styles/navbar.css">
    <link rel="stylesheet" href="styles/footer.css">
    <link rel="stylesheet" href="styles/cart.css">
    <title>Your Cart</title>
</head>
<body>

<h1>Your Cart</h1>

<div class="cart-container">

<?php if (empty($cart['items'])): ?>
    <p>Your cart is empty.</p>

<?php else: ?>

    <?php foreach ($cart['items'] as $item): ?>
        <div class="cart-item">
            <img src="<?php echo $item['image']; ?>" class="thumb">

            <div>
                <h3><?php echo $item['name']; ?></h3>
                <p>GH₵ <?php echo number_format($item['price'], 2); ?></p>
                <p>Qty: <?php echo $item['quantity']; ?></p>
            </div>
        </div>
    <?php endforeach; ?>

    <h2>Total: GH₵ <?php echo number_format($cart['total'], 2); ?></h2>

<?php endif; ?>

</div>

<?php include "components/footer.php"; ?>
</body>
</html>
