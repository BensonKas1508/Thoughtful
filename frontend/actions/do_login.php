<?php
session_start();

$api_url = "http://169.239.251.102:442/~benson.vorsah/backend/auth/login.php";

$data = [
    "email" => $_POST['email'] ?? '',
    "password" => $_POST['password'] ?? ''
];

if (empty($data["email"]) || empty($data["password"])) {
    header("Location: ../login.php?error=Email and password required");
    exit;
}

$options = [
    "http" => [
        "header"  => "Content-Type: application/json\r\n",
        "method"  => "POST",
        "content" => json_encode($data),
        "ignore_errors" => true
    ]
];

$context = stream_context_create($options);
$response = file_get_contents($api_url, false, $context);

if ($response === false) {
    header("Location: ../login.php?error=Server unreachable");
    exit;
}

$result = json_decode($response, true);

if (!$result || $result["status"] !== "success") {
    $msg = $result["message"] ?? "Login failed";
    header("Location: ../login.php?error=" . urlencode($msg));
    exit;
}

// SUCCESS: set session
$_SESSION["user_id"]   = $result["user"]["id"];
$_SESSION["user_name"] = $result["user"]["name"];
$_SESSION["role"]      = $result["user"]["role"];

header("Location: ../home.php");
exit;
?>
