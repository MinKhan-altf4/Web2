<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Kết nối cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "shopquanao";
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Kiểm tra đăng nhập
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['id'];

// Kiểm tra có order_id được truyền không
if (!isset($_GET['order_id'])) {
    header("Location: orders.php");
    exit;
}

$order_id = intval($_GET['order_id']);

// Kiểm tra xem đơn hàng có thuộc về người dùng hiện tại không
$check_order = $conn->prepare("SELECT order_id FROM checkout WHERE order_id = ? AND user_id = ?");
$check_order->bind_param("ii", $order_id, $user_id);
$check_order->execute();
$order_result = $check_order->get_result();

if ($order_result->num_rows == 0) {
    // Đơn hàng không tồn tại hoặc không thuộc về người dùng
    header("Location: orders.php");
    exit;
}

// Kiểm tra xem đã có hóa đơn cho đơn hàng này chưa
$check_invoice = $conn->prepare("SELECT invoice_id FROM invoices WHERE order_id = ?");
$check_invoice->bind_param("i", $order_id);
$check_invoice->execute();
$invoice_result = $check_invoice->get_result();

if ($invoice_result->num_rows > 0) {
    // Đã có hóa đơn, chuyển hướng đến trang hóa đơn
    $invoice_data = $invoice_result->fetch_assoc();
    header("Location: invoice.php?invoice_id=" . $invoice_data['invoice_id']);
    exit;
}

// Tạo mã hóa đơn mới
function generateInvoiceNumber() {
    return 'INV-' . date('Ymd') . '-' . rand(1000, 9999);
}

// Lấy thông tin đơn hàng
$order_query = $conn->prepare("SELECT * FROM checkout WHERE order_id = ?");
$order_query->bind_param("i", $order_id);
$order_query->execute();
$order_result = $order_query->get_result();
$order_data = $order_result->fetch_assoc();

// Tính toán các khoản phí
$subtotal = $order_data['total_amount'];
$tax_rate = 0.10; // 10% thuế
$tax_amount = $subtotal * $tax_rate;
$shipping_fee = 0; // Miễn phí vận chuyển
$discount_amount = 0; // Không có giảm giá
$total_amount = $subtotal + $tax_amount + $shipping_fee - $discount_amount;

// Tạo mã hóa đơn
$invoice_number = generateInvoiceNumber();

// Thêm vào bảng invoices
$insert_invoice = $conn->prepare("INSERT INTO invoices 
    (order_id, invoice_number, invoice_date, payment_status, subtotal, tax_amount, 
    shipping_fee, discount_amount, total_amount) 
    VALUES (?, ?, NOW(), ?, ?, ?, ?, ?, ?)");
    
$payment_status = ($order_data['payment_method'] == 'Cash') ? 'Unpaid' : 'Paid';

$insert_invoice->bind_param("issddddd", 
    $order_id, 
    $invoice_number, 
    $payment_status,
    $subtotal,
    $tax_amount,
    $shipping_fee,
    $discount_amount,
    $total_amount
);

if (!$insert_invoice->execute()) {
    die("Error creating invoice: " . $conn->error);
}

// Lấy ID hóa đơn vừa tạo
$invoice_id = $conn->insert_id;

// Chuyển hướng đến trang hiển thị hóa đơn
header("Location: invoice.php?invoice_id=" . $invoice_id);
exit;
?>