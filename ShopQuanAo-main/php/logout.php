<?php
// Bắt đầu session
session_start();

// Mảng để lưu kết quả trả về
$response = array();

try {
    // Xóa tất cả các biến session
    $_SESSION = array();

    // Xóa cookie session nếu có
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time()-3600, '/');
    }

    // Hủy session
    session_destroy();

    // Trả về thông báo thành công
    $response['status'] = 'success';
    $response['message'] = 'Đăng xuất thành công';

} catch (Exception $e) {
    // Nếu có lỗi, trả về thông báo lỗi
    $response['status'] = 'error';
    $response['message'] = 'Lỗi khi đăng xuất: ' . $e->getMessage();
}

// Trả về kết quả dạng JSON
header('Content-Type: application/json');
echo json_encode($response);
?>