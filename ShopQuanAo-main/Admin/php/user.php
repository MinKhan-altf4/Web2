<html !DOCTYPE>
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="../css/accounts.css">
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
        <h2 class="header_title">User Management</h2>
      </div>
    
      <div class="add_user">
        <h3>ADD/ EDIT USER</h3>
        <form action="save_user.php" method="post">
          <div class="form_group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
          </div>
    
          <div class="form_group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
          </div>
    
          <div class="form_group">
            <label for="role">Role:</label>
            <select name="role" id="role">
              <option value="admin">Admin</option>
              <option value="staff">Staff</option>
              <option value="user">Customer</option>
            </select>
          </div>
    
          <div class="form_group">
            <label for="status">Status:</label>
            <select name="status" id="status">
              <option value="active">Active</option>
              <option value="locked">Locked</option>
            </select>
          </div>
    
          <div class="form_group">
            <button class="submit-btn">Save</button>
          </div>
        </form>
      </div>
    
      <div class="user_list">
        <h3>Danh sách người dùng</h3>
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Username</th>
              <th>Email</th>
              <th>Role</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <!-- Mỗi dòng là 1 user (giả lập ví dụ) -->
            <tr>
              <td>1</td>
              <td>Nguyễn Văn A</td>
              <td>vana@example.com</td>
              <td>Admin</td>
              <td>Active</td>
              <td>
                <button class="action-btn edit">Edit</button>
  <button class="action-btn lock">Lock</button>
  <button class="action-btn unlock">Unlock</button>
  <button class="action-btn delete">Delete</button>
              </td>
            </tr>
            <tr>
              <td>2</td>
              <td>Trần Thị B</td>
              <td>bt@example.com</td>
              <td>Customer</td>
              <td>Locked</td>
              <td>
                <button class="action-btn edit">Edit</button>
  <button class="action-btn lock">Lock</button>
  <button class="action-btn unlock">Unlock</button>
  <button class="action-btn delete">Delete</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    
      
        
</body>