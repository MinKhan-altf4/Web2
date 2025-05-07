<?php
require_once 'Admin/php/db.php';

// Get filter parameters
$minPrice = isset($_GET['min']) ? floatval($_GET['min']) : 0;
$maxPrice = isset($_GET['max']) ? floatval($_GET['max']) : PHP_FLOAT_MAX;
$category = isset($_GET['category']) && $_GET['category'] != 'all' ? $_GET['category'] : null;

try {
    // Build the base query
    $sql = "SELECT p.product_id, p.name, p.price, p.image, pt.type_name as category 
            FROM products p
            JOIN product_types pt ON p.type_id = pt.type_id 
            WHERE p.is_deleted = 0 AND p.price >= ? AND p.price <= ?";
    $params = array($minPrice, $maxPrice);

    // Add category filter if specified
    if ($category) {
        $sql .= " AND pt.type_name = ?";
        $params[] = $category;
    }

    // Prepare and execute the query
    $stmt = mysqli_prepare($conn, $sql);
    
    if ($category) {
        mysqli_stmt_bind_param($stmt, "dds", ...$params);
    } else {
        mysqli_stmt_bind_param($stmt, "dd", ...$params);
    }

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    // Fetch all products
    $products = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $products[] = array(
            'id' => $row['product_id'],
            'name' => $row['name'],
            'price' => '$' . $row['price'],
            'image' => 'Admin/img/' . $row['image'],
            'category' => $row['category']
        );
    }

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($products);

} catch(Exception $e) {
    header('Content-Type: application/json');
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
} finally {
    if (isset($stmt)) {
        mysqli_stmt_close($stmt);
    }
    mysqli_close($conn);
}