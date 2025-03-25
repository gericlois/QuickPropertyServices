<html lang="en">
<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'provider') {
    header("Location: login.php?error=AccessDenied");
    exit();
}
include "includes/head.php";
include "../admin/pages/scripts/connection.php";

$provider_id = $_SESSION['user_id'];
$query = "SELECT * FROM bookings b inner join providers p ON b.provider_id = p.provider_id WHERE b.provider_id = ?";
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

                <div class="row">
                    <!-- Provider Info --><div class="col-lg-4">
                        <div class="card p-3 shadow">
                            <h4>Welcome, <?php echo $_SESSION['first_name'] ?? 'Provider'; ?>!</h4>
                            <?php if (!empty($row['business_name'])): ?>
                                <p>Business Name: <?php echo $row['business_name']; ?></p>
                            <?php endif; ?>
                            <p>Email: <?php echo $_SESSION['email'] ?? 'Not Available'; ?></p>
                            <p>Status: <span class="badge bg-success">Active</span></p>
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

                <div class="row mt-4">
                    <!-- Booking Requests -->
                    <div class="row mt-4">
                    <div class="col-lg-12">
                        <div class="card p-3 shadow">
                            <h4>Booking Requests</h4>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Client ID</th>
                                        <th>Service ID</th>
                                        <th>Appointment Date</th>
                                        <th>Status</th>
                                        <th>Total Price</th>
                                        <th>Created At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $result->fetch_assoc()) { ?>
                                        <tr>
                                            <td><?php echo $row['booking_id']; ?></td>
                                            <td><?php echo $row['client_id']; ?></td>
                                            <td><?php echo $row['service_id']; ?></td>
                                            <td><?php echo $row['appointment_date']; ?></td>
                                            <td><?php echo $row['status']; ?></td>
                                            <td><?php echo $row['total_price']; ?></td>
                                            <td><?php echo $row['created_at']; ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                </div>
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
