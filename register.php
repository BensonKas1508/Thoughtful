<?php
session_start();
include "components/navbar.php";

// If logged in → redirect
if (isset($_SESSION['user_id'])) {
    header("Location: home.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register - Thoughtful</title>
    <link rel="stylesheet" href="styles/navbar.css">
    <link rel="stylesheet" href="styles/footer.css">
    <link rel="stylesheet" href="styles/auth.css">
</head>
<body>

<div class="auth-page">
    <div class="auth-wrapper">
        
        <!-- Left Side - Hero -->
        <div class="auth-hero">
            <div class="hero-content">
                <div class="hero-logo">
                    <img src="assets/logo.png" alt="Thoughtful">
                    <span>Thoughtful</span>
                </div>
                <h2>Join Our Gifting Community</h2>
                <p>Create meaningful moments with thoughtfully curated gifts for every occasion.</p>
                
                <div class="hero-features">
                    <div class="hero-feature">
                        <div class="feature-icon">🎁</div>
                        <span>Thousands of unique gifts</span>
                    </div>
                    <div class="hero-feature">
                        <div class="feature-icon">🚚</div>
                        <span>Fast & reliable delivery</span>
                    </div>
                    <div class="hero-feature">
                        <div class="feature-icon">⭐</div>
                        <span>Trusted by gift-givers</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Form -->
        <div class="auth-container">
            <div class="auth-header">
                <h2>Create Account</h2>
                <p>Start your gifting journey today</p>
            </div>

            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-error">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"/>
                    </svg>
                    <span><?= htmlspecialchars($_GET['error']) ?></span>
                </div>
            <?php endif; ?>

            <form action="frontend/actions/do_register.php" method="POST" class="auth-form">

                <div class="form-group">
                    <label for="name">Full Name</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                        </svg>
                        <input type="text" id="name" name="name" placeholder="John Doe" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                        </svg>
                        <input type="email" id="email" name="email" placeholder="you@example.com" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                        </svg>
                        <input type="tel" id="phone" name="phone" placeholder="+233 XX XXX XXXX" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"/>
                        </svg>
                        <input type="password" id="password" name="password" placeholder="Minimum 8 characters" minlength="8" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Confirm Password</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"/>
                        </svg>
                        <input type="password" id="password" name="confirm_password" placeholder="Minimum 8 characters" minlength="8" required>
                    </div>
                </div>

                <button type="submit" class="auth-button">
                    Create Account
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"/>
                    </svg>
                </button>

                <div class="auth-divider">
                    <span>or</span>
                </div>

                <div class="auth-link">
                    <p>Already have an account? <a href="login.php">Sign in</a></p>
                </div>
            </form>
        </div>

    </div>
</div>

<?php include "components/footer.php"; ?>

</body>
</html>