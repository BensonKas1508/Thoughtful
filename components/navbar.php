<nav class="navbar">
    <div class="nav-left">
        <a href="home.php" class="logo">Thoughtful</a>
    </div>

    <div class="nav-right">
        <a href="home.php">Home</a>
        <a href="categories.php">Categories</a>
        <a href="cart.php">Cart</a>

        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="profile.php">Hi, <?= $_SESSION['name'] ?></a>
            <a href="logout.php" class="logout">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
        <?php endif; ?>
    </div>
</nav>
