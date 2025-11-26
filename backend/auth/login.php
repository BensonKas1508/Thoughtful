<?php
require_once "../config/db.php";
require_once "../helpers/response.php";

if (!isset($_POST["email"], $_POST["password"])) {
    jsonResponse(["status" => "error", "message" => "Email and password required"], 400);
}

$email = trim($_POST["email"]);
$password = $_POST["password"];

// Check user
$stmt = $pdo->prepare("
    SELECT id, name, email, password_hash, role, phone 
    FROM users 
    WHERE email = ?
    LIMIT 1
");
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user) {
    jsonResponse(["status" => "error", "message" => "Invalid email or password"], 401);
}

// Verify password
if (!password_verify($password, $user["password_hash"])) {
    jsonResponse(["status" => "error", "message" => "Invalid email or password"], 401);
}

// Return user info
jsonResponse([
    "status" => "success",
    "message" => "Login successful",
    "user" => [
        "id" => $user["id"],
        "name" => $user["name"],
        "email" => $user["email"],
        "role" => $user["role"],
        "phone" => $user["phone"]
    ]
]);
?>
