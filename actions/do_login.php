<?php
session_start();

$email = $_POST['email'];
$password = $_POST['password'];

$api_url = "http://169.239.251.102:442//~benson.vorsah/thoughtful/backend/auth/login.php";

$data = [
    "email" => $email,
    "password" => $password
];

$options = [
    "http" => [
        "header"  => "Content-Type: application/json",
        "method"  => "POST",
        "content" => json_encode($data)
    ]
];

$response = file_get_contents($api_url, false, stream_context_create($options));
$result = json_decode($response, true);

if ($result["status"] === "success") {
    $_SESSION['user_id'] = $result['user']['id'];
    $_SESSION['name'] = $result['user']['name'];
    $_SESSION['role'] = $result['user']['role'];
    header("Location: home.php");
} else {
    header("Location: login.php?error=" . urlencode($result["message"]));
}
exit;
