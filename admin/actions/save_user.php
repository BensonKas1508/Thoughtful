<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../login.php");
    exit;
}

$user_id = $_POST['user_id'] ?? null;
$name = trim($_POST['name']);
$email = trim($_POST['email']);
$phone = trim($_POST['phone'] ?? '');
$role = $_POST['role'];
$password = $_POST['password'] ?? '';

$api_url = "http://169.239.251.102:442/~benson.vorsah/backend/admin/users/" . ($user_id ? "update.php" : "create.php");

$data = [
    "name" => $name,
    "email" => $email,
    "phone" => $phone,
    "role" => $role
];

if ($user_id) {
    $data['user_id'] = $user_id;
}

if ($password) {
    $data['password'] = password_hash($password, PASSWORD_BCRYPT);
}

$options = [
    "http" => [
        "header"  => "Content-Type: application/json\r\n",
        "method"  => "POST",
        "content" => json_encode($data)
    ]
];

$context = stream_context_create($options);
$response = file_get_contents($api_url, false, $context);
$result = json_decode($response, true);

if ($result['status'] === 'success') {
    header("Location: ../users.php?msg=" . urlencode($user_id ? "User updated" : "User created"));
} else {
    header("Location: ../users.php?error=" . urlencode($result['message'] ?? 'Operation failed'));
}
exit;
?>