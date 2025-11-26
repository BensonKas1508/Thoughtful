<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Thoughtful – Home</title>
    <link rel="stylesheet" href="styles/global.css" />
    <link rel="stylesheet" href="styles/home.css" />
</head>

<body>
    <!-- NAVBAR -->
    <header class="navbar">
        <div class="nav-left">
            <img src="assets/logo.png" class="logo" />
            <h1 class="brand">Thoughtful</h1>
        </div>

        <nav class="nav-links">
            <a href="#">Home</a>
            <a href="#">Shop</a>
            <a href="#">Occasions</a>
            <a href="#">Vendors</a>
            <a href="#">Contact</a>
        </nav>

        <div class="nav-right">
            <a href="login.html" class="btn-login">Login</a>
            <a href="cart.html" class="btn-cart">🛒</a>
        </div>
    </header>

    <!-- HERO -->
    <section class="hero">
        <div class="hero-text">
            <h2>Experience A Variety Of Unique Gift Products</h2>
            <p>Find the perfect gift handcrafted by trusted vendors.</p>
            <button class="btn-primary">Shop Now</button>
        </div>
        <div class="hero-image">
            <img src="assets/hero-placeholder.png" alt="Gift Box" />
        </div>
    </section>

    <!-- FEATURES -->
    <section class="features">
        <div class="feature-card">
            <div class="icon-circle">🎁</div>
            <h3>Easy to Order</h3>
        </div>

        <div class="feature-card">
            <div class="icon-circle">💜</div>
            <h3>A Special Touch</h3>
        </div>

        <div class="feature-card">
            <div class="icon-circle">🚚</div>
            <h3>Fast Delivery</h3>
        </div>
    </section>

    <!-- PRODUCT COLLECTION -->
    <section class="section-title">
        <h2>Shop A Gift Collection</h2>
        <p>Carefully curated gift sets for every occasion.</p>
    </section>

    <section class="products-grid" id="products-container">
        <!-- JS will load products here -->
    </section>

    <!-- FOOTER -->
    <footer class="footer">
        <p>© 2025 Thoughtful. Benson Kas-Vorsah.</p>
    </footer>

    <script src="scripts/api.js"></script>
    <script src="scripts/home.js"></script>
</body>
</html>
