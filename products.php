<?php
include "components/navbar.php";

$cat_id = $_GET['cat'] ?? 0;

$api_url = "http://169.239.251.102:442/~benson.vorsah/backend/products/list.php?category=$cat_id";
$response = file_get_contents($api_url);
$data = json_decode($response, true);

$products = $data["products"] ?? [];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Products</title>
    <link rel="stylesheet" href="styles/global.css">
    <link rel="stylesheet" href="styles/navbar.css">
    <link rel="stylesheet" href="styles/home.css">
    <link rel="stylesheet" href="styles/products.css">
    <link rel="stylesheet" href="styles/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<section class="products">
    <div class="section-title">Products</div>

    <div class="product-grid">
        <?php foreach ($products as $p): ?>
            <a href="product.php?id=<?= $p['id'] ?>" class="product-card">
                <div class="img-box">
                    <img src="<?= $p['image'] ?>" alt="<?= $p['name'] ?>">
                </div>
                <h3><?= $p['name'] ?></h3>
                <p class="price">GH₵ <?= number_format($p['price'], 2) ?></p>
            </a>
        <?php endforeach; ?>
    </div>
</section>

<!-- FILTER BAR -->
<section class="filter-section">
    <form method="GET" class="filter-form">

        <!-- Search -->
        <input 
            type="text" 
            name="search" 
            placeholder="Search gifts…" 
            value="<?= $_GET['search'] ?? '' ?>"
        >

        <!-- Category -->
        <select name="category">
            <option value="">All Categories</option>
            <?php foreach ($categories as $cat): ?>
                <option 
                    value="<?= $cat['id'] ?>"
                    <?= isset($_GET['category']) && $_GET['category'] == $cat['id'] ? 'selected' : '' ?>
                >
                    <?= htmlspecialchars($cat['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <!-- Price Min -->
        <input 
            type="number" 
            name="price_min" 
            placeholder="Min Price" 
            value="<?= $_GET['price_min'] ?? '' ?>"
        >

        <!-- Price Max -->
        <input 
            type="number" 
            name="price_max" 
            placeholder="Max Price" 
            value="<?= $_GET['price_max'] ?? '' ?>"
        >

        <!-- Delivery -->
        <select name="delivery">
            <option value="">Delivery Type</option>
            <option value="pickup">Pickup</option>
            <option value="delivery">Delivery</option>
            <option value="both">Both</option>
        </select>

        <button type="submit">Apply Filters</button>
    </form>
</section>


<?php include "components/footer.php"; ?>
</body>
</html>
