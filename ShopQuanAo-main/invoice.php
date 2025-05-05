<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Kết nối cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "shopquanao";
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Kiểm tra đăng nhập
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['id'];

// Kiểm tra có ID hóa đơn không
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: orders.php");
    exit;
}

$invoice_id = $_GET['id'];

// Lấy thông tin hóa đơn
$invoice_query = "
    SELECT i.*, 
           c.order_status, 
           c.shipping_fullname, 
           c.shipping_phone, 
           c.shipping_address, 
           c.shipping_city, 
           c.payment_method,
           c.order_date,
           CASE 
               WHEN LOWER(c.payment_method) IN ('card', 'transfer') THEN 'Đã thanh toán'
               WHEN i.payment_status = 'Paid' THEN 'Đã thanh toán'
               WHEN i.payment_status = 'Processing' THEN 'Đang xử lý'
               ELSE 'Chưa thanh toán'
           END as payment_status
    FROM invoices i
    INNER JOIN checkout c ON i.order_id = c.order_id
    WHERE i.invoice_id = ?
";

$stmt = $conn->prepare($invoice_query);
$stmt->bind_param("i", $invoice_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: orders.php");
    exit;
}

$invoice = $result->fetch_assoc();

// Lấy chi tiết sản phẩm trong đơn hàng
$items_query = "
    SELECT 
        ci.*,
        p.name,
        p.image,
        p.price as unit_price
    FROM checkout_items AS ci
    JOIN products AS p ON ci.product_id = p.product_id
    WHERE ci.order_id = ?
";

$stmt = $conn->prepare($items_query);
$stmt->bind_param("i", $invoice['order_id']);
$stmt->execute();
$items_result = $stmt->get_result();
$order_items = [];

while ($item = $items_result->fetch_assoc()) {
    $order_items[] = $item;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="description" content="Male_Fashion Template" />
    <meta name="keywords" content="Male_Fashion, unica, creative, html" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Bill #<?php echo $invoice['invoice_number']; ?></title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;600;700;800;900&display=swap"
        rel="stylesheet" />

    <!-- Css Styles -->
    <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css" />
    <link rel="stylesheet" href="css/font-awesome.min.css" type="text/css" />
    <link rel="stylesheet" href="css/elegant-icons.css" type="text/css" />
    <link rel="stylesheet" href="css/magnific-popup.css" type="text/css" />
    <link rel="stylesheet" href="css/nice-select.css" type="text/css" />
    <link rel="stylesheet" href="css/owl.carousel.min.css" type="text/css" />
    <link rel="stylesheet" href="css/slicknav.min.css" type="text/css" />
    <link rel="stylesheet" href="css/style.css" type="text/css" />
    <style>
    @media print {

        .no-print,
        .no-print * {
            display: none !important;
        }

        .container {
            max-width: 100%;
        }

        .product-image {
            max-width: 80px;
        }
    }
    </style>
    <style>
    .order__table h4 {
        font-weight: 700;
        /* Đậm hơn */
        font-size: 24px;
        /* To hơn */
        color: #111;
        /* Màu đậm, dễ đọc */
        margin-bottom: 25px;
        border-left: 4px solid #ff4d4f;
        /* Vạch nhấn ở bên trái */
        padding-left: 12px;
        text-transform: uppercase;
        /* Viết hoa hết nếu bạn thích phong cách mạnh mẽ */
        letter-spacing: 1px;
    }

    .order__table h3 {
        font-weight: 700;
        /* Đậm hơn */
        font-size: 24px;
        /* To hơn */
        color: #111;
        /* Màu đậm, dễ đọc */
        margin-bottom: 25px;
        border-left: 4px solid #ff4d4f;
        /* Vạch nhấn ở bên trái */
        padding-left: 12px;
        text-transform: uppercase;
        /* Viết hoa hết nếu bạn thích phong cách mạnh mẽ */
        letter-spacing: 1px;
    }


    .order__table {
        font-weight: 800;
        padding: 20px;
        /* tạo khoảng cách giữa phần này với phần xung quanh */
    }
    </style>
    <style>
    .order-table {
        width: 100%;
        border-collapse: collapse;
        border-radius: 10px;
        overflow: hidden;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        background-color: #fff;
        font-size: 17px;
        /* tăng cỡ chữ */
    }


    .order-table thead {
        background-color: #000;
        background: #2c2c2c;

        color: #fff;
        text-transform: uppercase;
        font-size: 16px;
        letter-spacing: 0.8px;
    }

    .order-table th,
    .order-table td {
        padding: 15px 20px;
        /* Giảm padding cho gọn */
        text-align: left;
        /* Mặc định căn trái */

        border-bottom: 1px solid #ddd;
    }


    .order-table th:nth-child(1),
    .order-table td:nth-child(1) {
        width: 15%;
        /* Cột hình ảnh */
        text-align: center;
    }

    .order-table th:nth-child(3),
    .order-table th:nth-child(4),
    .order-table th:nth-child(5),
    .order-table td:nth-child(3),
    .order-table td:nth-child(4),
    .order-table td:nth-child(5) {
        text-align: right;
        /* Căn phải cho cột số */
    }

    .order-table tbody tr:nth-child(even) {
        background: #f8f9fa;
        /* Màu nền xen kẽ */
    }

    .order-table tbody tr:hover {
        background-color: #f0f0f0;
    }

    .order-table img {
        border-radius: 6px;
        width: 120px;
        /* tăng kích thước ảnh */
        height: auto;
        object-fit: cover;
    }

    .order-table td {
        color: #2c2c2c;
        font-weight: 500;
    }

    .order-table th {
        font-weight: 700;
    }

    .order-table tfoot td {
        padding-top: 15px;
        border-top: 2px solid #ddd;
    }

    .order-table tfoot strong {
        font-size: 18px;
        font-weight: 700;
    }
    </style>
    <style>
    .row {
        display: flex;
        flex-wrap: wrap;
        margin: -15px;
    }

    .col-lg-6 {
        padding: 15px;
    }

    .invoice-info-box {
        height: 100%;
        /* Chiều cao bằng nhau */
        padding: 20px 25px;
        /* Giảm padding trên/dưới */

    }

    .invoice-info-box {
        border: 1px solid #e0e0e0;
        /* Viền màu xám nhạt */
        border-radius: 12px;
        padding: 25px;
        margin-bottom: 30px;
        font-family: 'Segoe UI', sans-serif;
        background-color: #ffffff;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
        /* Bóng mềm hơn */
        transition: transform 0.2s ease;
        /* Hiệu ứng hover */
        position: relative;
        overflow: hidden;
    }

    .invoice-info-box:hover {
        transform: translateY(-2px);
        /* Hiệu ứng nổi khi hover */
        box-shadow: 0 6px 28px rgba(0, 0, 0, 0.08);
    }

    .invoice-info-box::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: #d3824f;
        /* Thanh accent màu đen */
    }

    .invoice-info-box h4 {
        font-weight: 700;
        font-size: 20px;
        margin-bottom: 20px;
        color: #2c2c2c;
        font-weight: 600;
        position: relative;
        padding-left: 15px;
    }

    .invoice-info-box h4::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 6px;
        height: 60%;
        background: #d3824f;
        /* Thanh accent màu cam */
    }

    .invoice-info-box p {
        margin: 8px 0;
        font-size: 16px;
        font-weight: 500;
        color: #555;
        line-height: 1.6;
        display: flex;
        justify-content: space-between;
    }

    .invoice-info-box strong {
        color: #1a1a1a;
        font-weight: 700;
        min-width: 45%;
        margin-right: 10px;
        display: inline-block;
    }

    .invoice-info-box span.badge {
        font-size: 14px;
        font-weight: 600;
        padding: 6px 12px;
        border-radius: 20px;
    }

    /* Thêm khoảng cách giữa các nút */
    .btn {
        margin: 0 8px;
        padding: 10px 25px;
    }

    /* Làm nổi bật nút in */
    .btn-secondary {
        background: #2c2c2c;
        border-color: #2c2c2c;
    }

    @media (max-width: 768px) {
        .col-lg-6 {
            flex: 0 0 100%;
            /* Hiển thị full width trên mobile */
            max-width: 100%;
        }

        .invoice-info-box p {
            flex-direction: column;
            /* Xếp dọc trên mobile */
        }

        .invoice-info-box strong {
            min-width: 100%;
            margin-bottom: 4px;
        }
    }
    </style>

</head>

<body>
    <!-- Page Preloder -->
    <div id="preloder">
        <div class="loader"></div>
    </div>

    <!-- Header Section Begin -->
    <!-- Thêm phần header từ file checkout.php (class="no-print") -->
    <!-- Offcanvas Menu Begin -->
    <div class="offcanvas-menu-overlay"></div>
    <div class="offcanvas-menu-wrapper">
        <div class="offcanvas__option">
            <div class="offcanvas__links" id="offcanvaslinks">
                <a href="login.php">Sign in</a>
                <a href="contact.php">SUPPORT</a>
            </div>
            <div class="offcanvas__top__hover">
                <span>Usd <i class="arrow_carrot-down"></i></span>
                <ul>
                    <li>USD</li>
                    <li>EUR</li>
                    <li>USD</li>
                </ul>
            </div>
        </div>
        <div class="offcanvas__nav__option">
            <input type="text" id="searchInput" placeholder="Search..." onkeyup="searchProducts()" />
            <a href="./shopping-cart.php"><img src="img/icon/cart.png" alt="" /> </a>
        </div>
        <div id="mobile-menu-wrap"></div>
        <div class="offcanvas__text">
            <p>Free shipping, 30-day return or refund guarantee.</p>
        </div>
    </div>
    <!-- Offcanvas Menu End -->

    <!-- Header Section Begin -->
    <header class="header">
        <div class="header__top">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-md-7">
                        <div class="header__top__left">
                            <p>Free shipping, 30-day return or refund guarantee.</p>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-5">
                        <div class="header__top__right">
                            <div class="header__top__links">
                                <a href="login.php" id="userMenu">Sign in</a>
                                <a href="contact.php">SUPPORT</a>
                            </div>
                            <div class="header__top__hover">
                                <span>Usd <i class="arrow_carrot-down"></i></span>
                                <ul>
                                    <li>USD</li>
                                    <li>EUR</li>
                                    <li>USD</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-3">
                    <div class="header__logo">
                        <a href="./index.php"><img src="img/logo.png" alt="" /></a>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <nav class="header__menu mobile-menu">
                        <ul>
                            <li><a href="./index.php">Home</a></li>
                            <li class="active"><a href="./shop.php">Shop</a></li>
                            <li>
                                <a href="#">Pages</a>
                                <ul class="dropdown">
                                    <li><a href="./about.php">About Us</a></li>
                                    <li><a href="./shopping-cart.php">Shopping Cart</a></li>
                                    <li><a href="./checkout.php">Check Out</a></li>
                                    <li><a href="./blog-details.php">Blog Details</a></li>
                                </ul>
                            </li>
                            <li><a href="./blog.php">Blog</a></li>
                            <li><a href="./contact.php">Contacts</a></li>
                        </ul>
                    </nav>
                </div>
                <div class="col-lg-3 col-md-3">
                    <div class="header__nav__option">
                        <a href="./shopping-cart.php"><img src="img/icon/cart.png" alt="" /> </a>
                    </div>
                </div>
            </div>
            <div class="canvas__open"><i class="fa fa-bars"></i></div>
        </div>
    </header>
    <!-- Header Section End -->
    <!-- Breadcrumb Section Begin -->
<section class="breadcrumb-option no-print">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="order__table">
                    <h4>Bills</h4>
                    <div class="breadcrumb__links">
                        <a href="./index.php">Home</a>
                        <?php if (isset($_GET['source']) && $_GET['source'] == 'orders'): ?>
                            <a href="./orders.php">Orders</a>
                        <?php elseif (isset($_GET['source']) && $_GET['source'] == 'checkout'): ?>
                            <a href="./checkout.php">Checkout</a>
                        <?php endif; ?>
                        <span>Bill #<?php echo $invoice['invoice_number']; ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Breadcrumb Section End -->

    <!-- Invoice Section Begin -->
    <section class="invoice spad">
        <div class="container">
            <div class="row">
                <div class="order__table">
                    <h3 class="mb-4">Bill #<?php echo $invoice['invoice_number']; ?></h3>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="invoice-info-box">
                        <h4>Customer information</h4>
                        <p><strong>Full Name:</strong> <?php echo htmlspecialchars($invoice['shipping_fullname']); ?>
                        </p>
                        <p><strong>Phone:</strong> <?php echo htmlspecialchars($invoice['shipping_phone']); ?>
                        </p>
                        <p><strong>Address:</strong> <?php echo htmlspecialchars($invoice['shipping_address']); ?></p>
                        <p><strong>City:</strong> <?php echo htmlspecialchars($invoice['shipping_city']); ?></p>
                        <p><strong>Payment method:</strong>
                            <?php echo htmlspecialchars($invoice['payment_method']); ?></p>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="invoice-info-box">
                        <h4>Billing information</h4>
                        <p><strong>Order date:</strong>
                            <?php echo date('d/m/Y H:i', strtotime($invoice['order_date'])); ?></p>
                        <p><strong>Invoice number:</strong> <?php echo $invoice['invoice_number']; ?></p>
                        <p><strong>Payment Status:</strong>
                            <?php if ($invoice['payment_status'] === 'Đã thanh toán'): ?>
                            <span class="badge badge-success">Paid</span>
                            <?php elseif ($invoice['payment_status'] === 'Đang xử lý'): ?>
                            <span class="badge badge-warning">Processing</span>
                            <?php else: ?>
                            <span class="badge badge-danger">Unpaid</span>
                            <?php endif; ?>
                        </p>

                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-lg-12">
                    <div class="order__table">
                        <h4>Products purchased</h4>
                        <div class="table-responsive">
                            <table class="order-table">
                                <thead>
                                    <tr>
                                        <th>Image</th>
                                        <th>Product Name</th>
                                        <th>Product Price</th>
                                        <th>Quantity</th>
                                        <th>Total amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
    $total = 0;
    if (!empty($order_items)):
        foreach ($order_items as $item): 
            $subtotal = $item['quantity'] * $item['unit_price'];
            $total += $subtotal;
    ?>
                                    <tr>
                                        <td>
                                            <?php if(!empty($item['image'])): ?>
                                            <img src="Admin/img/<?php echo htmlspecialchars($item['image']); ?>"
                                                alt="<?php echo htmlspecialchars($item['name']); ?>"
                                                class="product-image">
                                            <?php else: ?>
                                            <img src="img/product/no-image.jpg" alt="No image" class="product-image">
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($item['name']); ?></td>
                                        <td>$<?php echo number_format($item['unit_price'], 2); ?></td>
                                        <td><?php echo $item['quantity']; ?></td>
                                        <td>$<?php echo number_format($subtotal, 2); ?></td>
                                    </tr>
                                    <?php 
        endforeach;
    else: 
    ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-danger">There are no products in the
                                            order.</td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="4" class="text-right"><strong>Total:</strong></td>
                                        <td><strong>$<?php echo number_format($total, 2); ?></strong></td>
                                    </tr>
                                </tfoot>

                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-lg-12 text-center">
                    <a href="shop.php" class="btn btn-primary no-print">Continue Shopping</a>
                   

                </div>
            </div>
        </div>
    </section>
    <!-- Invoice Section End -->

    <!-- Footer Section Begin -->
    <footer class="footer">
        <div class="footer__top">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-3">
                        <div class="footer__widget">
                            <h6>About Us</h6>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quasi, voluptatum!</p>
                            <a href="#" class="footer__btn">Read More</a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3">
                        <div class="footer__widget">
                            <h6>Categories</h6>
                            <ul>
                                <li><a href="#">Fashion</a></li>
                                <li><a href="#">Lifestyle</a></li>
                                <li><a href="#">Sport</a></li>
                                <li><a href="#">Travel</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3">
                        <div class="footer__widget">
                            <h6>Quick Links</h6>
                            <ul>
                                <li><a href="#">Terms of Use</a></li>
                                <li><a href="#">Privacy Policy</a></li>
                                <li><a href="#">Support</a></li>
                                <li><a href="#">Contact</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3">
                        <div class="footer__widget">
                            <h6>Follow Us</h6>
                            <div class="footer__social">
                                <a href="#"><i class="fa fa-facebook"></i></a>
                                <a href="#"><i class="fa fa-twitter"></i></a>
                                <a href="#"><i class="fa fa-instagram"></i></a>
                                <a href="#"><i class="fa fa-pinterest"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer__bottom">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="footer__copyright__text">
                            <p>
                                <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 2.5. -->
                                Copyright &copy;<script>
                                document.write(new Date().getFullYear());
                                </script> All rights reserved | This template is made with <i class="fa fa-heart"
                                    aria-hidden="true"></i> by <a href="https://colorlib.com"
                                    target="_blank">Colorlib</a>
                                <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 2.5. -->
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- Footer Section End -->
    <!-- Thêm phần footer từ file checkout.php (class="no-print") -->

    <!-- Js Plugins -->
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.nice-select.min.js"></script>
    <script src="js/jquery.nicescroll.min.js"></script>
    <script src="js/jquery.magnific-popup.min.js"></script>
    <script src="js/jquery.countdown.min.js"></script>
    <script src="js/jquery.slicknav.js"></script>
    <script src="js/mixitup.min.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/main.js"></script>
    <script src="js/auth.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        checkLoginStatus();
    });
    </script>
</body>

</html>