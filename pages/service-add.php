<!DOCTYPE html>
<html lang="en">
<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'provider') {
  header("Location: login.php?error=AccessDenied");
} else {
    include "includes/head.php";
    include "../admin/pages/scripts/connection.php";
}?>

<body class="index-page">

    <?php include "includes/header.php" ?>

    <main class="main">

        <!-- Add Services Section -->
        <section id="contact" class="contact section light-background">

            <div class="container" data-aos="fade-up" data-aos-delay="100">
                <div class="row g-4 g-lg-5 justify-content-center">
                    <div class="col-lg-5 col-md-7 mx-auto">
                        <div class="contact-form text-center p-4 shadow rounded" data-aos="fade-up"
                            data-aos-delay="300">
                            <h3 class="mb-3">Add a Service</h3>
                            <p class="text-muted">Fill in the details of your service.</p>
                            <?php
                            if (isset($_GET['error'])) {
                                if ($_GET["error"] == "ServiceNameAlreadyExists") {
                                    echo '
                                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                       <b>Service name already exists!</b> Please choose a unique name for your service.
                                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                        </div>';
                                }
                            }
                            ?>

                            <form action="scripts/service-add.php" method="post">
                                <div class="row gy-3">
                                    <div class="col-12">
                                        <input type="text" class="form-control" name="service_name"
                                            placeholder="Service Name" required>
                                    </div>

                                    <div class="col-12">
                                        <textarea class="form-control" name="description" rows="4"
                                            placeholder="Service Description" required></textarea>
                                    </div>

                                    <div class="col-12">
                                        <select class="form-control" name="category_id" required>
                                            <option value="">Select Category</option>
                                            <?php
                                            $query = "SELECT category_id, name FROM category ORDER BY name ASC";
                                            $result = mysqli_query($conn, $query);

                                            while ($row = mysqli_fetch_assoc($result)) {
                                                echo '<option value="' . $row['category_id'] . '">' . htmlspecialchars($row['name']) . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <div class="col-12">
                                        <input type="number" step="0.01" class="form-control" name="base_price"
                                            placeholder="Base Price ($)" required>
                                    </div>

                                    <div class="col-12 text-center">
                                        <button type="submit" class="btn btn-primary w-100">Add Service</button>
                                    </div>

                                    <div class="col-12 text-center">
                                        <p class="mt-3 mb-0"><a href="provider_services.php"
                                                class="text-decoration-none">Back to Services</a></p>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>

        </section>
        <!-- /Add Services Section -->

    </main>

    <?php include "includes/footer.php" ?>

    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <?php include "includes/script.php" ?>

</body>

</html>