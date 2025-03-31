<html lang="en">
<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'provider') {
    header("Location: login.php?error=AccessDenied");
    exit();
}
include "includes/head.php";
include "../admin/pages/scripts/connection.php";

$provider_id = $_SESSION['provider_id'];
$query = "SELECT b.booking_id, 
                 CONCAT(c.first_name, ' ', c.last_name) AS client_name, 
                 s.service_name, 
                 b.appointment_date, 
                 b.status, 
                 b.total_price,
                 c.address, 
                 b.created_at 
          FROM bookings b 
          INNER JOIN providers p ON b.provider_id = p.provider_id 
          INNER JOIN users c ON b.client_id = c.user_id 
          INNER JOIN services s ON b.service_id = s.service_id 
          WHERE b.provider_id = ?
          order by b.status asc";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $provider_id);
$stmt->execute();
$result = $stmt->get_result();
?>


<body class="index-page">
    <?php include "includes/header.php"; ?>

    <main class="main">
        <section id="features" class="features section light-background">

            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>My Dashboard</h2>
                <p>Manage your personal information and account settings</p>
            </div>
            <!-- End Section Title -->
            <div class="container">
                <?php
                if (isset($_GET['success'])) {
                    if ($_GET["success"] == "BookingUpdated") {
                        echo '
                                                        <div class="alert alert-primary alert-dismissible fade show" role="alert">
                                                        <b>Booking Updated. Please double-check your entry for possible mistake!</b>
                                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                        </div>';
                    }
                }
                ?>
            </div>

            <div class="container">

                <div class="row">
                    <!-- Provider Info -->
                    <div class="col-lg-4">
                        <div class="card p-3 shadow">
                            <h4>Welcome, <?php echo $_SESSION['first_name'] ?? 'Provider'; ?>!</h4>
                            <?php if (!empty($row['business_name'])): ?>
                            <p>Business Name: <?php echo $row['business_name']; ?></p>
                            <?php endif; ?>
                            <p>Email: <?php echo $_SESSION['email'] ?? 'Not Available'; ?></p>
                            <p>Phone: <?php echo $_SESSION['phone'] ?? 'Not Available'; ?></p>
                        </div>
                    </div>

                    <!-- Manage Services -->
                    <div class="col-lg-4">
                        <div class="card p-3 shadow">
                            <h4>Manage Your Services</h4>
                            <a href="provider-services.php" class="btn btn-primary">View & Manage Services</a>
                        </div>
                    </div>


                    <!-- Profile Management -->
                    <div class="col-lg-4">
                        <div class="card p-3 shadow">
                            <h4>Manage Profile</h4>
                            <a href="provider-profile.php" class="btn btn-secondary">Edit Profile</a>
                        </div>
                    </div>
                </div>

                <!-- Table 
                            -->

                <hr>
                <div class="row mt-4">
                    <div class="col-lg-12">
                        <h4>Booking Requests</h4>
                    </div>
                </div>

                <div class="row">
                    <?php while ($row = $result->fetch_assoc()) { ?>
                    <div class="col-lg-6 col-md-4 mb-2">
                        <div class="card p-3 shadow">
                            <p>Booking # <?php echo htmlspecialchars($row['booking_id']); ?> |
                                <?php
                    switch ((int)$row['status']) {
                        case 1:
                            echo '<span class="badge bg-warning">Pending</span>';
                            break;
                        case 2:
                            echo '<span class="badge bg-primary">Accepted</span>';
                            break;
                        case 3:
                            echo '<span class="badge bg-success">Done</span>';
                            break;
                        case 4:
                            echo '<span class="badge bg-danger">Declined</span>';
                            break;
                        default:
                            echo '<span class="badge bg-secondary">Unknown</span>';
                    }
                    ?> | <?php echo htmlspecialchars($row['created_at']); ?></p>
                            <h5 class="card-title">Client: <?php echo htmlspecialchars($row['client_name']); ?></h5>
                            <p><strong>Service:</strong> <?php echo htmlspecialchars($row['service_name']); ?></p>
                            <p><strong>Appointment Date:</strong>
                                <?php echo htmlspecialchars($row['appointment_date']); ?></p>
                                <p><strong>Address:</strong>
                                <?php echo htmlspecialchars($row['address']); ?></p>
                            <p><strong>Total Price:</strong> <?php echo htmlspecialchars($row['total_price']); ?></p>

                            <form action="scripts/update-booking.php" method="POST">
                                <input type="hidden" name="booking_id"
                                    value="<?php echo htmlspecialchars($row['booking_id']); ?>">

                                <?php if ((int)$row['status'] === 1) { ?>
                                <button type="submit" name="action" value="accepted"
                                    class="btn btn-primary btn-sm">Accept</button>
                                <button type="submit" name="action" value="declined"
                                    class="btn btn-danger btn-sm">Decline</button>
                                <?php } elseif ((int)$row['status'] === 2) { ?>
                                <button type="submit" name="action" value="done"
                                    class="btn btn-success btn-sm">Done</button>
                                <?php } else { ?>
                                <a href="view-booking.php?booking_id=<?php echo htmlspecialchars($row['booking_id']); ?>"
                                    class="btn btn-info btn-sm">View</a>
                                <?php } ?>
                            </form>
                        </div>
                    </div>
                    <?php } ?>
                </div>



            </div>
        </section>
    </main>

    <?php include "includes/footer.php"; ?>

    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center">
        <i class="bi bi-arrow-up-short"></i>
    </a>

    <?php include "includes/script.php"; ?>
</body>

</html>