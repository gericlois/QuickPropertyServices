<html lang="en">
<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'client') {
    header("Location: login.php?error=AccessDenied");
    exit();
}

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

<?php
$role = $_SESSION['role'] ?? 'guest'; // fallback if not logged in
?>

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Client Dashboard | Track Your Service – Fixing Techs</title>
  <meta name="description" content="Access your Fixing Techs client dashboard to view service status, request updates, and manage account securely. Stay informed every step of the way.">
  <meta name="keywords" content="Access your Fixing Techs client dashboard to view service status, request updates, and manage account securely. Stay informed every step of the way.">

  <!-- Favicons -->
  <link href="../assets/img/logo.jpg" rel="icon">
  <link href="../assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Inter:wght@100;200;300;400;500;600;700;800;900&family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="../assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="../assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="../assets/css/main.css" rel="stylesheet">
</head>

<body class="index-page <?php echo $role; ?>">
    <?php include "includes/header.php"; ?>

    <main class="main">

        <section id="features" class="features section light-background">

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
                <hr>
                <div class="row mt-4">
                    <div class="col-lg-12">
                        <h4>My Bookings</h4>
                    </div>

                    <?php while ($row = $result->fetch_assoc()) { ?>
                    <div class="col-md-6 col-lg-6">
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
                            <h5 class="card-title"><?php echo htmlspecialchars($row['service_name']); ?></h5>
                            <p><strong>Total Price:</strong> $<?php echo number_format($row['total_price'], 2); ?></p>
                            <p><strong>Created At:</strong> <?php echo $row['created_at']; ?></p>

                            <!-- Buttons -->
                            <div class="d-flex justify-content-between">
                                <?php if ($row['status'] == '3') { ?>
                                <a href="rate-booking.php?booking_id=<?php echo $row['booking_id']; ?>"
                                    class="btn btn-warning btn-sm">Rate</a>
                                <?php } else { ?>
                                <a href="client-bookservices-view.php?service_id=<?php echo $row['service_id']; ?>"
                                    class="btn btn-info btn-sm">View</a>
                                <?php } ?>
                            </div>
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