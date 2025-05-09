<?php

session_start();

if (!isset($_SESSION['id'])) {
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
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

    <style>
    /* Style cho nút Proceed to Checkout */
    .proceed-checkout-btn {
        display: block;
        width: 100%;
        padding: 15px 0;
        background-color: #000;
        color: #fff;
        text-transform: uppercase;
        font-weight: bold;
        text-align: center;
        border: none;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .proceed-checkout-btn:hover {
        background-color: #333;
    }
    </style>
    <style>
    /* Quantity Controls - Updated Design */
    .pro-qty {
        display: inline-flex;
        align-items: center;
        border: 1px solid #e5e5e5;
        border-radius: 25px;
        width: 120px;
        height: 40px;
        background: #fff;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }

    .pro-qty input {
        width: 40px;
        text-align: center;
        border: none;
        background: transparent;
        font-size: 15px;
        font-weight: 600;
        color: #333;
    }

    .pro-qty .qtybtn {
        width: 40px;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 16px;
        color: #333;
        transition: all 0.3s ease;
        background: transparent;
    }

    .pro-qty .dec {
        border-right: 1px solid #eee;
        border-top-left-radius: 25px;
        border-bottom-left-radius: 25px;
    }

    .pro-qty .inc {
        border-left: 1px solid #eee;
        border-top-right-radius: 25px;
        border-bottom-right-radius: 25px;
    }

    .pro-qty .qtybtn:hover {
        background-color: #f8f9fa;
        color: #000;
    }

    .pro-qty .qtybtn:active {
        background-color: #e9ecef;
        transform: translateY(1px);
    }

    /* Delete Button */
    .cart__close {
        text-align: center;
    }

    .cart__close i {
        font-size: 18px;
        padding: 8px;
        border-radius: 50%;
        color: #666;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .cart__close i:hover {
        background-color: #ff0000;
        color: #fff;
        transform: rotate(90deg);
    }

    /* Table Styles */
    .shopping__cart__table {
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .shopping__cart__table table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }

    .shopping__cart__table th,
    .shopping__cart__table td {
        padding: 15px;
        vertical-align: middle;
        border-bottom: 1px solid #e5e5e5;
    }

    /* Column Widths and Alignment */
    .shopping__cart__table th {
        font-weight: 600;
        color: #333;
    }

    .shopping__cart__table th:first-child {
        width: 50%;
        text-align: left;
    }

    .shopping__cart__table th:nth-child(2) {
        width: 25%;
        text-align: center;
    }

    .shopping__cart__table th:nth-child(3) {
        width: 15%;
        text-align: right;
    }

    .shopping__cart__table th:last-child {
        width: 10%;
        text-align: center;
    }

    /* Product Column */
    .product__cart__item {
        display: flex;
        align-items: center;
        gap: 20px;
        padding-right: 15px;
    }

    .product__cart__item__pic img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 4px;
    }

    .product__cart__item__text {
        flex: 1;
    }

    .product__cart__item__text h6 {
        margin-bottom: 5px;
        font-weight: 600;
    }

    /* Quantity Column */
    .quantity__item {
        text-align: center;
    }

    .pro-qty {
        display: inline-flex;
        align-items: center;
        border: 1px solid #e5e5e5;
        border-radius: 5px;
        width: 120px;
        height: 40px;
        margin: 0 auto;
    }

    /* Price Column */
    .cart__price {
        text-align: right;
        font-weight: 600;
        padding-right: 15px;
    }

    /* Delete Button Column */
    .cart__close {
        text-align: center;
        width: 40px;
    }

    .cart__close i {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        border-radius: 50%;
        color: #666;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .cart__close i:hover {
        background-color: #ff0000;
        color: #fff;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .shopping__cart__table {
            padding: 15px;
        }

        .product__cart__item {
            gap: 10px;
        }

        .product__cart__item__pic img {
            width: 60px;
            height: 60px;
        }

        .pro-qty {
            width: 100px;
        }
    }

    /* Responsive Design Updates */
    @media (max-width: 991px) {
        .shopping__cart__table {
            overflow-x: auto;
            display: block;
        }
        
        .cart__total {
            margin-top: 30px;
        }
    }

    @media (max-width: 768px) {
        .shopping__cart__table table {
            min-width: 600px;
        }
        
        .product__cart__item {
            padding-right: 0;
        }
        
        .product__cart__item__pic img {
            width: 60px;
            height: 60px;
        }
        
        .product__cart__item__text h6 {
            font-size: 14px;
        }
        
        .pro-qty {
            width: 100px;
            height: 35px;
        }
        
        .pro-qty input {
            width: 35px;
            font-size: 14px;
        }
        
        .pro-qty .qtybtn {
            width: 32px;
        }
        
        .cart__total {
            padding: 20px;
        }
        
        .cart__total ul li {
            font-size: 14px;
        }
        
        .proceed-checkout-btn {
            padding: 12px 0;
            font-size: 14px;
        }
        
        .continue__btn a {
            padding: 12px 20px;
            font-size: 14px;
        }
    }

    @media (max-width: 576px) {
        .shopping-cart.spad {
            padding-top: 40px;
            padding-bottom: 40px;
        }
        
        .breadcrumb__text h4 {
            font-size: 24px;
        }
        
        .breadcrumb__links a,
        .breadcrumb__links span {
            font-size: 13px;
        }
        
        .shopping__cart__table {
            padding: 10px;
        }
        
        .cart__close i {
            font-size: 16px;
            width: 28px;
            height: 28px;
        }
        
        .cart__total h6 {
            font-size: 16px;
            margin-bottom: 15px;
        }
    }

    /* Cải thiện hiển thị trên thiết bị di động cực nhỏ */
    @media (max-width: 375px) {
        .product__cart__item {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }
        
        .product__cart__item__text {
            padding-left: 0;
        }
        
        .cart__price {
            font-size: 14px;
        }
        
        .continue__btn {
            margin-bottom: 15px;
        }
        
        .continue__btn a {
            width: 100%;
            text-align: center;
        }
    }

    /* Thêm smooth scrolling cho table container */
    .shopping__cart__table {
        -webkit-overflow-scrolling: touch;
        scrollbar-width: thin;
        scrollbar-color: #888 #f1f1f1;
    }

    /* Custom scrollbar styling */
    .shopping__cart__table::-webkit-scrollbar {
        height: 6px;
    }

    .shopping__cart__table::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }

    .shopping__cart__table::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 3px;
    }

    .shopping__cart__table::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    /* Thêm indicator cho scrollable table */
    .shopping__cart__table::after {
        content: '';
        position: absolute;
        right: 0;
        top: 0;
        bottom: 0;
        width: 5px;
        background: linear-gradient(to left, rgba(0,0,0,0.05), transparent);
        pointer-events: none;
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
    <section class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__text">
                        <h4>Shopping Cart</h4>
                        <div class="breadcrumb__links">
                            <a href="./index.php">Home</a>
                            <a href="./shop.php">Shop</a>
                            <span>Shopping Cart</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Shopping Cart Section Begin -->
    <section class="shopping-cart spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="shopping__cart__table">
                        <table>
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="cartItems">
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="continue__btn">
                                <a href="shop.php">Continue Shopping</a>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-lg-4">

                    <div class="cart__total">
                        <h6>Cart total</h6>
                        <ul>
                            <li>Subtotal <span id="subtotal">$ </span></li>
                            <li>Total <span id="total">$ </span></li>
                        </ul>
                        <button id="checkoutBtn" class="proceed-checkout-btn" type="button">
                            Proceed to Checkout
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Shopping Cart Section End -->

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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/auth.js"></script>
    <script src="js/cart.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        checkLoginStatus();
    });
    </script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Improve table scrolling on mobile
        const cartTable = document.querySelector('.shopping__cart__table');
        let isScrolling = false;
        
        cartTable.addEventListener('touchmove', function() {
            if (!isScrolling) {
                isScrolling = true;
                cartTable.style.pointerEvents = 'none';
            }
        });
        
        cartTable.addEventListener('touchend', function() {
            isScrolling = false;
            setTimeout(() => {
                cartTable.style.pointerEvents = 'auto';
            }, 100);
        });
        
        // Fix double tap zoom on mobile
        document.addEventListener('touchend', function(event) {
            if (event.target.classList.contains('qtybtn')) {
                event.preventDefault();
            }
        });
    });
    </script>
</body>

</html>