<?php
include("Admin/php/db.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    // Sử dụng JOIN để lấy thông tin sản phẩm và loại sản phẩm
    $sql = "SELECT p.*, pt.type_name 
            FROM products p
            JOIN product_types pt ON p.type_id = pt.type_id 
            WHERE p.product_id = ? AND p.is_deleted = 0";
            
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $product = mysqli_fetch_assoc($result);
    
    if (!$product) {
        header("Location: shop.php");
        exit();
    }
} else {
    header("Location: shop.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Male_Fashion Template">
    <meta name="keywords" content="Male_Fashion, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Male-Fashion | Nhóm 5TL</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;600;700;800;900&display=swap"
        rel="stylesheet">

    <!-- Css Styles -->
    <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="css/elegant-icons.css" type="text/css">
    <link rel="stylesheet" href="css/magnific-popup.css" type="text/css">
    <link rel="stylesheet" href="css/nice-select.css" type="text/css">
    <link rel="stylesheet" href="css/owl.carousel.min.css" type="text/css">
    <link rel="stylesheet" href="css/slicknav.min.css" type="text/css">
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <style>
    body,
    html {
        height: 100%;
        /* Đảm bảo chiều cao của body và html chiếm toàn bộ chiều cao cửa sổ */

    }

    .khung {
        display: flex;
        justify-content: center;
        /* Canh giữa theo chiều ngang */


    }

    .buttonn {
        padding: 10px 20px;
        background-color: black;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .buttonn:hover {
        background-color: gray;
    }

    .product__details__pic__main {
        margin-bottom: 30px;
        transition: transform 0.3s ease;
    }

    .product__details__pic__main:hover {
        transform: scale(1.02);
    }

    .product__details__text {
        background-color: #fff;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .product__details__text:hover {
        transform: translateY(-5px);
    }

    .product__details__price h3 {
        font-size: 2.5rem;
        font-weight: 600;
        color: rgb(16, 16, 17);
    }

    .product__details__price {
        text-align: right;
    }

    .badge {
        font-size: 0.9rem;
        padding: 0.5rem 1rem;
        border-radius: 4px;
    }

    .product__details__description {
        color: #636e72;
        line-height: 1.8;
    }

    .btn-primary {
        background-color: rgb(88, 89, 92);
        border: none;
        padding: 15px 30px;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 1px;
    }

    .btn-primary:hover,
    .btn-primary:active,
    .btn-primary:focus {
        background-color: rgb(16, 16, 17) !important;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(16, 16, 17, 0.3);
    }

    /* Remove any conflicting Bootstrap default styles */
    .btn-primary:not(:disabled):not(.disabled):active,
    .btn-primary:not(:disabled):not(.disabled).active {
        background-color: rgb(16, 16, 17) !important;
        border-color: rgb(16, 16, 17) !important;
    }

    .meta-item {
        padding: 15px;
        transition: all 0.3s ease;
    }

    .meta-item:hover {
        transform: translateY(-5px);
    }

    .meta-item i {
        color: #2d3436;
    }

    .meta-item p {
        margin-top: 10px;
        font-size: 0.9rem;
        color: #636e72;
    }

    .product__details__info {
        background-color: #fff;
        border-radius: 8px;
    }

    .product__details__info li {
        font-size: 0.95rem;
        padding: 0.75rem 0;
    }

    .product__details__info li:last-child {
        border-bottom: none;
    }

    .product__details__info .row {
        margin-left: -1rem;
        margin-right: -1rem;
    }

    .product__details__info .col-6 {
        padding: 0 1rem;
    }

    .info-box {
        height: 100%;
        transition: all 0.3s ease;
        border: 1px solid rgba(0, 0, 0, 0.1);
    }

    .info-box:hover {
        transform: translateY(-2px);
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .shop-details {
        background-color: #f8f9fa;
        min-height: 100vh;
        padding: 40px 0;
    }

    .product-title {
        font-size: 2rem;
        font-weight: 700;
        color: #2d3436;
        margin-bottom: 1rem;
    }

    .feature-item {
        padding: 15px;
        transition: all 0.3s ease;
        border-radius: 8px;
    }

    .feature-item:hover {
        background-color: #f8f9fa;
        transform: translateY(-5px);
    }

    .feature-item i {
        color: rgb(16, 16, 17);
    }

    .product__details__description {
        background-color: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
    }

    .product__details__info {
        background-color: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
    }

    .product__details__actions {
        margin-top: 2rem;
    }

    .breadcrumb {
        background-color: transparent;
        padding: 0;
    }

    .breadcrumb-item a {
        color: rgb(16, 16, 17);
        text-decoration: none;
    }

    .breadcrumb-item.active {
        color: rgb(16, 16, 17);
    }

    /* Responsive adjustments */
    @media (max-width: 991px) {
        .product__details__text {
            margin-top: 2rem;
        }
    }

    @media (max-width: 576px) {
        .product-title {
            font-size: 1.5rem;
        }

        .feature-item {
            margin-bottom: 1rem;
        }

        .product__details__info .col-6 {
            flex: 0 0 100%;
            max-width: 100%;
            margin-bottom: 1rem;
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
                                <a href="#" id="userMenu">Sign in</a>
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
                        <a href="./index.php"><img src="img/logo.png" alt=""></a>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <nav class="header__menu mobile-menu">
                        <ul>
                            <li><a href="./index.php">Home</a></li>
                            <li class="active"><a href="./shop.php">Shop</a></li>
                            <li><a href="#">Pages</a>
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

                        <a href="./shopping-cart.php"><img src="img/icon/cart.png" alt=""> </a>
                    </div>
                </div>
            </div>
            <div class="canvas__open"><i class="fa fa-bars"></i></div>
        </div>
    </header>
    <!-- Header Section End -->

    <!-- Shop Details Section Begin -->
    <section class="shop-details py-5">
        <div class="container">
            <!-- Breadcrumb -->
            <div class="row mb-4">
                <div class="col-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="./index.php">Home</a></li>
                            <li class="breadcrumb-item"><a href="./shop.php">Shop</a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo $product['name']; ?></li>
                        </ol>
                    </nav>
                </div>
            </div>

            <!-- Product Details -->
            <div class="row">
                <!-- Product Image Column -->
                <div class="col-lg-5">
                    <div class="product__details__pic__main mb-4">
                        <img src="Admin/img/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>"
                            class="img-fluid rounded shadow-lg w-100">
                    </div>

                    <!-- Product Features -->
                    <div class="product__features p-4 bg-white rounded shadow mb-4">
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="feature-item">
                                    <i class="fa fa-shield fa-2x mb-2 text-primary"></i>
                                    <h6 class="mb-1">Secure Payment</h6>
                                    <small class="text-muted">100% secure payment</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="feature-item">
                                    <i class="fa fa-truck fa-2x mb-2 text-primary"></i>
                                    <h6 class="mb-1">Fast Shipping</h6>
                                    <small class="text-muted">2-3 business days</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="feature-item">
                                    <i class="fa fa-refresh fa-2x mb-2 text-primary"></i>
                                    <h6 class="mb-1">Easy Returns</h6>
                                    <small class="text-muted">30 day returns</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Info Column -->
                <div class="col-lg-7">
                    <div class="product__details__text p-4 bg-white rounded shadow">
                        <div class="d-flex justify-content-between align-items-start mb-4">
                            <h2 class="product-title"><?php echo $product['name']; ?></h2>
                            <div class="product__details__price">
                                <span class="badge bg-danger mb-2">20% OFF</span>
                                <h3 class="mb-0">$<?php echo number_format($product['price'], 2); ?></h3>
                                <small class="text-muted">
                                    <del>$<?php echo number_format($product['price'] * 1.2, 2); ?></del>
                                </small>
                            </div>
                        </div>

                        <!-- Product Description -->
                        <div class="product__details__description mb-4">
                            <h5 class="mb-3">Product Description</h5>
                            <p class="lead text-muted"><?php echo $product['description']; ?></p>
                        </div>

                        <!-- Product Information with equal columns -->
                        <div class="product__details__info mb-4">
                            <h5 class="mb-3">Product Details</h5>
                            <div class="row g-4">
                                <div class="col-6">
                                    <div class="info-box p-3 bg-light rounded">
                                        <ul class="list-unstyled mb-0">
                                            <li class="d-flex justify-content-between py-2 border-bottom">
                                                <span class="text-muted">SKU:</span>
                                                <span class="fw-bold">#<?php echo $product['product_id']; ?></span>
                                            </li>
                                            <li class="d-flex justify-content-between py-2">
                                                <span class="text-muted">Category:</span>
                                                <span class="fw-bold"><?php echo $product['type_name']; ?></span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="info-box p-3 bg-light rounded">
                                        <ul class="list-unstyled mb-0">
                                            <li class="d-flex justify-content-between py-2 border-bottom">
                                                <span class="text-muted">Tags:</span>
                                                <span class="fw-bold"><?php echo $product['tag']; ?></span>
                                            </li>
                                            <li class="d-flex justify-content-between py-2">
                                                <span class="text-muted">Shipping:</span>
                                                <span class="fw-bold">Free</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Add to Cart Button -->
                        <div class="product__details__actions">
                            <button class="btn btn-primary btn-lg w-100 add-cart"
                                data-id="<?php echo $product['product_id']; ?>"
                                data-name="<?php echo $product['name']; ?>"
                                data-price="<?php echo $product['price']; ?>"
                                data-image="Admin/img/<?php echo $product['image']; ?>">
                                <i class="fa fa-shopping-cart me-2"></i>
                                Add To Cart
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Shop Details Section End -->

    <!-- Related Section Begin -->

    <!-- Related Section End -->

    <!-- Footer Section Begin -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="footer__about">
                        <div class="footer__logo">
                            <a href="#"><img src="img/footer-logo.png" alt=""></a>
                        </div>
                        <p>The customer is at the heart of our unique business model, which includes design.</p>
                        <a href="#"><img src="img/payment.png" alt=""></a>
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
                            <p>Be the first to know about new arrivals, look books, sales & promos!</p>
                            <form action="#">
                                <input type="text" placeholder="Your email">
                                <button type="submit"><span class="icon_mail_alt"></span></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="footer__copyright__text">
                        <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                        <p>Copyright ©
                            <script>
                            document.write(new Date().getFullYear());
                            </script>2020
                            All rights reserved | This template is made with <i class="fa fa-heart-o"
                                aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank">Colorlib</a>
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
                <input type="text" id="search-input" placeholder="Search here.....">
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/auth.js"></script>
    <script src="js/cart.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        checkLoginStatus();
    });
    </script>

</body>

</html>