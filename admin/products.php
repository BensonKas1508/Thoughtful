<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

include "components/admin_header.php";

// Fetch products from backend
$api_url = "http://169.239.251.102:442/~benson.vorsah/backend/products/list_all.php";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$resp = curl_exec($ch);
curl_close($ch);

$products = [];
if ($resp) {
    $data = json_decode($resp, true);
    $products = $data['products'] ?? [];
}

// Fetch categories for dropdown
$cat_url = "http://169.239.251.102:442/~benson.vorsah/backend/categories/list.php";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $cat_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$cat_resp = curl_exec($ch);
curl_close($ch);

$categories = [];
if ($cat_resp) {
    $cat_data = json_decode($cat_resp, true);
    $categories = $cat_data['categories'] ?? [];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Products - Admin</title>
    <link rel="stylesheet" href="../styles/global.css">
    <link rel="stylesheet" href="../styles/admin.css">
</head>
<body class="admin-body">

<div class="admin-container">
    <?php include "components/admin_sidebar.php"; ?>

    <main class="admin-main">
        <div class="admin-header-bar">
            <h1>Manage Products</h1>
        </div>

        <?php if (!empty($_GET['msg'])): ?>
            <div class="alert alert-success" style="margin-bottom: 24px; padding: 16px; background: #d1fae5; color: #065f46; border-radius: 12px;">
                <?= htmlspecialchars($_GET['msg']) ?>
            </div>
        <?php endif; ?>

        <div class="data-table">
            <div class="table-header">
                <h2>All Products</h2>
                <div class="table-actions">
                    <button class="btn btn-primary" onclick="openAddModal()">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"/>
                        </svg>
                        Add Product
                    </button>
                </div>
            </div>

            <div class="search-box">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"/>
                </svg>
                <input type="text" id="searchInput" placeholder="Search products..." onkeyup="filterTable()">
            </div>

            <table id="productsTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?= $product['id'] ?></td>
                            <td>
                                <img src="<?= htmlspecialchars($product['image'] ?? '') ?>" alt="" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                            </td>
                            <td><?= htmlspecialchars($product['name']) ?></td>
                            <td><?= htmlspecialchars($product['category_name'] ?? 'N/A') ?></td>
                            <td>GH₵ <?= number_format($product['price'], 2) ?></td>
                            <td><?= $product['stock'] ?? 'N/A' ?></td>
                            <td>
                                <span class="badge badge-<?= $product['status'] === 'active' ? 'success' : 'warning' ?>">
                                    <?= ucfirst($product['status']) ?>
                                </span>
                            </td>
                            <td class="table-actions-cell">
                                <button class="btn btn-sm btn-secondary" onclick='openEditModal(<?= json_encode($product) ?>)'>
                                    Edit
                                </button>
                                <button class="btn btn-sm btn-danger" onclick="deleteProduct(<?= $product['id'] ?>)">
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

<!-- Add/Edit Product Modal -->
<div id="productModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modalTitle">Add Product</h2>
            <button class="modal-close" onclick="closeModal()">&times;</button>
        </div>
        <form id="productForm" action="actions/save_product.php" method="POST">
            <input type="hidden" name="product_id" id="product_id">
            
            <div class="form-group">
                <label for="name">Product Name</label>
                <input type="text" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="category_id">Category</label>
                <select id="category_id" name="category_id" required>
                    <option value="">Select Category</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="vendor_id">Vendor ID</label>
                <input type="number" id="vendor_id" name="vendor_id" required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" required></textarea>
            </div>

            <div class="form-group">
                <label for="price">Price (GH₵)</label>
                <input type="number" id="price" name="price" step="0.01" required>
            </div>

            <div class="form-group">
                <label for="stock">Stock</label>
                <input type="number" id="stock" name="stock" required>
            </div>

            <div class="form-group">
                <label for="image_url">Image URL</label>
                <input type="url" id="image_url" name="image_url" required>
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status" required>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>

            <div class="table-actions">
                <button type="submit" class="btn btn-primary">Save Product</button>
                <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
function openAddModal() {
    document.getElementById('modalTitle').textContent = 'Add Product';
    document.getElementById('productForm').reset();
    document.getElementById('product_id').value = '';
    document.getElementById('productModal').classList.add('active');
}

function openEditModal(product) {
    document.getElementById('modalTitle').textContent = 'Edit Product';
    document.getElementById('product_id').value = product.id;
    document.getElementById('name').value = product.name;
    document.getElementById('category_id').value = product.category_id;
    document.getElementById('vendor_id').value = product.vendor_id;
    document.getElementById('description').value = product.description;
    document.getElementById('price').value = product.price;
    document.getElementById('stock').value = product.stock || 0;
    document.getElementById('image_url').value = product.image || '';
    document.getElementById('status').value = product.status;
    document.getElementById('productModal').classList.add('active');
}

function closeModal() {
    document.getElementById('productModal').classList.remove('active');
}

function deleteProduct(id) {
    if (confirm('Are you sure you want to delete this product?')) {
        window.location.href = 'actions/delete_product.php?id=' + id;
    }
}

function filterTable() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toLowerCase();
    const table = document.getElementById('productsTable');
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