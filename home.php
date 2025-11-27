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
    <link rel="stylesheet" href="styles/navbar.css">
    <link rel="stylesheet" href="styles/home.css">
    <link rel="stylesheet" href="styles/footer.css">
</head>

<body>

<!-- HERO SECTION -->
<section class="hero">
    <h1>Find the Perfect Thoughtful Gift</h1>
    <p>Gift ideas for every budget, style & occasion — made easy.</p>

    <form class="search-bar">
        <input type="text" placeholder="Search gifts, vendors or occasions…">
        <button>Search</button>
    </form>
</section>

<!-- CATEGORY ROW -->
<section class="categories">
    <div class="cat-title">Browse Categories</div>

    <div class="cat-grid">
        <div class="cat-card">Birthdays</div>
        <div class="cat-card">Anniversary</div>
        <div class="cat-card">Mother’s Day</div>
        <div class="cat-card">Valentine's</div>
        <div class="cat-card">Corporate</div>
    </div>
</section>

<!-- PRODUCT GRID -->
<section class="products">
    <div class="section-title">Top Picks For You</div>

    <div class="product-grid">
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $p): ?>
                <a href="product.php?id=<?= $p['id'] ?>" class="product-card">
                    <div class="img-box">
                        <img src="<?= $p['image'] ?>" alt="<?= $p['name'] ?>">
                    </div>

                    <h3><?= $p['name'] ?></h3>
                    <p class="price">GH₵ <?= number_format($p['price'], 2) ?></p>
                </a>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="empty">No products available.</p>
        <?php endif; ?>
    </div>
</section>

<?php include "components/footer.php"; ?>

</body>
</html>
