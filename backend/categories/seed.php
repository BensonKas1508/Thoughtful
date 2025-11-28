<?php
include "../config/db.php";

$categories = [
    "Birthday",
    "Anniversary",
    "Mother's Day",
    "Valentine's Day",
    "Corporate",
    "Gift Boxes",
    "Hampers",
    "Flowers",
    "Chocolates",
    "Personalized Items",
    "Birthday Specials"
];

foreach ($categories as $cat) {
    $stmt = $pdo->prepare("INSERT INTO categories (name) VALUES (?)");
    $stmt->execute([$cat]);
}

echo "Categories seeded successfully!";
