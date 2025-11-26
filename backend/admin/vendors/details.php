<?php
require_once "../../config/db.php";
require_once "../../helpers/response.php";
require_once "../auth_check.php";

$admin_id = $_POST["admin_id"] ?? null;
$vendor_id = $_POST["vendor_id"] ?? null;

requireAdmin($pdo, $admin_id);

$stmt = $pdo->prepare("
    SELECT v.*, u.name AS owner_name, u.email AS owner_email, u.phone
    FROM vendors v
    LEFT JOIN users u ON u.id = v.user_id
    WHERE v.id = ?
");
$stmt->execute([$vendor_id]);
$vendor = $stmt->fetch();

if (!$vendor) {
    jsonResponse(["status"=>"error","message"=>"Vendor not found"],404);
}

jsonResponse([
    "status" => "success",
    "vendor" => $vendor
]);
?>
