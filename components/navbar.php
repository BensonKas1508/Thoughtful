<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<nav class="navbar">
    <div class="nav-container">

        <div class="nav-left">
            <a class="logo" href="home.php">
                <img src="assets/logo.png" alt="Thoughtful Logo">
                <span>Thoughtful</span>
            </a>
        </div>

        <div class="nav-right">
            <a href="home.php" class="nav-link">
                <svg width="18" height="18" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                </svg>
                <span>Home</span>
            </a>
            
            <a href="categories.php" class="nav-link">
                <svg width="18" height="18" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                </svg>
                <span>Categories</span>
            </a>
            
            <a href="cart.php" class="nav-link cart-link">
                <svg width="18" height="18" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                </svg>
                <span>Cart</span>
                <?php 
                // Calculate cart item count (cached in session)
                $cart_count = 0;

                // only fetch cart count if not already in session or if it's been more than 30 seconds
                if (!isset($_SESSION['cart_count']) || !isset($_SESSION['cart_count_time']) || (time() - $_SESSION['cart_count_time']) > 30) {
                    if (!empty($_SESSION['user_id'])) {
                    // For logged-in users - fetch from backend
                    $cart_api = "http://169.239.251.102:442/~benson.vorsah/backend/cart/list.php?user_id=" . (int)$_SESSION['user_id'];
                    $cart_resp = @file_get_contents($cart_api);
                    if ($cart_resp) {
                        $cart_data = json_decode($cart_resp, true);
                        $cart_count = count($cart_data['items'] ?? []);
                    }
                } else {
                    // For guests - count session cart
                    $cart_count = count($_SESSION['cart'] ?? []);
                }

                // Cache the count
                $_SESSION['cart_count'] = $cart_count;
                $_SESSION['cart_count_time'] = time();
            } else {
                $cart_count = $_SESSION['cart_count'];
            }
            ?>
            <?php if ($cart_count > 0): ?>
                <span class="cart-badge"><?= $cart_count ?></span>
            <?php endif; ?>
        </a>

            <?php if (isset($_SESSION['user_id'])): ?>
                <div class="user-menu">
                    <button class="user-button">
                        <svg width="18" height="18" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                        </svg>
                        <span class="user">Hi, <?= $_SESSION['user_name'] ?? $_SESSION['name'] ?? 'Guest' ?></span>                        <svg class="dropdown-icon" width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                        </svg>
                    </button>
                    <div class="dropdown-menu">
                        <a href="profile.php" class="dropdown-item">
                            <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                            </svg>
                            My Profile
                        </a>
                        <a href="orders.php" class="dropdown-item">
                            <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3z"/>
                            </svg>
                            My Orders
                        </a>

                        <?php if ($_SESSION['role'] === 'admin'): ?>
                            <a href="admin/dashboard.php" class="dropdown-item">
                                <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                                </svg>
                                Admin Dashboard
                            </a>
                        <?php endif; ?>

                        <div class="dropdown-divider"></div>
                        <a href="logout.php" class="dropdown-item logout-item">
                            <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z"/>
                            </svg>
                            Logout
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <a class="login-btn" href="login.php">
                    <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 011-1zm7.707 3.293a1 1 0 010 1.414L9.414 9H17a1 1 0 110 2H9.414l1.293 1.293a1 1 0 01-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0z"/>
                    </svg>
                    Login
                </a>
            <?php endif; ?>
        </div>

        <!-- Mobile Menu Toggle -->
        <button class="mobile-menu-toggle" id="mobileMenuToggle">
            <svg class="menu-icon" width="24" height="24" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"/>
            </svg>
        </button>

    </div>
</nav>

<script>
// Dropdown menu toggle
document.addEventListener('DOMContentLoaded', function() {
    const userButton = document.querySelector('.user-button');
    const dropdownMenu = document.querySelector('.dropdown-menu');
    
    if (userButton && dropdownMenu) {
        userButton.addEventListener('click', function(e) {
            e.stopPropagation();
            dropdownMenu.classList.toggle('show');
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function() {
            dropdownMenu.classList.remove('show');
        });
    }
    
    // Mobile menu toggle
    const mobileToggle = document.getElementById('mobileMenuToggle');
    const navRight = document.querySelector('.nav-right');
    
    if (mobileToggle && navRight) {
        mobileToggle.addEventListener('click', function() {
            navRight.classList.toggle('mobile-active');
        });
    }
});
</script>