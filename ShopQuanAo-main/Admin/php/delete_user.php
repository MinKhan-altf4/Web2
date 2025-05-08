<?php
require_once 'auth.php';
require_once 'db.php';

if(isset($_GET['id'])) {
    $user_id = (int)$_GET['id'];
    
    // Bắt đầu transaction
    $conn->begin_transaction();
    
    try {
        // Kiểm tra user không phải admin
        $check_sql = "SELECT role FROM user WHERE id = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("i", $user_id);
        $check_stmt->execute();
        $result = $check_stmt->get_result();
        $user = $result->fetch_assoc();

        if(!$user || $user['role'] == 'admin') {
            throw new Exception("Không thể xóa tài khoản admin!");
        }

        // 1. Xóa các checkout_items liên quan
        $delete_items = "DELETE FROM checkout_items WHERE order_id IN 
                        (SELECT order_id FROM checkout WHERE user_id = ?)";
        $stmt = $conn->prepare($delete_items);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        // 2. Xóa các invoices liên quan
        $delete_invoices = "DELETE FROM invoices WHERE order_id IN 
                          (SELECT order_id FROM checkout WHERE user_id = ?)";
        $stmt = $conn->prepare($delete_invoices);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        // 3. Xóa các đơn hàng trong checkout
        $delete_orders = "DELETE FROM checkout WHERE user_id = ?";
        $stmt = $conn->prepare($delete_orders);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        // 4. Xóa cart items
        $delete_cart = "DELETE FROM cart WHERE id = ?";
        $stmt = $conn->prepare($delete_cart);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        // 5. Cuối cùng xóa user
        $delete_user = "DELETE FROM user WHERE id = ? AND role != 'admin'";
        $stmt = $conn->prepare($delete_user);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        // Commit transaction nếu mọi thứ OK
        $conn->commit();
        
        echo "<script>
            alert('Xóa người dùng và dữ liệu liên quan thành công!');
            window.location.href = 'user.php';
        </script>";
        
    } catch (Exception $e) {
        // Rollback nếu có lỗi
        $conn->rollback();
        
        echo "<script>
            alert('Lỗi: " . addslashes($e->getMessage()) . "');
            window.location.href = 'user.php';
        </script>";
    }
} else {
    header("Location: user.php");
    exit();
}