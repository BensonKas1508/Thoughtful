<?php
session_start();

file_put_contents("debug_register.txt", json_encode($_POST));

// Backend register API URL
$api_url = "http://169.239.251.102:442/~benson.vorsah/backend/auth/register.php";

// Collect form data safely
$data = [
    "name" => $_POST['name'] ?? '',
    "email" => $_POST['email'] ?? '',
    "phone" => $_POST['phone'] ?? '',
    "password" => $_POST['password'] ?? ''
];

// Validate at least on frontend
if (empty($data["name"]) || empty($data["email"]) || empty($data["password"])) {
    header("Location: ../register.php?error=All fields are required");
    exit;
}

// Send JSON to backend
$options = [
    "http" => [
        "header"  => "Content-Type: application/json\r\n",
        "method"  => "POST",
        "content" => json_encode($data),
        "ignore_errors" => true   // <-- IMPORTANT: Allows reading API error response
    ]
];

$context = stream_context_create($options);
$response = file_get_contents($api_url, false, $context);

// If API unreachable
if ($response === false) {
    header("Location: ../register.php?error=Server error. Try again.");
    exit;
}

echo "<pre>RAW RESPONSE:\n$response\n</pre>";
exit;

$result = json_decode($response, true);

// On failure
if (!$result || $result["status"] !== "success") {
    $msg = $result["message"] ?? "Registration failed";
    header("Location: ../register.php?error=" . urlencode($msg));
    exit;
}

// SUCCESS — log user in
$_SESSION["user_id"]   = $result["user"]["id"];
$_SESSION["user_name"] = $result["user"]["name"];
$_SESSION["role"]      = $result["user"]["role"];

// Redirect to home
header("Location: ../home.php");
exit;
?>
