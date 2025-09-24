<!DOCTYPE html>
<html lang="en">

<?php include "includes/head.php" ?>

<body>
    <main>
        <div class="container">

            <section
                class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

                            <div class="d-flex justify-content-center py-4">
                                <a href="index.php" class="logo d-flex align-items-center w-auto">
                                    <span class="d-none d-lg-block">QPS Admin</span>
                                </a>
                            </div><!-- End Logo -->

                            <div class="card mb-3">

                                <div class="card-body">

                                    <div class="pt-4 pb-2">
                                        <h5 class="card-title text-center pb-0 fs-4">Login to Your Admin Account</h5>
                                        <p class="text-center small">Enter your username & password to login</p>
                                    </div>
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
                                    }
                                    ?>
                                    <form action="scripts/login.php" method="post">
                                        <div class="col-12">
                                            <label for="email" class="form-label">Email</label>
                                            <div class="input-group has-validation">
                                                <span class="input-group-text" id="inputGroupPrepend">@</span>
                                                <input type="email" name="email" class="form-control" id="email"
                                                    required>
                                                <div class="invalid-feedback">Please enter your email.</div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <label for="yourPassword" class="form-label">Password</label>
                                            <input type="password" name="password" class="form-control"
                                                id="yourPassword" required>
                                            <div class="invalid-feedback">Please enter your password!</div>
                                        </div>

                                        <div class="col-12">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="remember"
                                                    value="true" id="rememberMe">
                                                <label class="form-check-label" for="rememberMe">Remember me</label>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <button class="btn btn-primary w-100" type="submit">Login</button>
                                        </div>
                                        <div class="col-12">
                                            <p class="small mb-0">Don't have account? <a href="#">Create an account</a>
                                            </p>
                                        </div>
                                    </form>

                                </div>
                            </div>

                            <div class="credits">
                                Designed by <a href="https://casugayportfolio.my.canva.site/" target="_blank"
                                    rel="noopener noreferrer">GLCasugay</a>
                            </div>

                        </div>
                    </div>
                </div>

            </section>

        </div>
    </main>
    <!-- End #main -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <?php include "includes/scripts.php" ?>

</body>

</html>