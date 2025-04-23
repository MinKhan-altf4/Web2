<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = array();
    
    // Lấy dữ liệu từ form
    $username = $_POST['username'];
    $email = $_POST['email']; 
    $password = $_POST['password'];
    $fullname = $_POST['fullname'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $gender = $_POST['gender'];

    // Kiểm tra email đã tồn tại
    $check_sql = "SELECT * FROM user WHERE email = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        $response['status'] = 'error';
        $response['message'] = 'Email đã tồn tại!';
    } else {
        // Mã hóa mật khẩu
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Thêm user mới
        $sql = "INSERT INTO user (username, email, password, fullname, phone, address, gender, role, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, 'customer', '1')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssss", $username, $email, $hashed_password, $fullname, $phone, $address, $gender);

        if ($stmt->execute()) {
            $response['status'] = 'success';
            $response['message'] = 'Đăng ký thành công!';
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Lỗi: ' . $conn->error;
        }
    }

    echo json_encode($response);
}