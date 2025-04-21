<?php
include("Admin/php/db.php");

// Get products from database
$result = mysqli_query($conn, "SELECT * FROM products WHERE is_deleted = 0");
$products = [];

while ($row = mysqli_fetch_assoc($result)) {
    $products[] = [
        'id' => $row['id'],
        'name' => $row['name'],
        'price' => '$' . $row['price'],
        'image' => 'Admin/img/' . $row['image'],
        'category' => strtolower($row['category']),
        'description' => $row['description'],
        'link' => 'shop-details.php?id=' . $row['id']
    ];
}

// Return as JSON
header('Content-Type: application/json');
echo json_encode($products);
?>