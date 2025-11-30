<?php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include "components/navbar.php";

// Fetch orders from backend
$user_id = (int)$_SESSION['user_id'];
$api_url = "http://169.239.251.102:442/~benson.vorsah/backend/orders/list.php?user_id=" . $user_id;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
$resp = curl_exec($ch);
curl_close($ch);

$orders = [];
if ($resp) {
    $data = json_decode($resp, true);
    $orders = $data['orders'] ?? [];
}

// Status badge colors
function getStatusClass($status) {
    $classes = [
        'pending' => 'status-pending',
        'processing' => 'status-processing',
        'shipped' => 'status-shipped',
        'delivered' => 'status-delivered',
        'cancelled' => 'status-cancelled'
    ];
    return $classes[$status] ?? 'status-pending';
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Orders - Thoughtful</title>
    <link rel="stylesheet" href="styles/global.css">
    <link rel="stylesheet" href="styles/navbar.css">
    <link rel="stylesheet" href="styles/footer.css">
    <link rel="stylesheet" href="styles/orders.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<!-- Hero Section -->
<section class="orders-hero">
    <div class="hero-content">
        <h1>My Orders</h1>
        <p>Track and manage your orders</p>
    </div>
</section>

<!-- Orders Content -->
<section class="orders-section">
    <div class="orders-container">

        <!-- Back to Profile -->
        <a href="profile.php" class="back-link">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"/>
            </svg>
            Back to Profile
        </a>

        <?php if (empty($orders)): ?>
            <!-- Empty Orders State -->
            <div class="empty-orders">
                <div class="empty-icon">📦</div>
                <h2>No Orders Yet</h2>
                <p>You haven't placed any orders yet. Start shopping to see your orders here!</p>
                <a href="home.php" class="continue-shopping-btn">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.707-10.293a1 1 0 00-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L9.414 11H13a1 1 0 100-2H9.414l1.293-1.293z"/>
                    </svg>
                    Start Shopping
                </a>
            </div>
        <?php else: ?>
            
            <!-- Orders Summary -->
            <div class="orders-summary">
                <div class="summary-card">
                    <div class="summary-icon">📦</div>
                    <div class="summary-info">
                        <p class="summary-label">Total Orders</p>
                        <p class="summary-value"><?= count($orders) ?></p>
                    </div>
                </div>
                
                <div class="summary-card">
                    <div class="summary-icon">⏳</div>
                    <div class="summary-info">
                        <p class="summary-label">Pending</p>
                        <p class="summary-value"><?= count(array_filter($orders, fn($o) => $o['status'] === 'pending')) ?></p>
                    </div>
                </div>
                
                <div class="summary-card">
                    <div class="summary-icon">✅</div>
                    <div class="summary-info">
                        <p class="summary-label">Delivered</p>
                        <p class="summary-value"><?= count(array_filter($orders, fn($o) => $o['status'] === 'delivered')) ?></p>
                    </div>
                </div>
            </div>

            <!-- Orders List -->
            <div class="orders-list">
                <?php foreach ($orders as $order): ?>
                    <div class="order-card">
                        <div class="order-header">
                            <div class="order-info">
                                <h3>Order #<?= htmlspecialchars($order['id']) ?></h3>
                                <p class="order-date">
                                    <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"/>
                                    </svg>
                                    <?= date('F j, Y', strtotime($order['created_at'])) ?>
                                </p>
                            </div>
                            <span class="order-status <?= getStatusClass($order['status']) ?>">
                                <?= ucfirst(htmlspecialchars($order['status'])) ?>
                            </span>
                        </div>

                        <div class="order-items">
                            <?php 
                            $items = $order['items'] ?? [];
                            $displayCount = min(3, count($items));
                            for ($i = 0; $i < $displayCount; $i++):
                                $item = $items[$i];
                            ?>
                                <div class="order-item">
                                    <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                                    <div class="item-info">
                                        <p class="item-name"><?= htmlspecialchars($item['name']) ?></p>
                                        <p class="item-qty">Qty: <?= (int)$item['quantity'] ?></p>
                                    </div>
                                </div>
                            <?php endfor; ?>
                            
                            <?php if (count($items) > 3): ?>
                                <p class="more-items">+<?= count($items) - 3 ?> more item(s)</p>
                            <?php endif; ?>
                        </div>

                        <div class="order-footer">
                            <div class="order-total">
                                <span>Total:</span>
                                <strong>GH₵ <?= number_format($order['total'], 2) ?></strong>
                            </div>
                            <a href="order-details.php?id=<?= $order['id'] ?>" class="view-order-btn">
                                View Details
                                <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

        <?php endif; ?>

    </div>
</section>

<?php include "components/footer.php"; ?>

</body>
</html>