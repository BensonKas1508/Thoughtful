<?php
include "components/navbar.php";

// Fetch categories from backend
$api_url = "http://169.239.251.102:442/~benson.vorsah/backend/categories/list.php";
$response = file_get_contents($api_url);
$data = json_decode($response, true);

$categories = $data["categories"] ?? [];

// Category icons mapping
$category_icons = [
    'Birthdays' => '🎂',
    'Anniversary' => '💑',
    'Mothers Day' => '🌹',
    "Mother's Day" => '🌹',
    'Valentines' => '❤️',
    "Valentine's" => '❤️',
    'Corporate' => '💼',
    'Wedding' => '💍',
    'Christmas' => '🎄',
    'Graduation' => '🎓',
    'Baby Shower' => '🍼',
    'Thank You' => '🙏',
    'Get Well' => '💐',
    'Congratulations' => '🎉',
    'Default' => '🎁'
];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Categories - Thoughtful Gifts</title>
    <link rel="stylesheet" href="styles/global.css">
    <link rel="stylesheet" href="styles/navbar.css">
    <link rel="stylesheet" href="styles/footer.css">
    <link rel="stylesheet" href="styles/categories.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>

<!-- Hero Section -->
<section class="categories-hero">
    <div class="hero-content">
        <h1>Browse All Categories</h1>
        <p>Find the perfect gift for every occasion</p>
    </div>
</section>

<!-- Categories Grid Section -->
<section class="categories-section">
    <div class="categories-container">
        
        <?php if (!empty($categories)): ?>
            <div class="categories-grid">
                <?php foreach ($categories as $c): ?>
                    <?php 
                        $categoryName = htmlspecialchars($c['name']);
                        $icon = $category_icons[$categoryName] ?? $category_icons['Default'];
                    ?>
                    <a href="category.php?cat=<?= $c['id'] ?>" class="category-card">
                        <div class="category-icon-wrapper">
                            <div class="category-icon"><?= $icon ?></div>
                        </div>
                        <div class="category-info">
                            <h3><?= $categoryName ?></h3>
                            <p>Explore gifts</p>
                            <div class="category-arrow">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"/>
                                </svg>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-icon">🎁</div>
                <h3>No Categories Available</h3>
                <p>Check back soon for new categories!</p>
            </div>
        <?php endif; ?>
        
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
    <div class="cta-content">
        <h2>Can't Find What You're Looking For?</h2>
        <p>Browse all our products or contact us for custom gift recommendations</p>
        <div class="cta-buttons">
            <a href="products.php" class="cta-btn primary">View All Products</a>
            <a href="contact.php" class="cta-btn secondary">Contact Us</a>
        </div>
    </div>
</section>

<?php include "components/footer.php"; ?>

</body>
</html>