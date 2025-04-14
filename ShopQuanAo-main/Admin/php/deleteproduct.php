<?php
include("db.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Trước khi xóa, lấy tên file ảnh để xóa khỏi thư mục uploads (tùy chọn)
    $query = mysqli_query($conn, "SELECT image FROM products WHERE id = $id");
    $row = mysqli_fetch_assoc($query);
    $image_path = "../uploads/" . $row['image'];
    
    if (file_exists($image_path)) {
        unlink($image_path); // Xóa ảnh khỏi thư mục nếu tồn tại
    }

    // Thực hiện xóa sản phẩm khỏi database
    $sql = "DELETE FROM products WHERE id = $id";
    mysqli_query($conn, $sql);
}

header("Location: addproduct.php"); // Quay về trang chính
exit();
