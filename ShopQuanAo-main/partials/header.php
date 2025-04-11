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
                            <?php if ($isLoggedIn): ?>
                            <a href="account.php">My Account</a>
                            <a href="logout.php">Logout</a>
                            <?php else: ?>
                            <a href="login.php" id="userMenu">Sign in</a>
                            <?php endif; ?>
                            <a href="contact.php">SPT</a>
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
                    <a href="./index.php"><img src="img/logo.png" alt="Logo" /></a>
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <nav class="header__menu mobile-menu">
                    <ul>
                        <li class="<?php echo $currentPage == 'home' ? 'active' : ''; ?>"><a href="./index.php">Home</a>
                        </li>
                        <li class="<?php echo $currentPage == 'shop' ? 'active' : ''; ?>"><a href="./shop.php">Shop</a>
                        </li>
                        <li>
                            <a href="#">Pages</a>
                            <ul class="dropdown">
                                <li class="<?php echo $currentPage == 'about' ? 'active' : ''; ?>"><a
                                        href="./about.php">About Us</a></li>
                                <li><a href="./shop-details.php">Shop Details</a></li>
                                <li><a href="./shopping-cart.php">Shopping Cart</a></li>
                                <li><a href="./checkout.php">Check Out</a></li>
                                <li><a href="./blog-details.php">Blog Details</a></li>
                            </ul>
                        </li>
                        <li class="<?php echo $currentPage == 'blog' ? 'active' : ''; ?>"><a href="./blog.php">Blog</a>
                        </li>
                        <li class="<?php echo $currentPage == 'contact' ? 'active' : ''; ?>"><a
                                href="./contact.php">Contacts</a></li>
                    </ul>
                </nav>
            </div>
            <div class="col-lg-3 col-md-3">
                <div class="header__nav__option">
                    <a href="./shopping-cart.php"><img src="img/icon/cart.png" alt="Cart" />
                        <span><?php echo $cartItemCount; ?></span></a>
                </div>
            </div>
        </div>
        <div class="canvas__open"><i class="fa fa-bars"></i></div>
    </div>
</header>