<?php


include "components/navbar.php";

// FETCH PRODUCTS FROM BACKEND
$api_url = "http://169.239.251.102:442/~benson.vorsah/backend/products/list.php";
$response = file_get_contents($api_url);
$products = json_decode($response, true);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Thoughtful Gifts</title>
    <link rel="stylesheet" href="styles/global.css">
    <link rel="stylesheet" href="styles/navbar.css">
    <link rel="stylesheet" href="styles/home.css">
    <link rel="stylesheet" href="styles/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>

<!-- HERO SECTION -->
<section class="hero">
    <div class="hero-content">
        <div class="hero-text">
            <h1>Find the Perfect Thoughtful Gift</h1>
            <p>Gift ideas for every budget, style & occasion — made easy.</p>

            <form class="search-bar">
                <input type="text" placeholder="Search gifts, vendors or occasions…">
                <button type="submit">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <path d="M9 17A8 8 0 1 0 9 1a8 8 0 0 0 0 16zM19 19l-4.35-4.35" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    Search
                </button>
            </form>
        </div>
        <div class="hero-image">
            <img src="https://images.unsplash.com/photo-1513885535751-8b9238bd345a?w=600&h=600&fit=crop" alt="Gift boxes">
        </div>
    </div>
</section>

<!-- FEATURES SECTION -->
<section class="features">
    <div class="feature-card">
        <div class="feature-icon">🎁</div>
        <h3>Curated Selection</h3>
        <p>Handpicked gifts for every occasion</p>
    </div>
    <div class="feature-card">
        <div class="feature-icon">🚚</div>
        <h3>Fast Delivery</h3>
        <p>Same-day delivery available</p>
    </div>
    <div class="feature-card">
        <div class="feature-icon">💝</div>
        <h3>Gift Wrapping</h3>
        <p>Beautiful packaging included</p>
    </div>
    <div class="feature-card">
        <div class="feature-icon">⭐</div>
        <h3>Trusted Vendors</h3>
        <p>Quality guaranteed products</p>
    </div>
</section>

<!-- CATEGORY ROW -->
<?php
$cat_api = "http://169.239.251.102:442/~benson.vorsah/backend/categories/list.php";
$cat_json = file_get_contents($cat_api);
$cat_data = json_decode($cat_json, true);
$categories = $cat_data["categories"] ?? [];
?>

<section class="categories">
    <div class="cat-title">Browse by Occasion</div>

    <div class="cat-grid">
        <?php foreach ($categories as $cat): ?>
            <a href="products.php?cat=<?= $cat['id'] ?>" class="cat-card">
                <div class="category-name"><?= $cat['name'] ?></div>
            </a>
        <?php endforeach; ?>
    </div>
</section>


<!-- PRODUCT GRID -->
<section class="products">
    <div class="section-header">
        <h2 class="section-title">Top Picks For You</h2>
        <a href="products.php" class="view-all">View All →</a>
    </div>

    <div class="product-grid">
        <?php if (!empty($products["products"])): ?>
            <?php foreach ($products["products"] as $p): ?>
                <a href="product.php?id=<?= $p['id'] ?>" class="product-card">
                    <div class="img-box">
                        <img src="<?= $p['image'] ?>" alt="<?= $p['name'] ?>">
                        <div class="product-badge">New</div>
                    </div>

                    <div class="product-info">
                        <h3><?= $p['name'] ?></h3>
                        <p class="price">GH₵ <?= number_format($p['price'], 2) ?></p>
                        <button class="quick-view">Quick View</button>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="empty">No products available.</p>
        <?php endif; ?>
    </div>
</section>

<!-- CTA SECTION -->
<section class="cta-section">
    <div class="cta-content">
        <h2>Become a Vendor</h2>
        <p>Share your unique gifts with thousands of customers</p>
        <a href="register.php?role=vendor" class="cta-button">Start Selling</a>
    </div>
</section>

<?php include "components/footer.php"; ?>

</body>
</html>