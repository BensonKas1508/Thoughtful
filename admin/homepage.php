<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

include "components/admin_header.php";

// Fetch all orders
$api_url = "http://169.239.251.102:442/~benson.vorsah/backend/admin/orders/list.php";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$resp = curl_exec($ch);
curl_close($ch);

$orders = [];
if ($resp) {
    $data = json_decode($resp, true);
    $orders = $data['orders'] ?? [];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Orders - Admin</title>
    <link rel="stylesheet" href="../styles/global.css">
    <link rel="stylesheet" href="../styles/admin.css">
</head>
<body class="admin-body">

<div class="admin-container">
    <?php include "components/admin_sidebar.php"; ?>

    <main class="admin-main">
        <div class="admin-header-bar">
            <h1>Manage Orders</h1>
        </div>

        <?php if (!empty($_GET['msg'])): ?>
            <div class="alert alert-success" style="margin-bottom: 24px; padding: 16px; background: #d1fae5; color: #065f46; border-radius: 12px;">
                <?= htmlspecialchars($_GET['msg']) ?>
            </div>
        <?php endif; ?>

        <div class="data-table">
            <div class="table-header">
                <h2>All Orders</h2>
            </div>

            <div class="search-box">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"/>
                </svg>
                <input type="text" id="searchInput" placeholder="Search orders..." onkeyup="filterTable()">
            </div>

            <table id="ordersTable">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Delivery</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td>#<?= $order['id'] ?></td>
                            <td><?= htmlspecialchars($order['customer_name']) ?></td>
                            <td>GH₵ <?= number_format($order['total'], 2) ?></td>
                            <td>
                                <span class="badge badge-<?= 
                                    $order['status'] === 'delivered' ? 'success' : 
                                    ($order['status'] === 'cancelled' ? 'danger' : 
                                    ($order['status'] === 'shipped' ? 'info' : 'warning')) 
                                ?>">
                                    <?= ucfirst($order['status']) ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($order['delivery_type'] ?? 'Standard') ?></td>
                            <td><?= date('M d, Y', strtotime($order['created_at'])) ?></td>
                            <td class="table-actions-cell">
                                <button class="btn btn-sm btn-secondary" onclick='openStatusModal(<?= json_encode($order) ?>)'>
                                    Update Status
                                </button>
                                <button class="btn btn-sm btn-primary" onclick="viewOrder(<?= $order['id'] ?>)">
                                    View Details
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

<!-- Update Status Modal -->
<div id="statusModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Update Order Status</h2>
            <button class="modal-close" onclick="closeModal()">&times;</button>
        </div>
        <form id="statusForm" action="actions/update_order_status.php" method="POST">
            <input type="hidden" name="order_id" id="order_id">
            
            <div class="form-group">
                <label>Order ID</label>
                <input type="text" id="display_order_id" disabled style="background: #f9fafb;">
            </div>

            <div class="form-group">
                <label>Customer</label>
                <input type="text" id="display_customer" disabled style="background: #f9fafb;">
            </div>

            <div class="form-group">
                <label>Total Amount</label>
                <input type="text" id="display_total" disabled style="background: #f9fafb;">
            </div>

            <div class="form-group">
                <label for="status">New Status</label>
                <select id="status" name="status" required>
                    <option value="pending">Pending</option>
                    <option value="processing">Processing</option>
                    <option value="shipped">Shipped</option>
                    <option value="delivered">Delivered</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>

            <div class="form-group">
                <label for="refund">Issue Refund?</label>
                <select id="refund" name="refund">
                    <option value="no">No</option>
                    <option value="yes">Yes - Full Refund</option>
                </select>
                <small style="color: #6b7280;">Select "Yes" if cancelling the order and issuing a refund</small>
            </div>

            <div class="table-actions">
                <button type="submit" class="btn btn-primary">Update Order</button>
                <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
function openStatusModal(order) {
    document.getElementById('order_id').value = order.id;
    document.getElementById('display_order_id').value = '#' + order.id;
    document.getElementById('display_customer').value = order.customer_name;
    document.getElementById('display_total').value = 'GH₵ ' + parseFloat(order.total).toFixed(2);
    document.getElementById('status').value = order.status;
    document.getElementById('statusModal').classList.add('active');
}

function closeModal() {
    document.getElementById('statusModal').classList.remove('active');
}

function viewOrder(id) {
    window.location.href = 'order_details.php?id=' + id;
}

function filterTable() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toLowerCase();
    const table = document.getElementById('ordersTable');
    const rows = table.getElementsByTagName('tr');

    for (let i = 1; i < rows.length; i++) {
        const cells = rows[i].getElementsByTagName('td');
        let found = false;
        
        for (let j = 0; j < cells.length - 1; j++) {
            if (cells[j].textContent.toLowerCase().indexOf(filter) > -1) {
                found = true;
                break;
            }
        }
        
        rows[i].style.display = found ? '' : 'none';
    }
}
</script>

</body>
</html>