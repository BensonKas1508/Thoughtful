<?php
// Validate product ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Product not found.");
}

$product_id = (int) $_GET['id'];

// Backend API endpoint
$api_url = "http://169.239.251.102:442/~benson.vorsah/backend/products/details.php?id=" . $product_id;

// Fetch product from backend API
$response = @file_get_contents($api_url);

if (!$response) {
    die("Unable to load product details.");
}

$json = json_decode($response, true);

// Ensure backend returned proper structure
if (!isset($json["status"]) || $json["status"] !== "success") {
    die("Product not available.");
}

if (!isset($json["product"])) {
    die("Invalid product data.");
}

$product = $json["product"];

// Load navbar
include "components/navbar.php";
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($product['name']) ?></title>

    <link rel="stylesheet" href="styles/navbar.css">
    <link rel="stylesheet" href="styles/footer.css">
    <link rel="stylesheet" href="styles/product_details.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>

<div class="product-page">

    <!-- PRODUCT IMAGE -->
    <div class="product-image">
        <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
    </div>

    <!-- PRODUCT DETAILS -->
    <div class="product-details">
        <h1><?= htmlspecialchars($product['name']) ?></h1>

        <p class="price">
            GH₵ <?= number_format($product['price'], 2) ?>
        </p>

        <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>

        <!-- ADD TO CART FORM -->
        <form action="frontend/actions/add_to_cart.php" method="POST">
            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">

            <label>Quantity:</label>
            <input type="number" name="quantity" value="1" min="1">

            <button type="submit" class="btn-add">
                <i class="fa fa-cart-plus"></i> Add to Cart
            </button>
        </form>
    </div>

</div>

<?php include "components/footer.php"; ?>

</body>
</html>
