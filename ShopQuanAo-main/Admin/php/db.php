<?php
$host = "localhost";
$username = "c02u";
$password = "nxfQI4elq8A5FvZz"; // nếu bạn có đặt mật khẩu cho MySQL thì điền vào đây
$database = "c02db"; // tên database bạn đã tạo

$conn = mysqli_connect($host, $username, $password, $database);

// Kiểm tra kết nối
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
