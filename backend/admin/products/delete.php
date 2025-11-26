<?php
require_once "../../config/db.php";
require_once "../../helpers/response.php";
require_once "../auth_check.php";

$admin_id = $_POST["admin_id"] ?? null;
$product_id = $_POST["product_id"] ?? null;
$status = $_POST["status"] ?? null;  // "active" or "inactive"

requireAdmin($pdo, $admin_id);

if (!$product_id || !$status) {
    jsonResponse(["status" => "error", "message" => "Missing required fields"], 400);
}

$stmt = $pdo->prepare("UPDATE products SET status = ? WHERE id = ?");
$stmt->execute([$status, $product_id]);

jsonResponse([
    "status" => "success",
    "message" => "Product status updated successfully"
]);
?>
