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
                            <h3 class="mb-3">Login to Your Account</h3>
                            <p class="text-muted">Enter your credentials to access your account.</p>
                            <?php
                            if (isset($_GET['error'])) {
                                if ($_GET["error"] == "InvalidPassword") {
                                    echo '
                                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                        <b>Incorrect password. Please double-check your entry before trying again!</b>
                                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                        </div>';
                                }
                                if ($_GET["error"] == "UserNotFound") {
                                    echo '
                                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                        <b>User not found. Please check your email or sign up for an account!!</b>
                                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                        </div>';
                                }
                                if ($_GET["error"] == "AccountDeactivated") {
                                    echo '
                                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                        <b>Your account has been deactivated. <hr> Please contact the administrator if you wish to reactivate it. <a href="contact.php"
                                                class="text-decoration-none">Contact Us Here</a></b>
                                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                        </div>';
                                }
                                if ($_GET["error"] == "UnauthorizedAccess") {
                                    echo '
                                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                        <b>Your account has been is not Authorized to Login. <hr> Please contact the administrator for questions. <a href="contact.php"
                                                class="text-decoration-none">Contact Us Here</a></b>
                                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                        </div>';
                                }
                                if ($_GET["error"] == "AccountPendingApproval") {
                                    echo '
                                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                        <b>Your account is still pending approval. Please wait until the admin reviews your credentials and approves your account. <hr> Please contact the administrator for questions. <a href="contact.php" class="text-decoration-none">Contact Us Here</a></b>
                                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                        </div>';
                                }
                            }
                            ?>
                            <form action="scripts/login.php" method="post">
                                <div class="row gy-3">
                                    <div class="col-12">
                                        <input type="email" class="form-control" name="email"
                                            placeholder="Email Address" required>
                                    </div>

                                    <div class="col-12">
                                        <input type="password" class="form-control" name="password"
                                            placeholder="Password" required>
                                    </div>

                                    <div class="col-12 text-end">
                                        <a href="#" class="text-decoration-none small">Forgot Password?</a>
                                    </div>

                                    <div class="col-12 text-center">
                                        <button type="submit" class="btn btn-primary w-100">Login</button>
                                    </div>

                                    <div class="col-12 text-center">
                                        <p class="mt-3 mb-0">Don't have an account? <a href="signup.php"
                                                class="text-decoration-none">Sign Up</a></p>
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