<?php
require_once "../config/db.php";
require_once "../helpers/response.php";

// Validate required fields
if (!isset($_POST["name"], $_POST["email"], $_POST["password"])) {
    jsonResponse(["status" => "error", "message" => "Missing required fields"], 400);
}

$name = trim($_POST["name"]);
$email = trim($_POST["email"]);
$password = $_POST["password"];
$phone = $_POST["phone"] ?? null;
$role = $_POST["role"] ?? "customer";

// Validate role
$allowed_roles = ["customer", "vendor"];
if (!in_array($role, $allowed_roles)) {
    $role = "customer";
}

// Check if email already exists
$check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$check->execute([$email]);
if ($check->fetch()) {
    jsonResponse(["status" => "error", "message" => "Email already registered"], 400);
}

// Hash password
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// Insert user
$stmt = $pdo->prepare("
    INSERT INTO users (name, email, password_hash, role, phone)
    VALUES (?, ?, ?, ?, ?)
");
$stmt->execute([$name, $email, $password_hash, $role, $phone]);

$user_id = $pdo->lastInsertId();

// If vendor role → create vendor profile
if ($role === "vendor") {

    $vendorStmt = $pdo->prepare("
        INSERT INTO vendors (user_id, business_name)
        VALUES (?, ?)
    ");
    $vendorStmt->execute([$user_id, $name . "'s Shop"]);
}

jsonResponse([
    "status" => "success",
    "message" => "Registration successful",
    "user_id" => $user_id
]);
?>
