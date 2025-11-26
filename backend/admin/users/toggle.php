<?php
require_once "../../config/db.php";
require_once "../../helpers/response.php";
require_once "../auth_check.php";

$admin_id = $_POST["admin_id"] ?? null;
$user_id = $_POST["user_id"] ?? null;
$status = $_POST["status"] ?? null; // "active" or "inactive"

requireAdmin($pdo, $admin_id);

if (!$user_id || !$status) {
    jsonResponse(["status"=>"error","message"=>"Missing fields"],400);
}

$stmt = $pdo->prepare("UPDATE users SET role = ? WHERE id = ?");
$stmt->execute([$status, $user_id]);

jsonResponse(["status" => "success", "message" => "User updated"]);
?>
