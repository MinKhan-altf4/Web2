<?php
require_once 'Admin/php/db.php'; // Kết nối database
session_start();

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

// Lấy thông tin người dùng từ database
$id = $_SESSION['id'];
$sql = "SELECT * FROM user WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    // Nếu không tìm thấy user, chuyển hướng về trang đăng nhập
    header("Location: login.php");
    exit;
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
    <link rel="stylesheet" href="css/login.css" type="text/css" />
    <link rel="stylesheet" href="css/profile.css" type="text/css" />
    <style>
    /* Container của phần Profile */
    .profile {
        margin: 50px auto;
        max-width: 600px;
        padding: 30px;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    }

    /* Tiêu đề của Profile */
    .profile h1 {
        text-align: center;
        font-size: 2.5rem;
        color: #222;
        font-weight: 700;
        margin-bottom: 20px;
    }

    /* Thông tin người dùng */
    .profile-info {
        text-align: left;
        font-size: 1.2rem;
        color: #555;
        line-height: 1.8;
    }

    .profile-info p {
        margin: 10px 0;
    }

    .profile-info strong {
        color: #333;
        font-weight: 600;
    }

    /* Hiệu ứng bo tròn và viền nhẹ */
    .profile-card {
        background-color: #f9f9f9;
        padding: 20px;
        border-radius: 10px;
        border: 1px solid #eee;
    }

    /* Nút hành động */
    .profile-actions {
        margin-top: 20px;
        text-align: center;
    }

    .profile-actions button {
        padding: 10px 20px;
        margin: 5px;
        font-size: 1rem;
        border-radius: 5px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .profile-actions .edit-btn {
        background-color: #007bff;
        color: #fff;
    }

    .profile-actions .edit-btn:hover {
        background-color: #0056b3;
    }

    .profile-actions .logout-btn {
        background-color: #dc3545;
        color: #fff;
    }

    .profile-actions .logout-btn:hover {
        background-color: #a71d2a;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .profile {
            padding: 20px;
            margin: 20px auto;
        }

        .profile h1 {
            font-size: 2rem;
        }

        .profile-info p {
            font-size: 1rem;
        }
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
    <div class="offcanvas__links" id="offcanvasLinks">
      <a href="login.php">Sign in</a>
      <a href="contact.php">SUPPORT</a>
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


                        <a href="./shopping-cart.php"><img src="img/icon/cart.png" alt="" /> </a>
                    </div>
                </div>
            </div>
            <div class="canvas__open"><i class="fa fa-bars"></i></div>
        </div>
    </header>
    <!-- Header Section End -->

    <!-- Breadcrumb Section Begin -->

    <!-- Breadcrumb Section End -->
    <section class="profile">
        <div class="container">
            <h1>Your Profile</h1>
            <div id="userInfo" class="profile-card">
                <!-- Hiển thị thông tin người dùng từ database -->
                <div class="profile-info">
                    <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
                    <p><strong>Full name:</strong> <?php echo htmlspecialchars($user['fullname'] ?? 'Not set'); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                    <p><strong>Phone:</strong> <?php echo htmlspecialchars($user['phone'] ?? 'Not set'); ?></p>
                    <p><strong>Address:</strong> <?php echo htmlspecialchars($user['address'] ?? 'Not set'); ?></p>
                    <p><strong>City:</strong> <?php echo htmlspecialchars($user['city'] ?? 'Not set'); ?></p>
                    <p><strong>Gender:</strong> <?php echo htmlspecialchars($user['gender'] ?? 'Not set'); ?></p>
                </div>

            </div>
    </section>

    <!-- Footer -->

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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        checkLoginStatus();
    });
    </script>
</body>

</html>