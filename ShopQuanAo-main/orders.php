<?php
require_once 'Admin/php/db.php'; // Sử dụng db.php từ thư mục Admin

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id'])) {
    die("Bạn chưa đăng nhập!");
}

$user_id = $_SESSION['id'];

// Kiểm tra kết nối và lấy tên database
if (!isset($conn)) {
    die("Kết nối database thất bại!");
}

$db_result = $conn->query("SELECT DATABASE()");
if ($db_result) {
    $dbname = $db_result->fetch_row()[0];
} else {
    die("Không thể lấy tên database.");
}
function columnExists($conn, $dbname, $table, $column): bool {
    $stmt = $conn->prepare("
        SELECT COUNT(*) FROM information_schema.COLUMNS 
        WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = ?
    ");
    $stmt->bind_param("sss", $dbname, $table, $column);
    $stmt->execute();

    // Khai báo biến trước khi bind
    $count = 0;
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    return $count > 0;
}


// Thêm từng cột nếu chưa có
if (!columnExists($conn, $dbname, 'checkout', 'card_number')) {
    $conn->query("ALTER TABLE checkout ADD COLUMN card_number VARCHAR(16)");
}

if (!columnExists($conn, $dbname, 'checkout', 'card_holder')) {
    $conn->query("ALTER TABLE checkout ADD COLUMN card_holder VARCHAR(100)");
}

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
<meta charset="utf-8"/>
<meta content="Male_Fashion Template" name="description"/>
<meta content="Male_Fashion, unica, creative, html" name="keywords"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta content="ie=edge" http-equiv="X-UA-Compatible"/>
<title>Male-Fashion | Nhóm 5TL</title>
<!-- Google Font -->
<link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;600;700;800;900&amp;display=swap" rel="stylesheet"/>
<!-- Css Styles -->
<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<link href="css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
<link href="css/elegant-icons.css" rel="stylesheet" type="text/css"/>
<link href="css/magnific-popup.css" rel="stylesheet" type="text/css"/>
<link href="css/nice-select.css" rel="stylesheet" type="text/css"/>
<link href="css/owl.carousel.min.css" rel="stylesheet" type="text/css"/>
<link href="css/slicknav.min.css" rel="stylesheet" type="text/css"/>
<link href="css/style.css" rel="stylesheet" type="text/css"/>
<link href="css/login.css" rel="stylesheet" type="text/css"/>
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
<div class="offcanvas__links" id="offcanvasLinks">
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
<a href="./shopping-cart.php"><img alt="" src="img/icon/cart.png"/></a>
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
<a href="./index.php"><img alt="" src="img/logo.png"/></a>
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
<a href="./shopping-cart.php"><img alt="" src="img/icon/cart.png"/> </a>
</div>
</div>
</div>
<div class="canvas__open"><i class="fa fa-bars"></i></div>
</div>
</header>
<!-- Header Section End -->
<!-- Breadcrumb Section Begin -->
<section class="breadcrumb-option">
<div class="container">
<div class="row">
<div class="col-lg-12">
<div class="breadcrumb__text">
<h4>Orders</h4>
<div class="breadcrumb__links">
<a href="./index.php">Home</a>
<span>Orders</span>
</div>
</div>
</div>
</div>
</div>
</section>
<!-- Breadcrumb Section End -->
<!-- your cart -->
<div class="container">
<div class="row">
<div class="col-lg-12">
<div class="order__table">
<h4>List of your orders</h4>
<div class="table-responsive"><table class="table table-bordered">
<thead>
<tr>
<th>Order ID</th>
<th>Order Date</th>
<th>Invoice No.</th>
<th>Order Status</th>
<th>Payment Status</th>
<th>Payment Method</th>
<th>Total Amount</th>
<th>Invoice</th>
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
                                            echo '<span class="order-status status-pending">Pending';
                                            break;
                                        case 'processing':
                                            echo '<span class="order-status status-processing">Processing</span>';
                                            break;
                                        case 'shipped':
                                            echo '<span class="order-status status-shipped">Shipped</span>';
                                            break;
                                        case 'confirmed':
                                            echo '<span class="order-status status-confirmed">Confirmed</span>';
                                            break;
                                        case 'delivered':
                                            echo '<span class="order-status status-delivered">Delivered</span>';
                                            break;
                                        case 'cancelled':
                                            echo '<span class="order-status status-cancelled">Cancelled</span>';
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

                                    if ($payment_method == 'card' || $payment_method == 'transfer') {
                                        $update_sql = "UPDATE invoices SET payment_status = 'Paid' WHERE order_id = ?";
                                        $stmt = $conn->prepare($update_sql);
                                        $stmt->bind_param("i", $row['order_id']);
                                        $stmt->execute();
                                        echo '<span class="badge badge-success">Paid</span>';
                                    } else {
                                        switch($payment_status) {
                                            case 'Paid':
                                                echo '<span class="badge badge-success">Paid</span>';
                                                break;
                                            case 'Processing':
                                                echo '<span class="badge badge-warning">Processing</span>';
                                                break;
                                            case 'Unpaid':
                                            default:
                                                echo '<span class="badge badge-danger">Unpaid</span>';
                                                break;
                                        }
                                    }
                                    ?>
                                </td>
                                <td><?php echo htmlspecialchars($row['payment_method']); ?></td>
<td>$<?php echo number_format($row['total_amount'], 2); ?></td>
<td>
<?php if ($row['invoice_id']): ?>
    <a class="btn btn-sm btn-info" href="invoice.php?id=<?php echo $row['invoice_id']; ?>&source=orders">View Invoice</a>
<?php else: ?>
    <span class="text-muted">Not Available</span>
<?php endif; ?>
</td>
</tr>

<?php
                                }
                            } else {
                                echo '<tr><td class="text-center" colspan="8">You have no orders yet.</td>';
                            }
                            ?>
                        </tbody>
</table></div>
</div>
</div>
</div>
</div>
<!-- Footer Section Begin -->
<footer class="footer">
<div class="container">
<div class="row">
<div class="col-lg-3 col-md-6 col-sm-6">
<div class="footer__about">
<div class="footer__logo">
<a href="#"><img alt="" src="img/footer-logo.png"/></a>
</div>
<p>
                            The customer is at the heart of our unique business model, which
                            includes design.
                        </p>
<a href="#"><img alt="" src="img/payment.png"/></a>
</div>
</div>
<div class="col-lg-2 offset-lg-1 col-md-3 col-sm-6">
<div class="footer__widget">
<h6>Shopping</h6>
<ul>
<li><a href="#">Clothing Store</a></li>
<li><a href="#">Trending Shoes</a></li>
<li><a href="#">Accessories</a></li>
<li><a href="#">Sale</a></li>
</ul>
</div>
</div>
<div class="col-lg-2 col-md-3 col-sm-6">
<div class="footer__widget">
<h6>Shopping</h6>
<ul>
<li><a href="#">Contact Us</a></li>
<li><a href="#">Payment Methods</a></li>
<li><a href="#">Delivary</a></li>
<li><a href="#">Return &amp; Exchanges</a></li>
</ul>
</div>
</div>
<div class="col-lg-3 offset-lg-1 col-md-6 col-sm-6">
<div class="footer__widget">
<h6>NewLetter</h6>
<div class="footer__newslatter">
<p>
                                Be the first to know about new arrivals, look books, sales &amp;
                                promos!
                            </p>
<form action="#">
<input placeholder="Your email" type="text"/>
<button type="submit">
<span class="icon_mail_alt"></span>
</button>
</form>
</div>
</div>
</div>
</div>
<div class="row">
<div class="col-lg-12 text-center">
<div class="footer__copyright__text">
<!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
<p>
                            Copyright ©
                            <script>
                            document.write(new Date().getFullYear());
                            </script>
                            2020 All rights reserved | This template is made with
                            <i aria-hidden="true" class="fa fa-heart-o"></i> by
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
<div class="search-model">
<div class="h-100 d-flex align-items-center justify-content-center">
<div class="search-close-switch">+</div>
<form class="search-model-form">
<input id="search-input" placeholder="Search here....." type="text"/>
</form>
</div>
</div>
<!-- Search End -->
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
<script src="js/cart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        checkLoginStatus();
    });
    </script>
</body>
</html>