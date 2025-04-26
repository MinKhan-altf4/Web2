<?php
session_start();
header('Content-Type: application/json');

$response = array(
    'isLoggedIn' => false,
    'username' => null,
    'role' => null
);

if (isset($_SESSION['user_id']) && isset($_SESSION['username'])) {
    $response['isLoggedIn'] = true;
    $response['username'] = $_SESSION['username'];
    $response['role'] = $_SESSION['role'];
}

echo json_encode($response);
?>