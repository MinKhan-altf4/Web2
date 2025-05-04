<?php
require_once 'auth.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    // Thêm city vào danh sách các trường cần select
    $sql = "SELECT id, username, fullname, email, phone, address, city, gender, role, status, original_password FROM user WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    header('Content-Type: application/json');
    echo json_encode($user);
}