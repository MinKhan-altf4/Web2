<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Kiểm tra đăng nhập
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

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

// Kiểm tra và thêm cột card_number và card_holder vào bảng checkout
$check_columns_query = "
    SELECT COUNT(*) as count 
    FROM information_schema.COLUMNS 
    WHERE TABLE_SCHEMA = '$dbname' 
    AND TABLE_NAME = 'checkout' 
    AND COLUMN_NAME IN ('card_number', 'card_holder')
";

$check_result = $conn->query($check_columns_query);
$column_exists = $check_result->fetch_assoc()['count'];

if ($column_exists == 0) {
    $alter_table_query = "
        ALTER TABLE checkout
        ADD COLUMN card_number VARCHAR(16),
        ADD COLUMN card_holder VARCHAR(100)
    ";
    if ($conn->query($alter_table_query) === FALSE) {
        die("Lỗi khi thêm cột vào bảng checkout: " . $conn->error);
    }
}

$user_id = $_SESSION['id'];

// Lấy danh sách đơn hàng và hóa đơn của người dùng
$orders_query = "
    SELECT 
        c.order_id,
        c.order_date,
        c.total_amount,
        c.payment_method,
        c.card_number,
        c.card_holder,
        c.shipping_fullname,
        c.shipping_phone,
        c.shipping_address,
        c.order_status,
        i.invoice_id,
        i.invoice_number,
        i.payment_status,
        i.invoice_date
    FROM checkout AS c
    LEFT JOIN invoices AS i ON c.order_id = i.order_id
    WHERE c.user_id = ?
    ORDER BY c.order_date DESC
";

$stmt = $conn->prepare($orders_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// CSS styles cho badges và buttons
$styles = "
<style>
.badge {
    padding: 8px 12px;
    border-radius: 4px;
    font-weight: 600;
    font-size: 12px;
    text-transform: uppercase;
}
.badge-success { background-color: #28a745; color: white; }
.badge-warning { background-color: #ffc107; color: #000; }
.badge-danger { background-color: #dc3545; color: white; }

.btn-sm {
    padding: 5px 10px;
    font-size: 12px;
    margin: 2px;
}
.btn-info { background-color: #17a2b8; color: white; }

.table td, .table th {
    vertical-align: middle;
    padding: 12px;
}

.order-status {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 14px;
}

/* Updated status colors */
.status-pending { background-color: #f39c12; color: white; }    /* Yellow */
.status-processing { background-color: #3498db; color: white; } /* Blue */
.status-shipped { background-color: #2ecc71; color: white; }    /* Light Green */
.status-delivered { background-color: #27ae60; color: white; }  /* Dark Green */
.status-confirmed { background-color: #3498db; color: white; }  /* Blue */
.status-cancelled { background-color: #e74c3c; color: white; }  /* Red */

/* Handle case sensitivity */
[class*='status-'] {
    text-transform: capitalize;
}
</style>
";
?>

<!-- Giữ nguyên phần DOCTYPE và head của file orders.php gốc -->
<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8" />
    <meta name="description" content="Male_Fashion Template" />
    <meta name="keywords" content="Male_Fashion, unica, creative, html" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Male-Fashion | Nhóm 5TL</title>

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
    <link rel="stylesheet" href="css/login.css" type="text/css" />
    <?php echo $styles; ?>
</head>
<title>Lịch Sử Mua Hàng</title>

<body>
    <!-- Page Preloder -->
    <div id="preloder">
        <div class="loader"></div>
    </div>

    <!-- Offcanvas Menu Begin -->
    <div class="offcanvas-menu-overlay"></div>
    <div class="offcanvas-menu-wrapper">
        <div class="offcanvas__option">
            <div class="offcanvas__links" id="offcanvaslinks">
                <a href="#">Sign in</a>
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
            <a href="shopping-cart.php"><img src="img/icon/cart.png" alt="" /> <span>0</span></a>
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
                            <li><a href="./shop.php">Shop</a></li>
                            <li>
                                <a href="#">Pages</a>
                                <ul class="dropdown">
                                    <li><a href="./about.php">About Us</a></li>
                                    <li><a href="./shopping-cart.php">Shopping Cart</a></li>
                                    <li><a href="./checkout.php">Check Out</a></li>
                                    <li><a href="./blog-details.php">Blog Details</a></li>
                                </ul>
                            </li>
                            <li class="actives"><a href="./blog.php">Blog</a></li>
                            <li><a href="./contact.php">Contacts</a></li>
                        </ul>
                    </nav>
                </div>
                <div class="col-lg-3 col-md-3">
                    <div class="header__nav__option">
                        <a href="./shopping-cart.php"><img src="img/icon/cart.png" alt="" /> <span>0</span></a>
                    </div>
                </div>
            </div>
            <div class="canvas__open"><i class="fa fa-bars"></i></div>
        </div>
    </header>
    <!-- Header Section End -->

    <!-- your cart -->
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="order__table">
                    <h4>Danh sách đơn hàng của bạn</h4>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Mã đơn hàng</th>
                                <th>Ngày đặt hàng</th>
                                <th>Số hóa đơn</th>
                                <th>Trạng thái đơn hàng</th>
                                <th>Trạng thái thanh toán</th>
                                <th>Phương thức thanh toán</th>
                                <th>Tổng tiền</th>
                                <th>Hóa đơn</th>  <!-- Changed from "Thao tác" -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                            ?>
                            <tr>
                                <td>#<?php echo $row['order_id']; ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($row['order_date'])); ?></td>
                                <td><?php echo $row['invoice_number'] ?? 'N/A'; ?></td>
                                <td>
                                    <?php
                                    switch(strtolower($row['order_status'])) {
                                        case 'pending':
                                            echo '<span class="order-status status-pending">Đang xử lý</span>';
                                            break;
                                        case 'processing':
                                            echo '<span class="order-status status-processing">Đang chuẩn bị</span>';
                                            break;
                                        case 'shipped':
                                            echo '<span class="order-status status-shipped">Đang giao hàng</span>';
                                            break;
                                        case 'confirmed':
                                            echo '<span class="order-status status-confirmed">Đã xác nhận</span>';
                                            break;
                                        case 'delivered':
                                            echo '<span class="order-status status-delivered">Đã giao hàng</span>';
                                            break;
                                        case 'cancelled':
                                            echo '<span class="order-status status-cancelled">Đã hủy</span>';
                                            break;
                                        default:
                                            echo '<span class="order-status status-' . strtolower($row['order_status']) . '">' 
                                                 . htmlspecialchars($row['order_status']) . '</span>';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    $payment_method = strtolower($row['payment_method']);
                                    $payment_status = $row['payment_status'];

                                    // Standardize payment status display
                                    if ($payment_method == 'card' || $payment_method == 'transfer') {
                                        // Auto set to paid for card/transfer payments
                                        $update_sql = "UPDATE invoices SET payment_status = 'Paid' WHERE order_id = ?";
                                        $stmt = $conn->prepare($update_sql);
                                        $stmt->bind_param("i", $row['order_id']);
                                        $stmt->execute();
                                        echo '<span class="badge badge-success">Đã thanh toán</span>';
                                    } else {
                                        // For other payment methods
                                        switch($payment_status) {
                                            case 'Paid':
                                                echo '<span class="badge badge-success">Đã thanh toán</span>';
                                                break;
                                            case 'Processing':
                                                echo '<span class="badge badge-warning">Đang xử lý</span>';
                                                break;
                                            case 'Unpaid':
                                            default:
                                                echo '<span class="badge badge-danger">Chưa thanh toán</span>';
                                                break;
                                        }
                                    }
                                    ?>
                                </td>
                                <td><?php echo htmlspecialchars($row['payment_method']); ?></td>
                                <td>$<?php echo number_format($row['total_amount'], 2); ?></td>
                                <td>
                                    <?php if ($row['invoice_id']): ?>
                                        <a href="invoice.php?id=<?php echo $row['invoice_id']; ?>" class="btn btn-sm btn-info">Xem hóa đơn</a>
                                    <?php else: ?>
                                        <span class="text-muted">Chưa có</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php
                                }
                            } else {
                                echo '<tr><td colspan="8" class="text-center">Bạn chưa có đơn hàng nào.</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </div>
    <!-- Footer -->
    <!-- Footer -->
    <!-- About Section Begin -->
    <!-- About Section Begin -->
    <!-- Client Section End -->
    <!-- Client Section End -->
    <!-- Footer Section Begin -->
    <!-- Footer Section Begin -->
    <footer class="footer">er">
        <div class="container">
            <div class="row">ol-lg-3 col-md-6 col-sm-6">
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="footer__about">o">
                        <div class="footer__logo">img/footer-logo.png" alt="" /></a>
                            <a href="#"><img src="img/footer-logo.png" alt="" /></a>
                        </div>
                        <p> The customer is at the heart of our unique business model, which
                            The customer is at the heart of our unique business model, which
                            includes design.
                        </p>ref="#"><img src="img/payment.png" alt="" /></a>
                        <a href="#"><img src="img/payment.png" alt="" /></a>
                    </div>
                </div>lass="col-lg-2 offset-lg-1 col-md-3 col-sm-6">
                <div class="col-lg-2 offset-lg-1 col-md-3 col-sm-6">
                    <div class="footer__widget">
                        <h6>Shopping</h6>
                        <ul><li><a href="#">Clothing Store</a></li>
                            <li><a href="#">Clothing Store</a></li>
                            <li><a href="#">Trending Shoes</a></li>
                            <li><a href="#">Accessories</a></li>
                            <li><a href="#">Sale</a></li>
                        </ul>
                    </div>
                </div>lass="col-lg-2 col-md-3 col-sm-6">
                <div class="col-lg-2 col-md-3 col-sm-6">
                    <div class="footer__widget">
                        <h6>Shopping</h6>
                        <ul><li><a href="#">Contact Us</a></li>
                            <li><a href="#">Contact Us</a></li></li>
                            <li><a href="#">Payment Methods</a></li>
                            <li><a href="#">Delivary</a></li>s</a></li>
                            <li><a href="#">Return & Exchanges</a></li>
                        </ul>
                    </div>
                </div>lass="col-lg-3 offset-lg-1 col-md-6 col-sm-6">
                <div class="col-lg-3 offset-lg-1 col-md-6 col-sm-6">
                    <div class="footer__widget">
                        <h6>NewLetter</h6>__newslatter">
                        <div class="footer__newslatter">
                            <p> Be the first to know about new arrivals, look books, sales &
                                Be the first to know about new arrivals, look books, sales &
                                promos!
                            </p>m action="#">
                            <form action="#">
                                <input type="text" placeholder="Your email" />
                                <input type="text" placeholder="Your email" />
                                <button type="submit">mail_alt"></span>
                                    <span class="icon_mail_alt"></span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>lass="row">
            <div class="row">ol-lg-12 text-center">
                <div class="col-lg-12 text-center">text">
                    <div class="footer__copyright__text">
                        <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                        <p> Copyright ©
                            Copyright ©
                            <script>.write(new Date().getFullYear());
                            document.write(new Date().getFullYear());
                            </script>rights reserved | This template is made with
                            2020 All rights reserved | This template is made with
                            <i class="fa fa-heart-o" aria-hidden="true"></i> byrlib</a>
                            <a href="https://colorlib.com" target="_blank">Colorlib</a>
                        </p> 
                  
                        <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- Footer Section End -->
    <!-- Search Begin -->
    <!-- Search Begin -->del">
    <div class="search-model">ex align-items-center justify-content-center">
        <div class="h-100 d-flex align-items-center justify-content-center">
            <div class="search-close-switch">+</div>
            <form class="search-model-form">h-input" placeholder="Search here....." />
                <input type="text" id="search-input" placeholder="Search here....." />
            </form>
        </div>
    </div>
    <!-- Search End -->
    <!-- Js Plugins -->
    <!-- Js Plugins -->ery-3.3.1.min.js"></script>
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script></script>
    <script src="js/jquery.nice-select.min.js"></script>
    <script src="js/jquery.nicescroll.min.js"></script>ipt>
    <script src="js/jquery.magnific-popup.min.js"></script>
    <script src="js/jquery.countdown.min.js"></script>
    <script src="js/jquery.slicknav.js"></script>
    <script src="js/mixitup.min.js"></script>ript>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/main.js"></script>
    <script src="js/auth.js"></script>
    <script src="js/cart.js"></script>
    // Thêm vào cuối file
<script> document.addEventListener('DOMContentLoaded', function() {
    checkLoginStatus();
});  </script>
</body>

</html></html>