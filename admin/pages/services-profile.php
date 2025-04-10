<?php
session_start();
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php?error=AccessDenied");
} else {
    include "includes/head.php";
    include "scripts/connection.php";
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid service listing.");
}

$service_id = intval($_GET['id']); // Sanitize input

// Fetch service details along with provider and category
$stmt = $conn->prepare("SELECT s.*, p.business_name, p.status AS provider_status, c.name as category_name
                        FROM services s
                        LEFT JOIN providers p ON s.provider_id = p.provider_id
                        LEFT JOIN category c ON s.category_id = c.category_id
                        WHERE s.service_id = ?");
$stmt->bind_param("i", $service_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Service not found.");
}

$service = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<body>

    <!-- ======= Header ======= -->
    <?php include "includes/header.php" ?>
    <!-- End Header -->

    <!-- ======= Sidebar ======= -->
    <?php include "includes/sidebar.php" ?>
    <!-- End Sidebar-->


    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Service Profile</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item"><a href="services.php">Services</a></li>
                    <li class="breadcrumb-item active">Service Profile</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section profile">
            <div class="row">
                <div class="col-xl-4">

                    <div class="card">
                        <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                            <i class="bi bi-bag-check fs-4"></i>
                            <h2><?php echo htmlspecialchars($service['service_name']); ?></h2>
                            <h3>Category: <?php echo htmlspecialchars($service['category_name']); ?></h3>
                            <h3>Status:
                                <?php
                                if ($service['status'] == 1) {
                                    echo '<span class="badge bg-primary"><i class="bi bi-check-circle me-1"></i> Active</span>';
                                } else {
                                    echo '<span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i> Inactive</span>';
                                }
                                ?>
                            </h3>
                        </div>
                    </div>

                </div>

                <div class="col-xl-8">

                    <div class="card">
                        <div class="card-body pt-3">
                            <!-- Bordered Tabs -->
                            <ul class="nav nav-tabs nav-tabs-bordered">

                                <li class="nav-item">
                                    <button class="nav-link active" data-bs-toggle="tab"
                                        data-bs-target="#service-overview">Overview</button>
                                </li>

                                <li class="nav-item">
                                    <button class="nav-link" data-bs-toggle="tab"
                                        data-bs-target="#service-details">Details</button>
                                </li>

                            </ul>
                            <div class="tab-content pt-2">

                                <div class="tab-pane fade show active service-overview" id="service-overview">

                                    <h5 class="card-title">Service Details</h5>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Service Name</div>
                                        <div class="col-lg-9 col-md-8">
                                            <?php echo htmlspecialchars($service['service_name']); ?>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Provider</div>
                                        <div class="col-lg-9 col-md-8">
                                            <a
                                                href="providers-profile.php?id=<?php echo $service['provider_id']; ?>"><?php echo htmlspecialchars($service['business_name']); ?></a>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Category</div>
                                        <div class="col-lg-9 col-md-8">
                                            <?php echo htmlspecialchars($service['category_name']); ?>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Base Price</div>
                                        <div class="col-lg-9 col-md-8">
                                            $<?php echo htmlspecialchars($service['base_price']); ?></div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Created At</div>
                                        <div class="col-lg-9 col-md-8">
                                            <?php echo htmlspecialchars($service['created_at']); ?>
                                        </div>
                                    </div>

                                </div>

                                <div class="tab-pane fade service-details pt-3" id="service-details">
                                    <h5 class="card-title">Description</h5>
                                    <p><?php echo nl2br(htmlspecialchars($service['description'])); ?></p>
                                </div>

                            </div><!-- End Bordered Tabs -->

                        </div>
                    </div>

                </div>

                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Bookings for Selected Service</h5>
                            <p>View and manage all bookings for the selected service.</p>

                            <!-- Table with stripped rows -->
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>Booking ID</th>
                                        <th>Client Name</th>
                                        <th>Appointment Date</th>
                                        <th>Total Price</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!isset($_GET['id']) || empty($_GET['id'])) {
                                        die("Invalid service selected.");
                                    }

                                    $service_id = intval($_GET['id']); // Sanitize input
                                    
                                    $sql = "SELECT 
                                b.booking_id, 
                                b.appointment_date, 
                                b.total_price, 
                                b.status AS booking_status, 
                                u.first_name AS client_first_name, 
                                u.last_name AS client_last_name 
                            FROM bookings b
                            LEFT JOIN clients c ON b.client_id = c.client_id
                            LEFT JOIN users u ON c.user_id = u.user_id
                            WHERE b.service_id = ?";

                                    $stmt = $conn->prepare($sql);
                                    $stmt->bind_param("i", $service_id);
                                    $stmt->execute();
                                    $result = $stmt->get_result();

                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            $status_class = "bg-secondary";
                                            $status_text = "Unknown"; // Default text in case of unexpected values
                                    
                                            if ($row['booking_status'] == "1") {
                                                $status_class = "bg-primary";
                                                $status_text = "Confirmed";
                                            } elseif ($row['booking_status'] == "2") {
                                                $status_class = "bg-warning";
                                                $status_text = "Pending";
                                            } elseif ($row['booking_status'] == "3") {
                                                $status_class = "bg-danger";
                                                $status_text = "Cancelled";
                                            }
                                            ?>
                                            <tr>
                                                <td><?php echo $row['booking_id']; ?></td>
                                                <td><?php echo $row['client_first_name'] . " " . $row['client_last_name']; ?>
                                                </td>
                                                <td><?php echo htmlspecialchars($row['appointment_date']); ?></td>
                                                <td><?php echo htmlspecialchars($row['total_price']); ?></td>
                                                <td>
                                                    <span class="badge <?php echo $status_class; ?>">
                                                        <?php echo $status_text; ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href='booking-details.php?id=<?php echo $row['booking_id']; ?>'
                                                        class='btn btn-sm btn-success'>View</a>
                                                    <?php if ($row['booking_status'] == "1") { ?>
                                                        <a href='scripts/booking-update.php?id=<?php echo $row['booking_id']; ?>&status=3'
                                                            class='btn btn-sm btn-danger'
                                                            onclick='return confirm("Are you sure you want to cancel this booking?")'>Cancel</a>
                                                    <?php } elseif ($row['booking_status'] == "2") { ?>
                                                        <a href='scripts/booking-update.php?id=<?php echo $row['booking_id']; ?>&status=1'
                                                            class='btn btn-sm btn-primary'
                                                            onclick='return confirm("Are you sure you want to confirm this booking?")'>Confirm</a>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    } else {
                                        echo "<tr><td colspan='6' class='text-center'>No bookings found for this service</td></tr>";
                                    }

                                    $stmt->close();
                                    ?>
                                </tbody>
                            </table>
                            <!-- End Table -->
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