<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<nav class="navbar">
    <div class="nav-container">

        <div class="nav-left">
            <a class="logo" href="home.php">
                <img src="../assets/logo.png" alt="Thoughtful Logo">
                <span>Thoughtful</span>
            </a>
        </div>

        <div class="nav-right">
            <a href="home.php">Home</a>
            <a href="categories.php">Categories</a>
            <a href="cart.php">Cart</a>

            <?php if (isset($_SESSION['user_id'])): ?>
                <span class="user">Hi, <?= $_SESSION['name'] ?></span>
                <a class="logout" href="logout.php">Logout</a>
            <?php else: ?>
                <a class="login-btn" href="login.php">Login</a>
            <?php endif; ?>
        </div>

    </div>
</nav>
