<!DOCTYPE html>
<html lang="en">

<?php include "includes/head.php" ?>

<?php
$role = $_SESSION['role'] ?? 'guest'; // fallback if not logged in
?>
<body class="index-page <?php echo $role; ?>">

    <?php include "includes/header.php" ?>

    <main class="main">

        <!-- Contact Section -->
        <section id="contact" class="contact section light-background">

            <div class="container" data-aos="fade-up" data-aos-delay="100">

                <div class="row g-4 g-lg-5 justify-content-center">
                    <div class="col-lg-5 col-md-7 mx-auto">
                        <div class="contact-form text-center p-4 shadow rounded" data-aos="fade-up"
                            data-aos-delay="300">
                            <h3 class="mb-3">Create an Account</h3>
                            <p class="text-muted">Fill in your details to register.</p>
                            <?php
                            if (isset($_GET['error'])) {
                                if ($_GET["error"] == "EmailAlreadyExists") {
                                    echo '
                                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                        <b>Email has been taken, select another email!</b>
                                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                        </div>';
                                }
                                if ($_GET["error"] == "emailtaken") {
                                    echo '
                                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                        <b>Email has been taken, select another email!</b>
                                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                        </div>';
                                }
                            }
                            ?>
                            <form action="scripts/signup.php" method="post">
                                <div class="row gy-3">
                                    <div class="col-12">
                                        <input type="text" class="form-control" name="first_name"
                                            placeholder="First Name" required>
                                    </div>

                                    <div class="col-12">
                                        <input type="text" class="form-control" name="last_name" placeholder="Last Name"
                                            required>
                                    </div>

                                    <div class="col-12">
                                        <input type="email" class="form-control" name="email"
                                            placeholder="Email Address" required>
                                    </div>

                                    <div class="col-12">
                                        <input type="password" class="form-control" name="password"
                                            placeholder="Password" required>
                                    </div>

                                    <div class="col-12">
                                        <input type="text" class="form-control" name="phone" placeholder="Phone Number">
                                    </div>

                                    <div class="col-12">
                                        <input type="text" class="form-control" name="address" placeholder="Address">
                                    </div>

                                    <input type="hidden" name="role" value="provider">

                                    <div class="col-12 d-none" id="businessField">
                                        <input type="text" class="form-control" name="business_name"
                                            placeholder="Business Name">
                                    </div>

                                    <div class="col-12 text-center">
                                        <button type="submit" class="btn btn-primary w-100">Sign Up</button>
                                    </div>

                                    <div class="col-12 text-center">
                                        <p class="mt-3 mb-0">Already have an account? <a href="login.php"
                                                class="text-decoration-none">Login</a></p>
                                    </div>
                                </div>
                            </form>
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