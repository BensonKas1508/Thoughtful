<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');

// Call backend API to update
$api_url = "http://169.239.251.102:442/~benson.vorsah/backend/users/update.php";

$data = [
    "user_id" => $user_id,
    "name" => $name,
    "email" => $email,
    "phone" => $phone
];

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
    $_SESSION['name'] = $name;
    $_SESSION['email'] = $email;
    header("Location: ../profile.php?msg=Profile+updated+successfully");
} else {
    header("Location: ../profile.php?error=" . urlencode($result['message'] ?? 'Update failed'));
}
exit;
?>