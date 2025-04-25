<?php
session_start();
require_once 'db.php';

// Kiểm tra session admin riêng biệt
if (!isset($_SESSION['admin_id']) || $_SESSION['admin_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Set biến admin cho các trang admin
$current_admin = [
    'id' => $_SESSION['admin_id'],
    'username' => $_SESSION['admin_username'],
    'role' => $_SESSION['admin_role']
];