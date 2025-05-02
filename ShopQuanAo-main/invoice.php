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
    <title>Hóa đơn #<?php echo $invoice['invoice_number']; ?></title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;600;700;800;900&display=swap" rel="stylesheet" />

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
        .invoice-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .invoice-header {
            border-bottom: 1px solid #ddd;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .invoice-title {
            font-size: 24px;
            font-weight: 700;
            color: #333;
        }
        .invoice-details {
            display: flex;
            justify-content: space-between;
        }
        .print-btn {
            margin-top: 20px;
        }
        @media print {
            .no-print {
                display: none;
            }
        }
        
        .product-image {
            width: 120px;            /* Increased from 80px */
            height: 120px;           /* Increased from 80px */
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        
        .product-image:hover {
            transform: scale(1.1);
        }
        
        .table td {
            vertical-align: middle;
            padding: 1.5rem;        /* Increased from 1rem */
        }
        
        @media print {
            .product-image {
                width: 100px;        /* Increased from 60px */
                height: 100px;       /* Increased from 60px */
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
    
    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-option no-print">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__text">
                        <h4>Hóa đơn</h4>
                        <div class="breadcrumb__links">
                            <a href="./index.php">Trang chủ</a>
                            <a href="./orders.php">Đơn hàng</a>
                            <span>Hóa đơn</span>
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
                <div class="col-lg-12">
                    <h3 class="mb-4">Hóa đơn #<?php echo $invoice['invoice_number']; ?></h3>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="customer-info">
                        <h4>Thông tin khách hàng</h4>
                        <p><strong>Họ tên:</strong> <?php echo htmlspecialchars($invoice['shipping_fullname']); ?></p>
                        <p><strong>Số điện thoại:</strong> <?php echo htmlspecialchars($invoice['shipping_phone']); ?></p>
                        <p><strong>Địa chỉ:</strong> <?php echo htmlspecialchars($invoice['shipping_address']); ?></p>
                        <p><strong>Thành phố:</strong> <?php echo htmlspecialchars($invoice['shipping_city']); ?></p>
                        <p><strong>Phương thức thanh toán:</strong> <?php echo htmlspecialchars($invoice['payment_method']); ?></p>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="order-info">
                        <h4>Thông tin hóa đơn</h4>
                        <p><strong>Ngày đặt hàng:</strong> <?php echo date('d/m/Y H:i', strtotime($invoice['order_date'])); ?></p>
                        <p><strong>Số hóa đơn:</strong> <?php echo $invoice['invoice_number']; ?></p>
                        <p><strong>Trạng thái thanh toán:</strong> <?php echo htmlspecialchars($invoice['payment_status']); ?></p>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-lg-12">
                    <div class="order-items">
                        <h4>Sản phẩm đã mua</h4>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Hình ảnh</th>
                                        <th>Tên sản phẩm</th>
                                        <th>Đơn giá</th>
                                        <th>Số lượng</th>
                                        <th>Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $total = 0;
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
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="4" class="text-right"><strong>Tổng cộng:</strong></td>
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
                    <a href="orders.php" class="btn btn-primary no-print">Quay lại danh sách đơn hàng</a>
                    <button onclick="window.print()" class="btn btn-secondary no-print">In hóa đơn</button>
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
                            <p><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 2.5. -->
                                Copyright &copy;<script>
                                    document.write(new Date().getFullYear());
                                </script> All rights reserved | This template is made with <i class="fa fa-heart"
                                    aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank">Colorlib</a>
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
</body>
</html>