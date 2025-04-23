<?php
require_once '../auth.php';
header('Content-Type: application/json');

if($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$old_password = $data['old_password'];
$new_password = $data['new_password'];

if(!password_verify($old_password, $user['password'])) {
    echo json_encode(['error' => 'Invalid current password']);
    exit();
}

$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
$sql = "UPDATE user SET password=? WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $hashed_password, $_SESSION['user_id']);

if($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => $conn->error]);
}