<?php
include "components/navbar.php";

// Check category ID
if (!isset($_GET['cat'])) {
    die("Category not found.");
}

$cat_id = intval($_GET['cat']);

// Fetch category products
$api_url = "http://169.239.251.102:442/~benson.vorsah/backend/products/list.php?cat=" . $cat_id;
$response = file_get_contents($api_url);
$data = json_decode($response, true);

$products = $data["products"] ?? [];

// Fetch category name
$cat_api = "http://169.239.251.102:442/~benson.vorsah/backend/categories/list.php";
$cat_resp = file_get_contents($cat_api);
$cat_data = json_decode($cat_resp, true);

$cat_name = "Category";
if (!empty($cat_data["categories"])) {
    foreach ($cat_data["categories"] as $c) {
        if ($c["id"] == $cat_id) {
            $cat_name = $c["name"];
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($cat_name) ?> - Thoughtful Gifts</title>
    <link rel="stylesheet" href="styles/global.css">
    <link rel="stylesheet" href="styles/navbar.css">
    <link rel="stylesheet" href="styles/footer.css">
    <link rel="stylesheet" href="styles/categories.css">
    <link rel="stylesheet" href="styles/home.css"> <!-- product grid styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>

<section class="categories-hero">
    <div class="hero-content">
        <h1><?= htmlspecialchars($cat_name) ?></h1>
        <p>Explore all gifts under this category</p>
    </div>
</section>

<section class="categories-section">
    <div class="categories-container">

        <?php if (!empty($products)): ?>
            <div class="product-grid">

                <?php foreach ($products as $p): ?>
                    <a href="product.php?id=<?= $p['id'] ?>" class="product-card">
                        <div class="img-box">
                            <img src="<?= $p['image'] ?>" alt="<?= $p['name'] ?>">
                        </div>

                        <h3><?= htmlspecialchars($p['name']) ?></h3>
                        <p class="price">GH₵ <?= number_format($p['price'], 2) ?></p>
                    </a>
                <?php endforeach; ?>

            </div>
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-icon">😔</div>
                <h3>No Products Found</h3>
                <p>Check back later — new gifts are added often!</p>
            </div>
        <?php endif; ?>

    </div>
</section>

<?php include "components/footer.php"; ?>

</body>
</html>
