<?php
session_start();
include "components/navbar.php";

// If already logged in → redirect
if (isset($_SESSION['user_id'])) {
    header("Location: home.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Thoughtful</title>
    <link rel="stylesheet" href="styles/global.css">
    <link rel="stylesheet" href="styles/navbar.css">
    <link rel="stylesheet" href="styles/footer.css">
    <link rel="stylesheet" href="styles/auth.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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
                <h2>Welcome Back!</h2>
                <p>Continue your gifting journey and spread joy with thoughtfully curated gifts.</p>
                
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
                <h2>Sign In</h2>
                <p>Access your account to continue</p>
            </div>

            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-error">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"/>
                    </svg>
                    <span><?= htmlspecialchars($_GET['error']) ?></span>
                </div>
            <?php endif; ?>

            <form action="frontend/actions/do_login.php" method="POST" class="auth-form">

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
                    <label for="password">Password</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"/>
                        </svg>
                        <input type="password" id="password" name="password" placeholder="Enter your password" required>
                    </div>
                </div>

                <div class="forgot-password">
                    <a href="forgot-password.php">Forgot password?</a>
                </div>

                <button type="submit" class="auth-button">
                    Sign In
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"/>
                    </svg>
                </button>

                <div class="auth-divider">
                    <span>or</span>
                </div>

                <div class="auth-link">
                    <p>Don't have an account? <a href="register.php">Create one</a></p>
                </div>
            </form>
        </div>

    </div>
</div>

<?php include "components/footer.php"; ?>

</body>
</html>