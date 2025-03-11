<!DOCTYPE html>
<html lang="en">
<?php
session_start();
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
  header("Location: login.php?error=AccessDenied");
} else {
    include "includes/head.php";
    include "scripts/connection.php";
}?>

<body>

    <!-- ======= Header ======= -->
    <?php include "includes/header.php" ?>
    <!-- End Header -->

    <!-- ======= Sidebar ======= -->
    <?php include "includes/sidebar.php" ?>
    <!-- End Sidebar-->

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Dashboard</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section dashboard">
            <div class="row">
            </div>
        </section>

    </main>
    <!-- End #main -->

    <!-- ======= Footer ======= -->
    <?php include "includes/footer.php" ?>
    <!-- End Footer -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <?php include "includes/scripts.php" ?>

</body>

</html>