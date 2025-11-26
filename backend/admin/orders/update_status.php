<?php
require_once "../../config/db.php";
require_once "../../helpers/response.php";
require_once "../auth_check.php";

$admin_id = $_POST["admin_id"] ?? null;
$order_id = $_POST["order_id"] ?? null;
$status = $_POST["status"] ?? null;

requireAdmin($pdo, $admin_id);

$allowed = ["pending", "confirmed", "out_for_delivery", "delivered", "cancelled"];
if (!in_array($status, $allowed)) {
    jsonResponse(["status"=>"error","message"=>"Invalid status"],400);
}

$stmt = $pdo->prepare("UPDATE orders SET order_status = ? WHERE id = ?");
$stmt->execute([$status, $order_id]);

jsonResponse([
    "status"=>"success",
    "message"=>"Order status updated"
]);
?>
