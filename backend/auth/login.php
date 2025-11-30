<?php
header("Content-Type: application/json");
include "../config/db.php";

$input = json_decode(file_get_contents("php://input"), true);

$email = $input["email"] ?? null;
$password = $input["password"] ?? null;

if (!$email || !$password) {
    echo json_encode(["status" => "error", "message" => "Missing email or password"]);
    exit;
}

// Find user by email
$stmt = $pdo->prepare("SELECT id, name, email, password_hash, role FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user) {
    echo json_encode(["status" => "error", "message" => "Invalid email or password"]);
    exit;
}

// Verify password
if (!password_verify($password, $user['password_hash'])) {
    echo json_encode(["status" => "error", "message" => "Invalid email or password"]);
    exit;
}

// Success
echo json_encode([
    "status" => "success",
    "message" => "Login successful",
    "user" => [
        "id" => $user['id'],
        "name" => $user['name'],
        "email" => $user['email'],
        "role" => $user['role']
    ]
]);
?>