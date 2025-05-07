<?php
include("Admin/php/db.php");

header('Content-Type: application/json');

$query = isset($_GET['query']) ? mysqli_real_escape_string($conn, $_GET['query']) : '';

if (empty($query)) {
    echo json_encode([]);
    exit;
}

$sql = "SELECT p.product_id, p.name, p.price, pt.type_name as category, p.image 
        FROM products p
        JOIN product_types pt ON p.type_id = pt.type_id 
        WHERE p.is_deleted = 0 
        AND p.is_visible = 1
        AND (p.name LIKE ? OR pt.type_name LIKE ?)";

$stmt = mysqli_prepare($conn, $sql);
$searchTerm = "%$query%";
mysqli_stmt_bind_param($stmt, "ss", $searchTerm, $searchTerm);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$products = array();
while ($row = mysqli_fetch_assoc($result)) {
    $products[] = array(
        'id' => $row['product_id'],
        'name' => $row['name'],
        'price' => '$' . number_format($row['price'], 2),
        'category' => $row['category'],
        'image' => 'Admin/img/' . $row['image']
    );
}

echo json_encode($products);
mysqli_stmt_close($stmt);
mysqli_close($conn);