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
    <title>Login</title>
    <link rel="stylesheet" href="styles/auth.css">
</head>
<body>

<div class="auth-container">
    <h2>Login</h2>

    <?php if (isset($_GET['error'])): ?>
        <p style="color:red;"><?= htmlspecialchars($_GET['error']) ?></p>
    <?php endif; ?>

    <form action="actions/do_login.php" method="POST">

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit">Login</button>

        <div class="auth-link">
            <p>Don't have an account? <a href="register.php">Create one</a></p>
        </div>
    </form>

</div>

</body>
</html>
