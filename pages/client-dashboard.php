<html lang="en">
<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'client') {
    header("Location: login.php?error=AccessDenied");
    exit();
}
include "includes/head.php";
include "../admin/pages/scripts/connection.php";

$client_id = $_SESSION['user_id'];
$query = "SELECT * FROM bookings b 
          INNER JOIN services s ON b.service_id = s.service_id 
          WHERE b.client_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $client_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<body class="index-page">
    <?php include "includes/header.php"; ?>

    <main class="main">
        <section id="dashboard" class="section light-background">

            <div class="container section-title" data-aos="fade-up">
                <h2>Client Dashboard</h2>
                <p>Manage your bookings and personal information</p>
            </div>

            <div class="container">
                <div class="row">
                    <!-- Client Info -->
                    <div class="col-lg-4">
                        <div class="card p-3 shadow">
                            <h4>Welcome, <?php echo $_SESSION['first_name'] ?? 'Client'; ?>!</h4>
                            <p>Email: <?php echo $_SESSION['email'] ?? 'Not Available'; ?></p>
                            <p>Status: <span class="badge bg-success">Active</span></p>
                        </div>
                    </div>

                    <!-- Manage Bookings -->
                    <div class="col-lg-4">
                        <div class="card p-3 shadow">
                            <h4>Manage Your Bookings</h4>
                            <a href="client-bookings.php" class="btn btn-primary">View & Manage Bookings</a>
                        </div>
                    </div>

                    <!-- Profile Management -->
                    <div class="col-lg-4">
                        <div class="card p-3 shadow">
                            <h4>Manage Profile</h4>
                            <a href="client-profile.php" class="btn btn-secondary">Edit Profile</a>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <!-- Booking History -->
                    <div class="col-lg-12">
                        <div class="card p-3 shadow">
                            <h4>My Bookings</h4>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Service Name</th>
                                        <th>Appointment Date</th>
                                        <th>Status</th>
                                        <th>Total Price</th>
                                        <th>Created At</th>
                                        <th>Action</th> <!-- Added Action Column -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $result->fetch_assoc()) { ?>
                                        <tr>
                                            <td><?php echo $row['booking_id']; ?></td>
                                            <td><?php echo htmlspecialchars($row['service_name']); ?></td>
                                            <td><?php echo $row['appointment_date']; ?></td>
                                            <td>
                                                <?php
                                                switch ($row['status']) {
                                                    case '1':
                                                        echo '<span class="badge bg-success">Pending</span>';
                                                        break;
                                                    case '2':
                                                        echo '<span class="badge bg-primary">Accepted</span>';
                                                        break;
                                                    case '3':
                                                        echo '<span class="badge bg-danger">Done</span>';
                                                        break;
                                                    default:
                                                        echo '<span class="badge bg-secondary">Declined</span>';
                                                }
                                                ?>
                                            </td>
                                            <td>$<?php echo number_format($row['total_price'], 2); ?></td>
                                            <td><?php echo $row['created_at']; ?></td>
                                            <td>
                                                <?php if ($row['status'] == '3') { ?>
                                                    <a href="rate-booking.php?booking_id=<?php echo $row['booking_id']; ?>"
                                                        class="btn btn-warning btn-sm">Rate</a>
                                                <?php } else { ?>
                                                    <a href="client-bookservices-view.php?service_id=<?php echo $row['service_id']; ?>"
                                                        class="btn btn-info btn-sm">View</a>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>

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