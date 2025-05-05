<?php
session_start();
require_once 'db.php';

if(isset($_POST['signin'])) {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    if(empty($email) || empty($password)) {
        $error = 'Vui lòng điền đầy đủ thông tin';
    } else {
        // Kiểm tra email có phải admin không trước
        $sql = "SELECT * FROM user WHERE email = ? AND role = 'admin'";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if(password_verify($password, $user['password'])) {
                // Kiểm tra trạng thái tài khoản
                if($user['status'] == 1) {
                    $_SESSION['admin_id'] = $user['id'];
                    $_SESSION['admin_username'] = $user['username'];
                    $_SESSION['admin_role'] = $user['role'];
                    
                    header('Location: dashboard.php');
                    exit();
                } else {
                    $error = 'Tài khoản đã bị khóa';
                }
            } else {
                $error = 'Sai mật khẩu';
            }
        } else {
            $error = 'Email này không có quyền truy cập trang admin';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - Admin</title>
    <link rel="stylesheet" href="../css/login.css">
    <link rel="stylesheet" href="../css/grid.css">
    <link rel="stylesheet" href="../css/responsive.css">
    
</head>
<body>
    <div class="login-box">
        <div class="login-header">
            <h1>MALE FASHION</h1>
        </div>
        
        <?php if(isset($error)): ?>
        <div class="error-message" style="color: red; text-align: center; margin-bottom: 10px;">
            <?php echo htmlspecialchars($error); ?>
        </div>
        <?php endif; ?>
        
        <form method="POST" action="login.php">
            <div class="input-box">
                <input type="email" class="input-field" placeholder="Email" name="email" required>
            </div>
            <div class="input-box">
                <input type="password" class="input-field" placeholder="Password" name="password" required>
            </div>
            
            <div class="input-submit">
                <button type="submit" class="submit-btn" name="signin">Sign In</button>
            </div>
        </form>
    </div>
</body>
</html>
