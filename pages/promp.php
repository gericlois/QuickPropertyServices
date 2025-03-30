<!DOCTYPE html>
<html lang="en">

<?php include "includes/head.php" ?>

<body class="index-page">

    <?php include "includes/header.php" ?>

    <main class="main">

        <!-- Contact Section -->
        <section id="contact" class="contact section light-background">

            <div class="container" data-aos="fade-up" data-aos-delay="100">

                <div class="row g-4 g-lg-5 justify-content-center">
                    <div class="col-lg-6 col-md-7 mx-auto">
                        <div class="contact-form text-center p-4 shadow rounded" data-aos="fade-up"
                            data-aos-delay="300">
                            <?php
                            if (isset($_GET['success'])) {
                                if ($_GET["success"] == "AccountCreated") {
                                    echo '
                                        <h1 class="mb-4">
                                            Account Created Successfully!
                                        </h1>
                                        <h4 class="mb-1">
                                            Welcome to Fixing Techs
                                        </h4>

                                        <p class="mb-3 mb-md-5">
                                        Your account has been successfully created. You can now log in to access your dashboard, explore features, and start using our services. If you need any assistance, feel free to contact our support team.
                                        </p>

                                        <hr>

                                        <div class="hero-buttons">
                                            <a href="login.php" class="btn btn-primary me-0 me-sm-2 mx-1">Login</a>
                                        </div>';
                                }
                            }
                            ?>


                        </div>
                    </div>
                </div>


            </div>

        </section>
        <!-- /Contact Section -->

    </main>

    <?php include "includes/footer.php" ?>

    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <?php include "includes/script.php" ?>

</body>

</html>