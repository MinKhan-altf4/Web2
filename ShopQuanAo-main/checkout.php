<?php
// Nếu sau này cần xử lý PHP, thêm ở đây
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
    .address-fields input:disabled {
        background-color: #f9f9f9;
        opacity: 0.6;
        cursor: not-allowed;
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

            <a href="shopping-cart.php"><img src="img/icon/cart.png" alt="" /> <span>0</span></a>
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

                        <a href="./shopping-cart.php"><img src="img/icon/cart.png" alt="" /> <span>0</span></a>

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
                <form action="#">
                    <div class="row">
                        <div class="col-lg-8 col-md-6">

                            <h6 class="checkout__title">Billing Details</h6>
                            <div class="checkout__input__checkbox">
                                <label for="different-address">
                                    Different address?
                                    <input type="checkbox" id="different-address" />
                                    <span class="checkmark"></span>
                                </label>
                            </div>

                            <div id="different-address-fields-wrapper" class="address-fields">
                                <div class="checkout-section">
                                    <!-- Phần địa chỉ -->
                                    <div id="different-address-fields" class="address-fields">
                                        <div class="checkout-section">
                                            <!-- Phần địa chỉ giao hàng khác -->
                                            <div id="different-address-fields" class="address-fields">
                                                <div class="checkout__input">
                                                    <p>Full Name<span>*</span></p>
                                                    <input type="text" id="other-full-name" placeholder="Full Name"
                                                        disabled />
                                                </div>
                                                <div class="checkout__input">
                                                    <p>Phone Number<span>*</span></p>
                                                    <input type="tel" id="other-phone-number" placeholder="Phone Number"
                                                        disabled />
                                                </div>
                                                <div class="checkout__input">
                                                    <p>Street Address<span>*</span></p>
                                                    <input type="text" id="other-address" placeholder="Street Address"
                                                        disabled />
                                                </div>
                                                <div class="checkout__input">
                                                    <p>City<span>*</span></p>
                                                    <input type="text" id="other-city" placeholder="City" disabled />
                                                </div>
                                                <div class="checkout__input">
                                                    <p>State<span>*</span></p>
                                                    <input type="text" id="other-state" placeholder="State" disabled />
                                                </div>
                                                <div class="checkout__input">
                                                    <p>Postcode / ZIP<span>*</span></p>
                                                    <input type="text" id="other-zip" placeholder="Postcode / ZIP"
                                                        disabled />
                                                </div>

                                            </div>


                                        </div>
                                    </div>
                                </div>




                            </div>




                        </div>

                        <div class="col-lg-4 col-md-6">
                            <div class="checkout__order">
                                <h4 class="order__title">Your order</h4>
                                <div class="checkout__order__products">
                                    Product <span>Total</span>
                                </div>
                                <ul class="checkout__total__products">
                                    <!-- Product details will be dynamically added here -->
                                </ul>
                                <ul class="checkout__total__all">
                                    <li>Subtotal <span id="subtotal">$0.00</span></li>
                                    <li>Total <span id="total">$0.00</span></li>
                                </ul>

                                <!-- Ghi chú cho đơn hàng -->
                                <div class="checkout__input">
                                    <p>Ghi chú cho đơn hàng (Nếu có)</p>
                                    <textarea id="order-note" placeholder="Nhập ghi chú của bạn tại đây" rows="1"
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
                                    <div class="bank-transfer-form" style="display: none">
                                        <h5>Bank Details</h5>
                                        <p>Bank Name: XYZ Bank</p>
                                        <p>Account Number: 123456789</p>
                                        <p>SWIFT Code: XYZ123</p>
                                        <input type="text" id="bank-reference" placeholder="Reference Number" />
                                    </div>
                                    <div class="checkout__input__checkbox">
                                        <label for="payment-method-card">
                                            Credit/Debit Card
                                            <input type="radio" name="payment-method" value="card"
                                                id="payment-method-card" />
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="card-payment-form" style="display: none">
                                        <h5>Card Payment</h5>
                                        <div class="form-group">
                                            <label for="card-number">Card Number</label>
                                            <input type="text" id="card-number" class="form-control"
                                                placeholder="1234 5678 9012 3456" maxlength="19" />
                                        </div>
                                        <div class="form-group">
                                            <label for="card-holder">Card Holder Name</label>
                                            <input type="text" id="card-holder" class="form-control"
                                                placeholder="John Doe" />
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group">
                                                <label for="expiry-date">Expiry Date</label>
                                                <input type="text" id="expiry-date" class="form-control"
                                                    placeholder="MM/YY" maxlength="5" />
                                            </div>
                                            <div class="form-group">
                                                <label for="cvv">CVV</label>
                                                <input type="text" id="cvv" class="form-control" placeholder="123"
                                                    maxlength="3" />
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Di chuyển nút PLACE ORDER xuống đây -->
                                    <button type="button" class="site-btn place-order-btn">
                                        PLACE ORDER
                                    </button>
                                </div>
                            </div>
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
    <script src="js/checkout.js"></script>
    <script>
    window.onload = function() {
        const isLoggedIn = localStorage.getItem("isLoggedIn");

        // Kiểm tra xem người dùng đã đăng nhập chưa
        if (isLoggedIn !== "true") {
            // Nếu chưa đăng nhập, hiển thị thông báo và chuyển hướng đến trang đăng nhập
            alert("Vui lòng đăng nhập để xem checkout.");
            window.location.href = "login.php"; // Chuyển hướng đến trang đăng nhập
        }
    };
    </script>
</body>

</html>