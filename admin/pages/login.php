<!DOCTYPE html>
<html lang="en">

<?php include "includes/head.php" ?>

<body>
    <main class="auth-minimal-wrapper">
        <div class="auth-minimal-inner">
            <div class="minimal-card-wrapper">
                <div class="card mb-4 mt-5 mx-4 mx-sm-0 position-relative">
                    <div class="wd-50 bg-white p-2 rounded-circle shadow-lg position-absolute translate-middle top-0 start-50">
                        <img src="../assets/images/logo-abbr.png" alt="QPS" class="img-fluid">
                    </div>
                    <div class="card-body p-sm-5">
                        <h2 class="fs-20 fw-bolder mb-4">Admin Login</h2>
                        <h4 class="fs-13 fw-bold mb-2">Login to your account</h4>
                        <p class="fs-12 fw-medium text-muted">Enter your credentials to access the Quick Property Services admin panel.</p>

                        <?php
                        if (isset($_GET['error'])) {
                            if ($_GET["error"] == "InvalidPassword") {
                                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <b>Incorrect password. Please double-check your entry before trying again!</b>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>';
                            }
                            if ($_GET["error"] == "UserNotFound") {
                                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <b>User not found. Please check your email or sign up for an account!</b>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>';
                            }
                            if ($_GET["error"] == "AccessDenied") {
                                echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                                    <b>Access denied. Please log in with an admin account.</b>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>';
                            }
                        }
                        ?>

                        <form action="scripts/login.php" method="post" class="w-100 mt-4 pt-2">
                            <div class="mb-4">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" id="email" placeholder="Enter your email" required>
                            </div>
                            <div class="mb-3">
                                <label for="yourPassword" class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" id="yourPassword" placeholder="Enter your password" required>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" name="remember" value="true" id="rememberMe">
                                    <label class="custom-control-label c-pointer" for="rememberMe">Remember Me</label>
                                </div>
                            </div>
                            <div class="mt-5">
                                <button type="submit" class="btn btn-lg btn-primary w-100">Login</button>
                            </div>
                        </form>

                        <div class="mt-5 text-muted text-center">
                            <span class="fs-12">Designed by <a href="https://casugayportfolio.my.canva.site/" target="_blank" rel="noopener noreferrer">GLCasugay</a></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="../assets/vendors/js/vendors.min.js"></script>
    <script src="../assets/vendors/js/bootstrap.min.js"></script>
    <script src="../assets/js/common-init.min.js"></script>
    <script src="../assets/js/theme-customizer-init.min.js"></script>

</body>

</html>
