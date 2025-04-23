<?php
require_once 'auth.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $role = $conn->real_escape_string($_POST['role']);
    $status = $conn->real_escape_string($_POST['status']);

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

    if(isset($_POST['id'])) {
        // Update existing user
        $user_id = $_POST['id'];
        
        if(!empty($_POST['password'])) {
            // Hash new password if provided
            $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $sql = "UPDATE user SET username=?, email=?, password=?, role=?, status=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssi", $username, $email, $hashed_password, $role, $status, $user_id);
        } else {
            // Keep existing password if not provided
            $sql = "UPDATE user SET username=?, email=?, role=?, status=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssi", $username, $email, $role, $status, $user_id);
        }
    } else {
        // Create new user with hashed password
        $default_password = !empty($_POST['password']) ? $_POST['password'] : "123456";
        $hashed_password = password_hash($default_password, PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO user (username, email, password, role, status) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $username, $email, $hashed_password, $role, $status);
    }

    if($stmt->execute()) {
        $_SESSION['success'] = isset($_POST['id']) ? 
            "Cập nhật thành công!" : 
            "Thêm mới thành công! Mật khẩu mặc định: 123456";
    } else {
        $_SESSION['error'] = "Có lỗi xảy ra: " . $conn->error;
    }
}

header('Location: user.php');
exit();