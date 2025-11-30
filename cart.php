<?php
session_start();

// Clear cart count cache so navbar refreshes
unset($_SESSION['cart_count']);
unset($_SESSION['cart_count_time']);

include "components/navbar.php";

$cart_items = [];
$total = 0.0;

if (!empty($_SESSION['user_id'])) {
    // logged in -> fetch from backend
    $api_url = "http://169.239.251.102:442/~benson.vorsah/backend/cart/list.php?user_id=" . (int)$_SESSION['user_id'];
    $resp = file_get_contents($api_url);
    
    //DEBUG: See what we're getting
    file_put_contents('debug_cart_response.txt', $resp);
    
    $data = json_decode($resp, true);
    $cart_items = $data['items'] ?? [];
} else {
    // guest -> session cart -> we must enrich with product details
    $session_cart = $_SESSION['cart'] ?? [];
    foreach ($session_cart as $entry) {
        // call product details to get price/name/image
        $pid = (int)$entry['product_id'];
        $prod_api = "http://169.239.251.102:442/~benson.vorsah/backend/products/details.php?id={$pid}";
        $prod_resp = @file_get_contents($prod_api);
        $prod = $prod_resp ? json_decode($prod_resp, true) : null;

        $price = $prod['price'] ?? 0;
        $name = $prod['name'] ?? 'Product';
        $image = $prod['image'] ?? '';

        $subtotal = $price * $entry['quantity'];
        $total += $subtotal;

        $cart_items[] = [
            'cart_item_id' => 's-'.$pid,
            'product_id' => $pid,
            'name' => $name,
            'image' => $image,
            'unit_price' => $price,
            'quantity' => $entry['quantity'],
            'subtotal' => $subtotal
        ];
    }
}

// Calculate total for logged-in users
if (!empty($_SESSION['user_id'])) {
    foreach ($cart_items as $item) {
        $total += $item['subtotal'] ?? 0;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Cart - Thoughtful</title>
    <link rel="stylesheet" href="styles/global.css">
    <link rel="stylesheet" href="styles/navbar.css">
    <link rel="stylesheet" href="styles/footer.css">
    <link rel="stylesheet" href="styles/cart.css">
</head>
<body>

<!-- Hero Section -->
<section class="cart-hero">
    <div class="hero-content">
        <h1>Shopping Cart</h1>
        <p><?= count($cart_items) ?> item<?= count($cart_items) !== 1 ? 's' : '' ?> in your cart</p>
    </div>
</section>

<!-- Cart Content -->
<section class="cart-section">
    <div class="cart-container">

        <?php if (!empty($_GET['msg'])): ?>
            <div class="alert alert-success">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                </svg>
                <span><?= htmlspecialchars($_GET['msg']) ?></span>
            </div>
        <?php endif; ?>

        <?php if (empty($cart_items)): ?>
            <!-- Empty Cart State -->
            <div class="empty-cart">
                <div class="empty-icon">🛒</div>
                <h2>Your Cart is Empty</h2>
                <p>Looks like you haven't added any items to your cart yet.</p>
                <a href="home.php" class="continue-shopping-btn">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.707-10.293a1 1 0 00-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L9.414 11H13a1 1 0 100-2H9.414l1.293-1.293z"/>
                    </svg>
                    Continue Shopping
                </a>
            </div>
        <?php else: ?>
            <div class="cart-layout">
                
                <!-- Cart Items -->
                <div class="cart-items">
                    <?php foreach ($cart_items as $item): ?>
                        <div class="cart-item">
                            <div class="item-image">
                                <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                            </div>

                            <div class="item-details">
                                <h3><?= htmlspecialchars($item['name']) ?></h3>
                                <p class="item-id">Product #<?= $item['product_id'] ?></p>
                                <p class="item-price">GH₵ <?= number_format($item['unit_price'], 2) ?></p>
                            </div>

                            <div class="item-quantity">
                                <form action="actions/update_cart.php" method="POST" class="quantity-form">
                                    <input type="hidden" name="cart_item_id" value="<?= htmlspecialchars($item['cart_item_id']) ?>">
                                    <div class="quantity-selector">
                                        <button type="button" class="qty-btn minus" onclick="decreaseQty(this)">−</button>
                                        <input type="number" name="quantity" value="<?= (int)$item['quantity'] ?>" min="1" class="qty-input" readonly>
                                        <button type="button" class="qty-btn plus" onclick="increaseQty(this)">+</button>
                                    </div>
                                    <button type="submit" class="update-btn">Update</button>
                                </form>
                            </div>

                            <div class="item-subtotal">
                                <p class="subtotal-label">Subtotal</p>
                                <p class="subtotal-amount">GH₵ <?= number_format($item['subtotal'], 2) ?></p>
                            </div>

                            <div class="item-remove">
                                <form action="actions/remove_from_cart.php" method="POST">
                                    <input type="hidden" name="cart_item_id" value="<?= htmlspecialchars($item['cart_item_id']) ?>">
                                    <button type="submit" class="remove-btn" title="Remove from cart">
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <a href="home.php" class="continue-link">
                        <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"/>
                        </svg>
                        Continue Shopping
                    </a>
                </div>

                <!-- Order Summary -->
                <div class="order-summary">
                    <h3>Order Summary</h3>

                    <div class="summary-row">
                        <span>Subtotal (<?= count($cart_items) ?> items)</span>
                        <span>GH₵ <?= number_format($total, 2) ?></span>
                    </div>

                    <div class="summary-row">
                        <span>Shipping</span>
                        <span class="free-shipping">FREE</span>
                    </div>

                    <div class="summary-divider"></div>

                    <div class="summary-row total">
                        <span>Total</span>
                        <span>GH₵ <?= number_format($total, 2) ?></span>
                    </div>

                    <a href="<?= empty($_SESSION['user_id']) ? 'login.php' : 'checkout.php' ?>" class="checkout-btn">
                        <?= empty($_SESSION['user_id']) ? 'Login to Checkout' : 'Proceed to Checkout' ?>
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"/>
                        </svg>
                    </a>

                    <div class="trust-badges">
                        <div class="badge">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                            </svg>
                            <span>Secure Checkout</span>
                        </div>
                        <div class="badge">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/>
                                <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z"/>
                            </svg>
                            <span>Free Shipping</span>
                        </div>
                    </div>
                </div>

            </div>
        <?php endif; ?>

    </div>
</section>

<?php include "components/footer.php"; ?>

<script>
function increaseQty(btn) {
    const input = btn.parentElement.querySelector('.qty-input');
    input.value = parseInt(input.value) + 1;
}

function decreaseQty(btn) {
    const input = btn.parentElement.querySelector('.qty-input');
    if (parseInt(input.value) > 1) {
        input.value = parseInt(input.value) - 1;
    }
}
</script>

</body>
</html>