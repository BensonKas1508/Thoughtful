<?php
// Get product ID from URL
if (!isset($_GET['id'])) {
    die("Product not found.");
}
$product_id = $_GET['id'];

// API URL (UPDATE USERNAME!)
$api_url = "http://169.239.251.102:442/~benson.vorsah/thoughtful/backend/products/details.php?id=" . $product_id;

// Fetch product details from backend API
$response = file_get_contents($api_url);
$product = json_decode($response, true);

if (!$product || isset($product['error'])) {
    die("Product not available.");
}

include "components/navbar.php";
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="styles/product.css">
    <title><?php echo $product['name']; ?></title>
</head>
<body>

<div class="product-page">

    <div class="product-image">
        <img src="<?php echo $product['image']; ?>" alt="">
    </div>

    <div class="product-details">
        <h1><?php echo $product['name']; ?></h1>
        <p class="price">GH₵ <?php echo number_format($product['price'], 2); ?></p>
        <p><?php echo $product['description']; ?></p>

        <form action="actions/add_to_cart.php" method="POST">
            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
            <label>Quantity:</label>
            <input type="number" name="quantity" value="1" min="1">

            <button type="submit" class="btn-add">Add to Cart</button>
        </form>
    </div>

</div>

<?php include "components/footer.php"; ?>
</body>
</html>
