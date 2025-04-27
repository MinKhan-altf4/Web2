<?php
session_start();
require_once '../Admin/php/db.php';
header('Content-Type: application/json');

// Kiểm tra đăng nhập
if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập']);
    exit;
}

$action = $_GET['action'] ?? '';
$id = $_SESSION['id'];

// Lấy dữ liệu từ JSON request body
$inputData = json_decode(file_get_contents('php://input'), true);

switch($action) {
    case 'add':
        try {
            $product_id = $inputData['product_id'] ?? 0;
            $quantity = $inputData['quantity'] ?? 1;

            // Kiểm tra sản phẩm đã có trong giỏ hàng chưa
            $stmt = $conn->prepare("SELECT quantity FROM cart WHERE id = ? AND product_id = ?");
            $stmt->bind_param("ii", $id, $product_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Cập nhật số lượng nếu sản phẩm đã tồn tại
                $row = $result->fetch_assoc();
                $new_quantity = $row['quantity'] + $quantity;
                
                $update = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ? AND product_id = ?");
                $update->bind_param("iii", $new_quantity, $id, $product_id);
                $update->execute();
            } else {
                // Thêm mới nếu sản phẩm chưa có trong giỏ
                $insert = $conn->prepare("INSERT INTO cart (id, product_id, quantity) VALUES (?, ?, ?)");
                $insert->bind_param("iii", $id, $product_id, $quantity);
                $insert->execute();
            }

            echo json_encode(['success' => true, 'message' => 'Thêm vào giỏ hàng thành công']);

        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
        }
        break;

    case 'get':
        try {
            // Lấy thông tin giỏ hàng kèm chi tiết sản phẩm
            $stmt = $conn->prepare("
                SELECT c.*, p.name, p.price, p.image 
                FROM cart c
                JOIN products p ON c.product_id = p.product_id
                WHERE c.id = ?
            ");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $cart_items = $result->fetch_all(MYSQLI_ASSOC);

            echo json_encode(['success' => true, 'cart' => $cart_items]);

        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
        }
        break;

    case 'update':
        try {
            $product_id = $inputData['product_id'] ?? 0;
            $quantity = $inputData['quantity'] ?? 0;
            
            // Validate input
            if ($product_id <= 0 || $quantity <= 0) {
                echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
                exit;
            }
            
            // Cập nhật số lượng sản phẩm trong giỏ hàng
            $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ? AND product_id = ?");
            $stmt->bind_param("iii", $quantity, $id, $product_id);
            $stmt->execute();
            
            if ($stmt->affected_rows > 0 || $stmt->errno === 0) {
                echo json_encode(['success' => true, 'message' => 'Cập nhật giỏ hàng thành công']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Không thể cập nhật giỏ hàng']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
        }
        break;

    case 'remove':
        try {
            $product_id = $inputData['product_id'] ?? 0;
            
            // Validate input
            if ($product_id <= 0) {
                echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
                exit;
            }
            
            // Xóa sản phẩm khỏi giỏ hàng
            $stmt = $conn->prepare("DELETE FROM cart WHERE id = ? AND product_id = ?");
            $stmt->bind_param("ii", $id, $product_id);
            $stmt->execute();
            
            if ($stmt->affected_rows > 0) {
                echo json_encode(['success' => true, 'message' => 'Xóa sản phẩm thành công']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Không thể xóa sản phẩm']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
        }
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Action không hợp lệ']);
}
?>