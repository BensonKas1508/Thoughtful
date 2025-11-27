<?php
session_start();

$api_url = "http://169.239.251.102:442/~benson.vorsah/backend/auth/register.php";

$data = [
    "name" => $_POST['name'],
    "email" => $_POST['email'],
    "phone" => $_POST['phone'],
    "password" => $_POST['password']
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
    header("Location: login.php?success=Account created successfully. Please log in.");
} else {
    header("Location: register.php?error=" . urlencode($result["message"]));
}
exit;
