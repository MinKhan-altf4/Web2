<?php
session_start();
require_once 'db.php';

if(!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM user WHERE id = ? AND status = '1'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows == 0) {
    session_destroy();
    header('Location: login.php');
    exit();
}

$user = $result->fetch_assoc();
?>