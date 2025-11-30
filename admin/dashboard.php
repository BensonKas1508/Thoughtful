<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

include "components/admin_header.php";

// Fetch analytics from backend
$api_url = "http://169.239.251.102:442/~benson.vorsah/backend/admin/analytics.php";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
$resp = curl_exec($ch);
curl_close($ch);

$analytics = [
    'total_users' => 0,
    'total_orders' => 0,
    'total_revenue' => 0,
    'total_products' => 0,
    'pending_orders' => 0,
    'monthly_revenue' => []
];

if ($resp) {
    $data = json_decode($resp, true);
    if ($data['status'] === 'success') {
        $analytics = array_merge($analytics, $data['analytics']);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - Thoughtful</title>
    <link rel="stylesheet" href="../styles/global.css">
    <link rel="stylesheet" href="../styles/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="admin-body">

<div class="admin-container">
    
    <?php include "components/admin_sidebar.php"; ?>

    <main class="admin-main">
        
        <div class="admin-header-bar">
            <h1>Dashboard</h1>
            <div class="admin-user">
                <span>Welcome, <?= htmlspecialchars($_SESSION['name']) ?></span>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background: #dbeafe;">
                    <svg width="32" height="32" viewBox="0 0 20 20" fill="#1e40af">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                    </svg>
                </div>
                <div class="stat-info">
                    <p class="stat-label">Total Users</p>
                    <p class="stat-value"><?= number_format($analytics['total_users']) ?></p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: #fef3c7;">
                    <svg width="32" height="32" viewBox="0 0 20 20" fill="#92400e">
                        <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3z"/>
                    </svg>
                </div>
                <div class="stat-info">
                    <p class="stat-label">Total Orders</p>
                    <p class="stat-value"><?= number_format($analytics['total_orders']) ?></p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: #d1fae5;">
                    <svg width="32" height="32" viewBox="0 0 20 20" fill="#065f46">
                        <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z"/>
                    </svg>
                </div>
                <div class="stat-info">
                    <p class="stat-label">Total Revenue</p>
                    <p class="stat-value">GH₵ <?= number_format($analytics['total_revenue'], 2) ?></p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: #e0e7ff;">
                    <svg width="32" height="32" viewBox="0 0 20 20" fill="#4338ca">
                        <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z"/>
                    </svg>
                </div>
                <div class="stat-info">
                    <p class="stat-label">Total Products</p>
                    <p class="stat-value"><?= number_format($analytics['total_products']) ?></p>
                </div>
            </div>
        </div>

        <!-- Charts and Recent Activity -->
        <div class="dashboard-grid">
            
            <!-- Revenue Chart -->
            <div class="dashboard-card">
                <h2>Monthly Revenue</h2>
                <div class="chart-container">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h2>Pending Orders</h2>
                    <a href="orders.php" class="view-all">View All</a>
                </div>
                <div class="pending-orders-count">
                    <p class="pending-number"><?= $analytics['pending_orders'] ?></p>
                    <p class="pending-label">Orders need attention</p>
                </div>
            </div>

        </div>

        <!-- Quick Actions -->
        <div class="quick-actions">
            <h2>Quick Actions</h2>
            <div class="actions-grid">
                <a href="users.php" class="action-card">
                    <svg width="24" height="24" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                    </svg>
                    <span>Manage Users</span>
                </a>
                
                <a href="products.php" class="action-card">
                    <svg width="24" height="24" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z"/>
                    </svg>
                    <span>Manage Products</span>
                </a>
                
                <a href="categories.php" class="action-card">
                    <svg width="24" height="24" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                    </svg>
                    <span>Manage Categories</span>
                </a>
                
                <a href="orders.php" class="action-card">
                    <svg width="24" height="24" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3z"/>
                    </svg>
                    <span>Manage Orders</span>
                </a>
            </div>
        </div>

    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Revenue Chart
const ctx = document.getElementById('revenueChart').getContext('2d');
const monthlyData = <?= json_encode($analytics['monthly_revenue']) ?>;

const months = monthlyData.map(item => item.month);
const revenue = monthlyData.map(item => parseFloat(item.revenue));

new Chart(ctx, {
    type: 'line',
    data: {
        labels: months,
        datasets: [{
            label: 'Revenue (GH₵)',
            data: revenue,
            borderColor: '#7c3aed',
            backgroundColor: 'rgba(124, 58, 237, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'GH₵ ' + value.toLocaleString();
                    }
                }
            }
        }
    }
});
</script>

</body>
</html>