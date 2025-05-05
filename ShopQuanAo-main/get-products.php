<?php
include("Admin/php/db.php");

header('Content-Type: application/json');

$category = isset($_GET['category']) ? $_GET['category'] : 'all';

$sql = "SELECT product_id, name, price, category, image FROM products WHERE is_deleted = 0";
if ($category !== 'all') {
    $sql .= " AND category = '$category'";
}

$result = mysqli_query($conn, $sql);
$products = array();

while ($row = mysqli_fetch_assoc($result)) {
    $products[] = array(
        'id' => $row['product_id'],
        'name' => $row['name'],
        'price' => '$' . number_format($row['price'], 2),
        'category' => $row['category'],
        'image' => 'Admin/img/' . $row['image'],
        'link' => 'shop-details.php?id=' . $row['product_id']
    );
}

echo json_encode($products);
?>