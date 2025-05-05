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
    <link rel="stylesheet" href="css/phantrang.css" type="text/css" />


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
            <a href="./shopping-cart.php"><img src="img/icon/cart.png" alt="" /> </a>
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
                <!-- Sidebar -->
                <div class="col-lg-3 col-md-3">
                    <div class="shop__sidebar">
                        <div class="shop__sidebar__search">
                            <form action="#" onsubmit="return false;">
                                <input type="text" id="searchInputHome" placeholder="Search..." autocomplete="off" />
                                <button type="button" onclick="searchProducts()">
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
                                                        <a href="#" data-category="all"
                                                            onclick="filterByCategory('all')">All</a>
                                                    </li>
                                                    <li>
                                                        <a href="#" data-category="Bags"
                                                            onclick="filterByCategory('Bags')">Bags</a>
                                                    </li>
                                                    <li>
                                                        <a href="#" data-category="Clothing"
                                                            onclick="filterByCategory('Clothing')">Clothing</a>
                                                    </li>
                                                    <li>
                                                        <a href="#" data-category="Shoes"
                                                            onclick="filterByCategory('Shoes')">Shoes</a>
                                                    </li>
                                                    <li>
                                                        <a href="#" data-category="Accessories"
                                                            onclick="filterByCategory('Accessories')">Accessories</a>
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
                                                    <li><a href="#" onclick="filterByPrice(0, 50)">$0.00 - $50.00</a>
                                                    </li>
                                                    <li><a href="#" onclick="filterByPrice(50, 100)">$50.00 -
                                                            $100.00</a></li>
                                                    <li><a href="#" onclick="filterByPrice(100, 150)">$100.00 -
                                                            $150.00</a></li>
                                                    <li><a href="#" onclick="filterByPrice(150, 999999)">$150.00+</a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <!-- Custom price range input -->
                                            <div class="price-range-input">
                                                <input type="number" id="minPrice" placeholder="Min Price" min="0">
                                                <input type="number" id="maxPrice" placeholder="Max Price" min="0">
                                                <button onclick="filterByCustomPrice()">Apply</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Grid -->
                <div class="col-lg-9 col-md-9">
                    <div class="row" id="product-container">
                        <!-- Products will be loaded here -->
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

    <script>
    let currentProducts = []; // Thêm biến global để lưu danh sách sản phẩm hiện tại

    // Hàm tìm kiếm sản phẩm
    function searchProducts() {
        const searchQuery = document.getElementById('searchInputHome').value;

        if (!searchQuery.trim()) {
            filterByCategory('all');
            return;
        }

        fetch(`search-products.php?query=${encodeURIComponent(searchQuery)}`)
            .then(response => response.json())
            .then(products => {
                currentProducts = products; // Store filtered products
                displayProducts(products, 1); // Always start at page 1 when searching
            })
            .catch(error => console.error('Error:', error));
    }

    // Thêm debounce để tránh gọi API quá nhiều
    let searchTimeout;
    document.getElementById('searchInputHome').addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(searchProducts, 300);
    });

    // Xử lý khi nhấn nút tìm kiếm
    document.querySelector('.shop__sidebar__search button').addEventListener('click', searchProducts);

    // Xử lý khi nhấn Enter trong ô tìm kiếm
    document.getElementById('searchInputHome').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            searchProducts();
        }
    });

    // Tự động hiện tất cả sản phẩm khi trang được load
    document.addEventListener('DOMContentLoaded', function() {
        filterByCategory('all'); // Gọi hàm để hiển thị tất cả sản phẩm
    });

    function filterByCategory(category) {
        fetch(`get-products.php${category !== 'all' ? '?category=' + category : ''}`)
            .then(response => response.json())
            .then(products => {
                currentProducts = products; // Lưu sản phẩm vào biến global
                displayProducts(products, 1);
            })
            .catch(error => console.error('Error:', error));
    }

    function filterByPrice(minPrice, maxPrice) {
        event.preventDefault();

        const activeCategory = document.querySelector('.shop__sidebar__categories a.active');
        const category = activeCategory ? activeCategory.dataset.category : 'all';

        fetch(`filter-products.php?min=${minPrice}&max=${maxPrice}&category=${category}`)
            .then(response => response.json())
            .then(products => {
                currentProducts = products; // Store filtered products
                displayProducts(products, 1); // Always start at page 1 when filtering
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error filtering products. Please try again.');
            });
    }

    function filterByCustomPrice() {
        const minPrice = document.getElementById('minPrice').value;
        const maxPrice = document.getElementById('maxPrice').value;

        if (!minPrice || !maxPrice) {
            alert('Please enter both minimum and maximum prices');
            return;
        }

        if (parseFloat(minPrice) > parseFloat(maxPrice)) {
            alert('Minimum price cannot be greater than maximum price');
            return;
        }

        // Remove highlight from predefined ranges
        document.querySelectorAll('.shop__sidebar__price a').forEach(link => {
            link.classList.remove('active');
        });

        const activeCategory = document.querySelector('.shop__sidebar__categories a.active');
        const category = activeCategory ? activeCategory.dataset.category : 'all';

        fetch(`filter-products.php?min=${minPrice}&max=${maxPrice}&category=${category}`)
            .then(response => response.json())
            .then(products => {
                currentProducts = products; // Store filtered products
                displayProducts(products, 1); // Always start at page 1 when filtering
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error filtering products. Please try again.');
            });
    }

    function displayProducts(products, page = 1) {
        const productContainer = document.getElementById("product-container");
        productContainer.innerHTML = "";

        // Remove existing pagination
        const existingPagination = document.querySelector('.pagination-wrapper');
        if (existingPagination) {
            existingPagination.remove();
        }

        if (products.length === 0) {
            productContainer.innerHTML = `
            <div class="col-12 text-center">
                <p>No products found.</p>
            </div>`;
            return;
        }

        // Pagination logic
        const productsPerPage = 9;
        const totalPages = Math.ceil(products.length / productsPerPage);
        const start = (page - 1) * productsPerPage;
        const end = start + productsPerPage;
        const paginatedProducts = products.slice(start, end);

        // Display products for current page
        paginatedProducts.forEach(product => {
            const productHTML = `
            <div class="col-lg-4 col-md-6 col-sm-6">
                <div class="product__item">
                    <div class="product__item__pic set-bg" 
                         style="background-image: url('${product.image}');">
                        <ul class="product__hover">
                            <li><a href="shop-details.php?id=${product.id}">
                                <img src="img/icon/search.png" alt="">
                            </a></li>
                        </ul>
                    </div>
                    <div class="product__item__text">
                        <h6>${product.name}</h6>
                        <a href="#" class="add-cart" data-id="${product.id}">+ Add To Cart</a>
                        <h5>${product.price}</h5>
                    </div>
                </div>
            </div>`;
            productContainer.innerHTML += productHTML;
        });

        // Create pagination wrapper
        const paginationWrapper = document.createElement('div');
        paginationWrapper.className = 'pagination-wrapper';

        // Create pagination HTML
        if (totalPages > 1) {
            paginationWrapper.innerHTML = `
            <div class="pagination">
                ${page > 1 ? `<a href="#" onclick="changePage(${page - 1})">&laquo;</a>` : ''}
                ${[...Array(totalPages)].map((_, i) => 
                    `<a href="#" class="${i + 1 === page ? 'active' : ''}" 
                        onclick="changePage(${i + 1})">${i + 1}</a>`
                ).join('')}
                ${page < totalPages ? `<a href="#" onclick="changePage(${page + 1})">&raquo;</a>` : ''}
            </div>`;
        }

        // Add pagination after product container
        productContainer.parentNode.appendChild(paginationWrapper);
    }

    // Sửa lại hàm changePage
    function changePage(page) {
        if (!currentProducts) return; // Kiểm tra nếu không có sản phẩm
        displayProducts(currentProducts, page);

        // Scroll to top of product container
        document.getElementById('product-container').scrollIntoView({
            behavior: 'smooth'
        });
    }
    </script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        checkLoginStatus();
    });
    </script>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


</body>

</html>