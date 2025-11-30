<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

include "components/admin_header.php";

// Fetch users from backend
$api_url = "http://169.239.251.102:442/~benson.vorsah/backend/admin/users/list.php";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$resp = curl_exec($ch);
curl_close($ch);

$users = [];
if ($resp) {
    $data = json_decode($resp, true);
    $users = $data['users'] ?? [];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Users - Admin</title>
    <link rel="stylesheet" href="../styles/global.css">
    <link rel="stylesheet" href="../styles/admin.css">
</head>
<body class="admin-body">

<div class="admin-container">
    <?php include "components/admin_sidebar.php"; ?>

    <main class="admin-main">
        <div class="admin-header-bar">
            <h1>Manage Users</h1>
        </div>

        <?php if (!empty($_GET['msg'])): ?>
            <div class="alert alert-success" style="margin-bottom: 24px; padding: 16px; background: #d1fae5; color: #065f46; border-radius: 12px;">
                <?= htmlspecialchars($_GET['msg']) ?>
            </div>
        <?php endif; ?>

        <div class="data-table">
            <div class="table-header">
                <h2>All Users</h2>
                <div class="table-actions">
                    <button class="btn btn-primary" onclick="openAddModal()">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"/>
                        </svg>
                        Add User
                    </button>
                </div>
            </div>

            <div class="search-box">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"/>
                </svg>
                <input type="text" id="searchInput" placeholder="Search users..." onkeyup="filterTable()">
            </div>

            <table id="usersTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Role</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= $user['id'] ?></td>
                            <td><?= htmlspecialchars($user['name']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td><?= htmlspecialchars($user['phone'] ?? 'N/A') ?></td>
                            <td>
                                <span class="badge badge-<?= $user['role'] === 'admin' ? 'danger' : ($user['role'] === 'vendor' ? 'warning' : 'info') ?>">
                                    <?= ucfirst($user['role']) ?>
                                </span>
                            </td>
                            <td><?= date('M d, Y', strtotime($user['created_at'])) ?></td>
                            <td class="table-actions-cell">
                                <button class="btn btn-sm btn-secondary" onclick='openEditModal(<?= json_encode($user) ?>)'>
                                    Edit
                                </button>
                                <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                    <button class="btn btn-sm btn-danger" onclick="deleteUser(<?= $user['id'] ?>)">
                                        Delete
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

<!-- Add/Edit User Modal -->
<div id="userModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modalTitle">Add User</h2>
            <button class="modal-close" onclick="closeModal()">&times;</button>
        </div>
        <form id="userForm" action="actions/save_user.php" method="POST">
            <input type="hidden" name="user_id" id="user_id">
            
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="tel" id="phone" name="phone">
            </div>

            <div class="form-group">
                <label for="role">Role</label>
                <select id="role" name="role" required>
                    <option value="customer">Customer</option>
                    <option value="vendor">Vendor</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <div class="form-group" id="passwordGroup">
                <label for="password">Password</label>
                <input type="password" id="password" name="password">
                <small style="color: #6b7280;">Leave blank to keep current password (when editing)</small>
            </div>

            <div class="table-actions">
                <button type="submit" class="btn btn-primary">Save User</button>
                <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
function openAddModal() {
    document.getElementById('modalTitle').textContent = 'Add User';
    document.getElementById('userForm').reset();
    document.getElementById('user_id').value = '';
    document.getElementById('password').required = true;
    document.getElementById('userModal').classList.add('active');
}

function openEditModal(user) {
    document.getElementById('modalTitle').textContent = 'Edit User';
    document.getElementById('user_id').value = user.id;
    document.getElementById('name').value = user.name;
    document.getElementById('email').value = user.email;
    document.getElementById('phone').value = user.phone || '';
    document.getElementById('role').value = user.role;
    document.getElementById('password').required = false;
    document.getElementById('userModal').classList.add('active');
}

function closeModal() {
    document.getElementById('userModal').classList.remove('active');
}

function deleteUser(id) {
    if (confirm('Are you sure you want to delete this user?')) {
        window.location.href = 'actions/delete_user.php?id=' + id;
    }
}

function filterTable() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toLowerCase();
    const table = document.getElementById('usersTable');
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