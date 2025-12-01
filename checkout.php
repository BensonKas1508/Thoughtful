<?php
session_start();

// Must be logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include "components/navbar.php";

// Fetch cart items
$cart_items = [];
$total = 0.0;

$api_url = "http://169.239.251.102:442/~benson.vorsah/backend/cart/list.php?user_id=" . (int)$_SESSION['user_id'];
$resp = @file_get_contents($api_url);
if ($resp) {
    $data = json_decode($resp, true);
    if (json_last_error() === JSON_ERROR_NONE && isset($data['items'])) {
        $cart_items = $data['items'];
        foreach ($cart_items as $item) {
            $total += $item['subtotal'] ?? 0;
        }
    }
}

// If cart is empty, redirect
if (empty($cart_items)) {
    header("Location: cart.php?error=Your+cart+is+empty");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout - Thoughtful</title>
    <link rel="stylesheet" href="styles/global.css">
    <link rel="stylesheet" href="styles/navbar.css">
    <link rel="stylesheet" href="styles/footer.css">
    <link rel="stylesheet" href="styles/checkout.css">
</head>
<body>

<section class="checkout-hero">
    <div class="hero-content">
        <h1>Checkout</h1>
        <p>Review your order and complete purchase</p>
    </div>
</section>

<section class="checkout-section">
    <div class="checkout-container">
        
        <div class="checkout-layout">
            
            <!-- Left: Order Summary -->
            <div class="order-review">
                <h2>Order Summary</h2>
                
                <?php foreach ($cart_items as $item): ?>
                    <div class="checkout-item">
                        <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                        <div class="item-info">
                            <h4><?= htmlspecialchars($item['name']) ?></h4>
                            <p>Qty: <?= $item['quantity'] ?> × GH₵ <?= number_format($item['unit_price'], 2) ?></p>
                        </div>
                        <div class="item-price">
                            GH₵ <?= number_format($item['subtotal'], 2) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
                
                <div class="order-total">
                    <span>Total</span>
                    <span>GH₵ <?= number_format($total, 2) ?></span>
                </div>
            </div>

            <!-- Right: Shipping & Payment -->
            <div class="checkout-form">
                <form action="actions/do_checkout.php" method="POST">
                    
                    <h3>Shipping Information</h3>
                    
                    <div class="form-group">
                        <label for="delivery_address">Delivery Address</label>
                        <textarea id="delivery_address" name="delivery_address" rows="3" required placeholder="Enter your full delivery address"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone" required placeholder="+233 XX XXX XXXX">
                    </div>

                    <h3>Payment Method</h3>
                    
                    <div class="payment-options">
                        <label class="payment-option">
                            <input type="radio" name="payment_method" value="momo" checked>
                            <div class="payment-card">
                                <span>📱 Mobile Money</span>
                            </div>
                        </label>
                        
                        <label class="payment-option">
                            <input type="radio" name="payment_method" value="card">
                            <div class="payment-card">
                                <span>💳 Card Payment</span>
                            </div>
                        </label>
                        
                        <label class="payment-option">
                            <input type="radio" name="payment_method" value="cod">
                            <div class="payment-card">
                                <span>💵 Cash on Delivery</span>
                            </div>
                        </label>
                    </div>

                    <button type="submit" class="checkout-btn">
                        Place Order - GH₵ <?= number_format($total, 2) ?>
                    </button>
                </form>
            </div>

        </div>
        
    </div>
</section>

<?php include "components/footer.php"; ?>

</body>
</html>