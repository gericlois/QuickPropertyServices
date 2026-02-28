<!DOCTYPE html>
<html lang="en">

<?php session_start(); ?>

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Register | Quick Property Services</title>

  <!-- Favicons -->
  <link href="../assets/img/logo.jpg" rel="icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="../assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="../assets/css/main.css" rel="stylesheet">
</head>

<body class="index-page">

    <?php include "includes/header.php" ?>

    <main class="main">

        <!-- Homeowner Registration Section -->
        <section id="contact" class="contact section light-background">

            <div class="container" data-aos="fade-up" data-aos-delay="100">

                <div class="row g-4 g-lg-5 justify-content-center">
                    <div class="col-lg-5 col-md-7 mx-auto">
                        <div class="contact-form text-center p-4 shadow rounded" data-aos="fade-up" data-aos-delay="300">
                            <h3 class="mb-3"><i class="bi bi-person-plus me-2"></i>Create Account</h3>
                            <p class="text-muted">Register to submit and track your service requests.</p>

                            <?php if (isset($_GET['error'])): ?>
                                <?php if ($_GET['error'] == 'MissingFields'): ?>
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <b>Please fill in all required fields.</b>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                <?php elseif ($_GET['error'] == 'EmailExists'): ?>
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <b>An account with this email already exists. <a href="homeowner-login.php" class="alert-link">Login instead</a>.</b>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                <?php elseif ($_GET['error'] == 'PasswordMismatch'): ?>
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <b>Passwords do not match.</b>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                <?php elseif ($_GET['error'] == 'PasswordTooShort'): ?>
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <b>Password must be at least 6 characters.</b>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                <?php elseif ($_GET['error'] == 'RegistrationFailed'): ?>
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <b>Something went wrong. Please try again.</b>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>

                            <?php if (isset($_GET['success']) && $_GET['success'] == 'Registered'): ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <b>Your account has been created!</b> Please wait for admin approval before logging in.
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif; ?>

                            <form action="scripts/homeowner-register.php" method="post">
                                <div class="row gy-3">
                                    <div class="col-12">
                                        <input type="text" class="form-control" name="name" placeholder="Full Name" required>
                                    </div>

                                    <div class="col-12">
                                        <input type="email" class="form-control" name="email" placeholder="Email Address" required>
                                    </div>

                                    <div class="col-12">
                                        <input type="text" class="form-control" name="phone" placeholder="Phone Number" required>
                                    </div>

                                    <div class="col-12">
                                        <input type="password" class="form-control" name="password" placeholder="Password (min. 6 characters)" required minlength="6">
                                    </div>

                                    <div class="col-12">
                                        <input type="password" class="form-control" name="confirm_password" placeholder="Confirm Password" required minlength="6">
                                    </div>

                                    <div class="col-12 text-center">
                                        <button type="submit" class="btn btn-primary w-100">Register</button>
                                    </div>

                                    <div class="col-12 text-center">
                                        <p class="mt-2 mb-1"><a href="homeowner-login.php" class="text-decoration-none"><i class="bi bi-box-arrow-in-right me-1"></i>Already have an account? Login</a></p>
                                        <p class="mb-0"><a href="index.php" class="text-decoration-none"><i class="bi bi-arrow-left me-1"></i>Back to Home</a></p>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>

        </section>
        <!-- /Homeowner Registration Section -->

    </main>

    <?php include "includes/footer.php" ?>

    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <?php include "includes/script.php" ?>

</body>

</html>
