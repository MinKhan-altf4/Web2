<?php
include("db.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Sử dụng prepared statement để kiểm tra đơn hàng
    $check_sql = "SELECT * FROM cart WHERE product_id = ?";
    $check_stmt = mysqli_prepare($conn, $check_sql);
    mysqli_stmt_bind_param($check_stmt, "i", $id);
    mysqli_stmt_execute($check_stmt);
    $check_result = mysqli_stmt_get_result($check_stmt);

    if (!$check_result) {
        die("Query failed: " . mysqli_error($conn));
    }

    if (mysqli_num_rows($check_result) > 0) {
        // Nếu sản phẩm đã bán → ẩn sản phẩm
        $update_sql = "UPDATE products SET is_deleted = 1 WHERE product_id = ?";
        $update_stmt = mysqli_prepare($conn, $update_sql);
        mysqli_stmt_bind_param($update_stmt, "i", $id);
        mysqli_stmt_execute($update_stmt);
    } else {
        // Nếu chưa bán → xóa hoàn toàn
        // Lấy thông tin ảnh
        $query_sql = "SELECT image FROM products WHERE product_id = ?";
        $query_stmt = mysqli_prepare($conn, $query_sql);
        mysqli_stmt_bind_param($query_stmt, "i", $id);
        mysqli_stmt_execute($query_stmt);
        $row = mysqli_fetch_assoc(mysqli_stmt_get_result($query_stmt));
        
        // Xóa file ảnh nếu tồn tại
        $image_path = "../img/" . $row['image'];
        if (file_exists($image_path)) {
            unlink($image_path);
        }

        // Xóa sản phẩm
        $delete_sql = "DELETE FROM products WHERE product_id = ?";
        $delete_stmt = mysqli_prepare($conn, $delete_sql);
        mysqli_stmt_bind_param($delete_stmt, "i", $id);
        mysqli_stmt_execute($delete_stmt);
    }
}

header("Location: addproduct.php");
exit();
?>
