<?php
include "components/navbar.php";

// Fetch categories from backend
$api_url = "http://169.239.251.102:442/~benson.vorsah/backend/categories/list.php";
$response = file_get_contents($api_url);
$data = json_decode($response, true);

$categories = $data["categories"] ?? [];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Categories - Thoughtful Gifts</title>
    <link rel="stylesheet" href="styles/navbar.css">
    <link rel="stylesheet" href="styles/home.css">
    <link rel="stylesheet" href="styles/footer.css">
    <link rel="stylesheet" href="styles/categories.css">
</head>

<body>

<section class="category-page">
    <h2 class="title">Browse All Categories</h2>

    <div class="cat-grid-page">
        <?php foreach ($categories as $c): ?>
            <a href="products.php?cat=<?= $c['id'] ?>" class="cat-box">
                <?= htmlspecialchars($c['name']) ?>
            </a>
        <?php endforeach; ?>
    </div>
</section>

<?php include "components/footer.php"; ?>
</body>
</html>
