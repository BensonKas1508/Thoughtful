<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    exit;
}

$id = $_GET['id'] ?? null;

if ($id) {
    $api_url = "http://169.239.251.102:442/~benson.vorsah/backend/admin/users/delete.php";
    
    $options = [
        "http" => [
            "header"  => "Content-Type: application/json\r\n",
            "method"  => "POST",
            "content" => json_encode(["user_id" => $id])
        ]
    ];
    
    $context = stream_context_create($options);
    file_get_contents($api_url, false, $context);
}

header("Location: ../users.php?msg=User+deleted");
exit;
?>