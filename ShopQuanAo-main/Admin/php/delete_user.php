<?php
require_once 'auth.php';

if(isset($_GET['id'])) {
    $user_id = (int)$_GET['id'];
    
    // Kiểm tra user không phải admin trước khi xóa
    $check_sql = "SELECT role FROM user WHERE id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("i", $user_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    $user = $result->fetch_assoc();

    if($user && $user['role'] != 'admin') {
        // Thực hiện xóa user
        $delete_sql = "DELETE FROM user WHERE id = ? AND role != 'admin'";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param("i", $user_id);
        
        if($delete_stmt->execute()) {
            echo "<script>
                alert('Xóa người dùng thành công!');
                window.location.href = 'user.php';
            </script>";
        } else {
            echo "<script>
                alert('Có lỗi xảy ra khi xóa người dùng!');
                window.location.href = 'user.php';
            </script>";
        }
    } else {
        echo "<script>
            alert('Không thể xóa tài khoản admin!');
            window.location.href = 'user.php';
        </script>";
    }
} else {
    header("Location: user.php");
    exit();
}