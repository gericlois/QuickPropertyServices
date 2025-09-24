<!DOCTYPE html>
<html lang="en">
<?php
session_start();
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php?error=AccessDenied");
} else {
    include "includes/head.php";
    include "scripts/connection.php";
}

// Get service ID from URL
$service_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($service_id > 0) {
    // Fetch service and provider details
    $service_query = "SELECT 
                        s.service_name, s.description, s.base_price, s.created_at, 
                        p.business_name, p.profile_picture, 
                        u.first_name, u.last_name, u.email, u.phone, u.address, 
                        u.link_facebook, u.link_instagram, u.link_linkedin 
                      FROM services s
                      JOIN providers p ON s.provider_id = p.provider_id
                      JOIN users u ON p.user_id = u.user_id
                      WHERE s.service_id = ?";

    $stmt = $conn->prepare($service_query);
    $stmt->bind_param("i", $service_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $service = $result->fetch_assoc();
    } else {
        echo "<p>Service not found.</p>";
        exit;
    }

    // Fetch bookings for this service
    $bookings_query = "SELECT 
                        b.booking_id, b.appointment_date, b.status, b.total_price, b.rate, 
                        c.first_name AS client_first_name, c.last_name AS client_last_name, c.email AS client_email
                      FROM bookings b
                      JOIN users c ON b.client_id = c.user_id
                      WHERE b.service_id = ?
                      ORDER BY b.appointment_date DESC";

    $stmt = $conn->prepare($bookings_query);
    $stmt->bind_param("i", $service_id);
    $stmt->execute();
    $bookings_result = $stmt->get_result();
}
?>

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
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section profile">
            <div class="row">
                <!-- Provider Info -->
                <div class="col-xl-4">
                    <div class="card">
                        <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                            <?php if (!empty($service['profile_picture'])) : ?>
                            <img src="<?php echo htmlspecialchars($service['profile_picture']); ?>" alt="Profile"
                                class="rounded-circle">
                            <?php endif; ?>
                            <h2><?php echo htmlspecialchars($service['first_name'] . ' ' . $service['last_name']); ?>
                            </h2>
                            <h3><?php echo htmlspecialchars($service['business_name'] ?: 'Service Provider'); ?></h3>
                            <div class="social-links mt-2">
                                <a href="<?php echo htmlspecialchars($service['link_facebook']); ?>" class="facebook"><i
                                        class="bi bi-facebook"></i></a>
                                <a href="<?php echo htmlspecialchars($service['link_instagram']); ?>"
                                    class="instagram"><i class="bi bi-instagram"></i></a>
                                <a href="<?php echo htmlspecialchars($service['link_linkedin']); ?>" class="linkedin"><i
                                        class="bi bi-linkedin"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Service Details -->
                <div class="col-xl-8">
                    <div class="card">
                        <div class="card-body pt-3">
                            <h5 class="card-title">Service Details</h5>
                            <p><strong>Service Name:</strong> <?php echo htmlspecialchars($service['service_name']); ?>
                            </p>
                            <p><strong>Description:</strong>
                                <?php echo nl2br(htmlspecialchars($service['description'])); ?></p>
                            <p><strong>Price:</strong> $<?php echo htmlspecialchars($service['base_price']); ?></p>
                            <p><strong>Created At:</strong> <?php echo htmlspecialchars($service['created_at']); ?></p>

                            <h5 class="card-title mt-3">Provider Contact</h5>
                            <p><strong>Email:</strong> <?php echo htmlspecialchars($service['email']); ?></p>
                            <p><strong>Phone:</strong> <?php echo htmlspecialchars($service['phone']); ?></p>
                            <p><strong>Address:</strong> <?php echo htmlspecialchars($service['address']); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bookings Card -->
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body pt-3">
                            <h5 class="card-title">Bookings for This Service</h5>
                            <?php if ($bookings_result->num_rows > 0) : ?>
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>Client Name</th>
                                        <th>Appointment Date</th>
                                        <th>Status</th>
                                        <th>Total Price</th>
                                        <th>Rating</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($booking = $bookings_result->fetch_assoc()) : ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($booking['client_first_name'] . ' ' . $booking['client_last_name']); ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($booking['appointment_date']); ?></td>
                                        <td>
                                            <?php
                                                    switch ($booking['status']) {
                                                        case 'P': echo '<span class="badge bg-warning">Pending</span>'; break;
                                                        case 'C': echo '<span class="badge bg-success">Completed</span>'; break;
                                                        case 'X': echo '<span class="badge bg-danger">Cancelled</span>'; break;
                                                        default: echo '<span class="badge bg-secondary">Unknown</span>'; break;
                                                    }
                                                    ?>
                                        </td>
                                        <td>$<?php echo htmlspecialchars($booking['total_price']); ?></td>
                                        <td><?php echo ($booking['rate'] > 0) ? $booking['rate'] . ' / 5' : 'N/A'; ?>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                            <?php else : ?>
                            <p>No bookings found for this service.</p>
                            <?php endif; ?>
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