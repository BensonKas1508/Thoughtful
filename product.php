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
    <link rel="stylesheet" href="styles/navbar.css">
    <link rel="stylesheet" href="styles/home.css">
    <link rel="stylesheet" href="styles/footer.css">
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

<?php include "components/footer.php"; ?>
</body>
</html>
