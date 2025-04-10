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
            <h1>Service Details</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item"><a href="services.php">Services</a></li>
                    <li class="breadcrumb-item active">View Service</li>
                </ol>
            </nav>
        </div>

        <section class="section dashboard">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Bookings Data Table</h5>
                            <p>View and manage customer bookings in a structured table format.</p>

                            <!-- Table with stripped rows -->
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>Booking ID</th>
                                        <th>Client</th>
                                        <th>Provider</th>
                                        <th>Service</th>
                                        <th>Appointment Date</th>
                                        <th>Total Price</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT b.booking_id, 
                                                   c.first_name AS client_name, 
                                                   p.business_name AS provider_name, 
                                                   s.service_name, 
                                                   b.appointment_date, 
                                                   b.total_price, 
                                                   b.status 
                                            FROM bookings b
                                            LEFT JOIN users c ON b.client_id = c.user_id
                                            LEFT JOIN providers p ON b.provider_id = p.provider_id
                                            LEFT JOIN services s ON b.service_id = s.service_id";

                                    $result = $conn->query($sql);

                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            $status_class = "bg-secondary"; 
                                            $status_text = "Unknown"; 

                                            if ($row['status'] == "1") {
                                                $status_class = "bg-warning";
                                                $status_text = "Pending";
                                            } elseif ($row['status'] == "2") { 
                                                $status_class = "bg-primary";
                                                $status_text = "Accepted";
                                            } elseif ($row['status'] == "3") {
                                                $status_class = "bg-success";
                                                $status_text = "Done";
                                            } elseif ($row['status'] == "4") {
                                                $status_class = "bg-danger";
                                                $status_text = "Declined";
                                            }
                                    ?>
                                    <tr>
                                        <td><?php echo $row['booking_id']; ?></td>
                                        <td><?php echo $row['client_name'] ? $row['client_name'] : "Unknown"; ?></td>
                                        <td><?php echo $row['provider_name'] ? $row['provider_name'] : "Unknown"; ?>
                                        </td>
                                        <td><?php echo $row['service_name']; ?></td>
                                        <td><?php echo $row['appointment_date']; ?></td>
                                        <td><?php echo "$" . number_format($row['total_price'], 2); ?></td>
                                        <td><span
                                                class="badge <?php echo $status_class; ?>"><?php echo $status_text; ?></span>
                                        </td>
                                    </tr>

                                    <?php
                                        }
                                    } else {
                                        echo "<tr><td colspan='7' class='text-center'>No bookings found</td></tr>";
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

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <?php include "includes/scripts.php" ?>

</body>

</html>