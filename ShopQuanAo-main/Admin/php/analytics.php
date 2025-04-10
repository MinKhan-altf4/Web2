<html !DOCTYPE>
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="../css/analytics.css">
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
        <h2 class="header_title">Analytics Management</h2>
      </div>
    
      <div class="analytics_management">
        <h3>FILTER DATE</h3>
        <div class="filter-section">
          <label>From: <input type="date" id="fromDate"></label>
          <label>To: <input type="date" id="toDate"></label>
          <button onclick="fetchAnalytics() " class="submit-btn">Statistic</button>
        </div>
        <div class="section">
          <h2>Statistics by item</h2>
          <table>
            <thead>
              <tr>
                <th>Item</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Invoice details</th>
              </tr>
            </thead>
            <tbody id="productStats"></tbody>
          </table>
          <div id="totalRevenue"></div>
          <div id="bestWorstProduct"></div>
        </div>
      
        <div class="section">
          <h2>Top 5 customers by revenue</h2>
          <table>
            <thead>
              <tr>
                <th>Customer name</th>
                <th>Total expenditure</th>
                <th>Invoice details</th>
              </tr>
            </thead>
            <tbody id="customerStats"></tbody>
          </table>
        
       
    </div>
        </div>
        
    
  
</body>

<script>
  function fetchAnalytics() {
    const fromDate = document.getElementById('fromDate').value;
    const toDate = document.getElementById('toDate').value;
  
    // Gửi request đến PHP để lấy dữ liệu thống kê từ CSDL
    fetch(`analytics.php?from=${fromDate}&to=${toDate}`)
      .then(res => res.json())
      .then(data => {
        renderProductStats(data.products);
        renderCustomerStats(data.customers);
      });
  }
  
  function renderProductStats(products) {
    let total = 0;
    let max = products[0], min = products[0];
    const tbody = document.getElementById('productStats');
    tbody.innerHTML = '';
  
    products.forEach(p => {
      total += p.total;
      if (p.total > max.total) max = p;
      if (p.total < min.total) min = p;
  
      tbody.innerHTML += `
        <tr>
          <td>${p.name}</td>
          <td>${p.quantity}</td>
          <td>${p.total.toLocaleString()}đ</td>
          <td><a href="invoices.php?product_id=${p.id}">Xem</a></td>
        </tr>
      `;
    });
  
    document.getElementById('totalRevenue').innerText = `Tổng thu: ${total.toLocaleString()}đ`;
    document.getElementById('bestWorstProduct').innerText = `Bán chạy nhất: ${max.name}, Ế nhất: ${min.name}`;
  }
  
  function renderCustomerStats(customers) {
    const tbody = document.getElementById('customerStats');
    tbody.innerHTML = '';
  
    customers.forEach(c => {
      tbody.innerHTML += `
        <tr>
          <td>${c.name}</td>
          <td>${c.total.toLocaleString()}đ</td>
          <td><a href="invoices.php?customer_id=${c.id}">Xem</a></td>
        </tr>
      `;
    });
  }
  
</script>