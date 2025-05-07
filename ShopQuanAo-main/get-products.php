<?php
include("Admin/php/db.php");

header('Content-Type: application/json');

$category = isset($_GET['category']) ? $_GET['category'] : 'all';

$sql = "SELECT p.product_id, p.name, p.price, pt.type_name as category, p.image 
        FROM products p
        JOIN product_types pt ON p.type_id = pt.type_id 
        WHERE p.is_deleted = 0 AND p.is_visible = 1";

if ($category !== 'all') {
    $sql .= " AND pt.type_name = ?";
}

$stmt = mysqli_prepare($conn, $sql);

if ($category !== 'all') {
    mysqli_stmt_bind_param($stmt, "s", $category);
}

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$products = array();

while ($row = mysqli_fetch_assoc($result)) {
    $products[] = array(
        'id' => $row['product_id'],
        'name' => $row['name'],
        'price' => '$' . $row['price'],
        'category' => $row['category'],
        'image' => 'Admin/img/' . $row['image'],
        'link' => 'shop-details.php?id=' . $row['product_id']
    );
}

echo json_encode($products);
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>