<?php
$host = "localhost";
$username = "root";
$password = ""; // nếu bạn có đặt mật khẩu cho MySQL thì điền vào đây
$database = "shopquanao"; // tên database bạn đã tạo

$conn = mysqli_connect($host, $username, $password, $database);

// Kiểm tra kết nối
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
