<?php
require_once 'auth.php';
?>
<html !DOCTYPE>
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="../css/dashboard.css">
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
      <div class="header_title">
        <span>MALE FASHION</span>
        <h2>Dashboard</h2>
      </div>
      <div class="user_info">
        <div class="search_box">
          <i class='bx bx-search'></i>
          <input type="text" placeholder="Search">
        </div>
        <img src="../img/user.png" alt="">
      </div>
    </div>
    <div class="card_container">
      <h3 class="main_title">Today's data</h3> 
      <div class="card_wrapper">

        <div class="payment_card light_red">
          <div class="card_header">
          <div class="amount">
            <span class="title">
              Payment amount
            </span >  
            <span class="amount_value">
              $500.00

            </span>
          </div>
          <i class='bx bx-dollar-circle dark_red'></i>
        </div>
          <span class="card_detail">
          **** **** **** 3484
        </span>
        </div>

        <div class="payment_card light_purple">
          <div class="card_header">
          <div class="amount">
            <span class="title">
              Payment oder
            </span >  
            <span class="amount_value">
              $500.00

            </span>
          </div>
          <i class='bx bx-list-ul dark_purple'></i>
        </div>
          <span class="card_detail">
          **** **** **** 3484
        </span>
        </div>

        <div class="payment_card light_green">
          <div class="card_header">
          <div class="amount">
            <span class="title">
              Payment customer
            </span >  
            <span class="amount_value">
              $500.00

            </span>
          </div>
          <i class='bx bxs-user dark_green'></i>
        </div>
          <span class="card_detail">
          **** **** **** 3484
        </span>
        </div>
        
        <div class="payment_card light_blue">
          <div class="card_header">
          <div class="amount">
            <span class="title">
              Payment proceed
            </span >  
            <span class="amount_value">
              $500.00

            </span>
          </div>
          <i class='bx bx-check dark_blue'></i>
        </div>
          <span class="card_detail">
          **** **** **** 3484
        </span>
        </div>
        
      </div>
    </div>
    <div class="tabular_wrapper">
  <h3 class="main_title">Finance data</h3>
  <div class="table_container">
    <table>
      <thead>
        <tr>
          <th>Date</th>
          <th>Transaction Type</th>
          <th>Decription</th>
          <th>Amount</th>
          <th>Category</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
        <tbody>
          <tr>
            <td>
              2025-04-10
            </td>
            <td>
              Expenses
            </td>
            <td>
            Office Supplies
            </td>
            <td>
              $250
            </td>
            <td>
              Office Expenses
            </td>
            <td>
              Pendding
            </td>
            <td>
              <button>Edit</button>
            </td>
          </tr>
        </tbody>
      
    </table>
  </div>
</div>

  </div>
  



</body>
</html>