<?php
require_once "../../config/db.php";
require_once "../../helpers/response.php";
require_once "../auth_check.php";

$admin_id = $_POST["admin_id"] ?? null;
$user_id = $_POST["user_id"] ?? null;

requireAdmin($pdo, $admin_id);

$stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
$stmt->execute([$user_id]);

jsonResponse(["status"=>"success","message"=>"User deleted"]);
?>
