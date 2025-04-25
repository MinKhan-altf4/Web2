<?php
require_once 'auth.php';
require_once 'db.php';

if(isset($_GET['id'])) {
    $user_id = (int)$_GET['id'];
    
    // Không cho phép xóa chính mình
    if($user_id == $_SESSION['user_id']) {
        header("Location: user.php?error=cannot_delete_self");
        exit();
    }
    
    $sql = "DELETE FROM user WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    
    if($stmt->execute()) {
        header("Location: user.php?success=user_deleted");
    } else {
        header("Location: user.php?error=delete_failed");
    }
} else {
    header("Location: user.php?error=invalid_id");
}
exit();