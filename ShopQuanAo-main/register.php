<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'Admin/php/db.php'; // Correct path to db.php in Admin folder

// Create user table if not exists
$create_table_sql = "CREATE TABLE IF NOT EXISTS user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    fullname VARCHAR(100),
    phone VARCHAR(20),
    address TEXT,
    gender VARCHAR(20),
    role ENUM('admin', 'staff', 'customer') DEFAULT 'customer',
    status TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
$conn->query($create_table_sql);



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Debug: Print POST data
    // error_log(print_r($_POST, true));
    
    // Sanitize input data
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone_number']);
    $address = mysqli_real_escape_string($conn, $_POST['address']); // thay vì registerAddress
    $gender = isset($_POST['gender']) ? mysqli_real_escape_string($conn, $_POST['gender']) : '';

    // Validate required fields
    if(empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "Vui lòng điền đầy đủ thông tin!";
    }
    // Validate phone number length
    else if(strlen($phone) > 10) {
        $error = "Vui lòng điền đúng số điện thoại!";
    }
    // Kiểm tra password khớp nhau
    else if($password !== $confirm_password) {
        $error = "Mật khẩu không khớp!";
    } 
    else {
        // Kiểm tra username/email đã tồn tại
        $check_sql = "SELECT * FROM user WHERE username = ? OR email = ?";
        $check_stmt = $conn->prepare($check_sql);
        
        if($check_stmt === false) {
            $error = "Lỗi prepare statement check: " . $conn->error;
        } else {
            $check_stmt->bind_param("ss", $username, $email);
            $check_stmt->execute();
            $result = $check_stmt->get_result();

            if($result->num_rows > 0) {
                $error = "Username hoặc email đã tồn tại!";
            } else {
                // Hash password và lưu user mới
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                
                // Debug: Print SQL query
                // error_log("INSERT INTO user (username, email, password, fullname, phone, address, gender, role, status) VALUES ('$username', '$email', '[HASHED]', '$fullname', '$phone', '$address', '$gender', 'customer', '1')");
                
                $sql = "INSERT INTO user (username, email, password, fullname, phone, address, gender, role, status) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, 'customer', '1')";
                $stmt = $conn->prepare($sql);
                
                if($stmt === false) {
                    $error = "Lỗi prepare statement insert: " . $conn->error;
                } else {
                    $stmt->bind_param("sssssss", 
                        $username, 
                        $email, 
                        $hashed_password, 
                        $fullname, 
                        $phone, 
                        $address, 
                        $gender
                    );
                    
                    if($stmt->execute()) {
                        echo "<script>
                            alert('Đăng ký thành công!');
                            window.location.href = 'login.php';
                        </script>";
                        exit();
                    } else {
                        echo "<script>
                            alert('Đăng ký thất bại: " . addslashes($stmt->error) . "');
                        </script>";
                    }
                }
            }
            $check_stmt->close();
        }
    }
}

// Display error message in form
if(isset($error)) {
    echo "<script>
        alert('" . addslashes($error) . "');
    </script>";
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
    <link rel="stylesheet" href="css/register.css" type="text/css" />
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
                                <a href="#">Sign in</a>
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
                            <li class="active"><a href="./blog.php">Blog</a></li>
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

    <!-- Breadcrumb Section Begin -->

    <!-- Breadcrumb Section End -->

    <div class="col-lg-12 col-md-3">
        <div id="successMessage" style="display: none; color: green; font-weight: bold"></div>
        <div class="register-form">
            <div class="title">Registration</div>
            <div class="content">
                <!-- Registration form -->
                <form method="POST" action="">
                    <div class="user-details">
                        <div class="input-box">
                            <span class="details">Full Name</span>
                            <input type="text" name="fullname" placeholder="Enter your name" required />
                        </div>
                        <div class="input-box">
                            <span class="details">Username</span>
                            <input type="text" name="username" placeholder="Enter your username" required />
                        </div>
                        <div class="input-box">
                            <span class="details">Email</span>
                            <input type="email" name="email" placeholder="Enter your email" required />
                        </div>
                        <div class="input-box">
                            <span class="details">Phone Number</span>
                            <input type="text" name="phone_number" id="phone_number" 
                                   placeholder="Enter your number" 
                                   required />
                            <span id="phone-message" class="message"></span>
                        </div>
                        <div class="input-box">
                            <span class="details">Password</span>
                            <input type="password" name="password" placeholder="Enter your password" required style="width: 95%" />
                            <i class="fa fa-eye" id="togglePassword" style="cursor: pointer"></i>
                        </div>
                        <div class="input-box">
                            <span class="details">Confirm Password</span>
                            <input type="password" name="confirm_password" placeholder="Confirm your password" required style="width: 95%" />
                            <i class="fa fa-eye" id="toggleConfirmPassword" style="cursor: pointer"></i>
                        </div>
                        <div class="input-box">
                            <span class="details">Address</span>
                            <input type="text" name="address" placeholder="Enter your address" required />
                        </div>
                    </div>
                    <div class="gender-details">
                        <input type="radio" name="gender" value="Male" id="dot-1" required />
                        <input type="radio" name="gender" value="Female" id="dot-2" />
                        <input type="radio" name="gender" value="Prefer not to say" id="dot-3" />
                        <span class="gender-title">Gender</span>
                        <div class="category">
                            <label for="dot-1">
                                <span class="dot one"></span>
                                <span class="gender">Male</span>
                            </label>
                            <label for="dot-2">
                                <span class="dot two"></span>
                                <span class="gender">Female</span>
                            </label>
                            <label for="dot-3">
                                <span class="dot three"></span>
                                <span class="gender">Prefer not to say</span>
                            </label>
                        </div>
                    </div>
                    <div class="button">
                        <input type="submit" value="Register" />
                    </div>
                </form>
                <?php if(isset($error)): ?>
                    <div class="message error-message">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <?php if(isset($_GET['message']) && $_GET['message'] == 'register_success'): ?>
                    <div class="message success-message">
                        Đăng ký thành công! Vui lòng đăng nhập.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <!-- About Section Begin -->

    <!-- Client Section End -->

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
    <script>
document.querySelector('form').addEventListener('submit', function(e) {
    const phoneNumber = document.getElementById('phone_number').value;
    
    // Check phone number length
    if (phoneNumber.length > 10) {
        e.preventDefault(); // Prevent form submission
        alert('Vui lòng điền đúng số điện thoại!');
        document.getElementById('phone_number').focus();
        return false;
    }
    
    // Remove any non-numeric characters for server-side validation
    document.getElementById('phone_number').value = phoneNumber.replace(/[^0-9]/g, '');
});
    </script>
</body>

</html>