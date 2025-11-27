<?php
header("Content-Type: application/json");
include "../config/database.php";

$input = json_decode(file_get_contents("php://input"), true);

$name = $input["name"];
$email = $input["email"];
$phone = $input["phone"];
$password = $input["password"];

if (!$name || !$email || !$password) {
    echo json_encode(["status" => "error", "message" => "Missing fields"]);
    exit;
}

// Check if email exists
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$email]);

if ($stmt->rowCount() > 0) {
    echo json_encode(["status" => "error", "message" => "Email already exists"]);
    exit;
}

// Insert user
$hashed = password_hash($password, PASSWORD_BCRYPT);

$insert = $conn->prepare("
    INSERT INTO users (name, email, phone, password_hash, role)
    VALUES (?, ?, ?, ?, 'customer')
");

$insert->execute([$name, $email, $phone, $hashed]);

$user_id = $conn->lastInsertId();

echo json_encode([
    "status" => "success",
    "message" => "User registered",
    "user" => [
        "id" => $user_id,
        "name" => $name,
        "email" => $email,
        "role" => "customer"
    ]
]);
