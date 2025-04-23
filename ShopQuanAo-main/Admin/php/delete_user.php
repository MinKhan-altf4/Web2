<?php
require_once 'auth.php';

if($user['role'] !== 'admin') {
    header('Location: dashboard.php');
    exit();
}

if(isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    // Không cho phép xóa tài khoản đang đăng nhập
    if($id == $_SESSION['user_id']) {
        $_SESSION['error'] = "Không thể xóa tài khoản đang sử dụng!";
        header('Location: user.php');
        exit();
    }

    // Không cho phép xóa tài khoản admin khác
    $check_sql = "SELECT role FROM user WHERE id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("i", $id);
    $check_stmt->execute();
    $user_role = $check_stmt->get_result()->fetch_assoc()['role'];

    if($user_role == 'admin') {
        $_SESSION['error'] = "Không thể xóa tài khoản admin khác!";
        header('Location: user.php');
        exit();
    }

    // Thực hiện xóa
    $sql = "DELETE FROM user WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if($stmt->execute()) {
        $_SESSION['success'] = "Xóa người dùng thành công!";
    } else {
        $_SESSION['error'] = "Có lỗi xảy ra: " . $conn->error;
    }
}

header('Location: user.php');
exit();