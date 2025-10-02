<!DOCTYPE html>
<html lang="en">
<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'provider') {
    header("Location: login.php?error=AccessDenied");
    exit();
} else {
    
    include "../admin/pages/scripts/connection.php";
}

$provider_id = $_SESSION['provider_id']; // Get the logged-in provider's ID

// Pagination setup
$limit = 10; // Number of services per page
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Fetch provider services with pagination
$query = "SELECT * FROM services WHERE provider_id = ? ORDER BY created_at DESC LIMIT ? OFFSET ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("iii", $provider_id, $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();

// Count total services for pagination
$count_query = "SELECT COUNT(*) AS total FROM services WHERE provider_id = ?";
$count_stmt = $conn->prepare($count_query);
$count_stmt->bind_param("i", $provider_id);
$count_stmt->execute();
$count_result = $count_stmt->get_result();
$total_services = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_services / $limit);
?>

<?php
$role = $_SESSION['role'] ?? 'guest'; // fallback if not logged in
?>

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Provider Services | Service Assignments – Fixing Techs</title>
  <meta name="description" content="View and manage your assigned services as a provider with Fixing Techs. Update tasks, check client requests, and stay organized.">
  <meta name="keywords" content="provider services, Fixing Techs provider, service assignments, provider portal, contractor services, manage service tasks, assigned jobs, client requests">

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

    <?php include "includes/header.php" ?>

    <main class="main">

        <!-- Services Section -->
        <section id="services" class="services section light-background">

            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>My Services</h2>
                <p>Manage and showcase the services you offer.</p>
            </div>

            <!-- Add New Service Button -->
            <div class="container text-end mb-3">
                <a href="service-add.php" class="btn btn-primary">Add New Service</a>
            </div>
            <div class="container">
                <?php
                if (isset($_GET['success'])) {
                    if ($_GET["success"] == "ServiceUpdated") {
                        echo '
                                                        <div class="alert alert-primary alert-dismissible fade show" role="alert">
                                                        <b>Service Updated. Please double-check your entry for possible mistake!</b>
                                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                        </div>';
                    }
                    if ($_GET["success"] == "ServiceDeactivated") {
                        echo '
                                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                        <b>Service Deactivated. Service is not available for clients as of this time!</b>
                                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                        </div>';
                    }
                    if ($_GET["success"] == "ServiceActivated") {
                        echo '
                                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                                        <b>Service Activated. Service will be available for clients as of this time!</b>
                                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                        </div>';
                    }
                }
                ?>
            </div>

            <!-- Services List -->
            <div class="container" data-aos="fade-up" data-aos-delay="100">
                <div class="row g-4">
                    <?php while ($row = $result->fetch_assoc()) : ?>
                        <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                            <div class="service-card d-flex">
                                <div class="icon flex-shrink-0">
                                    <i class="bi bi-diagram-3"></i>
                                </div>
                                <div>
                                    <h3>
                                        <?= htmlspecialchars($row['service_name']) ?>
                                        <span class="badge <?= ($row['status'] == 1) ? 'bg-success' : 'bg-danger' ?>">
                                            <?= ($row['status'] == 1) ? 'Active' : 'Inactive' ?>
                                        </span>
                                    </h3>
                                    <p><strong>Price:</strong> $<?= number_format($row['base_price'], 2) ?></p>
                                    <p><?= nl2br(htmlspecialchars($row['description'])) ?></p>
                                    <a href="provider-services-view.php?service_id=<?= $row['service_id'] ?>"
                                        class="btn btn-sm btn-primary">View <i class="bi bi-arrow-right"></i></a>
                                    <a href="provider-services-edit.php?service_id=<?= urlencode($row['service_id']); ?>&provider_id=<?= urlencode($row['provider_id']); ?>"
                                        class="btn btn-sm btn-primary">Edit</a>
                                    <?php if ($row['status'] == 1): ?>
                                        <a href="scripts/service-deactivate.php?service_id=<?= urlencode($row['service_id']); ?>"
                                            class="btn btn-sm btn-danger"
                                            onclick="return confirm('Are you sure you want to deactivate this service?')">Deactivate</a>
                                    <?php elseif ($row['status'] == 2): ?>
                                        <a href="scripts/service-activate.php?service_id=<?= urlencode($row['service_id']); ?>"
                                            class="btn btn-sm btn-secondary"
                                            onclick="return confirm('Are you sure you want to activate this service?')">Activate</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>

            <!-- Pagination -->
            <div class="container mt-4 text-center">
                <nav>
                    <ul class="pagination justify-content-center">
                        <?php if ($page > 1): ?>
                            <li class="page-item"><a class="page-link" href="?page=<?= $page - 1 ?>">Previous</a></li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($page < $total_pages): ?>
                            <li class="page-item"><a class="page-link" href="?page=<?= $page + 1 ?>">Next</a></li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>

        </section><!-- /Services Section -->

    </main>

    <?php include "includes/footer.php" ?>

    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <?php include "includes/script.php" ?>

</body>

</html>