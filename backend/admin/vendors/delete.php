<?php
require_once "../../config/db.php";
require_once "../../helpers/response.php";
require_once "../auth_check.php";

$admin_id = $_POST["admin_id"] ?? null;
$vendor_id = $_POST["vendor_id"] ?? null;

requireAdmin($pdo, $admin_id);

if (!$vendor_id) {
    jsonResponse(["status"=>"error","message"=>"Vendor ID required"],400);
}

$stmt = $pdo->prepare("DELETE FROM vendors WHERE id = ?");
$stmt->execute([$vendor_id]);

jsonResponse([
    "status" => "success",
    "message" => "Vendor deleted successfully"
]);
?>
