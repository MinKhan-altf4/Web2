<?php
require_once 'auth.php';
?>
<html html>
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
      <li>
        <a href="order.php">
          <i class='bx bx-cart'></i>
          <span>Order</span>
        </a>
      </li>
      <li class="logout">
        <a href="logout.php">
          <i class='bx bx-log-out' id="log_out"></i>
          <span>Logout</span>
        </a>
      </li>
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
      <form action="addproduct.php" method="POST" enctype="multipart/form-data">
        <div class="product_info">
          <label for="name_product">Product Name:</label>
          <input type="text" name="name_product" id="name_product" placeholder="Enter product name" class="product_enter" required>

          <label for="size_product">Size Product:</label>
          <select name="size[]" id="size_product" class="product_enter" required>
            <option value="">-- Select Size --</option>
            <option value="S">S</option>
            <option value="M">M</option>
            <option value="L">L</option>
            <option value="XL">XL</option>
          </select>

          <label for="price_product">Price Product:</label>
          <input type="number" name="price_product" id="price_product" placeholder="Enter price $" class="product_enter" required>

          <label for="description">Product Description:</label>
<textarea name="description" id="description" placeholder="Enter product description" class="product_enter" rows="4"></textarea>


          <label for="category_product">Category Product:</label>
          <select name="category_product" id="category_product" class="product_enter" required>
            <option value="">-- Select Category --</option>
            <option value="Bags">Bags</option>
            <option value="Clothing">Clothing</option>
            <option value="Shoes">Shoes</option>
            <option value="Accessories">Accessories</option>
          </select>

          <label for="tag_product">Tag Product:</label>
          <input type="text" name="tag_product" id="tag_product" placeholder="Enter tag" class="product_enter" required>

          <label for="image">Image Product:</label>
          <input type="file" class="product_enter" name="image" id="image" accept="image/*" required>

          <button type="submit" class="submit-btn">ADD PRODUCT</button>
        </div>
      </form>
    </div>

<?php
include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['size']) || empty($_POST['size'])) {
        echo "<script>alert('Please select a size!'); window.history.back();</script>";
        exit();
    }

    $name = $_POST['name_product'];
    $size = is_array($_POST['size']) ? implode(",", $_POST['size']) : $_POST['size'];
    $price = $_POST['price_product'];
    $description = $_POST['description']; // Add this line
    $category = $_POST['category_product'];
    $tag = $_POST['tag_product'];

    $image_name = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];
    $upload_dir = "../img/";
    move_uploaded_file($image_tmp, $upload_dir . $image_name);

    $sql = "INSERT INTO products (name, size, price, description, category, tag, image)
            VALUES ('$name', '$size', '$price', '$description', '$category', '$tag', '$image_name')";
    mysqli_query($conn, $sql);
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
          <th>Size</th>
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
        $result = mysqli_query($conn, "SELECT * FROM products WHERE is_deleted = 0");

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                <td>{$row['name']}</td>
                <td>{$row['size']}</td>
                <td>{$row['price']}</td>
                <td>{$row['category']}</td>
                <td>{$row['tag']}</td>
                <td><img src='../img/{$row['image']}' width='60'></td>
                <td>
                    <a href='editproduct.php?id={$row['id']}'>Edit</a> |
                    <a href='deleteproduct.php?id={$row['id']}' class='delete-btn' onclick=\"return confirm('Are you sure?')\">Delete</a>
                </td>
              </tr>";
        }
        ?>
      </tbody>
    </table>
  </div>
</div>
</div>
</body>
</html>