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

    <!-- ======= Header ======= -->
    <?php include "includes/header.php" ?>
    <!-- End Header -->

    <!-- ======= Sidebar ======= -->
    <?php include "includes/sidebar.php" ?>
    <!-- End Sidebar-->

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Services</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item active">Services</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <?php
        if (isset($_GET['success'])) {
            if ($_GET["success"] == "ServiceUpdated") {
                echo '
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <b>The Service #00' . htmlspecialchars($_GET["service_id"]) . ' has been successfully updated!</b>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
            }
        }
        ?>

        <section class="section dashboard">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Services Data Table</h5>
                            <p>View and manage registered services in a structured table format.</p>

                            <!-- Table with stripped rows -->
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>Service ID</th>
                                        <th>Provider</th>
                                        <th>Service Name</th>
                                        <th>Base Price</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT s.service_id, p.business_name, s.service_name, s.category_id, s.base_price, s.status 
                                            FROM services s
                                            LEFT JOIN providers p ON s.provider_id = p.provider_id
                                            WHERE s.status = 1";

                                    $result = $conn->query($sql);

                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            $status_class = "bg-secondary"; 
                                            $status_text = "Unknown"; 

                                            if ($row['status'] == "1") {
                                                $status_class = "bg-primary";
                                                $status_text = "Active";
                                            } elseif ($row['status'] == "2") { 
                                                $status_class = "bg-danger";
                                                $status_text = "Inactive";
                                            } elseif ($row['status'] == "3") {
                                                $status_class = "bg-warning";
                                                $status_text = "Pending";
                                            }
                                    ?>
                                    <tr>
                                        <td><?php echo $row['service_id']; ?></td>
                                        <td><?php echo $row['business_name'] ? $row['business_name'] : "Unknown"; ?></td>
                                        <td><?php echo $row['service_name']; ?></td>
                                        <td><?php echo "$" . number_format($row['base_price'], 2); ?></td>
                                        <td><span class="badge <?php echo $status_class; ?>"><?php echo $status_text; ?></span></td>
                                        <td>
                                            <a href='services-view.php?id=<?php echo $row['service_id']; ?>' class='btn btn-sm btn-success'>View</a>
                                        </td>
                                    </tr>

                                    <?php
                                        }
                                    } else {
                                        echo "<tr><td colspan='7' class='text-center'>No services found</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                            <!-- End Table with stripped rows -->

                        </div>
                    </div>

                </div>
            </div>
        </section>

    </main>
    <!-- End #main -->

    <!-- ======= Footer ======= -->
    <?php include "includes/footer.php" ?>
    <!-- End Footer -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <?php include "includes/scripts.php" ?>

</body>

</html>
