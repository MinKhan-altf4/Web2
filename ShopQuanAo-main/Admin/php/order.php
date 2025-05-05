<?php
require_once 'auth.php';
require_once 'config.php';

// Add after the require statements
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_order'])) {
    $order_id = (int)$_POST['order_id'];
    
    // Start transaction
    $conn->begin_transaction();
    
    try {
        // Delete related invoice first (if exists)
        $delete_invoice = "DELETE FROM invoices WHERE order_id = ?";
        $stmt = $conn->prepare($delete_invoice);
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        
        // Then delete the order
        $delete_order = "DELETE FROM checkout WHERE order_id = ?";
        $stmt = $conn->prepare($delete_order);
        $stmt->bind_param("i", $order_id);
        
        if ($stmt->execute()) {
            $conn->commit();
            $success_message = "Đơn hàng đã được xóa thành công!";
            header("Location: order.php?success=1");
            exit;
        } else {
            throw new Exception("Lỗi khi xóa đơn hàng");
        }
    } catch (Exception $e) {
        $conn->rollback();
        $error_message = "Lỗi: " . $e->getMessage();
    }
}

// Sửa lại phần xử lý cập nhật đơn hàng
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_order'])) {
    $order_id = $_POST['order_id'];
    $status = strtolower($_POST['status']);
    $shipping_address = $_POST['shipping_address'];
    $shipping_city = $_POST['city']; // Thêm dòng này
    
    $update_sql = "UPDATE checkout SET 
                  order_status = ?,
                  shipping_address = ?,
                  shipping_city = ?
                  WHERE order_id = ?";
    
    if ($stmt = $conn->prepare($update_sql)) {
        $stmt->bind_param("sssi", $status, $shipping_address, $shipping_city, $order_id);
        
        if ($stmt->execute()) {
            $success_message = "Cập nhật đơn hàng thành công!";
            header("Location: order.php?success=1");
            exit;
        } else {
            $error_message = "Lỗi khi cập nhật đơn hàng: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $error_message = "Lỗi khi chuẩn bị câu lệnh: " . $conn->error;
    }
}
// Kiểm tra kết nối CSDL trước khi xóa
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_order'])) {
    if (!$conn) {
        die("Kết nối CSDL thất bại");
    }
    
    $order_id = (int)$_POST['order_id'];
    
    // Validate order_id
    if ($order_id <= 0) {
        $error_message = "ID đơn hàng không hợp lệ";
        header("Location: order.php?error=1");
        exit;
    }
    
    // Start transaction
    $conn->begin_transaction();
    
    try {
        // Delete related invoice first (if exists)
        $delete_invoice = "DELETE FROM invoices WHERE order_id = ?";
        $stmt = $conn->prepare($delete_invoice);
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        
        // Then delete the order
        $delete_order = "DELETE FROM checkout WHERE order_id = ?";
        $stmt = $conn->prepare($delete_order);
        $stmt->bind_param("i", $order_id);
        
        if ($stmt->execute()) {
            $conn->commit();
            $success_message = "Đơn hàng đã được xóa thành công!";
            header("Location: order.php?success=1");
            exit;
        } else {
            throw new Exception("Lỗi khi xóa đơn hàng: " . $stmt->error);
        }
    } catch (Exception $e) {
        $conn->rollback();
        $error_message = "Lỗi: " . $e->getMessage();
        header("Location: order.php?error=1&message=" . urlencode($error_message));
        exit;
    }
}

// Sửa lại phần xử lý filter

$where_clause = "1=1";
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['update_order'])) {
    if (!empty($_POST['from_date'])) {
        $from_date = $_POST['from_date'] . ' 00:00:00';
        $where_clause .= " AND o.order_date >= '" . $conn->real_escape_string($from_date) . "'";
    }
    if (!empty($_POST['to_date'])) {
        $to_date = $_POST['to_date'] . ' 23:59:59';
        $where_clause .= " AND o.order_date <= '" . $conn->real_escape_string($to_date) . "'";
    }
   
}
    if (!empty($_POST['status'])) {
        $where_clause .= " AND o.order_status = '" . $_POST['status'] . "'";
    }
    if (!empty($_POST['city'])) {
        $where_clause .= " AND o.shipping_city = '" . $_POST['city'] . "'";
    }
    if (!empty($_POST['payment_status'])) {
        $where_clause .= " AND i.payment_status = '" . $_POST['payment_status'] . "'";
    }


// Cập nhật phần query để lấy thêm thông tin cần thiết
$sql = "SELECT o.*, u.fullname as customer_name, i.invoice_number, i.payment_status, i.total_amount,
               o.shipping_fullname, o.shipping_phone, o.shipping_address, o.shipping_city,
               o.payment_method, o.order_date, o.order_status
        FROM checkout o 
        LEFT JOIN user u ON o.user_id = u.id
        LEFT JOIN invoices i ON o.order_id = i.order_id
        WHERE $where_clause 
        ORDER BY o.order_date DESC";

// Thêm xử lý lỗi cho query
$result = $conn->query($sql);
if ($result === false) {
    $error_message = "Query failed: " . $conn->error;
    $result = null;
}

// Thêm xử lý chỉnh sửa đơn hàng
$edit_order = null;
if (isset($_GET['edit']) && !empty($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    $edit_sql = "SELECT o.*, i.invoice_id, i.payment_status, i.total_amount 
                 FROM checkout o
                 LEFT JOIN invoices i ON o.order_id = i.order_id
                 WHERE o.order_id = $edit_id";
                 
    $edit_result = $conn->query($edit_sql);
    if ($edit_result->num_rows > 0) {
        $edit_order = $edit_result->fetch_assoc();
    }
}

// Cập nhật query khi xem hóa đơn
if (isset($_GET['id'])) {
    $invoice_id = (int)$_GET['id'];
    $sql = "SELECT i.*, o.order_status, o.shipping_fullname, o.shipping_phone, 
                   o.shipping_address, o.shipping_city, o.payment_method
            FROM invoices i
            JOIN checkout o ON i.order_id = o.order_id
            WHERE i.invoice_id = ?";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $invoice_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $order = $result->fetch_assoc();
    } else {
        die("Không tìm thấy hóa đơn");
    }
}

// Thêm styles mới
$additional_styles = "
<style>
    .invoice-info { color: #3498db; font-weight: bold; }
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0,0,0,0.4);
    }
    .modal-content {
        background-color: #fefefe;
        margin: 15% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 50%;
        border-radius: 5px;
        position: relative;
    }
    
    .status {
        padding: 3px 8px;
        border-radius: 12px;
        color: white;
        font-size: 12px;
        font-weight: bold;
    }
    
    .status.pending { background-color: #f39c12; }
    .status.confirmed { background-color: #3498db; }
    .status.delivered { background-color: #27ae60; }
    .status.cancelled { background-color: #e74c3c; }
    
    td {
        vertical-align: middle;
        padding: 12px !important;
    }
    
    strong {
        color: #34495e;
    }
    
    .form-group {
        margin-bottom: 15px;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }
    
    .form-group input, .form-group select {
        width: 100%;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
    
    .submit-btn {
        background-color: #3498db;
        color: white;
        border: none;
        padding: 10px 15px;
        border-radius: 4px;
        cursor: pointer;
    }
    
    .submit-btn:hover {
        background-color: #2980b9;
    }
    
    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 4px;
    }
    
    .alert-success {
        background-color: #d4edda;
        color: #155724;
    }
    
    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
    }
    
    .close {
        float: right;
        font-size: 24px;
        font-weight: bold;
        cursor: pointer;
    }
</style>
";
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Order Management</title>
  <link rel="stylesheet" href="../css/order.css">
  <link rel="stylesheet" href="../css/grid.css">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <?php echo $additional_styles; // Thêm styles mới ?>
</head>
<body>
  <div class="sidebar">
    <div class="logo"></div>
    <ul class="menu">
        <li>
            <a href="dashboard.php">
                <i class='bx bx-grid-alt'></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li>
            <a href="user.php">
                <i class='bx bx-user'></i>
                <span>User</span>
            </a>
        </li>
        <li>
            <a href="addproduct.php">
                <i class='bx bx-box'></i>
                <span>Product</span>
            </a>
        </li>
        <li>
            <a href="analytics.php">
                <i class='bx bx-pie-chart-alt'></i>
                <span>Analytics</span>
            </a>
        </li>
        <li class="active">
            <a href="order.php">
                <i class='bx bx-cart'></i>
                <span>Order</span>
            </a>
        </li>
        <li class="logout">
            <a href="logout.php">
                <i class='bx bx-log-out'></i>
                <span>Logout</span>
            </a>
        </li>
    </ul>
</div>
  <div class="main_content">
    <div class="header_wrapper">
      <h2 class="header_title">Order Management</h2>
    </div>
    
    <!-- Display success/error messages -->
    <?php if(isset($success_message)): ?>
        <div class="alert alert-success">
            <?php echo $success_message; ?>
        </div>
    <?php endif; ?>
    
    <?php if(isset($error_message)): ?>
        <div class="alert alert-danger">
            <?php echo $error_message; ?>
        </div>
    <?php endif; ?>
  
    <!-- Bộ lọc đơn hàng -->
    <div class="filter_order">
        <h3>Filter Orders</h3>
        <form method="POST" action="order.php" class="filter_info">
            <div class="filter">
              <label>From Date:</label>
              <input type="date" name="from_date">
            </div>
            <div class="filter">
              <label>To Date:</label>
              <input type="date" name="to_date">
            </div>
            <div class="filter">
              <label>City:</label>
              <select name="city">
                <option value="">All</option>
                <option value="HoChiMinh">HoChiMinh</option>
                <option value="Hanoi">Hanoi</option>
              </select>
            </div>
            <div class="filter">
              <label>Status:</label>
              <select name="status">
                <option value="">All</option>
                <option value="pending">Pending</option>
                <option value="confirmed">Confirmed</option>
                <option value="delivered">Delivered</option>
                <option value="cancelled">Cancelled</option>
              </select>
            </div>
            <div class="filter">
              <label>Payment Status:</label>
              <select name="payment_status">
                <option value="">All</option>
                <option value="paid">Paid</option>
                <option value="unpaid">Unpaid</option>
              </select>
            </div>
            <button type="submit" class="submit-btn">Apply Filter</button>
        </form>
    </div>
  
    <!-- Bảng danh sách đơn hàng -->
    <div class="tabular_wrapper">
  <div class="table_container">
    <table>
      <thead>
        <tr>
          <th>Order ID</th>
          <th>Customer Info</th>
          <th>Payment Info</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
          <?php while($row = $result->fetch_assoc()): ?>
            <tr>
              <td>#<?php echo sprintf('%04d', $row['order_id']); ?></td>
              <td>
                <strong>Name:</strong> <?php echo htmlspecialchars($row['shipping_fullname']); ?><br>
                <strong>Phone:</strong> <?php echo htmlspecialchars($row['shipping_phone']); ?><br>
                <strong>Address:</strong> <?php echo htmlspecialchars($row['shipping_address']); ?><br>
                <strong>City:</strong> <?php echo htmlspecialchars($row['shipping_city']); ?>
              </td>
              <td>
                <strong>Date:</strong> <?php echo date('d/m/Y H:i', strtotime($row['order_date'])); ?><br>
                <strong>Invoice:</strong> <?php echo $row['invoice_number'] ?? 'N/A'; ?><br>
                <strong>Payment Method:</strong> <?php echo htmlspecialchars($row['payment_method']); ?><br>
                <strong>Payment Status:</strong> 
                <span class="status <?php echo strtolower($row['payment_status']); ?>">
                  <?php echo htmlspecialchars($row['payment_status']); ?>
                </span><br>
                <strong>Total:</strong> $<?php echo number_format($row['total_amount'], 2); ?>
              </td>
              <td>
                <span class="status <?php echo strtolower($row['order_status']); ?>">
                  <?php echo ucfirst($row['order_status']); ?>
                </span>
              </td>
              <td class="action-buttons">
                <a href="order.php?edit=<?php echo $row['order_id']; ?>" class="btn btn-edit">
                    <i class='bx bx-edit-alt'></i>
                    Edit
                </a>
                <a href="#" onclick="return confirmDelete(<?php echo $row['order_id']; ?>)" class="btn btn-delete">
    <i class='bx bx-trash'></i>
    Delete
</a>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="5"><?php echo isset($error_message) ? $error_message : 'No orders found'; ?></td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
  </div>

<!-- Modal chỉnh sửa đơn hàng -->
<div id="editModal" class="modal" <?php echo isset($edit_order) ? 'style="display:block"' : ''; ?>>
    <div class="modal-content">
        <span class="close" onclick="document.getElementById('editModal').style.display='none'">&times;</span>
        <h3>Edit Order #<?php echo isset($edit_order) ? sprintf('%04d', $edit_order['order_id']) : ''; ?></h3>
        
        <?php if(isset($edit_order)): ?>
        <form method="POST" action="order.php">
            <input type="hidden" name="order_id" value="<?php echo $edit_order['order_id']; ?>">
            <input type="hidden" name="current_status" value="<?php echo $edit_order['order_status']; ?>">
            
            <div class="form-group">
                <label>Order Status:</label>
                <select name="status">
                    <?php if($edit_order['order_status'] == 'pending'): ?>
                        <option value="pending" selected>Pending</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="delivered">Delivered</option>
                        <option value="cancelled">Cancelled</option>
                    <?php elseif($edit_order['order_status'] == 'confirmed'): ?>
                        <option value="confirmed" selected>Confirmed</option>
                        <option value="delivered">Delivered</option>
                        <option value="cancelled">Cancelled</option>
                    <?php elseif($edit_order['order_status'] == 'delivered'): ?>
                        <option value="delivered" selected>Delivered</option>
                    <?php elseif($edit_order['order_status'] == 'cancelled'): ?>
                        <option value="cancelled" selected>Cancelled</option>
                    <?php else: ?>
                        <option value="pending" selected>Pending</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="delivered">Delivered</option>
                        <option value="cancelled">Cancelled</option>
                    <?php endif; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label>Shipping Address:</label>
                <input type="text" name="shipping_address" value="<?php echo htmlspecialchars($edit_order['shipping_address']); ?>" required>
            </div>
            
            <div class="form-group">
                <label>City:</label>
                <select name="city" required>
                    <option value="HoChiMinh" <?php echo ($edit_order['shipping_city'] == 'HoChiMinh') ? 'selected' : ''; ?>>HoChiMinh</option>
                    <option value="Hanoi" <?php echo ($edit_order['shipping_city'] == 'Hanoi') ? 'selected' : ''; ?>>Hanoi</option>
                </select>
            </div>
            
            <button type="submit" name="update_order" class="submit-btn">Update Order</button>
        </form>
        <?php endif; ?>
    </div>
</div>

<script>
    // Close modal when clicking outside of it
    window.onclick = function(event) {
        var modal = document.getElementById('editModal');
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
    
    // Auto-hide alerts after 3 seconds
    setTimeout(function() {
        var alerts = document.getElementsByClassName('alert');
        for (var i = 0; i < alerts.length; i++) {
            alerts[i].style.display = 'none';
        }
    }, 3000);
    
    function confirmDelete(orderId) {
        if (confirm('Bạn có chắc chắn muốn xóa đơn hàng này không?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'order.php';
            
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'delete_order';
            input.value = 'true';
            
            const orderIdInput = document.createElement('input');
            input.type = 'hidden';
            input.name = 'order_id';
            input.value = orderId;
            
            form.appendChild(input);
            form.appendChild(orderIdInput);
            document.body.appendChild(form);
            form.submit();
        }
    }
    function confirmDelete(orderId) {
    if (confirm('Bạn có chắc chắn muốn xóa đơn hàng này không?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'order.php';
        
        // Tạo input cho delete_order
        const deleteInput = document.createElement('input');
        deleteInput.type = 'hidden';
        deleteInput.name = 'delete_order';
        deleteInput.value = '1';
        
        // Tạo input cho order_id
        const orderIdInput = document.createElement('input');
        orderIdInput.type = 'hidden';
        orderIdInput.name = 'order_id';
        orderIdInput.value = orderId;
        
        // Thêm cả 2 input vào form
        form.appendChild(deleteInput);
        form.appendChild(orderIdInput);
        
        // Thêm form vào body và submit
        document.body.appendChild(form);
        form.submit();
    }
    return false;
}
</script>
</body>
</html>