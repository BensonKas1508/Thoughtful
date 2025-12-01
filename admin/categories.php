<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

include "components/admin_header.php";

// Fetch categories
$api_url = "http://169.239.251.102:442/~benson.vorsah/backend/categories/list.php";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$resp = curl_exec($ch);
curl_close($ch);

$categories = [];
if ($resp) {
    $data = json_decode($resp, true);
    $categories = $data['categories'] ?? [];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Categories - Admin</title>
    <link rel="stylesheet" href="../styles/global.css">
    <link rel="stylesheet" href="../styles/admin.css">
</head>
<body class="admin-body">

<div class="admin-container">
    <?php include "components/admin_sidebar.php"; ?>

    <main class="admin-main">
        <div class="admin-header-bar">
            <h1>Manage Categories</h1>
        </div>

        <?php if (!empty($_GET['msg'])): ?>
            <div class="alert alert-success" style="margin-bottom: 24px; padding: 16px; background: #d1fae5; color: #065f46; border-radius: 12px;">
                <?= htmlspecialchars($_GET['msg']) ?>
            </div>
        <?php endif; ?>

        <div class="data-table">
            <div class="table-header">
                <h2>All Categories</h2>
                <div class="table-actions">
                    <button class="btn btn-primary" onclick="openAddModal()">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"/>
                        </svg>
                        Add Category
                    </button>
                </div>
            </div>

            <table id="categoriesTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Icon</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Products</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $category): ?>
                        <tr>
                            <td><?= $category['id'] ?></td>
                            <td style="font-size: 2rem;"><?= htmlspecialchars($category['icon'] ?? '📦') ?></td>
                            <td><?= htmlspecialchars($category['name']) ?></td>
                            <td><?= htmlspecialchars($category['description'] ?? '') ?></td>
                            <td><?= $category['product_count'] ?? 0 ?></td>
                            <td class="table-actions-cell">
                                <button class="btn btn-sm btn-secondary" onclick='openEditModal(<?= json_encode($category) ?>)'>
                                    Edit
                                </button>
                                <button class="btn btn-sm btn-danger" onclick="deleteCategory(<?= $category['id'] ?>)">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

<!-- Add/Edit Category Modal -->
<div id="categoryModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modalTitle">Add Category</h2>
            <button class="modal-close" onclick="closeModal()">&times;</button>
        </div>
        <form id="categoryForm" action="actions/save_category.php" method="POST">
            <input type="hidden" name="category_id" id="category_id">
            
            <div class="form-group">
                <label for="name">Category Name</label>
                <input type="text" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="icon">Icon (Emoji)</label>
                <input type="text" id="icon" name="icon" placeholder="🎁" maxlength="2">
                <small style="color: #6b7280;">Enter an emoji (e.g., 🎁, 🎂, 💐)</small>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description"></textarea>
            </div>

            <div class="table-actions">
                <button type="submit" class="btn btn-primary">Save Category</button>
                <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
function openAddModal() {
    document.getElementById('modalTitle').textContent = 'Add Category';
    document.getElementById('categoryForm').reset();
    document.getElementById('category_id').value = '';
    document.getElementById('categoryModal').classList.add('active');
}

function openEditModal(category) {
    document.getElementById('modalTitle').textContent = 'Edit Category';
    document.getElementById('category_id').value = category.id;
    document.getElementById('name').value = category.name;
    document.getElementById('icon').value = category.icon || '';
    document.getElementById('description').value = category.description || '';
    document.getElementById('categoryModal').classList.add('active');
}

function closeModal() {
    document.getElementById('categoryModal').classList.remove('active');
}

function deleteCategory(id) {
    if (confirm('Are you sure you want to delete this category? All products in this category will be affected.')) {
        window.location.href = 'actions/delete_category.php?id=' + id;
    }
}
</script>

</body>
</html>