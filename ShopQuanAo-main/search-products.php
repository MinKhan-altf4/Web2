<?php
include("Admin/php/db.php");

$search = isset($_GET['query']) ? $_GET['query'] : '';

if ($search) {
    $search = mysqli_real_escape_string($conn, $search);
    $sql = "SELECT * FROM products WHERE 
            is_deleted = 0 AND 
            (name LIKE '%$search%' OR 
             description LIKE '%$search%' OR 
             category LIKE '%$search%')";
    
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
}
?>