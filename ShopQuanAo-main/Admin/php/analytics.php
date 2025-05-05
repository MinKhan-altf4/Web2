<?php
require_once 'auth.php';
require_once 'db.php'; // Kết nối CSDL
?>
<html !DOCTYPE>
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="../css/analytics.css">
  <link rel="stylesheet" href="../css/grid.css">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
  <div class="sidebar">
    <div class="logo"></div>
    
      <ul class="menu">
        <li class="active">
         <a href="dashboard.php" >
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
        <li><a href="addproduct.php">
          <i class='bx bx-box'></i>
        <span>Product</span>
        </a></li>
        <li><a href="analytics.php">
        <i class='bx bx-pie-chart-alt'></i>
        <span>Analytics</span>
        </a></li>
        <li><a href="order.php">
        <i class='bx bx-cart' ></i>
        <span>Order</span>
        </a></li>
       
        <li class="logout"><a href="logout.php">
          <i class='bx bx-log-out' id="log_out"></i>
          <span>Logout</span>
          </a></li>
      </ul>
    </div>

    <div class="main_content">
      <div class="header_wrapper">
        <h2 class="header_title">Analytics Management</h2>
      </div>
    
      <div class="analytics_management">
        <h3>FILTER DATE</h3>
        <div class="filter-section">
          <form method="GET" action="">
            <label>From: <input type="date" name="fromDate" id="fromDate" value="<?php echo isset($_GET['fromDate']) ? $_GET['fromDate'] : ''; ?>"></label>
            <label>To: <input type="date" name="toDate" id="toDate" value="<?php echo isset($_GET['toDate']) ? $_GET['toDate'] : ''; ?>"></label>
            <button type="submit" class="submit-btn">Statistic</button>
          </form>
        </div>
        
        <?php
        // Xử lý thống kê khi có dữ liệu filter
        if (isset($_GET['fromDate'])) {
            $fromDate = $_GET['fromDate'] . ' 00:00:00';
            $toDate = $_GET['toDate'] . ' 23:59:59';
        
            // Thống kê top 5 khách hàng
            $customerQuery = "SELECT 
                                u.id as user_id, 
                                u.fullname, 
                                SUM(c.total_amount) as total_expenditure,
                                GROUP_CONCAT(c.order_id ORDER BY c.order_id DESC SEPARATOR ',') as order_ids
                              FROM checkout c
                              JOIN user u ON c.user_id = u.id
                              WHERE c.order_date BETWEEN ? AND ?
                              AND c.order_status != 'cancelled'
                              GROUP BY u.id, u.fullname
                              ORDER BY total_expenditure DESC
                              LIMIT 5";
            $stmt = $conn->prepare($customerQuery);
            $stmt->bind_param("ss", $fromDate, $toDate);
            $stmt->execute();
            $customerResult = $stmt->get_result();
            
            // Thống kê theo sản phẩm
      // Thống kê theo sản phẩm
$productQuery = "SELECT 
p.product_id, 
p.name as product_name, 
SUM(ci.quantity) as total_quantity, 
SUM(ci.quantity * ci.price) as total_revenue
FROM checkout_items ci
JOIN products p ON ci.product_id = p.product_id
JOIN checkout c ON ci.order_id = c.order_id
WHERE c.order_date BETWEEN ? AND ?
AND c.order_status != 'cancelled'
GROUP BY p.product_id, p.name
ORDER BY total_revenue DESC";
            $stmt = $conn->prepare($productQuery);
            $stmt->bind_param("ss", $fromDate, $toDate);
            $stmt->execute();
            $productResult = $stmt->get_result();
            
            // Tính tổng doanh thu
            $totalRevenueQuery = "SELECT SUM(total_amount) as total 
                      FROM checkout 
                      WHERE order_date BETWEEN ? AND ?
                      AND order_status != 'cancelled'";
            $stmt = $conn->prepare($totalRevenueQuery);
            $stmt->bind_param("ss", $fromDate, $toDate);
            $stmt->execute();
            $totalRevenueResult = $stmt->get_result();
            $totalRevenue = $totalRevenueResult->fetch_assoc();
           
        ?>
        
        <div class="section">
          <h2>Statistics by item</h2>
          <table>
            <thead>
              <tr>
                <th>Item</th>
                <th>Quantity</th>
                <th>Total product price</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($row = $productResult->fetch_assoc()): ?>
              <tr>
                <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                <td><?php echo $row['total_quantity']; ?></td>
                <td><?php echo number_format($row['total_revenue'], 2); ?> $</td>
              </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
          <div class="total-revenue">
            <h3>Total Revenue: <?php echo isset($totalRevenue['total']) ? number_format($totalRevenue['total'], 2) : '0'; ?> $</h3>
          </div>
        </div>
      
        <div class="section">
          <h2>Top 5 customers by revenue</h2>
          <table>
            <thead>
              <tr>
                <th>Customer name</th>
                <th>Total expenditure</th>
                <th>Order details</th>
              </tr>
            </thead>
            <tbody>
            <?php while ($customer = $customerResult->fetch_assoc()): 
    $orderIds = explode(',', $customer['order_ids']);
?>
<tr>
    <td><?php echo htmlspecialchars($customer['fullname']); ?></td>
    <td><?php echo number_format($customer['total_expenditure'], 2); ?>$</td>
    <td>
        <?php foreach ($orderIds as $orderId): 
            // Truy vấn thông tin cơ bản về đơn hàng
            $orderQuery = "SELECT c.order_id, c.order_date, c.total_amount, 
                                  c.order_status, i.invoice_id, i.invoice_number
                           FROM checkout c
                           LEFT JOIN invoices i ON c.order_id = i.order_id
                           WHERE c.order_id = ?";
            $stmt = $conn->prepare($orderQuery);
            $stmt->bind_param("i", $orderId);
            $stmt->execute();
            $orderResult = $stmt->get_result();
            $order = $orderResult->fetch_assoc();
        ?>
        <div class="order-info-box">
            <a href="../../invoice.php?id=<?php echo $order['invoice_id'] ?? '#'; ?>" 
               class="order-link" title="View Invoice">
               Order #<?php echo $orderId; ?>
            </a>
            <div class="order-meta">
                <span class="order-date"><?php echo date('d/m/Y', strtotime($order['order_date'])); ?></span>
                <span class="order-status status-<?php echo strtolower($order['order_status']); ?>">
                    <?php echo htmlspecialchars($order['order_status']); ?>
                </span>
                <span class="order-amount"><?php echo number_format($order['total_amount'], 2); ?>$</span>
            </div>
        </div>
        <?php endforeach; ?>
    </td>
</tr>
<?php endwhile; ?>
            </tbody>
          </table>
        </div>
        
        <?php } ?>
      </div>
    </div>
</body>
</html>