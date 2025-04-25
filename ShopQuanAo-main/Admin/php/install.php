<?php
require_once 'db.php';

// Thông tin admin mặc định
$admin = [
    'username' => 'admin',
    'email' => 'admin@gmail.com',
    'password' => 'admin123123', // Mật khẩu mặc định
    'role' => 'admin',
    'status' => 1
];

// Kiểm tra xem đã có admin chưa
$sql = "SELECT * FROM user WHERE role = 'admin'";
$result = $conn->query($sql);

if($result->num_rows == 0) {
    // Chưa có admin thì thêm mới
    $sql = "INSERT INTO user (username, email, password, role, status) 
            VALUES (?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", 
        $admin['username'],
        $admin['email'],
        $admin['password'],
        $admin['role'],
        $admin['status']
    );

    if($stmt->execute()) {
        echo "Đã tạo tài khoản admin mặc định:<br>";
        echo "Email: admin@gmail.com<br>";
        echo "Password: admin123";
    } else {
        echo "Lỗi: " . $conn->error;
    }
} else {
    echo "Đã có tài khoản admin trong hệ thống";
}