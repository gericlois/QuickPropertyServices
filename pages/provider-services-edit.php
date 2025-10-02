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


$service_id = $_GET['service_id'];
$provider_id = $_SESSION['provider_id'];

// Fetch service details
$query = "SELECT service_name, description, category_id, base_price FROM services WHERE service_id = ? AND provider_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $service_id, $provider_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: ../provider-services.php?error=ServiceNotFound");
    exit();
}

$service = $result->fetch_assoc();
$stmt->close();

// Fetch categories
$category_query = "SELECT category_id, name FROM category";
$category_result = $conn->query($category_query);
?>

<?php
$role = $_SESSION['role'] ?? 'guest'; // fallback if not logged in
?>

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Edit Service | Provider Portal – Fixing Techs</title>
  <meta name="description" content="Update and manage service details in your provider portal. Securely edit assignments and status for Fixing Techs’ client jobs.">
  <meta name="keywords" content="provider edit service, Fixing Techs provider portal, edit assignment, update job details, service management, provider tools edit, service status update">

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
                <h2>Edit My Services</h2>
                <p>Manage the services you offer.</p>
            </div>

            <!-- Services List -->
            <div class="container" data-aos="fade-up" data-aos-delay="100">
                <div class="row g-4 justify-content-center">
                    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                        <div class="service-card d-flex">
                            <form action="scripts/service-update.php" method="post">
                                <input type="hidden" name="service_id" value="<?php echo $service_id; ?>">

                                <div class="row g-3">
                                    <!-- Service Name -->
                                    <div class="col-12">
                                        <label class="form-label">Service Name</label>
                                        <input type="text" class="form-control" name="service_name"
                                            value="<?php echo htmlspecialchars($service['service_name']); ?>" required>
                                    </div>

                                    <!-- Description -->
                                    <div class="col-12">
                                        <label class="form-label">Description</label>
                                        <textarea class="form-control" name="description" rows="3"
                                            required><?php echo htmlspecialchars($service['description']); ?></textarea>
                                    </div>

                                    <!-- Category -->
                                    <div class="col-12">
                                        <label class="form-label">Category</label>
                                        <select class="form-select" name="category_id" required>
                                            <?php while ($category = $category_result->fetch_assoc()): ?>
                                            <option value="<?php echo $category['category_id']; ?>"
                                                <?php echo ($category['category_id'] == $service['category_id']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($category['name']); ?>
                                            </option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>

                                    <!-- Base Price -->
                                    <div class="col-12">
                                        <label class="form-label">Base Price ($)</label>
                                        <input type="number" step="0.01" class="form-control" name="base_price"
                                            value="<?php echo $service['base_price']; ?>" required>
                                    </div>

                                    <!-- Buttons -->
                                    <div class="col-12 text-center">
                                        <button type="submit" class="btn btn-primary w-100">Update Service</button>
                                    </div>
                                    <div class="col-12 text-center">
                                        <a href="provider-services.php" class="btn btn-secondary w-100">Cancel</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
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