<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Kết nối cơ sở dữ liệu trực tiếp
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "shopquanao";
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Add after database connection code and before form processing

function generateInvoiceNumber() {
    // Get current timestamp
    $timestamp = time();
    // Get random number between 1000-9999
    $random = rand(1000, 9999);
    // Combine timestamp and random number
    $invoice_number = 'INV-' . $timestamp . '-' . $random;
    return $invoice_number;
}

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['id'];  

// Lấy thông tin user
$sql_user = "SELECT * FROM user WHERE id = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$result_user = $stmt_user->get_result();
$user_data = $result_user->fetch_assoc();


// Get cart items
$user_id = $_SESSION['id'];
$cart_query = "
    SELECT 
        c.*, 
        p.name, 
        p.price, 
        p.image,
        (p.price * c.quantity) AS total_price   -- <-- thêm khoảng trắng trước AS
    FROM cart    AS c
    JOIN products AS p ON c.product_id = p.product_id
    WHERE c.id = ?
";
$stmt = $conn->prepare($cart_query);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$cart_items = [];
$subtotal = 0;

while ($row = $result->fetch_assoc()) {
    $cart_items[] = $row;
    $subtotal += $row['total_price'];
}

// Thêm kiểm tra giỏ hàng trống 
if (empty($cart_items)) {
    echo '<script>
        alert("Giỏ hàng của bạn đang trống. Vui lòng thêm sản phẩm vào giỏ hàng trước khi thanh toán.");
        window.location.href = "shopping-cart.php";
    </script>';
    exit;
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Di chuyển lấy payment_method lên đầu
    $payment_method = $conn->real_escape_string($_POST['payment-method'] ?? '');
    
    // VALIDATION - Đặt ngay sau khi có payment_method
    if ($payment_method === "transfer") {
        if (empty($_POST['bank_reference'])) {
            echo '<script>alert("Vui lòng nhập số tham chiếu ngân hàng");</script>';
            echo '<script>window.history.back();</script>';
            exit;
        }
    } 
    elseif ($payment_method === "card") {
        if (empty($_POST['card_number']) || empty($_POST['card_holder']) || 
            empty($_POST['expiry_date']) || empty($_POST['cvv'])) {
            echo '<script>alert("Vui lòng nhập đầy đủ thông tin thẻ thanh toán");</script>';
            echo '<script>window.history.back();</script>';
            exit;
        }
    }
    // Lấy thông tin từ form
    $user_id = $_SESSION['id'];
    $order_date = date('Y-m-d H:i:s');
    $shipping_fullname = $conn->real_escape_string($_POST['shipping_fullname'] ?? '');
    $shipping_phone = $conn->real_escape_string($_POST['shipping_phone'] ?? '');
    $shipping_address = $conn->real_escape_string($_POST['shipping_address'] ?? '');
    $shipping_city = $conn->real_escape_string($_POST['shipping_city'] ?? '');
    $payment_method = $conn->real_escape_string($_POST['payment-method'] ?? '');
    $order_notes = $conn->real_escape_string($_POST['order_note'] ?? '');
    $total_amount = $subtotal;

    // Xử lý thông tin thanh toán
    $bank_reference = null;
    $card_last4 = null;
    $card_brand = null;

    if ($payment_method === "transfer") {
        $bank_reference = $conn->real_escape_string($_POST['bank_reference'] ?? '');
    } elseif ($payment_method === "card") {
        $card_number = $conn->real_escape_string($_POST['card_number'] ?? '');
        $card_last4 = substr($card_number, -4); // Lấy 4 số cuối của thẻ
        $card_brand = "Visa/MasterCard"; // Bạn có thể thêm logic để xác định loại thẻ
    }

    // Lưu vào bảng checkout - Sử dụng prepared statement để bảo mật hơn
    $sql = "INSERT INTO checkout (
            user_id, 
            order_date, 
            shipping_fullname, 
            shipping_phone, 
            shipping_address, 
            shipping_city, 
            payment_method, 
            bank_reference, 
            card_last4, 
            card_brand, 
            total_amount, 
            order_notes
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        // Nếu người dùng không tích "Different address" (đã disabled, $_POST trả về rỗng)
        if (empty($shipping_fullname) || empty($shipping_phone) || empty($shipping_address) || empty($shipping_city)) {
            $profile_sql   = "SELECT fullname, phone, address, city FROM user WHERE id = ?";
            $profile_stmt  = $conn->prepare($profile_sql);
            $profile_stmt->bind_param("i", $user_id);
            $profile_stmt->execute();
            $profile_res   = $profile_stmt->get_result();
            if ($profile_res->num_rows > 0) {
                $row = $profile_res->fetch_assoc();
                $shipping_fullname = $row['fullname'];
                $shipping_phone    = $row['phone'];
                $shipping_address  = $row['address'];
                $shipping_city     = $row['city'];
            }
            $profile_stmt->close();
            }

    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "isssssssssss", 
        $user_id, $order_date, $shipping_fullname, $shipping_phone,
        $shipping_address, $shipping_city,
        $payment_method, $bank_reference, $card_last4, $card_brand,
        $total_amount, $order_notes
    );
    
    
    if ($stmt->execute()) {
        $order_id = $conn->insert_id; // Lấy ID của đơn hàng vừa được tạo

        // Lưu từng sản phẩm trong giỏ hàng vào bảng checkout_items
        $insert_item_sql = "INSERT INTO checkout_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
        $item_stmt = $conn->prepare($insert_item_sql);
        
        foreach ($cart_items as $item) {
            $product_id = $item['product_id'];
            $quantity = $item['quantity'];
            $price = $item['price'];
            
            $item_stmt->bind_param("iiid", $order_id, $product_id, $quantity, $price);
            $item_stmt->execute();
        }

        // Kiểm tra xem đơn hàng đã có hóa đơn chưa
        $check_invoice = "SELECT invoice_id FROM invoices WHERE order_id = ?";
        $stmt = $conn->prepare($check_invoice);
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            // Chỉ tạo hóa đơn nếu chưa tồn tại
            $payment_status = 'Unpaid'; // Default status
            if ($payment_method === 'Cash') {
                $payment_status = 'Unpaid';
            } elseif ($payment_method === 'Transfer') {
                $payment_status = 'pending_transfer';
            } elseif ($payment_method === 'Card') {
                $payment_status = 'processing';
            }

            $create_invoice = "INSERT INTO invoices (order_id, invoice_number, payment_status, total_amount, invoice_date) 
                                VALUES (?, ?, ?, ?, NOW())";
            $stmt = $conn->prepare($create_invoice);
            $invoice_number = generateInvoiceNumber(); // Hàm tạo số hóa đơn
            $stmt->bind_param("issd", $order_id, $invoice_number, $payment_status, $total_amount);
            $stmt->execute();
        }

        // Xóa giỏ hàng sau khi đặt hàng thành công
        $clear_cart_sql = "DELETE FROM cart WHERE id = ?";
        $clear_stmt     = $conn->prepare($clear_cart_sql);
        $clear_stmt->bind_param("i", $user_id);
        $clear_stmt->execute();
        // Chuyển hướng đến trang cảm ơn
        echo '<script>
            alert("Đặt hàng thành công!");
            window.location.href = "orders.php";
        </script>';
        exit;
    } else {
        echo '<script>alert("Lỗi khi lưu đơn hàng: ' . $conn->error . '");</script>';
    }
}
?>
<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8" />
    <meta name="description" content="Male_Fashion Template" />
    <meta name="keywords" content="Male_Fashion, unica, creative, html" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Male-Fashion | Template</title>

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
    .form-group {
        margin-bottom: 1.2em;
    }

    .error {
        color: red;
        font-size: .85em;
        min-height: 1em;
        margin-top: .25em;
    }

    #transfer-group,
    #card-group {
        display: none;
    }
    </style>
    <style>
    .address-fields input:disabled {
        background-color: #f9f9f9;
        opacity: 0.6;
        cursor: not-allowed;
    }
    </style>
    <style>
    input:required:invalid,
    select:required:invalid {
        border-color: #ff0000;
    }

    input:required:valid,
    select:required:valid {
        border-color: #00ff00;
    }

    .error-message {
        color: red;
        font-size: 12px;
        margin-top: 5px;
        display: none;
    }
    </style>
    <style>
    /* Căn chỉnh các phần tử bên trong checkout__order */
    .checkout__input textarea {
        width: 100%;
        /* Chiếm toàn bộ chiều rộng của khung chứa */
        padding: 10px;
        /* Khoảng cách từ viền đến nội dung */
        border: 1px solid #ddd;
        /* Viền của textarea */
        border-radius: 5px;
        /* Góc bo tròn cho textarea */
        font-size: 14px;
        /* Kích thước chữ */
        height: 40px;
        /* Chiều cao ban đầu của textarea */
        resize: none;
        /* Ẩn mũi tên điều chỉnh kích thước */
        overflow-y: hidden;
        /* Ẩn thanh cuộn dọc */
    }
    </style>
    <style>
    /* Thêm vào phần style trong head */
    .delivery-info {
        background-color: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .delivery-info h5 {
        color: #333;
        font-weight: 600;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
    }

    .delivery-info p {
        margin-bottom: 8px;
        color: #555;
    }

    .delivery-info p strong {
        color: #333;
        min-width: 80px;
        display: inline-block;
    }
    </style>

</head>

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
            <a href="#" class="search-switch"><img src="img/icon/search.png" alt="" /></a>

            <a href="shopping-cart.php"><img src="img/icon/cart.png" alt="" /> </a>
            <div class="price">$0.00</div>
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
                                    <li><a href="./shop-details.php">Shop Details</a></li>
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
    <section class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__text">
                        <h4>Check Out</h4>
                        <div class="breadcrumb__links">
                            <a href="./index.php">Home</a>
                            <a href="./shop.php">Shop</a>
                            <span>Check Out</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Checkout Section Begin -->
    <section class="checkout spad">
        <div class="container">
            <div class="checkout__form">
                <form id="checkout-form" action="checkout.php" method="POST" novalidate>
                    <div class="row">
                        <div class="col-lg-8 col-md-6">
                            <h4>Billing Details</h4>
                            <div class="checkout__input__checkbox">
                            </div>

                            <div class="row">
                                <div class="col-lg-8 col-md-6">
                                    <div class="form-check mb-4">
                                        <input class="form-check-input" type="checkbox" id="diff-address">
                                        <label class="form-check-label" for="diff-address">
                                            Different address?
                                        </label>
                                    </div>

                                    <!-- BLOCK 1: Profile (mặc định show) -->
                                    <div id="profile-block" class="delivery-info">
                                        <h5>Delivery Information</h5>
                                        <p><strong>Full Name:</strong> <?= htmlspecialchars($user_data['fullname']) ?>
                                        </p>
                                        <p><strong>Email:</strong> <?= htmlspecialchars($user_data['email']) ?></p>
                                        <p><strong>Phone:</strong> <?= htmlspecialchars($user_data['phone']) ?></p>
                                        <p><strong>Address:</strong> <?= htmlspecialchars($user_data['address']) ?></p>
                                        <p><strong>City:</strong> <?= htmlspecialchars($user_data['city']) ?></p>
                                    </div>

                                    <!-- BLOCK 2: Form nhập (mặc định ẩn) -->
                                    <div id="form-block" class="p-4 bg-light rounded" style="display: none;">
                                        <h5 class="mb-3">
                                            Delivery Information</h5>
                                        <div class="mb-3">
                                            <label>Full Name*</label>
                                            <input type="text" name="shipping_fullname" class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <label>Email*</label>
                                            <input type="email" name="shipping_email" class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <label>Phone Number*</label>
                                            <input type="text" name="shipping_phone" class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <label>Street Address*</label>
                                            <input type="text" name="shipping_address" class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <select name="shipping_city" class="form-select">
                                                <!-- Đã bỏ readonly -->
                                                <option value="">-- Select city --</option>
                                                <option value="HoChiMinh">Hồ Chí Minh</option>
                                                <option value="Hanoi">Hà Nội</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Cột Your Order giữ nguyên -->
                                <div class="col-lg-4">
                                    <!-- ... -->
                                </div>
                            </div>

                        </div>
                        <div class="col-lg-4 col-md-6 position-sticky" style="top: 20px; align-self: start;">
                            <div class="checkout__order">
                                <h4 class="order__title">Your order</h4>
                                <div class="checkout__order__products">
                                    Product <span>Total</span>
                                </div>
                                <ul class="checkout__total__products">
                                    <?php foreach ($cart_items as $item): ?>
                                    <li>
                                        <?php echo htmlspecialchars($item['name']); ?>
                                        <span>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                                <ul class="checkout__total__all">
                                    <li>Subtotal <span>$<?php echo number_format($subtotal, 2); ?></span></li>
                                    <li>Total <span>$<?php echo number_format($subtotal, 2); ?></span></li>
                                </ul>

                                <!-- Ghi chú cho đơn hàng -->
                                <div class="checkout__input">
                                    <p>Ghi chú cho đơn hàng (Nếu có)</p>
                                    <textarea name="order_note" id="order-note"
                                        placeholder="Nhập ghi chú của bạn tại đây" rows="1"
                                        class="form-control"></textarea>
                                </div>

                                <!-- Payment Methods -->
                                <div class="checkout__payment">
                                    <h4>Payment Methods</h4>
                                    <div class="checkout__input__checkbox">
                                        <label for="payment-method-cash">
                                            Cash Payment
                                            <input type="radio" name="payment-method" value="Cash"
                                                id="payment-method-cash" checked />
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="checkout__input__checkbox">
                                        <label for="payment-method-transfer">
                                            Bank Transfer
                                            <input type="radio" name="payment-method" value="transfer"
                                                id="payment-method-transfer" />
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div id="transfer-group">
                                        <h5>Bank Transfer Details</h5>
                                        <p>Bank Name: XYZ Bank</p>
                                        <p>Account Number: 123456789</p>
                                        <p>SWIFT Code: XYZ123</p>
                                        <div class="form-group">
                                            <label for="bank-reference">Reference Number</label>
                                            <input type="text" name="bank_reference" id="bank-reference"
                                                class="form-control" placeholder="Reference Number">
                                            <div class="error" id="error-bank-reference"></div>
                                        </div>
                                    </div>
                                    <div class="checkout__input__checkbox">
                                        <label for="payment-method-card">
                                            Credit/Debit Card
                                            <input type="radio" name="payment-method" value="card"
                                                id="payment-method-card" />
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div id="card-group">
                                        <div class="form-group">
                                            <label for="card-number">Card Number</label>
                                            <input type="text" name="card_number" id="card-number" class="form-control"
                                                placeholder="1234 5678 9012 3456" maxlength="19">
                                            <div class="error" id="error-card-number"></div>
                                        </div>
                                        <div class="form-group">
                                            <label for="card-holder">Card Holder</label>
                                            <input type="text" name="card_holder" id="card-holder" class="form-control"
                                                placeholder="John Doe">
                                            <div class="error" id="error-card-holder"></div>
                                        </div>
                                        <div class="form-group">
                                            <label for="expiry-date">Expiry Date (MM/YY)</label>
                                            <input type="text" name="expiry_date" id="expiry-date" class="form-control"
                                                placeholder="MM/YY" maxlength="5">
                                            <div class="error" id="error-expiry-date"></div>
                                        </div>
                                        <div class="form-group">
                                            <label for="cvv">CVV</label>
                                            <input type="text" name="cvv" id="cvv" class="form-control"
                                                placeholder="123" maxlength="3">
                                            <div class="error" id="error-cvv"></div>
                                        </div>
                                    </div>

                                    <!-- Di chuyển nút PLACE ORDER xuống đây -->
                                    <button type="submit" class="site-btn place-order-btn">
                                        PLACE ORDER
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <!-- Checkout Section End -->

    <!-- Footer Section Begin -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="footer__about">
                        <div class="footer__logo">
                            <a href="#"><img src="img/footer-logo.png" alt="" /></a>
                        </div>
                        <p>
                            The customer is at the heart of our unique business model, which
                            includes design.
                        </p>
                        <a href="#"><img src="img/payment.png" alt="" /></a>
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
                            <li><a href="#">Return & Exchanges</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 offset-lg-1 col-md-6 col-sm-6">
                    <div class="footer__widget">
                        <h6>NewLetter</h6>
                        <div class="footer__newslatter">
                            <p>
                                Be the first to know about new arrivals, look books, sales &
                                promos!
                            </p>
                            <form action="#">
                                <input type="text" placeholder="Your email" />
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
                            <i class="fa fa-heart-o" aria-hidden="true"></i> by
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
                <input type="text" id="search-input" placeholder="Search here....." />
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
    (function() {
        const form = document.getElementById('checkout-form');
        const pmRadios = document.querySelectorAll('input[name="payment-method"]');
        const transferEl = document.getElementById('transfer-group');
        const cardEl = document.getElementById('card-group');

        const validators = {
            transfer: {
                input: document.getElementById('bank-reference'),
                error: document.getElementById('error-bank-reference'),
                validate: i => i.value.trim() !== '',
                msg: {
                    missing: 'Vui lòng nhập Reference Number.'
                }
            },
            card_number: {
                input: document.getElementById('card-number'),
                error: document.getElementById('error-card-number'),
                validate: i => /^\d{4}\s?\d{4}\s?\d{4}\s?\d{4}$/.test(i.value.trim()),
                msg: {
                    missing: 'Vui lòng nhập số thẻ.',
                    pattern: 'Số thẻ phải 16 chữ số.'
                }
            },
            card_holder: {
                input: document.getElementById('card-holder'),
                error: document.getElementById('error-card-holder'),
                validate: i => i.value.trim() !== '',
                msg: {
                    missing: 'Vui lòng nhập tên trên thẻ.'
                }
            },
            expiry_date: {
                input: document.getElementById('expiry-date'),
                error: document.getElementById('error-expiry-date'),
                validate: i => /^[0-1]\d\/\d{2}$/.test(i.value.trim()),
                msg: {
                    missing: 'Vui lòng nhập ngày hết hạn.',
                    pattern: 'Định dạng MM/YY.'
                }
            },
            cvv: {
                input: document.getElementById('cvv'),
                error: document.getElementById('error-cvv'),
                validate: i => /^\d{3}$/.test(i.value.trim()),
                msg: {
                    missing: 'Vui lòng nhập CVV.',
                    pattern: 'CVV phải 3 chữ số.'
                }
            }
        };

        function updateVisibility() {
            const m = document.querySelector('input[name="payment-method"]:checked').value;
            transferEl.style.display = m === 'transfer' ? 'block' : 'none';
            cardEl.style.display = m === 'card' ? 'block' : 'none';
        }

        function showError(v) {
            const val = v.input.value.trim();
            if (!val) {
                v.error.textContent = v.msg.missing;
                return false;
            }
            if (v.msg.pattern && !v.validate(v.input)) {
                v.error.textContent = v.msg.pattern;
                return false;
            }
            v.error.textContent = '';
            return true;
        }

        form.addEventListener('submit', e => {
            const m = document.querySelector('input[name="payment-method"]:checked').value;
            let ok = true;
            if (m === 'transfer') {
                if (!showError(validators.transfer)) ok = false;
            }
            if (m === 'card') {
                ['card_number', 'card_holder', 'expiry_date', 'cvv'].forEach(k => {
                    if (!showError(validators[k])) ok = false;
                });
            }
            if (!ok) e.preventDefault();
        });

        Object.values(validators).forEach(v => {
            v.input.addEventListener('input', () => {
                if (v.validate(v.input)) v.error.textContent = '';
            });
        });

        pmRadios.forEach(r => r.addEventListener('change', updateVisibility));
        updateVisibility();
    })();
    </script>

    <script>
    // Show/hide payment method fields
    document.addEventListener('DOMContentLoaded', function() {
        const cashRadio = document.getElementById('payment-method-cash');
        const transferRadio = document.getElementById('payment-method-transfer');
        const cardRadio = document.getElementById('payment-method-card');
        const bankTransferForm = document.querySelector('.bank-transfer-form');
        const cardPaymentForm = document.querySelector('.card-payment-form');

        // Initial state
        bankTransferForm.style.display = 'none';
        cardPaymentForm.style.display = 'none';

        // Event listeners
        cashRadio.addEventListener('change', function() {
            bankTransferForm.style.display = 'none';
            cardPaymentForm.style.display = 'none';
        });

        transferRadio.addEventListener('change', function() {
            bankTransferForm.style.display = 'block';
            cardPaymentForm.style.display = 'none';
        });

        cardRadio.addEventListener('change', function() {
            bankTransferForm.style.display = 'none';
            cardPaymentForm.style.display = 'block';
        });

        // Different address checkbox
        const differentAddressCheckbox = document.getElementById('different-address');
        const addressFields = document.querySelectorAll('#different-address-fields input');

        differentAddressCheckbox.addEventListener('change', function() {
            const isChecked = this.checked;
            addressFields.forEach(field => {
                field.disabled = !isChecked;
            });
        });

        // Initially disable address fields (assuming they start disabled)
        addressFields.forEach(field => {
            field.disabled = !differentAddressCheckbox.checked;
        });
    }); <
    /> <
    script >
        document.addEventListener('DOMContentLoaded', function() {
            // Kiểm tra nếu không có session (giả sử dùng AJAX hoặc cookie)
            fetch('check_session.php') // Tạo file này để kiểm tra session
                .then(response => response.json())
                .then(data => {
                    if (!data.loggedIn) {
                        alert('Vui lòng đăng nhập để tiếp tục thanh toán.');
                        window.location.href = 'login.php';
                    }
                });
        });
    </script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        checkLoginStatus();
    });
    </script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const chk = document.getElementById('diff-address');
        const profBlk = document.getElementById('profile-block');
        const formBlk = document.getElementById('form-block');

        chk.addEventListener('change', function() {
            if (chk.checked) {
                profBlk.style.display = 'none';
                formBlk.style.display = '';
            } else {
                profBlk.style.display = '';
                formBlk.style.display = 'none';
            }
        });
    });
    </script>

</body>

</html>