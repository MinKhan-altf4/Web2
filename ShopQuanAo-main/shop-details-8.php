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
                                <a href="#" id="userMenu">Sign in</a>
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

    <!-- Shop Details Section Begin -->
    <section class="shop-details">
        <div class="product__details__pic">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="product__details__breadcrumb">
                            <a href="./index.php">Home</a>
                            <a href="./shop.php">Shop</a>
                            <span>Product Details</span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-9">
                        <div class="tab-content" style="height: 100%">
                            <div class="tab-pane active" id="tabs-1" role="tabpanel" style="height: 100%">
                                <div class="product__details__pic__item" style="height: 100%; align-items: center">
                                    <img src="img/product/product-10.jpg" alt="" style="height: 100%; width: 50%" />
                                </div>
                            </div>
                            <div class="tab-pane" id="tabs-2" role="tabpanel">
                                <div class="product__details__pic__item">
                                    <img src="img/shop-details/product-big-3.png" alt="" />
                                </div>
                            </div>
                            <div class="tab-pane" id="tabs-3" role="tabpanel">
                                <div class="product__details__pic__item">
                                    <img src="img/shop-details/product-big.png" alt="" />
                                </div>
                            </div>
                            <div class="tab-pane" id="tabs-4" role="tabpanel">
                                <div class="product__details__pic__item">
                                    <img src="img/shop-details/product-big-4.png" alt="" />
                                    <a href="https://www.youtube.com/watch?v=8PJ3_p7VqHw&list=RD8PJ3_p7VqHw&start_radio=1"
                                        class="video-popup"><i class="fa fa-play"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="product__details__content">
            <div class="container">
                <div class="row d-flex justify-content-center">
                    <div class="col-lg-8">
                        <div class="product__details__text">
                            <div class="set-bg" data-setbg="img/product/product-10.jpg"></div>

                            <h4>Perfume Set</h4>

                            <h3>$43.48 <span>100.00</span></h3>
                            <p>
                                The "perfume set" is a curated collection of fragrance
                                bottles, offering a variety of scents to suit different moods
                                and occasions. This set usually includes three or more
                                miniature bottles, each with a unique fragrance
                                profile—ranging from fresh and floral to warm and woody.
                                Housed in an elegant box, the set is perfect for sampling
                                multiple scents or as a thoughtful gift for someone special.
                                The compact size of each bottle makes it convenient for travel
                                or on-the-go refreshment, allowing the user to explore and
                                enjoy a range of aromas. This perfume set is ideal for those
                                who appreciate variety and the art of fragrance.
                            </p>


                        </div>
                        <div class="khung">
                            <a href="#" class="buttonn" data-price="43.48">Add To Cart</a>
                        </div>
                        <br>
                        <div class="product__details__last__option">
                            <h5><span>Guaranteed Safe Checkout</span></h5>
                            <img src="img/shop-details/details-payment.png" alt="" />
                            <ul>
                                <li><span>SKU:</span> 3812912</li>
                                <li><span>Categories:</span> Clothes</li>
                                <li><span>Tag:</span> Clothes, Skin, Body</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="product__details__tab">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#tabs-5" role="tab">Description</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tabs-6" role="tab">Customer Previews(5)</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tabs-7" role="tab">Additional
                                    information</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tabs-5" role="tabpanel">
                                <div class="product__details__tab__content">
                                    <p class="note">
                                        Nam tempus turpis at metus scelerisque placerat nulla
                                        deumantos solicitud felis. Pellentesque diam dolor,
                                        elementum etos lobortis des mollis ut risus. Sedcus
                                        faucibus an sullamcorper mattis drostique des commodo
                                        pharetras loremos.
                                    </p>
                                    <div class="product__details__tab__content__item">
                                        <h5>Products Infomation</h5>
                                        <p>
                                            The "perfume set" is a curated collection of fragrance
                                            bottles, offering a variety of scents to suit
                                            different moods and occasions. This set usually
                                            includes three or more miniature bottles, each with a
                                            unique fragrance profile—ranging from fresh and floral
                                            to warm and woody. Housed in an elegant box, the set
                                            is perfect for sampling multiple scents or as a
                                            thoughtful gift for someone special. The compact size
                                            of each bottle makes it convenient for travel or
                                            on-the-go refreshment, allowing the user to explore
                                            and enjoy a range of aromas. This perfume set is ideal
                                            for those who appreciate variety and the art of
                                            fragrance.
                                        </p>
                                        <p>
                                            As is the case with any new technology product, the
                                            cost of a Pocket PC was substantial during it’s early
                                            release. For approximately $700.00, consumers could
                                            purchase one of top-of-the-line Pocket PCs in 2003.
                                            These days, customers are finding that prices have
                                            become much more reasonable now that the newness is
                                            wearing off. For approximately $350.00, a new Pocket
                                            PC can now be purchased.
                                        </p>
                                    </div>
                                    <div class="product__details__tab__content__item">
                                        <h5>Material used</h5>
                                        <p>
                                            The Piqué Biker Jacket combines the classic biker
                                            jacket design with piqué fabric, known for its
                                            durability and unique texture. Piqué fabric is
                                            typically composed of approximately 65% cotton, 30%
                                            polyester, and 5% spandex, offering a balance of
                                            softness, breathability, and slight stretch. LEVENTS
                                            This blend ensures the jacket provides comfort and
                                            flexibility while maintaining the structured look
                                            characteristic of biker jackets.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tabs-6" role="tabpanel">
                                <div class="product__details__tab__content">
                                    <div class="product__details__tab__content__item"></div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tabs-7" role="tabpanel">
                                <div class="product__details__tab__content">
                                    <p class="note">
                                        Nam tempus turpis at metus scelerisque placerat nulla
                                        deumantos solicitud felis. Pellentesque diam dolor,
                                        elementum etos lobortis des mollis ut risus. Sedcus
                                        faucibus an sullamcorper mattis drostique des commodo
                                        pharetras loremos.
                                    </p>
                                    <div class="product__details__tab__content__item">
                                        <h5>Products Infomation</h5>
                                        <p>
                                            The Piqué Biker Jacket is a stylish fusion of the
                                            classic biker jacket design and piqué fabric, known
                                            for its durability and unique texture. Typically
                                            crafted from a blend of approximately 65% cotton, 30%
                                            polyester, and 5% spandex, this composition offers a
                                            balance of softness, breathability, and slight
                                            stretch, ensuring comfort and flexibility while
                                            maintaining the structured look characteristic of
                                            biker jackets.
                                        </p>
                                        <p>
                                            As is the case with any new technology product, the
                                            cost of a Pocket PC was substantial during it’s early
                                            release. For approximately $700.00, consumers could
                                            purchase one of top-of-the-line Pocket PCs in 2003.
                                            These days, customers are finding that prices have
                                            become much more reasonable now that the newness is
                                            wearing off. For approximately $350.00, a new Pocket
                                            PC can now be purchased.
                                        </p>
                                    </div>
                                    <div class="product__details__tab__content__item">
                                        <h5>Material used</h5>
                                        <p>
                                            The Piqué Biker Jacket combines the classic biker
                                            jacket design with piqué fabric, known for its
                                            durability and unique texture. Piqué fabric is
                                            typically composed of approximately 65% cotton, 30%
                                            polyester, and 5% spandex, offering a balance of
                                            softness, breathability, and slight stretch. LEVENTS
                                            This blend ensures the jacket provides comfort and
                                            flexibility while maintaining the structured look
                                            characteristic of biker jackets.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>
    <!-- Shop Details Section End -->

    <!-- Related Section Begin -->
    <section class="related spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h3 class="related-title">Related Product</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6 col-sm-6">
                    <div class="product__item">
                        <div class="product__item__pic set-bg" data-setbg="img/product/product-1.jpg">
                            <span class="label">New</span>
                            <ul class="product__hover">


                            </ul>
                        </div>
                        <div class="product__item__text">
                            <h6>Piqué Biker Jacket</h6>
                            <a href="#" class="add-cart" onclick="addToCart()">+ Add To Cart</a>

                            <h5>$67.24</h5>

                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-sm-6">
                    <div class="product__item">
                        <div class="product__item__pic set-bg" data-setbg="img/product/product-2.jpg">
                            <ul class="product__hover">



                            </ul>
                        </div>
                        <div class="product__item__text">
                            <h6>Piqué Biker Jacket</h6>
                            <a href="#" class="add-cart" onclick="addToCart()">+ Add To Cart</a>

                            <h5>$67.24</h5>

                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-sm-6">
                    <div class="product__item sale">
                        <div class="product__item__pic set-bg" data-setbg="img/product/product-3.jpg">
                            <span class="label">Sale</span>
                            <ul class="product__hover">



                            </ul>
                        </div>
                        <div class="product__item__text">
                            <h6>Multi-pocket Chest Bag</h6>
                            <a href="#" class="add-cart" onclick="addToCart()">+ Add To Cart</a>

                            <h5>$43.48</h5>

                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-sm-6">
                    <div class="product__item">
                        <div class="product__item__pic set-bg" data-setbg="img/product/product-4.jpg">
                            <ul class="product__hover">



                            </ul>
                        </div>
                        <div class="product__item__text">
                            <h6>Diagonal Textured Cap</h6>
                            <a href="#" class="add-cart" onclick="addToCart()">+ Add To Cart</a>

                            <h5>$60.9</h5>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Related Section End -->

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

</body>

</html>