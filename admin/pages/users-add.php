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
}?>

<body>

    <?php include "includes/header.php" ?>
    <?php include "includes/sidebar.php" ?>

    <div class="main-content">

        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">Admin User</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item"><a href="users.php">Admin Users</a></li>
                    <li class="breadcrumb-item">Add Admin User</li>
                </ul>
            </div>
        </div>
                            <?php
                            if (isset($_GET['error'])) {
                                
                                if ($_GET["error"] == "EmailAlreadyExists") {
                                    echo '
                                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                            <b>This email already exists.</b> Use a different email address.
                                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                        </div>';
                                }
                               
                            }
                            ?>
        <section class="section">
            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Add Admin User</h5>

                            <form action="scripts/user-add.php" method="POST">
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
                                    <label for="address" class="col-sm-3 col-form-label">Address</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="address" name="address">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="birthday" class="col-sm-3 col-form-label">Birthday</label>
                                    <div class="col-sm-9">
                                        <input type="date" class="form-control" id="birthday" name="birthday">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-9 offset-sm-3">
                                        <button type="submit" class="btn btn-success">Add Admin User</button>
                                        <a href="users.php" class="btn btn-secondary">Cancel</a>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Admin Users Stats</h5>

                            <!-- Polar Area Chart -->
                            <canvas id="polarAreaChart" style="max-height: 400px;"></canvas>
                            <script>
                            document.addEventListener("DOMContentLoaded", () => {
                                new Chart(document.querySelector('#polarAreaChart'), {
                                    type: 'polarArea',
                                    data: {
                                        labels: [
                                            'Red',
                                            'Green',
                                            'Yellow',
                                            'Grey',
                                            'Blue'
                                        ],
                                        datasets: [{
                                            label: 'My First Dataset',
                                            data: [11, 16, 7, 3, 14],
                                            backgroundColor: [
                                                'rgb(255, 99, 132)',
                                                'rgb(75, 192, 192)',
                                                'rgb(255, 205, 86)',
                                                'rgb(201, 203, 207)',
                                                'rgb(54, 162, 235)'
                                            ]
                                        }]
                                    }
                                });
                            });
                            </script>
                            <!-- End Polar Area Chart -->

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
