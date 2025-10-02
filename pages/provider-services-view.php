<!DOCTYPE html>
<html lang="en">
<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'provider') {
    header("Location: login.php?error=AccessDenied");
    exit();
}


include "../admin/pages/scripts/connection.php";

// Validate service_id
if (!isset($_GET['service_id']) || !is_numeric($_GET['service_id'])) {
    die('Invalid service ID.');
}
$service_id = intval($_GET['service_id']);

// Fetch service details
$sql = "SELECT s.*, p.business_name, u.first_name, u.last_name, u.email, u.phone
        FROM services s
        JOIN providers p ON s.provider_id = p.provider_id
        JOIN users u ON p.user_id = u.user_id
        WHERE s.service_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $service_id);
$stmt->execute();
$result = $stmt->get_result();
$service = $result->fetch_assoc();

if (!$service) {
    die('Service not found.');
}
?>

<?php
$role = $_SESSION['role'] ?? 'guest'; // fallback if not logged in
?>

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Service Assignment | Provider View – Fixing Techs</title>
  <meta name="description" content="View service assignments and job details in the Fixing Techs provider portal. Manage your tasks, status updates, and client info securely.">
  <meta name="keywords" content="provider services view, Fixing Techs assignment, provider job details, service assignment portal, manage provider tasks, provider service view, job status update">

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
        <section id="services" class="services section light-background">
            <div class="container section-title" data-aos="fade-up">
                <h2>Service Details</h2>
            </div>

            <div class="container" data-aos="fade-up" data-aos-delay="100">
                <div class="row g-4">
                    <div class="col-lg-7">
                        <div class="service-card">

                            <h3>Service Details</h3>
                            <hr>
                            <h3><?= htmlspecialchars($service['service_name']) ?>
                                <span class="badge <?= ($service['status'] == 1) ? 'bg-success' : 'bg-danger' ?>">
                                    <?= ($service['status'] == 1) ? 'Active' : 'Inactive' ?>
                                </span>
                            </h3>
                            <p><strong>Business Name:</strong> <?= htmlspecialchars($service['business_name']) ?></p>
                            <p><strong>Price:</strong> $<?= number_format($service['base_price'], 2) ?></p>
                            <p><strong>Description:</strong> <?= nl2br(htmlspecialchars($service['description'])) ?></p>
                            <a href="provider-services.php" class="btn btn-secondary">Back to Services</a>
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <div class="service-card">
                            <h3>Clients Who Availed</h3>
                            <hr>
                            <ul>

                                <?php
                                $sql_clients = "SELECT u.first_name, u.last_name, b.appointment_date, b.rate
                            FROM bookings b
                            JOIN clients c ON b.client_id = c.user_id
                            JOIN users u ON c.user_id = u.user_id
                            WHERE b.service_id = ? 
                            ORDER BY b.appointment_date DESC;";

                                $stmt_clients = $conn->prepare($sql_clients);
                                $stmt_clients->bind_param('i', $service_id);
                                $stmt_clients->execute();
                                $result_clients = $stmt_clients->get_result();

                                if ($result_clients->num_rows > 0) {
                                    while ($client = $result_clients->fetch_assoc()) {
                                        $full_name = htmlspecialchars($client['first_name'] . " " . $client['last_name']); 
                                        $appointment_date = htmlspecialchars($client['appointment_date']);
                                        $rating = $client['rate'] !== null ? intval($client['rate']) : 0;

                                        echo "<li><strong>$full_name</strong> | $appointment_date | ";
                                        for ($i = 1; $i <= 5; $i++) {
                                            echo $i <= $rating ? "⭐" : "☆"; // Filled star if within rating, empty otherwise
                                        }
                                        echo "</li>";
                                    }
                                } else {
                                    echo "<li>No clients have availed this service yet.</li>";
                                }

                                $stmt_clients->close();
                                ?>
                            </ul>
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