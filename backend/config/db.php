<?php
$host = "localhost";
$dbname = "thoughtful_db";
$username = "root";
$password = "";
$paystack_secret_key = "sk_test_03cd2f0ca15219e55adb3862a0e9c5d0f8e11e25";

try{
    $pdo = new PDO(
        "mysql:host=$host; dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO:: ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (Exception $e){
    die(json_encode([
        "status" => "error",
        "message" => "Database connection failed",
        "error" => $e->getMessage()
    ]));
}
?>