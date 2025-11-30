// create.php
<?php
header("Content-Type: application/json");
include "../../config/db.php";

$input = json_decode(file_get_contents("php://input"), true);

$stmt = $pdo->prepare("INSERT INTO users (name, email, phone, password_hash, role) VALUES (?, ?, ?, ?, ?)");
$stmt->execute([$input['name'], $input['email'], $input['phone'], $input['password'], $input['role']]);

echo json_encode(["status" => "success", "message" => "User created"]);
?>