<?php
header("Content-Type: application/json");
include "../config/db.php";

$user_id = $_GET["user_id"] ?? null;

if (!$user_id) {
    echo json_encode(["status" => "error", "message" => "User ID required"]);
    exit;
}

$stmt = $pdo->prepare("SELECT id, name, email, phone, role, created_at FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo json_encode(["status" => "error", "message" => "User not found"]);
    exit;
}

echo json_encode([
    "status" => "success",
    "user" => $user
]);
?>