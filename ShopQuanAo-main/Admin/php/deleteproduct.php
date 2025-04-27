<?php
include("db.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Kiểm tra sản phẩm đã được bán hay chưa
    $check_order = mysqli_query($conn, "SELECT * FROM orders WHERE product_id = $id");

    if (!$check_order) {
        die("Query failed: " . mysqli_error($conn));
    }

    if (mysqli_num_rows($check_order) > 0) {
        // Nếu sản phẩm đã bán → ẩn sản phẩm
        mysqli_query($conn, "UPDATE products SET is_deleted = 1 WHERE product_id = $id");
    } else {
        // Nếu chưa bán → xóa hoàn toàn
        $query = mysqli_query($conn, "SELECT image FROM products WHERE product_id = $id");
        $row = mysqli_fetch_assoc($query);
        $image_path = "../img/" . $row['image'];
        if (file_exists($image_path)) {
            unlink($image_path);
        }

        mysqli_query($conn, "DELETE FROM products WHERE product_id = $id");
    }
}

header("Location: addproduct.php");
exit();
?>
