<?php
include("Admin/php/db.php");

// Lấy category từ query parameter nếu có
$category = isset($_GET['category']) ? $_GET['category'] : null;

// Xây dựng câu query dựa trên category
$sql = "SELECT * FROM products WHERE is_deleted = 0";
if ($category && $category !== 'all') {
    $category = mysqli_real_escape_string($conn, $category);
    $sql .= " AND category = '$category'";
}

$result = mysqli_query($conn, $sql);
$products = [];

while ($row = mysqli_fetch_assoc($result)) {
    $products[] = [
        'id' => $row['id'],
        'name' => $row['name'],
        'price' => '$' . $row['price'],
        'image' => 'Admin/img/' . $row['image'],
        'category' => $row['category'],
        'description' => $row['description'],
        'link' => 'shop-details.php?id=' . $row['id']
    ];
}

header('Content-Type: application/json');
echo json_encode($products);
?>