<?php
require_once 'db.php';

header('Content-Type: application/json');

$field = $_GET['field'] ?? '';
$value = $_GET['value'] ?? '';
$id = $_GET['id'] ?? null;

if(empty($field) || empty($value)) {
    echo json_encode(['error' => 'Missing parameters']);
    exit;
}

$where = $id ? "AND id != ?" : "";
$sql = "SELECT COUNT(*) as count FROM user WHERE $field = ? $where";

$stmt = $conn->prepare($sql);
if($id) {
    $stmt->bind_param("si", $value, $id);
} else {
    $stmt->bind_param("s", $value);
}

$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

echo json_encode(['exists' => $row['count'] > 0]);