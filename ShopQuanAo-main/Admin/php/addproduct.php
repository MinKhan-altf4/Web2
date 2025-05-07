<?php
require_once 'auth.php';
?>
<html html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/addproduct.css">
  <link rel="stylesheet" href="../css/grid.css">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
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
        <li class="active">
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
        <li>
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
    <div class="mobile-toggle">
        <i class='bx bx-menu'></i>
    </div>
    <div class="header_wrapper">
      <div class="header_title">
        <span>MALE FASHION</span>
        <h2>Product</h2>
      </div>
    </div>
    <div class="add_product">
      <h3>ADD A NEW PRODUCT</h3>
      <form action="addproduct.php" method="POST" enctype="multipart/form-data">
        <div class="product_info">
          <label for="name_product">Product Name:</label>
          <input type="text" name="name_product" id="name_product" placeholder="Enter product name" class="product_enter" required>

          <label for="price_product">Price Product:</label>
          <input type="number" name="price_product" id="price_product" placeholder="Enter price $" class="product_enter" required>

          <label for="description">Product Description:</label>
<textarea name="description" id="description" placeholder="Enter product description" class="product_enter" rows="4"></textarea>


          <label for="category_product">Category Product:</label>
          <select name="category_product" id="category_product" class="product_enter" required>
            <option value="">-- Select Category --</option>
            <?php
            // Lấy danh sách loại sản phẩm từ bảng product_types
            $type_query = "SELECT type_id, type_name FROM product_types ORDER BY type_name";
            $type_result = mysqli_query($conn, $type_query);
            while($type = mysqli_fetch_assoc($type_result)) {
                echo "<option value='{$type['type_id']}'>{$type['type_name']}</option>";
            }
            ?>
          </select>

          <label for="tag_product">Tag Product:</label>
          <input type="text" name="tag_product" id="tag_product" placeholder="Enter tag" class="product_enter" required>

          <label for="image">Image Product:</label>
          <input type="file" class="product_enter" name="image" id="image" accept="image/*" required>
          <label for="is_visible">Show products:</label>
<select name="is_visible" id="is_visible" class="product_enter" required>
    <option value="1" selected>Show</option>
    <option value="0">Hidden</option>
</select>


          <button type="submit" class="submit-btn">ADD PRODUCT</button>
          
        </div>
      </form>
    </div>

<?php
include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name_product'];
    $price = $_POST['price_product'];
    $description = $_POST['description'];
    $type_id = $_POST['category_product']; // Thay đổi từ category sang type_id
    $tag = $_POST['tag_product'];
    $is_visible = $_POST['is_visible'];

    $image_name = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];
    $upload_dir = "../img/";
    move_uploaded_file($image_tmp, $upload_dir . $image_name);

    // Sử dụng prepared statement để tránh SQL injection
    $sql = "INSERT INTO products (name, price, description, type_id, tag, image, is_visible)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
            
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sdsissi", $name, $price, $description, $type_id, $tag, $image_name, $is_visible);
    mysqli_stmt_execute($stmt);
    
    header("Location: addproduct.php");
    exit();
}
?>

<div class="tabular_wrapper">
  <h3 class="main_title">Product List</h3>
  <div class="table_container">
    <table>
      <thead>
        <tr>
          <th>Name</th>
          <th>Price</th>
          <th>Category</th>
          <th>Tag Product</th>
          <th>Image</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
        include("db.php");
        // Sử dụng JOIN để lấy tên loại sản phẩm
        $result = mysqli_query($conn, 
            "SELECT p.product_id, p.name, p.price, pt.type_name as category, 
                    p.tag, p.image 
             FROM products p
             JOIN product_types pt ON p.type_id = pt.type_id 
             WHERE p.is_deleted = 0");

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                <td>{$row['name']}</td>
                <td>{$row['price']}</td>
                <td>{$row['category']}</td>
                <td>{$row['tag']}</td>
                <td><img src='../img/{$row['image']}' width='60'></td>
                <td>
                    <div class='action-buttons'>
                        <a href='editproduct.php?id={$row['product_id']}' class='btn btn-edit'>
                            <i class='bx bx-edit-alt'></i>
                            Edit
                        </a>
                        <a href='deleteproduct.php?id={$row['product_id']}' 
                           class='btn btn-delete'
                           onclick=\"return confirm('Are you sure you want to delete this product?')\">
                            <i class='bx bx-trash'></i>
                            Delete
                        </a>
                    </div>
                </td>
              </tr>";
        }
        ?>
      </tbody>
    </table>
  </div>
</div>
    <script src="../js/main.js"></script>
</body>
</html>