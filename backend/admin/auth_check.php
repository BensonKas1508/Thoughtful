<?php
require_once "../config/db.php";
require_once "../helpers/response.php";

function requireAdmin($pdo, $user_id) {
    $stmt = $pdo->prepare("SELECT role FROM users WHERE id = ? LIMIT 1");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();

    if (!$user || $user["role"] !== "admin") {
        jsonResponse(["status" => "error", "message" => "Unauthorized"], 403);
        exit;
    }
}
?>
