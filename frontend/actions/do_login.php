<?php
session_start();

// Backend login API URL
$api_url = "http://169.239.251.102:442/~benson.vorsah/backend/auth/login.php";

// Collect form data
$data = [
    "email" => $_POST['email'],
    "password" => $_POST['password']
];

// Prepare POST request
$options = [
    "http" => [
        "header"  => "Content-Type: application/json\r\n",
        "method"  => "POST",
        "content" => json_encode($data)
    ]
];

$context  = stream_context_create($options);
$response = file_get_contents($api_url, false, $context);

// DEBUG MODE (REMOVE LATER)
if ($response === false) {
    die("Login API unreachable");
}

$result = json_decode($response, true);

// If login failed
if (!$result || $result["status"] !== "success") {
    $msg = $result["message"] ?? "Login failed";
    header("Location: /~benson.vorsah/login.php?error=" . urlencode($msg));
    exit;
}

// Login successful — set session
$_SESSION["user_id"]   = $result["user"]["id"];
$_SESSION["user_name"] = $result["user"]["name"];
$_SESSION["role"]      = $result["user"]["role"];

// Redirect home
header("Location: /~benson.vorsah/home.php");
exit;
?>
