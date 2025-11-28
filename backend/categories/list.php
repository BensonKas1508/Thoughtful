<?php
header("Content-Type: application/json");
include "../config/db.php";

try {
    $stmt = $conn->query("SELECT id, name FROM categories ORDER BY name ASC");
    $cats = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "status" => "success",
        "categories" => $cats
    ]);
} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}
?>
