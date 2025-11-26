<?php
require_once "../../config/db.php";
require_once "../../helpers/response.php";
require_once "../auth_check.php";

$admin_id = $_POST["admin_id"] ?? null;
requireAdmin($pdo, $admin_id);

$stmt = $pdo->query("
    SELECT id, name, email, phone, role, created_at 
    FROM users 
    ORDER BY created_at DESC
");

jsonResponse([
    "status" => "success",
    "users" => $stmt->fetchAll()
]);
?>
