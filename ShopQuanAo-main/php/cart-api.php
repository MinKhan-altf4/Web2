<?php
session_start();
require_once '../Admin/php/db.php';
header('Content-Type: application/json');

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập']);
    exit;
}

$action = $_GET['action'] ?? '';
$user_id = $_SESSION['user_id'];

switch($action) {
    case 'add':
        // Nhận dữ liệu từ request
        $data = json_decode(file_get_contents('php://input'), true);
        $product_id = $data['product_id'] ?? 0;
        $quantity = $data['quantity'] ?? 1;

        try {
            // Kiểm tra sản phẩm đã có trong giỏ hàng chưa
            $stmt = $conn->prepare("SELECT quantity FROM cart WHERE user_id = ? AND product_id = ?");
            $stmt->bind_param("ii", $user_id, $product_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Cập nhật số lượng nếu sản phẩm đã tồn tại
                $row = $result->fetch_assoc();
                $new_quantity = $row['quantity'] + $quantity;
                
                $update = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
                $update->bind_param("iii", $new_quantity, $user_id, $product_id);
                $update->execute();
            } else {
                // Thêm mới nếu sản phẩm chưa có trong giỏ
                $insert = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
                $insert->bind_param("iii", $user_id, $product_id, $quantity);
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
                JOIN products p ON c.product_id = p.id
                WHERE c.user_id = ?
            ");
            $stmt->bind_param("i", $user_id);
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
            // Nhận dữ liệu từ POST request 
            $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
            $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 0;
            
            // Validate input
            if ($product_id <= 0 || $quantity <= 0) {
                echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
                exit;
            }
            
            // Cập nhật số lượng sản phẩm trong giỏ hàng
            $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
            $stmt->bind_param("iii", $quantity, $user_id, $product_id);
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
            // Nhận dữ liệu từ POST request
            $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
            
            // Validate input
            if ($product_id <= 0) {
                echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
                exit;
            }
            
            // Xóa sản phẩm khỏi giỏ hàng
            $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
            $stmt->bind_param("ii", $user_id, $product_id);
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