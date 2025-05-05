<?php
require_once 'auth.php';
require_once 'db.php'; // Kết nối CSDL

// Lấy thống kê tổng quan
$today = date('Y-m-d');
$statsQuery = "SELECT 
    (SELECT COUNT(*) FROM user WHERE role = 'customer') as total_customers,
    (SELECT COUNT(*) FROM products WHERE is_deleted = 0) as total_products,
    (SELECT COUNT(*) FROM checkout WHERE DATE(order_date) = '$today' AND order_status != 'cancelled') as today_orders,
    (SELECT SUM(total_amount) FROM checkout WHERE DATE(order_date) = '$today' AND order_status != 'cancelled') as today_revenue,
    (SELECT COUNT(*) FROM checkout WHERE order_status = 'Pending') as pending_orders";
$statsResult = $conn->query($statsQuery);
$stats = $statsResult->fetch_assoc();

// Lấy đơn hàng gần đây
$recentOrdersQuery = "SELECT 
    c.order_id, 
    c.order_date, 
    c.total_amount, 
    c.order_status,
    u.fullname as customer_name
    FROM checkout c
    JOIN user u ON c.user_id = u.id
    WHERE c.order_status != 'cancelled'
    ORDER BY c.order_date DESC
    LIMIT 5";
$recentOrders = $conn->query($recentOrdersQuery);
?>
<html !DOCTYPE>
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="../css/dashboard.css">
  <link rel="stylesheet" href="../css/grid.css">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <style>
    /* Thêm CSS cho dashboard */
    .card_wrapper {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .payment_card {
        padding: 20px;
        border-radius: 10px;
        transition: transform 0.3s ease;
    }
    
    .payment_card:hover {
        transform: translateY(-5px);
    }
    
    .card_header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }
    
    .amount_value {
        font-size: 24px;
        font-weight: 700;
        display: block;
        margin-top: 5px;
    }
    
    .card_detail {
        font-size: 14px;
        opacity: 0.8;
    }
    
    /* Màu sắc */
    .light_red { background: #ffebee; }
    .dark_red { color: #f44336; }
    .light_purple { background: #f3e5f5; }
    .dark_purple { color: #9c27b0; }
    .light_green { background: #e8f5e9; }
    .dark_green { color: #4caf50; }
    .light_blue { background: #e3f2fd; }
    .dark_blue { color: #2196f3; }
    .light_orange { background: #fff3e0; }
    .dark_orange { color: #ff9800; }
    
    /* Bảng dữ liệu */
    .table_container {
        overflow-x: auto;
    }
    
    table {
        width: 100%;
        border-collapse: collapse;
    }
    
    th, td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solidrgb(10, 10, 10);
    }
    
    th {
        background-color:rgb(5, 5, 5);
        font-weight: 600;
    }
    
    .status-badge {
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
        text-transform: capitalize;
    }
    
  
    
    .action-btn {
        color: #2196f3;
        padding: 5px 10px;
        border-radius: 4px;
        text-decoration: none;
        font-size: 14px;
    }
    
    .action-btn:hover {
        background: #e3f2fd;
    }
  </style>
</head>
<body>
  <div class="sidebar">
    <!-- Giữ nguyên phần sidebar -->
    <div class="logo"></div>
    <ul class="menu">
      <li class="active">
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
      <li><a href="addproduct.php">
        <i class='bx bx-box'></i>
        <span>Product</span>
      </a></li>
      <li><a href="analytics.php">
        <i class='bx bx-pie-chart-alt'></i>
        <span>Analytics</span>
      </a></li>
      <li><a href="order.php">
        <i class='bx bx-cart'></i>
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
      <div class="header_title">
        <span>MALE FASHION</span>
        <h2>Dashboard</h2>
      </div>
      
    </div>
    
    <div class="card_container">
      <h3 class="main_title">Today's Overview</h3> 
      <div class="card_wrapper">
        <!-- Thẻ Tổng doanh thu -->
        <div class="payment_card light_red">
          <div class="card_header">
            <div class="amount">
              <span class="title">
                Today's Revenue
              </span>  
              <span class="amount_value">
                $<?php echo number_format($stats['today_revenue'] ?? 0, 2); ?>
              </span>
            </div>
            <i class='bx bx-dollar-circle dark_red'></i>
          </div>
          <span class="card_detail">
            <?php echo ($stats['today_orders'] ?? 0) . ' orders today'; ?>
          </span>
        </div>

        <!-- Thẻ Đơn hàng hôm nay -->
        <div class="payment_card light_purple">
          <div class="card_header">
            <div class="amount">
              <span class="title">
                Today's Orders
              </span>  
              <span class="amount_value">
                <?php echo $stats['today_orders'] ?? 0; ?>
              </span>
            </div>
            <i class='bx bx-list-ul dark_purple'></i>
          </div>
          <span class="card_detail">
            <?php echo number_format($stats['today_revenue'] ?? 0, 2) . ' total sales'; ?>
          </span>
        </div>

        <!-- Thẻ Khách hàng -->
        <div class="payment_card light_green">
          <div class="card_header">
            <div class="amount">
              <span class="title">
                Total Customers
              </span>  
              <span class="amount_value">
                <?php echo $stats['total_customers'] ?? 0; ?>
              </span>
            </div>
            <i class='bx bxs-user dark_green'></i>
          </div>
          <span class="card_detail">
            <?php echo ($stats['today_orders'] ?? 0) . ' orders today'; ?>
          </span>
        </div>
        
        <!-- Thẻ Sản phẩm -->
        <div class="payment_card light_blue">
          <div class="card_header">
            <div class="amount">
              <span class="title">
                Total Products
              </span>  
              <span class="amount_value">
                <?php echo $stats['total_products'] ?? 0; ?>
              </span>
            </div>
            <i class='bx bx-box dark_blue'></i>
          </div>
          <span class="card_detail">
            Active in store
          </span>
        </div>
      </div>
    </div>
    
    <div class="tabular_wrapper">
      <h3 class="main_title">Recent Orders</h3>
      <div class="table_container">
        <table>
          <thead>
            <tr>
              <th>Order ID</th>
              <th>Date</th>
              <th>Customer</th>
              <th>Amount</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php while($order = $recentOrders->fetch_assoc()): ?>
            <tr>
              <td>#<?php echo $order['order_id']; ?></td>
              <td><?php echo date('M d, Y', strtotime($order['order_date'])); ?></td>
              <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
              <td>$<?php echo number_format($order['total_amount'], 2); ?></td>
              <td>
                <span class="status-badge status-<?php echo strtolower($order['order_status']); ?>">
                  <?php echo htmlspecialchars($order['order_status']); ?>
                </span>
              </td>
              
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</body>
</html>