<?php
header("Content-Type: application/json");
include "../../config/db.php";

$input = json_decode(file_get_contents("php://input"), true);

if (!empty($input['password'])) {
    $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, phone = ?, role = ?, password_hash = ? WHERE id = ?");
    $stmt->execute([
        $input['name'], 
        $input['email'], 
        $input['phone'], 
        $input['role'], 
        $input['password'], 
        $input['user_id']
    ]);
} else {
    $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, phone = ?, role = ? WHERE id = ?");
    $stmt->execute([
        $input['name'], 
        $input['email'], 
        $input['phone'], 
        $input['role'], 
        $input['user_id']
    ]);
}

echo json_encode(["status" => "success", "message" => "User updated"]);
?>