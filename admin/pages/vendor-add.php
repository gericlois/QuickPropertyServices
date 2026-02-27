<!DOCTYPE html>
<html lang="en">
<?php
session_start();
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php?error=AccessDenied");
    exit();
} else {
    include "includes/head.php";
    include "scripts/connection.php";
} ?>

<body>

    <?php include "includes/header.php" ?>
    <?php include "includes/sidebar.php" ?>

    <div class="main-content">

        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">Add Vendor</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item"><a href="vendors.php">Vendors</a></li>
                    <li class="breadcrumb-item">Add Vendor</li>
                </ul>
            </div>
        </div>
        <?php
        if (isset($_GET['error'])) {
            if ($_GET["error"] == "EmailAlreadyExists") {
                echo '
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <b>The Vendor Account Email already exists.</b> Add another email!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
            }
        }
        ?>
        <section class="section">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Add Vendor</h5>

                            <form action="scripts/vendor-add.php" method="POST">
                                <div class="row mb-3">
                                    <label for="first_name" class="col-sm-3 col-form-label">First Name</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="first_name" name="first_name"
                                            required>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="last_name" class="col-sm-3 col-form-label">Last Name</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="last_name" name="last_name"
                                            required>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="email" class="col-sm-3 col-form-label">Email</label>
                                    <div class="col-sm-9">
                                        <input type="email" class="form-control" id="email" name="email" required>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="password" class="col-sm-3 col-form-label">Password</label>
                                    <div class="col-sm-9">
                                        <input type="password" class="form-control" id="password" name="password"
                                            required>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="phone" class="col-sm-3 col-form-label">Phone</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="phone" name="phone">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="business_name" class="col-sm-3 col-form-label">Business Name</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="business_name" name="business_name"
                                            required>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="specialty" class="col-sm-3 col-form-label">Specialty</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="specialty" name="specialty">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="status" class="col-sm-3 col-form-label">Status</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" id="status" name="status">
                                            <option value="1">Active</option>
                                            <option value="2">Inactive</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-9 offset-sm-3">
                                        <button type="submit" class="btn btn-success">Add Vendor</button>
                                        <a href="vendors.php" class="btn btn-secondary">Cancel</a>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>

    <?php include "includes/footer.php" ?>

    <!-- Vendor JS Files -->
    <?php include "includes/scripts.php" ?>

</body>

</html>
