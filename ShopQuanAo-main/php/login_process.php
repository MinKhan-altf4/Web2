<?php
// login_process.php
session_start();
require_once '../Admin/php/db.php';

$username = $_POST['username'];
$password = $_POST['password'];
$remember = isset($_POST['remember']) ? true : false;

$sql = "SELECT * FROM user WHERE username=? AND status='1'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

$response = array();

if($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    if(password_verify($password, $user['password'])) {
        $_SESSION['id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        
        // Thêm cho JS client
        $response['status'] = 'success';
        $response['message'] = 'Đăng nhập thành công!';
        $response['username'] = $user['username'];
        $response['role'] = $user['role'];
        
        if($remember) {
            setcookie('remember_user', $user['id'], time() + (30 * 24 * 60 * 60), '/');
        }
        
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Sai mật khẩu!';
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Tài khoản không tồn tại!';
}

echo json_encode($response);
?>