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
    <title>Register</title>
    <link rel="stylesheet" href="styles/auth.css">
</head>
<body>

<div class="auth-container">
    <h2>Create Account</h2>

    <?php if (isset($_GET['error'])): ?>
        <p style="color:red;"><?= htmlspecialchars($_GET['error']) ?></p>
    <?php endif; ?>

    <form action="actions/do_register.php" method="POST">

        <label>Full Name</label>
        <input type="text" name="name" required>

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Phone Number</label>
        <input type="text" name="phone" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit">Register</button>

        <div class="auth-link">
            <p>Already have an account? <a href="login.php">Login</a></p>
        </div>
    </form>
</div>

</body>
</html>
