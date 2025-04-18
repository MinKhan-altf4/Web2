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
            <input type="text" id="searchInput" placeholder="Search..." onkeyup="searchProducts()" />
            <a href="./shopping-cart.php"><img src="img/icon/cart.png" alt="" /> <span class="cart-count">0</span></a>
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
                            <p>Free shipping, 30-day return or refunad guarantee.</p>
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
                        <h4>Shop</h4>
                        <div class="breadcrumb__links">
                            <a href="./index.php">Home</a>
                            <span>Shop</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Shop Section Begin -->
    <section class="shop spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="shop__sidebar">
                        <div class="shop__sidebar__search">
                            <form action="#">
                                <input type="text" id="searchInputHome" placeholder="Search..."
                                    onkeyup="searchProducts()" />
                                <button type="button">
                                    <span class="icon_search"></span>
                                </button>
                            </form>
                        </div>
                        <div class="shop__sidebar__accordion">
                            <div class="accordion" id="accordionExample">
                                <div class="card">
                                    <div class="card-heading">
                                        <a data-toggle="collapse" data-target="#collapseOne">Categories</a>
                                    </div>
                                    <div id="collapseOne" class="collapse show" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <div class="shop__sidebar__categories">
                                                <ul class="nice-scroll">
                                                    <li>
                                                    <li>
                                                        <a href="#" data-category="all"
                                                            onclick="displayProducts(products); highlightLink(this)">All(12)</a>
                                                    </li>

                                                    </li>

                                                    <li>
                                                        <a href="#" data-category="bags"
                                                            onclick="filterByCategory(event)">Bags (3)</a>
                                                    </li>
                                                    <li>
                                                        <a href="#" data-category="clothing"
                                                            onclick="filterByCategory(event)">Clothing (5)</a>
                                                    </li>
                                                    <li>
                                                        <a href="#" data-category="shoes"
                                                            onclick="filterByCategory(event)">Shoes (1)</a>
                                                    </li>
                                                    <li>
                                                        <a href="#" data-category="accessories"
                                                            onclick="filterByCategory(event)">Accessories (3)</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-heading">
                                        <a data-toggle="collapse" data-target="#collapseThree">Filter Price</a>
                                    </div>
                                    <div id="collapseThree" class="collapse show" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <div class="shop__sidebar__price">
                                                <ul>
                                                    <li><a href="#"
                                                            onclick="filterByPrice(0, 50); highlightLink(this)">$0.00 -
                                                            $50.00</a></li>
                                                    <li><a href="#"
                                                            onclick="filterByPrice(50, 100); highlightLink(this)">$50.00
                                                            - $100.00</a></li>
                                                </ul>

                                                <div id="product-list"></div> <!-- Khu vực để hiển thị các sản phẩm -->

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="shop__product__option">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6"></div>
                            <div class="col-lg-6 col-md-6 col-sm-6"></div>
                        </div>
                    </div>

                    <div id="product-container" class="row"></div>

                    <div class="row">
                        <div class="col-lg-12"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Shop Section End -->

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
                <input type="text" id="search-input" name="query" placeholder="Search here....."
                    oninput="searchProducts()" />
            </form>
        </div>
    </div>

    <div class="search-results" id="search-results">
        <div id="searchResultsList"></div>
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
    <script src="js/searchbarbentrai.js"></script>
    <script src="js/thanhtimkiemotren.js"></script>
</body>

</html>

<script>
const productContainer = document.getElementById("product-container");

// Lặp qua mảng products và tạo HTML
products.forEach((product) => {
    // Tạo div chứa sản phẩm
    const productItem = document.createElement("div");
    productItem.classList.add("col-lg-4", "col-md-6", "col-sm-6");

    // Nội dung HTML cho mỗi sản phẩm
    productItem.innerHTML = `
  <div class="product__item">
    <div
      class="product__item__pic set-bg"
      data-setbg="${product.image}"
      style="background-image: url('${product.image}');"
    >
      <ul class="product__hover">
        <li>
          <a href="${product.link}">
            <img src="img/icon/search.png" alt="" />
          </a>
        </li>
      </ul>
    </div>
    <div class="product__item__text">
      <h6>${product.name}</h6>
      <a href="#" class="add-cart" data-id="${product.id}" data-name="${product.name}" data-price="${product.price.replace('$', '')}" data-image="${product.image}">+ Add To Cart</a>
      <h5>${product.price}</h5>
    </div>
  </div>
`;
    // Chèn sản phẩm vào container
    productContainer.appendChild(productItem);
});

// Lặp qua mảng sản phẩm và hiển thị sản phẩm
function displayProducts(productsToDisplay) {
    const productContainer = document.getElementById("product-container");
    productContainer.innerHTML = ""; // Xóa các sản phẩm cũ

    productsToDisplay.forEach((product) => {
        const productItem = document.createElement("div");
        productItem.classList.add("col-lg-4", "col-md-6", "col-sm-6");

        productItem.innerHTML = `
      <div class="product__item">
        <div
          class="product__item__pic set-bg"
          data-setbg="${product.image}"
          style="background-image: url('${product.image}');"
        >
          <ul class="product__hover">
           
            <li>
              <a href="${product.link}">
                <img src="img/icon/search.png" alt="" />
              </a>
            </li>
          </ul>
        </div>
        <div class="product__item__text">
          <h6>${product.name}</h6>
         <a href="#" class="add-cart">+ Add To Cart</a>
          
          <h5>${product.price}</h5>
          
        </div>
      </div>
    `;
        productContainer.appendChild(productItem);
    });
}

// Hiển thị toàn bộ sản phẩm ban đầu
displayProducts(products);

function filterByCategory(event) {
    event.preventDefault();

    // Xóa lớp 'active' khỏi tất cả các mục
    document
        .querySelectorAll(".shop__sidebar__categories a")
        .forEach((item) => {
            item.classList.remove("active");
        });

    // Thêm lớp 'active' vào mục được chọn
    event.target.classList.add("active");

    const category = event.target.getAttribute("data-category");
    const filteredProducts = products.filter(
        (product) => product.category === category
    );

    displayProducts(filteredProducts);
}

// Tìm kiếm sản phẩm theo từ khóa
function searchProducts() {
    const searchInput = document
        .getElementById("searchInputHome")
        .value.toLowerCase();

    const filteredProducts = products.filter((product) => {
        const productName = product.name.toLowerCase();
        const productPrice = parseFloat(product.price.replace("$", "")); // Lấy giá trị số từ chuỗi

        // Kiểm tra nếu từ khóa khớp với tên sản phẩm hoặc giá
        return (
            productName.includes(searchInput) ||
            productPrice.toString().includes(searchInput)
        );
    });

    // Hiển thị lại danh sách sản phẩm đã lọc
    displayProducts(filteredProducts);
}
</script>