<?php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include "components/navbar.php";

// Fetch user data from backend
$user_id = (int)$_SESSION['user_id'];
$api_url = "http://169.239.251.102:442/~benson.vorsah/backend/users/details.php?user_id=" . $user_id;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
$resp = curl_exec($ch);
curl_close($ch);

$user = null;
if ($resp) {
    $data = json_decode($resp, true);
    $user = $data['user'] ?? null;
}

// Fallback to session data if API fails
if (!$user) {
    $user = [
        'name' => $_SESSION['name'] ?? 'User',
        'email' => $_SESSION['email'] ?? '',
        'phone' => '',
        'role' => $_SESSION['role'] ?? 'customer',
        'created_at' => ''
    ];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Profile - Thoughtful</title>
    <link rel="stylesheet" href="styles/global.css">
    <link rel="stylesheet" href="styles/navbar.css">
    <link rel="stylesheet" href="styles/footer.css">
    <link rel="stylesheet" href="styles/profile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<!-- Hero Section -->
<section class="profile-hero">
    <div class="hero-content">
        <h1>My Profile</h1>
        <p>Manage your account information</p>
    </div>
</section>

<!-- Profile Content -->
<section class="profile-section">
    <div class="profile-container">

        <?php if (!empty($_GET['msg'])): ?>
            <div class="alert alert-success">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                </svg>
                <span><?= htmlspecialchars($_GET['msg']) ?></span>
            </div>
        <?php endif; ?>

        <?php if (!empty($_GET['error'])): ?>
            <div class="alert alert-error">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"/>
                </svg>
                <span><?= htmlspecialchars($_GET['error']) ?></span>
            </div>
        <?php endif; ?>

        <div class="profile-layout">
            
            <!-- Sidebar -->
            <aside class="profile-sidebar">
                <div class="profile-card">
                    <div class="profile-avatar">
                        <svg width="80" height="80" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                        </svg>
                    </div>
                    <h3><?= htmlspecialchars($user['name']) ?></h3>
                    <p class="profile-role"><?= ucfirst(htmlspecialchars($user['role'])) ?></p>
                    <?php if ($user['created_at']): ?>
                        <p class="profile-joined">Member since <?= date('M Y', strtotime($user['created_at'])) ?></p>
                    <?php endif; ?>
                </div>

                <nav class="profile-nav">
                    <a href="profile.php" class="nav-item active">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                        </svg>
                        Profile Information
                    </a>
                    <a href="orders.php" class="nav-item">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3z"/>
                        </svg>
                        My Orders
                    </a>
                    <a href="logout.php" class="nav-item logout">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z"/>
                        </svg>
                        Logout
                    </a>
                </nav>
            </aside>

            <!-- Main Content -->
            <div class="profile-main">
                
                <!-- Personal Information -->
                <div class="profile-section-card">
                    <div class="card-header">
                        <h2>Personal Information</h2>
                        <button type="button" class="btn-edit" onclick="toggleEdit('personal-form')">
                            <svg width="18" height="18" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                            </svg>
                            Edit
                        </button>
                    </div>
                    
                    <form id="personal-form" action="actions/update_profile.php" method="POST" class="profile-form">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="name">Full Name</label>
                                <input type="text" id="name" name="name" value="<?= htmlspecialchars($user['name']) ?>" disabled>
                            </div>
                            
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" disabled>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="tel" id="phone" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" disabled>
                            </div>
                        </div>

                        <div class="form-actions" style="display: none;">
                            <button type="submit" class="btn-primary">Save Changes</button>
                            <button type="button" class="btn-secondary" onclick="cancelEdit('personal-form')">Cancel</button>
                        </div>
                    </form>
                </div>

                <!-- Change Password -->
                <div class="profile-section-card">
                    <div class="card-header">
                        <h2>Change Password</h2>
                        <button type="button" class="btn-edit" onclick="toggleEdit('password-form')">
                            <svg width="18" height="18" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                            </svg>
                            Edit
                        </button>
                    </div>
                    
                    <form id="password-form" action="actions/change_password.php" method="POST" class="profile-form">
                        <div class="form-group">
                            <label for="current_password">Current Password</label>
                            <input type="password" id="current_password" name="current_password" disabled>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="new_password">New Password</label>
                                <input type="password" id="new_password" name="new_password" disabled>
                            </div>
                            
                            <div class="form-group">
                                <label for="confirm_password">Confirm New Password</label>
                                <input type="password" id="confirm_password" name="confirm_password" disabled>
                            </div>
                        </div>

                        <div class="form-actions" style="display: none;">
                            <button type="submit" class="btn-primary">Update Password</button>
                            <button type="button" class="btn-secondary" onclick="cancelEdit('password-form')">Cancel</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>

    </div>
</section>

<?php include "components/footer.php"; ?>

<script>
function toggleEdit(formId) {
    const form = document.getElementById(formId);
    const inputs = form.querySelectorAll('input');
    const actions = form.querySelector('.form-actions');
    
    inputs.forEach(input => input.disabled = false);
    actions.style.display = 'flex';
}

function cancelEdit(formId) {
    const form = document.getElementById(formId);
    const inputs = form.querySelectorAll('input');
    const actions = form.querySelector('.form-actions');
    
    form.reset();
    inputs.forEach(input => input.disabled = true);
    actions.style.display = 'none';
}
</script>

</body>
</html>