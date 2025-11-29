<?php
session_start();
include "components/navbar.php";

$cart_items = [];
$total = 0.0;

if (!empty($_SESSION['user_id'])) {
    // logged in -> fetch from backend
    $api_url = "http://169.239.251.102:442/~benson.vorsah/backend/cart/list.php?user_id=" . (int)$_SESSION['user_id'];
    $resp = file_get_contents($api_url);
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
?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Cart - Thoughtful</title>
    <link rel="stylesheet" href="styles/navbar.css">
    <link rel="stylesheet" href="styles/home.css">
    <link rel="stylesheet" href="styles/cart.css">
    <link rel="stylesheet" href="styles/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<div class="container" style="max-width:1100px;margin:30px auto;padding:0 20px;">
    <h2>Your Cart</h2>

    <?php if (!empty($_GET['msg'])): ?>
        <p style="color:green"><?= htmlspecialchars($_GET['msg']) ?></p>
    <?php endif; ?>

    <?php if (empty($cart_items)): ?>
        <p>Your cart is empty. <a href="home.php">Continue shopping</a></p>
    <?php else: ?>
        <table style="width:100%;border-collapse:collapse">
            <thead>
                <tr>
                    <th style="text-align:left">Product</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($cart_items as $item): ?>
                <tr>
                    <td>
                        <div style="display:flex;gap:12px;align-items:center">
                            <img src="<?= htmlspecialchars($item['image']) ?>" alt="" style="width:72px;height:72px;object-fit:cover;border-radius:8px">
                            <div>
                                <strong><?= htmlspecialchars($item['name']) ?></strong><br>
                                <small>Product #<?= $item['product_id'] ?></small>
                            </div>
                        </div>
                    </td>
                    <td style="text-align:center">GH₵ <?= number_format($item['unit_price'],2) ?></td>
                    <td style="text-align:center">
                        <form action="frontend/actions/update_cart.php" method="POST" style="display:inline-block">
                            <input type="hidden" name="cart_item_id" value="<?= htmlspecialchars($item['cart_item_id']) ?>">
                            <input type="number" name="quantity" value="<?= (int)$item['quantity'] ?>" min="1" style="width:70px;padding:6px">
                            <button type="submit">Update</button>
                        </form>
                    </td>
                    <td style="text-align:center">GH₵ <?= number_format($item['subtotal'],2) ?></td>
                    <td style="text-align:center">
                        <form action="frontend/actions/remove_from_cart.php" method="POST">
                            <input type="hidden" name="cart_item_id" value="<?= htmlspecialchars($item['cart_item_id']) ?>">
                            <button type="submit">Remove</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <div style="text-align:right;margin-top:20px">
            <h3>Total: GH₵ <?= number_format($total,2) ?></h3>
            <a href="<?= empty($_SESSION['user_id']) ? 'login.php' : 'checkout.php' ?>" class="btn">Proceed to Checkout</a>
        </div>
    <?php endif; ?>
</div>

<?php include "components/footer.php"; ?>
</body>
</html>
