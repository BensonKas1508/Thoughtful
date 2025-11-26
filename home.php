<?php
include "components/navbar.php";

// Fetch products from backend API
$api_url = "http://169.239.251.102:442//~benson.vorsah/thoughtful/backend/products/list.php";
$response = file_get_contents($api_url);
$products = json_decode($response, true);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Thoughtful - Home</title>
    <link rel="stylesheet" href="styles/home.css">
</head>

<body>

<div class="hero">
    <h1>Thoughtful Gifts</h1>
    <p>Find the perfect gift for any budget, any occasion.</p>
</div>

<div class="section-title">Popular Products</div>

<div class="product-grid">

    <?php if (!empty($products)): ?>
        <?php foreach ($products as $item): ?>
            <a href="product.php?id=<?php echo $item['id']; ?>" class="product-card">

                <div class="img-box">
                    <img src="<?php echo $item['image']; ?>" alt="">
                </div>

                <h3><?php echo $item['name']; ?></h3>

                <p class="price">GH₵ <?php echo number_format($item['price'], 2); ?></p>

            </a>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="empty-msg">No products found.</p>
    <?php endif; ?>

</div>

<?php include "components/footer.php"; ?>

</body>
</html>
