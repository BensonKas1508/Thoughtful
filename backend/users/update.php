<?php
header("Content-Type: application/json");
include "../config/db.php";

$input = json_decode(file_get_contents("php://input"), true);

$user_id = $input["user_id"] ?? null;
$name = $input["name"] ?? null;
$email = $input["email"] ?? null;
$phone = $input["phone"] ?? null;

if (!$user_id || !$name || !$email) {
    echo json_encode(["status" => "error", "message" => "Required fields missing"]);
    exit;
}

$stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, phone = ? WHERE id = ?");
$stmt->execute([$name, $email, $phone, $user_id]);

echo json_encode(["status" => "success", "message" => "Profile updated successfully"]);
?>