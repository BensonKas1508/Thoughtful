<?php
session_start();

// backend API URL
$api_url = "http://169.239.251.102:442/~benson.vorsah/backend/auth/register.php";

// Collect form data
$data = [
    "name" => $_POST['name'],
    "email" => $_POST['email'],
    "phone" => $_POST['phone'],
    "password" => $_POST['password']
];

// Send to backend
$options = [
    "http" => [
        "header"  => "Content-Type: application/json\r\n",
        "method"  => "POST",
        "content" => json_encode($data)
    ]
];

$context  = stream_context_create($options);
$response = file_get_contents($api_url, false, $context);

$result = json_decode($response, true);

// Handle response
if (!$result || $result["status"] !== "success") {
    $msg = isset($result["message"]) ? $result["message"] : "Registration failed.";
    header("Location: ../register.php?error=" . urlencode($msg));
    exit;
}

// Success → log user in
$_SESSION["user_id"] = $result["user"]["id"];
$_SESSION["user_name"] = $result["user"]["name"];
$_SESSION["role"] = $result["user"]["role"];

// Redirect to homepage
header("Location: ../home.php");
exit;
