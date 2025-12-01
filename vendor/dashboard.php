<?php
session_start();

// Check if vendor is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'vendor') {
    header("Location: ../login.php");
    exit;
}

$vendor_id = $_SESSION['user_id'];

// Fetch vendor analytics
$api_url = "http://169.239.251.102:442/~benson.vorsah/backend/vendor/analytics.php?vendor_id=" . $vendor_id;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$resp = curl_exec($ch);
curl_close($ch);

$analytics = [
    'total_products' => 0,
    'total_orders' => 0,
    'total_revenue' => 0,
    'pending_orders' => 0,
    'monthly_revenue' => []
];

if ($resp) {
    $data = json_decode($resp, true);
    if (isset($data['analytics'])) {
        $analytics = array_merge($analytics, $data['analytics']);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Vendor Dashboard - Thoughtful</title>
    <link rel="stylesheet" href="../styles/global.css">
    <link rel="stylesheet" href="../styles/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="admin-body">

<!-- Mobile Overlay -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

<div class="admin-container">
    
    <?php include "components/vendor_sidebar.php"; ?>

    <main class="admin-main">
        
        <!-- Top Bar -->
        <div class="admin-topbar">
            <button class="mobile-menu-btn" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
            <h1>Vendor Dashboard</h1>
            <div class="admin-user-info">
                <span>Welcome back, <strong><?= htmlspecialchars($_SESSION['name']) ?></strong></span>
                <div class="user-avatar">
                    <?= strtoupper(substr($_SESSION['name'], 0, 1)) ?>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background: #dbeafe;">
                    <svg width="32" height="32" viewBox="0 0 20 20" fill="#1e40af">
                        <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z"/>
                    </svg>
                </div>
                <div class="stat-info">
                    <p class="stat-label">My Products</p>
                    <p class="stat-value"><?= number_format($analytics['total_products']) ?></p>
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
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"/>
                    </svg>
                </div>
                <div class="stat-info">
                    <p class="stat-label">Pending Orders</p>
                    <p class="stat-value"><?= number_format($analytics['pending_orders']) ?></p>
                </div>
            </div>
        </div>

        <!-- Charts and Quick Actions -->
        <div class="dashboard-grid">
            
            <!-- Revenue Chart -->
            <div class="dashboard-card">
                <h2>Monthly Revenue</h2>
                <div class="chart-container">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="dashboard-card">
                <h2>Quick Actions</h2>
                <div style="display: flex; flex-direction: column; gap: 12px; margin-top: 20px;">
                    <a href="products.php" class="action-btn">
                        <i class="fas fa-box"></i>
                        <span>Manage Products</span>
                    </a>
                    <a href="orders.php" class="action-btn">
                        <i class="fas fa-shopping-bag"></i>
                        <span>View Orders</span>
                    </a>
                    <a href="products.php#add" class="action-btn" style="background: linear-gradient(135deg, #9b87f5 0%, #7c3aed 100%); color: white;">
                        <i class="fas fa-plus"></i>
                        <span>Add New Product</span>
                    </a>
                </div>
            </div>

        </div>

    </main>
</div>

<script>
function toggleSidebar() {
    document.querySelector('.admin-sidebar').classList.toggle('mobile-active');
    document.getElementById('sidebarOverlay').classList.toggle('active');
}

// Revenue Chart
const ctx = document.getElementById('revenueChart').getContext('2d');
const monthlyData = <?= json_encode($analytics['monthly_revenue']) ?>;

const months = monthlyData.map(item => item.month);
const revenue = monthlyData.map(item => parseFloat(item.revenue));

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: months,
        datasets: [{
            label: 'Revenue (GH₵)',
            data: revenue,
            backgroundColor: 'rgba(124, 58, 237, 0.8)',
            borderColor: '#7c3aed',
            borderWidth: 2,
            borderRadius: 8
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                padding: 12,
                callbacks: {
                    label: function(context) {
                        return 'Revenue: GH₵ ' + context.parsed.y.toLocaleString();
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'GH₵ ' + value.toLocaleString();
                    }
                },
                grid: {
                    color: 'rgba(0, 0, 0, 0.05)'
                }
            },
            x: {
                grid: {
                    display: false
                }
            }
        }
    }
});
</script>

<style>
.action-btn {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px 20px;
    background: #f9fafb;
    border-radius: 12px;
    text-decoration: none;
    color: #4b5563;
    font-weight: 600;
    transition: all 0.2s;
}

.action-btn:hover {
    transform: translateX(4px);
    background: #f3f4f6;
}

.action-btn i {
    font-size: 1.2rem;
}
</style>

</body>
</html>