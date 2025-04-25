<?php
session_start();
require_once 'db.php';

// Kiểm tra đăng nhập
if(isset($_SESSION['user_id']) && $_SESSION['role'] == 'admin'){
    header('Location: dashboard.php');
    exit();
}

// Xử lý đăng nhập
if(isset($_POST['signin'])){
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    if(empty($email) || empty($password)) {
        $error = 'Vui lòng điền đầy đủ thông tin';
    } else {
        $sql = "SELECT * FROM user WHERE email = ? AND status = '1'";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows > 0){
            $user = $result->fetch_assoc();
            
            // Kiểm tra mật khẩu đã mã hóa
            if(password_verify($password, $user['password'])){
                if($user['role'] == 'admin'){
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['username'] = $user['username'];

                    if(isset($_POST['remember'])){
                        setcookie('remember_user', $user['id'], time() + (30 * 24 * 60 * 60), '/');
                    }

                    header('Location: dashboard.php');
                    exit();
                } else {
                    $error = "Bạn không có quyền truy cập trang admin";
                }
            } else {
                $error = 'Sai mật khẩu';
            }
        } else {
            $error = 'Email không tồn tại hoặc tài khoản bị khóa';
        }
        $stmt->close();
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
            <h1>Login's MALE FASHION</h1>
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
            <div class="forgot">
                <section>
                    <input type="checkbox" id="check" name="remember">
                    <label for="check">Remember me</label>
                </section>
            </div>
            <div class="input-submit">
                <button type="submit" class="submit-btn" name="signin">Sign In</button>
            </div>
        </form>
    </div>
</body>
</html>
