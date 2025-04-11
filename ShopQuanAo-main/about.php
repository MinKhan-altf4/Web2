<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Page configuration
$pageTitle = "About Us | Male-Fashion";
$currentPage = "about";
$cartItemCount = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
$isLoggedIn = isset($_SESSION['user']);
?>
<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8" />
    <meta name="description" content="Male_Fashion Template" />
    <meta name="keywords" content="Male_Fashion, unica, creative, html" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title><?php echo $pageTitle; ?></title>

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

    <!-- Header Section -->
    <?php include 'partials/header.php'; ?>

    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__text">
                        <h4>About Us</h4>
                        <div class="breadcrumb__links">
                            <a href="index.php">Home</a>
                            <span>About Us</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- About Section Begin -->
    <section class="about spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="about__pic">
                        <img src="img/about/about-us.jpg" alt="About Us" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <div class="about__item">
                        <h4>Who We Are ?</h4>
                        <p>
                            Contextual advertising programs sometimes have strict policies
                            that need to be adhered too. Let's take Google as an example.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <div class="about__item">
                        <h4>Who We Do ?</h4>
                        <p>
                            In this digital generation where information can be easily
                            obtained within seconds, business cards still have retained
                            their importance.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <div class="about__item">
                        <h4>Why Choose Us</h4>
                        <p>
                            A two or three storey house is the ideal way to maximise the
                            piece of earth on which our home sits, but for older or infirm
                            people.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- About Section End -->

    <!-- Testimonial Section Begin -->
    <section class="testimonial">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6 p-0">
                    <div class="testimonial__text">
                        <span class="icon_quotations"></span>
                        <p>
                            "Going out after work? Take your butane curling iron with you to
                            the office, heat it up, style your hair before you leave the
                            office and you won't have to make a trip back home."
                        </p>
                        <div class="testimonial__author">
                            <div class="testimonial__author__pic">
                                <img src="img/about/testimonial-author.jpg" alt="Testimonial Author" />
                            </div>
                            <div class="testimonial__author__text">
                                <h5>Augusta Schultz</h5>
                                <p>Fashion Design</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 p-0">
                    <div class="testimonial__pic set-bg" data-setbg="img/about/testimonial-pic.jpg"></div>
                </div>
            </div>
        </div>
    </section>
    <!-- Testimonial Section End -->

    <!-- Counter Section Begin -->
    <section class="counter spad">
        <div class="container">
            <div class="row">
                <?php
                // Counter data - could be pulled from database in a real application
                $counters = [
                    ['number' => 102, 'label' => 'Our<br />Clients'],
                    ['number' => 30, 'label' => 'Total<br />Categories'],
                    ['number' => 102, 'label' => 'In<br />Country'],
                    ['number' => 98, 'suffix' => '%', 'label' => 'Happy<br />Customer']
                ];
                
                foreach ($counters as $counter) {
                    echo '<div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="counter__item">
                            <div class="counter__item__number">
                                <h2 class="cn_num">' . $counter['number'] . '</h2>
                                ' . (isset($counter['suffix']) ? '<strong>' . $counter['suffix'] . '</strong>' : '') . '
                            </div>
                            <span>' . $counter['label'] . '</span>
                        </div>
                    </div>';
                }
                ?>
            </div>
        </div>
    </section>
    <!-- Counter Section End -->

    <!-- Team Section Begin -->
    <section class="team">
        <style>
        .col-lg-2-4 {
            flex: 0 0 20%;
            max-width: 20%;
        }
        </style>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <span>Our Team</span>
                        <h2>Meet Our Team</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <?php
                // Team members data - could be pulled from database
                $teamMembers = [
                    ['image' => 'team-1.jpg', 'name' => 'Thành Nhân', 'role' => 'Login'],
                    ['image' => 'team-2.jpg', 'name' => 'Vũ Gia Huy', 'role' => 'Admin'],
                    ['image' => 'team-3.jpg', 'name' => 'Quang Trung', 'role' => 'Search'],
                    ['image' => 'team-4.jpg', 'name' => 'Ngọc Trúc', 'role' => 'Shopping Cart'],
                    ['image' => 'team-5.jpg', 'name' => 'Minh Khang', 'role' => 'Shopping Cart']
                ];
                
                foreach ($teamMembers as $member) {
                    echo '<div class="col-lg-2-4 col-md-6 col-sm-12">
                        <div class="team__item">
                            <img src="img/about/' . $member['image'] . '" alt="' . $member['name'] . '" />
                            <h4>' . $member['name'] . '</h4>
                            <span>' . $member['role'] . '</span>
                        </div>
                    </div>';
                }
                ?>
            </div>
        </div>
    </section>
    <!-- Team Section End -->

    <!-- Footer Section -->
    <?php include 'partials/footer.php'; ?>

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
</body>

</html>