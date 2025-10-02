<?php
session_start();

// Check if the user is logged in and if their role is 'provider'
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'provider') {
    header("Location: login.php?error=AccessDenied");
    exit(); // Ensure script stops execution after redirection
}

// If the user is a provider, proceed with page loading

include "../admin/pages/scripts/connection.php";
?>


<?php
$role = $_SESSION['role'] ?? 'guest'; // fallback if not logged in
?>

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Services | Home Repair & Remodeling – Fixing Techs</title>
  <meta name="description" content="Explore the full range of services offered by Fixing Techs: home repairs, remodeling, maintenance, and more. Trusted, insured, family-owned expertise.
">
  <meta name="keywords" content="home repair services, remodeling services, maintenance services, Fixing Techs services, Utah home improvement, property repair, remodeling solutions, insured contractors
">

  <!-- Favicons -->
  <link href="../assets/img/logo.jpg" rel="icon">
  <link href="../assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Inter:wght@100;200;300;400;500;600;700;800;900&family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="../assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="../assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="../assets/css/main.css" rel="stylesheet">
</head>

<body class="index-page <?php echo $role; ?>">

    <?php include "includes/header.php" ?>

    <main class="main">

        <!-- Services Section -->
        <section id="services" class="services section light-background">

            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>Services</h2>
                <p>Necessitatibus eius consequatur ex aliquid fuga eum quidem sint consectetur velit</p>
            </div><!-- End Section Title -->

            <div class="container" data-aos="fade-up" data-aos-delay="100">

                <div class="row g-4">

                    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                        <div class="service-card d-flex">
                            <div class="icon flex-shrink-0">
                                <i class="bi bi-activity"></i>
                            </div>
                            <div>
                                <h3>Nesciunt Mete</h3>
                                <p>Provident nihil minus qui consequatur non omnis maiores. Eos accusantium minus
                                    dolores iure perferendis tempore et consequatur.</p>
                                <a href="service-details.html" class="read-more">Read More <i
                                        class="bi bi-arrow-right"></i></a>
                            </div>
                        </div>
                    </div><!-- End Service Card -->

                    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
                        <div class="service-card d-flex">
                            <div class="icon flex-shrink-0">
                                <i class="bi bi-diagram-3"></i>
                            </div>
                            <div>
                                <h3>Eosle Commodi</h3>
                                <p>Ut autem aut autem non a. Sint sint sit facilis nam iusto sint. Libero corrupti neque
                                    eum hic non ut nesciunt dolorem.</p>
                                <a href="service-details.html" class="read-more">Read More <i
                                        class="bi bi-arrow-right"></i></a>
                            </div>
                        </div>
                    </div><!-- End Service Card -->

                    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="300">
                        <div class="service-card d-flex">
                            <div class="icon flex-shrink-0">
                                <i class="bi bi-easel"></i>
                            </div>
                            <div>
                                <h3>Ledo Markt</h3>
                                <p>Ut excepturi voluptatem nisi sed. Quidem fuga consequatur. Minus ea aut. Vel qui id
                                    voluptas adipisci eos earum corrupti.</p>
                                <a href="service-details.html" class="read-more">Read More <i
                                        class="bi bi-arrow-right"></i></a>
                            </div>
                        </div>
                    </div><!-- End Service Card -->

                    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="400">
                        <div class="service-card d-flex">
                            <div class="icon flex-shrink-0">
                                <i class="bi bi-clipboard-data"></i>
                            </div>
                            <div>
                                <h3>Asperiores Commodit</h3>
                                <p>Non et temporibus minus omnis sed dolor esse consequatur. Cupiditate sed error ea
                                    fuga sit provident adipisci neque.</p>
                                <a href="service-details.html" class="read-more">Read More <i
                                        class="bi bi-arrow-right"></i></a>
                            </div>
                        </div>
                    </div><!-- End Service Card -->

                </div>

            </div>

        </section><!-- /Services Section -->

        <!-- Pricing Section -->
        <section id="pricing" class="pricing section light-background">

            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>Pricing</h2>
                <p>Necessitatibus eius consequatur ex aliquid fuga eum quidem sint consectetur velit</p>
            </div><!-- End Section Title -->

            <div class="container" data-aos="fade-up" data-aos-delay="100">

                <div class="row g-4 justify-content-center">

                    <!-- Basic Plan -->
                    <div class="col-lg-3" data-aos="fade-up" data-aos-delay="100">
                        <div class="pricing-card">
                            <h3>Basic Plan</h3>
                            <div class="price">
                                <span class="currency">$</span>
                                <span class="amount">9.9</span>
                                <span class="period">/ month</span>
                            </div>
                            <p class="description">Sed ut perspiciatis unde omnis iste natus error sit voluptatem
                                accusantium doloremque laudantium totam.</p>

                            <h4>Featured Included:</h4>
                            <ul class="features-list">
                                <li>
                                    <i class="bi bi-check-circle-fill"></i>
                                    Duis aute irure dolor
                                </li>
                                <li>
                                    <i class="bi bi-check-circle-fill"></i>
                                    Excepteur sint occaecat
                                </li>
                                <li>
                                    <i class="bi bi-check-circle-fill"></i>
                                    Nemo enim ipsam voluptatem
                                </li>
                            </ul>

                            <a href="#" class="btn btn-primary">
                                Buy Now
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Standard Plan -->
                    <div class="col-lg-3" data-aos="fade-up" data-aos-delay="200">
                        <div class="pricing-card popular">
                            <div class="popular-badge">Most Popular</div>
                            <h3>Standard Plan</h3>
                            <div class="price">
                                <span class="currency">$</span>
                                <span class="amount">19.9</span>
                                <span class="period">/ month</span>
                            </div>
                            <p class="description">At vero eos et accusamus et iusto odio dignissimos ducimus qui
                                blanditiis praesentium voluptatum.</p>

                            <h4>Featured Included:</h4>
                            <ul class="features-list">
                                <li>
                                    <i class="bi bi-check-circle-fill"></i>
                                    Lorem ipsum dolor sit amet
                                </li>
                                <li>
                                    <i class="bi bi-check-circle-fill"></i>
                                    Consectetur adipiscing elit
                                </li>
                                <li>
                                    <i class="bi bi-check-circle-fill"></i>
                                    Sed do eiusmod tempor
                                </li>
                                <li>
                                    <i class="bi bi-check-circle-fill"></i>
                                    Ut labore et dolore magna
                                </li>
                            </ul>

                            <a href="#" class="btn btn-light">
                                Buy Now
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Premium Plan -->
                    <div class="col-lg-3" data-aos="fade-up" data-aos-delay="300">
                        <div class="pricing-card">
                            <h3>Premium Plan</h3>
                            <div class="price">
                                <span class="currency">$</span>
                                <span class="amount">39.9</span>
                                <span class="period">/ month</span>
                            </div>
                            <p class="description">Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse
                                quam nihil molestiae.</p>

                            <h4>Featured Included:</h4>
                            <ul class="features-list">
                                <li>
                                    <i class="bi bi-check-circle-fill"></i>
                                    Temporibus autem quibusdam
                                </li>
                                <li>
                                    <i class="bi bi-check-circle-fill"></i>
                                    Saepe eveniet ut et voluptates
                                </li>
                                <li>
                                    <i class="bi bi-check-circle-fill"></i>
                                    Nam libero tempore soluta
                                </li>
                                <li>
                                    <i class="bi bi-check-circle-fill"></i>
                                    Cumque nihil impedit quo
                                </li>
                                <li>
                                    <i class="bi bi-check-circle-fill"></i>
                                    Maxime placeat facere possimus
                                </li>
                            </ul>

                            <a href="#" class="btn btn-primary">
                                Buy Now
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Premium Plan -->
                    <div class="col-lg-3" data-aos="fade-up" data-aos-delay="300">
                        <div class="pricing-card">
                            <h3>Premium Plan</h3>
                            <div class="price">
                                <span class="currency">$</span>
                                <span class="amount">39.9</span>
                                <span class="period">/ month</span>
                            </div>
                            <p class="description">Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse
                                quam nihil molestiae.</p>

                            <h4>Featured Included:</h4>
                            <ul class="features-list">
                                <li>
                                    <i class="bi bi-check-circle-fill"></i>
                                    Temporibus autem quibusdam
                                </li>
                                <li>
                                    <i class="bi bi-check-circle-fill"></i>
                                    Saepe eveniet ut et voluptates
                                </li>
                                <li>
                                    <i class="bi bi-check-circle-fill"></i>
                                    Nam libero tempore soluta
                                </li>
                                <li>
                                    <i class="bi bi-check-circle-fill"></i>
                                    Cumque nihil impedit quo
                                </li>
                                <li>
                                    <i class="bi bi-check-circle-fill"></i>
                                    Maxime placeat facere possimus
                                </li>
                            </ul>

                            <a href="#" class="btn btn-primary">
                                Buy Now
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>

                </div>

            </div>

        </section><!-- /Pricing Section -->

    </main>

    <?php include "includes/footer.php" ?>

    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <?php include "includes/script.php" ?>

</body>

</html>