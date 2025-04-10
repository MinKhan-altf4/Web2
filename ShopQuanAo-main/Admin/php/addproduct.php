<html !DOCTYPE>
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="../css/addproduct.css">
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
        <h2>Product</h2>
       </div>
    </div>
    <div class="add_product">
      <h3>ADD A NEW PRODUCT</h3>
      <div class="product_info">
        <label for="">Name Product:</label>
       <input type="text" name="name_product " placeholder="Enter name" class="product_enter">
       <label for="">Size Product:</label>
      <select name="size[]" class="product_enter" multiple>
        <option value="S">S</option>
        <option value="M">M</option>
        <option value="L">L</option>
        <option value="XL">XL</option>
      </select>
       <label for="">Price Product:</label>
       <input type="number" name="price_product " placeholder="Enter price $" class="product_enter">
       <label for="">Category Product:</label>
       <input type="text" name="category_product" placeholder="Enter category"class="product_enter">
       <label for="">Tag Product:</label>
       <input type="text" name="tag_product" placeholder="Enter tag" class="product_enter">
       <label for="image">Image Product:</label>
       <input type="file" class="product_enter" name="image" accept="image/*" required >

       <div class="submit-btn">ADD PRODUCT</div>
       
      </div>
    </div>
     
    <div class="tabular_wrapper">
      <h3 class="main_title">Product List</h3>
      <div class="table_container">
        <table>
          <thead>
            <tr>
              <th>Name</th>
              <th>Size</th>
              <th>Price</th>
              <th>Category</th>
              <th>Tag Product</th>
              <th>Image</th>
              <th>Action</th>
            </tr>
          </thead>
            <tbody>
              <tr>
                <td>
                  Jacket
                </td>
                <td>
                  S,M,L,XL
                </td>
                <td>
                $500
                </td>
                <td>
                  Clothes
                </td>
                <td>
                  Tag 
                </td>
                <td>
                  Img
                </td>
                <td>
                  <button>Edit</button>
                  <button>Delete</button>
                </td>
              </tr>
            </tbody>
          
        </table>
      </div>

  </div>

</body>
</html>