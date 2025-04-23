<?php
include("db.php");

$id = $_GET['id'];
$product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE id = $id"));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name_product'];
    $size = implode(",", $_POST['size']);
    $price = $_POST['price_product'];
    $description = $_POST['description'];
    $category = $_POST['category_product'];
    $tag = $_POST['tag_product'];

    if ($_FILES['image']['name']) {
        $image_name = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
        move_uploaded_file($image_tmp, "../img/" . $image_name);
    } else {
        $image_name = $product['image'];
    }

    $sql = "UPDATE products SET 
            name='$name', size='$size', price='$price', description='$description',
            category='$category', tag='$tag', image='$image_name'
            WHERE id=$id";
    mysqli_query($conn, $sql);
    header("Location: addproduct.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Product</title>
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
          <i class='bx bx-cart' ></i>
          <span>Order</span>
        </a>
      </li>
      
    </ul>
  </div>

  <div class="main_content">
    <div class="header_wrapper">
      <div class="header_title">
        <span>MALE FASHION</span>
        <h2>Edit Product</h2>
      </div>
    </div>
    <div class="add_product">
      <h3>EDIT PRODUCT</h3>
      <form action="" method="POST" enctype="multipart/form-data">
        <div class="product_info">
          <label for="name_product">Product Name:</label>
          <input type="text" name="name_product" id="name_product" class="product_enter" value="<?= htmlspecialchars($product['name'] ?? '') ?>" required>

          <label for="size_product">Size Product:</label>
          <select name="size[]" id="size_product" class="product_enter" required>
            <option value="">-- Select Size --</option>
            <?php
            $sizes = isset($product['size']) ? explode(",", $product['size']) : [];
            $allSizes = ['S', 'M', 'L', 'XL'];
            foreach ($allSizes as $size) {
                $selected = in_array($size, $sizes) ? 'selected' : '';
                echo "<option value='$size' $selected>$size</option>";
            }
            ?>
          </select>

          <label for="price_product">Price Product:</label>
          <input type="number" name="price_product" id="price_product" class="product_enter" value="<?= htmlspecialchars($product['price'] ?? '') ?>" required>

          <label for="description">Product Description:</label>
          <textarea name="description" id="description" class="product_enter" rows="4"><?= htmlspecialchars($product['description'] ?? '') ?></textarea>

          <label for="category_product">Category Product:</label>
          <select name="category_product" id="category_product" class="product_enter" required>
            <option value="">-- Select Category --</option>
            <?php
            $categories = ['Bags', 'Clothing', 'Shoes', 'Accessories'];
            foreach ($categories as $cat) {
                $selected = ($product['category'] ?? '') === $cat ? 'selected' : '';
                echo "<option value='$cat' $selected>$cat</option>";
            }
            ?>
          </select>

          <label for="tag_product">Tag Product:</label>
          <input type="text" name="tag_product" id="tag_product" class="product_enter" value="<?= htmlspecialchars($product['tag'] ?? '') ?>" required>

          <label for="image">Image Product:</label>
          <input type="file" name="image" id="image" class="product_enter" accept="image/*">
          <?php if (!empty($product['image'])): ?>
            <div class="current-image">
              <p>Current Image:</p>
              <img src="../img/<?= htmlspecialchars($product['image']) ?>" width="100" alt="Current Image">
            </div>
          <?php endif; ?>

          <button type="submit" class="submit-btn">UPDATE PRODUCT</button>
        </div>
      </form>
    </div>
  </div>
</body>
</html>