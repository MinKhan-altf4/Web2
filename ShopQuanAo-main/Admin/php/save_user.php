<?php
require_once 'auth.php';
require_once 'db.php';

if(isset($_POST['save'])) {
    $username = $_POST['username'];
    $fullname = $_POST['fullname'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $status = $_POST['status'];
    
    // Nếu là thêm mới
    if(!isset($_POST['id'])) {
        $password = $_POST['password'];
        $original_password = $password; // Lưu mật khẩu gốc
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO user (username, fullname, phone, address, city, gender, email, password, original_password, role, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssssss", $username, $fullname, $phone, $address, $city, $gender, $email, $hashed_password, $original_password, $role, $status);
    }
    // Nếu là cập nhật
    else {
        $id = $_POST['id'];
        if(!empty($_POST['password'])) {
            $password = $_POST['password'];
            $original_password = $password; // Lưu mật khẩu gốc
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "UPDATE user SET username=?, fullname=?, phone=?, address=?, city=?, gender=?, email=?, password=?, original_password=?, role=?, status=? 
                    WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssssssssi", $username, $fullname, $phone, $address, $city, $gender, $email, $hashed_password, $original_password, $role, $status, $id);
        } else {
            $sql = "UPDATE user SET username=?, fullname=?, phone=?, address=?, city=?, gender=?, email=?, role=?, status=? 
                    WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssssssi", $username, $fullname, $phone, $address, $city, $gender, $email, $role, $status, $id);
        }
    }

    if($stmt->execute()) {
        header("Location: user.php");
    } else {
        header("Location: user.php?error=1");
    }
    exit();
}