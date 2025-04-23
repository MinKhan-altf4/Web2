<?php
require_once 'auth.php';
?>
<html !DOCTYPE>
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="../css/order.css">
  <link rel="stylesheet" href="../css/grid.css">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
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
      <h2 class="header_title">Order Management</h2>
    </div>
  
    <!-- Bộ lọc đơn hàng -->
    <div class="filter_order">
      <h3>Filter Orders</h3>
      <div class="filter_info">
        <div class="filter">
          <label>From Date:</label>
          <input type="date" name="from_date">
        </div>
        <div class="filter">
          <label>To Date:</label>
          <input type="date" name="to_date">
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
          <label>District:</label>
          <select name="district">
            <option value="">All</option>
            <option value="1">District 1</option>
            <option value="2">District 2</option>
            <option value="3">District 3</option>
          </select>
        </div>
        <div class="submit-btn">Apply Filter</div>
      </div>
    </div>
  
    <!-- Bảng danh sách đơn hàng -->
    <div class="tabular_wrapper">
      <div class="table_container">
        <table>
          <thead>
            <tr>
              <th>Order ID</th>
              <th>Customer</th>
              <th>Order Date</th>
              <th>Shipping Address</th>
              <th>District</th>
              <th>Status</th>
              <th>Details</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>#0001</td>
              <td>Nguyen Van A</td>
              <td>2025-04-10</td>
              <td>123 Le Loi, Ward 5</td>
              <td>District 1</td>
              <td><span class="status pending">Pending</span></td>
              <td><a href="order_detail.php?id=1" class="action_btn">View</a></td>
            </tr>
            <tr>
              <td>#0002</td>
              <td>Tran Thi B</td>
              <td>2025-04-09</td>
              <td>456 Tran Hung Dao, Ward 7</td>
              <td>District 2</td>
              <td><span class="status delivered">Delivered</span></td>
              <td><a href="order_detail.php?id=2" class="action_btn">View</a></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  
    
  </body>
  