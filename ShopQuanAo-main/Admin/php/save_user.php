<?php
require_once 'auth.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(isset($_POST['save'])) {
        $username = $conn->real_escape_string($_POST['username']);
        $email = $conn->real_escape_string($_POST['email']);
        $password = $_POST['password']; // Lưu mật khẩu gốc
        $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Mật khẩu mã hóa để lưu DB
        $role = $conn->real_escape_string($_POST['role']);
        $status = (int)$_POST['status'];

        // Check for existing email
        $check_sql = "SELECT id FROM user WHERE email = ? AND id != ?";
        $check_stmt = $conn->prepare($check_sql);
        $id = isset($_POST['id']) ? $_POST['id'] : 0;
        $check_stmt->bind_param("si", $email, $id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if($check_result->num_rows > 0) {
            $_SESSION['error'] = "Email đã tồn tại!";
            header('Location: user.php');
            exit();
        }

        if(isset($_POST['id'])) { // Update
            $id = (int)$_POST['id'];
            if(!empty($password)) {
                $sql = "UPDATE user SET username=?, email=?, password=?, role=?, status=? WHERE id=?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssssii", $username, $email, $password, $role, $status, $id);
            } else {
                $sql = "UPDATE user SET username=?, email=?, role=?, status=? WHERE id=?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssii", $username, $email, $role, $status, $id);
            }
        } else { // Insert
            $sql = "INSERT INTO user (username, email, password, role, status) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssi", $username, $email, $password, $role, $status);
        }

        if($stmt->execute()) {
            $_SESSION['success'] = isset($_POST['id']) ? 
                "Cập nhật thành công!" : 
                "Thêm mới thành công! Mật khẩu mặc định: 123456";
        } else {
            $_SESSION['error'] = "Có lỗi xảy ra: " . $conn->error;
        }
    }
}

header('Location: user.php');
exit();