<!DOCTYPE html>
<html lang="en">
<?php
session_start();
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php?error=AccessDenied");
<<<<<<< HEAD
=======
    exit();
>>>>>>> 8e5adf97b8dfe4aaff6b269ca0bc333d2fcd55d1
} else {
    include "includes/head.php";
    include "scripts/connection.php";
} ?>

<body>

<<<<<<< HEAD
    <?php include "includes/header.php" ?>
    <?php include "includes/sidebar.php" ?>
=======
    <!-- ======= Header ======= -->
    <?php include "includes/header.php" ?>
    <!-- End Header -->

    <!-- ======= Sidebar ======= -->
    <?php include "includes/sidebar.php" ?>
    <!-- End Sidebar-->
>>>>>>> 8e5adf97b8dfe4aaff6b269ca0bc333d2fcd55d1

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
<<<<<<< HEAD
                    <b>The Service #' . htmlspecialchars($_GET["service_id"]) . ' has been updated successfully!</b>
=======
                    <b>The Service #00' . htmlspecialchars($_GET["service_id"]) . ' has been successfully updated!</b>
>>>>>>> 8e5adf97b8dfe4aaff6b269ca0bc333d2fcd55d1
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
<<<<<<< HEAD
                            <h5 class="card-title">Services Table</h5>
                            <p>This table provides a full list of registered services and their associated providers.
                                You can sort, search, and manage service details directly from here.</p>

=======
                            <h5 class="card-title">Services Data Table</h5>
                            <p>View and manage registered services in a structured table format.</p>

                            <!-- Table with stripped rows -->
>>>>>>> 8e5adf97b8dfe4aaff6b269ca0bc333d2fcd55d1
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>Service ID</th>
<<<<<<< HEAD
                                        <th>Service Name</th>
                                        <th>Provider</th>
                                        <th>Base Price</th>
                                        <th>Status</th>
                                        <th>Created</th>
=======
                                        <th>Provider</th>
                                        <th>Service Name</th>
                                        <th>Base Price</th>
                                        <th>Status</th>
>>>>>>> 8e5adf97b8dfe4aaff6b269ca0bc333d2fcd55d1
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
<<<<<<< HEAD
                                    $sql = "SELECT s.service_id, s.service_name, s.description, s.base_price, s.status, s.created_at,
                                                    p.business_name,
                                                    u.first_name, u.last_name
                                            FROM services s
                                            LEFT JOIN providers p ON s.provider_id = p.provider_id
                                            LEFT JOIN users u ON p.user_id = u.user_id";
=======
                                    $sql = "SELECT s.service_id, p.business_name, s.service_name, s.category_id, s.base_price, s.status 
                                            FROM services s
                                            LEFT JOIN providers p ON s.provider_id = p.provider_id
                                            WHERE s.status = 1";
>>>>>>> 8e5adf97b8dfe4aaff6b269ca0bc333d2fcd55d1

                                    $result = $conn->query($sql);

                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
<<<<<<< HEAD

                                            $status_class = "bg-secondary";
                                            $status_text = "Unknown";
=======
                                            $status_class = "bg-secondary"; 
                                            $status_text = "Unknown"; 
>>>>>>> 8e5adf97b8dfe4aaff6b269ca0bc333d2fcd55d1

                                            if ($row['status'] == "1") {
                                                $status_class = "bg-primary";
                                                $status_text = "Active";
<<<<<<< HEAD
                                            } elseif ($row['status'] == "2") {
                                                $status_class = "bg-danger";
                                                $status_text = "Inactive";
                                            }

                                            $provider_name = $row['business_name'] ? $row['business_name'] : $row['first_name'] . ' ' . $row['last_name'];
                                            ?>
                                    <tr>
                                        <td><?php echo $row['service_id']; ?></td>
                                        <td><?php echo $row['service_name']; ?></td>
                                        <td>
                                            <a href='providers-profile.php?id=<?php echo $row['provider_id']; ?>'
                                                class='text-primary fw-bold'>
                                                <?php echo htmlspecialchars($provider_name); ?>
                                            </a>
                                        </td>
                                        <td>₱<?php echo number_format($row['base_price'], 2); ?></td>
                                        <td><span
                                                class="badge <?php echo $status_class; ?>"><?php echo $status_text; ?></span>
                                        </td>
                                        <td><?php echo $row['created_at']; ?></td>
                                        <td>
                                            <a href='services-profile.php?id=<?php echo $row['service_id']; ?>'
                                                class='btn btn-sm btn-success'>
                                                View
                                            </a>
                                            <a href='services-edit.php?id=<?php echo $row['service_id']; ?>'
                                                class='btn btn-sm btn-warning'>Edit</a>
                                            <?php if ($row['status'] == "1") { ?>
                                            <a href='scripts/service-update.php?id=<?php echo $row['service_id']; ?>&status=2'
                                                class='btn btn-sm btn-dark'
                                                onclick='return confirm("Deactivate this service?")'>Deactivate</a>
                                            <?php } else { ?>
                                            <a href='scripts/service-update.php?id=<?php echo $row['service_id']; ?>&status=1'
                                                class='btn btn-sm btn-primary'
                                                onclick='return confirm("Activate this service?")'>Activate</a>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <?php
                                        }
                                    } else {
                                        echo "<tr><td colspan='8' class='text-center'>No services found</td></tr>";
=======
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
>>>>>>> 8e5adf97b8dfe4aaff6b269ca0bc333d2fcd55d1
                                    }
                                    ?>
                                </tbody>
                            </table>
<<<<<<< HEAD

                        </div>
                    </div>
=======
                            <!-- End Table with stripped rows -->

                        </div>
                    </div>

>>>>>>> 8e5adf97b8dfe4aaff6b269ca0bc333d2fcd55d1
                </div>
            </div>
        </section>

<<<<<<< HEAD
    </main><!-- End #main -->

    <?php include "includes/footer.php" ?>
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>
=======
    </main>
    <!-- End #main -->

    <!-- ======= Footer ======= -->
    <?php include "includes/footer.php" ?>
    <!-- End Footer -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
>>>>>>> 8e5adf97b8dfe4aaff6b269ca0bc333d2fcd55d1
    <?php include "includes/scripts.php" ?>

</body>

<<<<<<< HEAD
</html>
=======
</html>
>>>>>>> 8e5adf97b8dfe4aaff6b269ca0bc333d2fcd55d1
