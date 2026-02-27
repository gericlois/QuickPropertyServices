<!DOCTYPE html>
<html lang="en">

<?php
session_start();
if (!isset($_SESSION['vendor_id'])) {
    header("Location: vendor-login.php");
    exit();
}
require '../admin/pages/scripts/connection.php';
require '../admin/pages/scripts/helpers.php';

$vendor_id = $_SESSION['vendor_id'];
$first_name = htmlspecialchars($_SESSION['first_name']);
$business_name = htmlspecialchars($_SESSION['business_name']);

// Stats: Assigned Requests count
$stmt = $conn->prepare("SELECT COUNT(*) AS cnt FROM vendor_assignments WHERE vendor_id = ? AND status = 'assigned'");
$stmt->bind_param("i", $vendor_id);
$stmt->execute();
$assigned_count = $stmt->get_result()->fetch_assoc()['cnt'];
$stmt->close();

// Stats: Submitted Estimates count
$stmt = $conn->prepare("SELECT COUNT(*) AS cnt FROM vendor_assignments WHERE vendor_id = ? AND status = 'estimate_submitted'");
$stmt->bind_param("i", $vendor_id);
$stmt->execute();
$estimates_count = $stmt->get_result()->fetch_assoc()['cnt'];
$stmt->close();

// Stats: Completed Projects count
$stmt = $conn->prepare("SELECT COUNT(*) AS cnt FROM vendor_assignments va INNER JOIN service_requests sr ON va.request_id = sr.request_id WHERE va.vendor_id = ? AND va.status = 'selected' AND sr.status IN ('completed', 'vendor_paid')");
$stmt->bind_param("i", $vendor_id);
$stmt->execute();
$completed_count = $stmt->get_result()->fetch_assoc()['cnt'];
$stmt->close();

// Fetch all assignments for this vendor
$stmt = $conn->prepare("SELECT va.assignment_id, va.request_id, va.status AS assignment_status, sr.tracking_code, sr.homeowner_name, sr.address, sr.description, sr.status AS request_status, ve.estimate_id FROM vendor_assignments va INNER JOIN service_requests sr ON va.request_id = sr.request_id LEFT JOIN vendor_estimates ve ON ve.assignment_id = va.assignment_id WHERE va.vendor_id = ? ORDER BY va.assignment_id DESC");
$stmt->bind_param("i", $vendor_id);
$stmt->execute();
$assignments = $stmt->get_result();
?>

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Vendor Dashboard | Quick Property Services</title>

  <!-- Favicons -->
  <link href="../assets/img/logo.jpg" rel="icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="../assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="../assets/css/main.css" rel="stylesheet">
</head>

<body class="index-page">

    <?php include "includes/header.php" ?>

    <main class="main">

        <!-- Dashboard Section -->
        <section class="section light-background" style="padding-top:120px;">
            <div class="container" data-aos="fade-up" data-aos-delay="100">

                <!-- Welcome -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h2><i class="bi bi-speedometer2 me-2"></i>Welcome, <?php echo $first_name; ?>!</h2>
                        <p class="text-muted"><?php echo $business_name; ?> - Vendor Portal</p>
                    </div>
                </div>

                <?php if (isset($_GET['success'])): ?>
                    <?php if ($_GET['success'] == 'CompletionSubmitted'): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <b>Completion report submitted successfully!</b>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                <!-- Stats Row -->
                <div class="row g-4 mb-5">
                    <div class="col-md-4">
                        <div class="card text-center shadow-sm border-0">
                            <div class="card-body">
                                <i class="bi bi-clipboard-check fs-1 text-primary"></i>
                                <h3 class="mt-2"><?php echo $assigned_count; ?></h3>
                                <p class="text-muted mb-0">Assigned Requests</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center shadow-sm border-0">
                            <div class="card-body">
                                <i class="bi bi-file-earmark-text fs-1 text-info"></i>
                                <h3 class="mt-2"><?php echo $estimates_count; ?></h3>
                                <p class="text-muted mb-0">Submitted Estimates</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center shadow-sm border-0">
                            <div class="card-body">
                                <i class="bi bi-check-circle fs-1 text-success"></i>
                                <h3 class="mt-2"><?php echo $completed_count; ?></h3>
                                <p class="text-muted mb-0">Completed Projects</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Assigned Requests -->
                <div class="row mb-3">
                    <div class="col-12">
                        <h4><i class="bi bi-list-task me-2"></i>Your Assigned Requests</h4>
                        <hr>
                    </div>
                </div>

                <div class="row g-4">
                    <?php if ($assignments->num_rows > 0): ?>
                        <?php while ($row = $assignments->fetch_assoc()): ?>
                            <div class="col-lg-6 col-xl-4">
                                <div class="card shadow-sm border-0 h-100">
                                    <div class="card-body">
                                        <h5 class="card-title">
                                            Request #<?php echo $row['request_id']; ?>
                                            <small class="text-muted">| <?php echo htmlspecialchars($row['tracking_code']); ?></small>
                                        </h5>
                                        <p class="mb-1"><strong><i class="bi bi-person me-1"></i></strong> <?php echo htmlspecialchars($row['homeowner_name']); ?></p>
                                        <p class="mb-1"><strong><i class="bi bi-geo-alt me-1"></i></strong> <?php echo htmlspecialchars($row['address']); ?></p>
                                        <p class="mb-2 text-muted small">
                                            <?php echo htmlspecialchars(mb_strimwidth($row['description'], 0, 100, '...')); ?>
                                        </p>
                                        <div class="mb-3">
                                            <span class="badge bg-secondary me-1"><?php echo ucwords(str_replace('_', ' ', $row['assignment_status'])); ?></span>
                                            <span class="badge <?php echo getStatusBadgeClass($row['request_status']); ?>"><?php echo getStatusLabel($row['request_status']); ?></span>
                                        </div>
                                        <div>
                                            <?php if ($row['assignment_status'] == 'assigned'): ?>
                                                <a href="vendor-request-view.php?id=<?php echo $row['request_id']; ?>" class="btn btn-primary btn-sm">
                                                    <i class="bi bi-eye me-1"></i>View & Submit Estimate
                                                </a>
                                            <?php elseif ($row['assignment_status'] == 'estimate_submitted'): ?>
                                                <a href="vendor-request-view.php?id=<?php echo $row['request_id']; ?>" class="btn btn-info btn-sm text-white">
                                                    <i class="bi bi-file-earmark-text me-1"></i>View Estimate
                                                </a>
                                            <?php elseif ($row['assignment_status'] == 'selected' && $row['request_status'] == 'in_progress'): ?>
                                                <a href="vendor-completion.php?id=<?php echo $row['request_id']; ?>" class="btn btn-success btn-sm">
                                                    <i class="bi bi-check2-square me-1"></i>Submit Completion
                                                </a>
                                            <?php elseif ($row['assignment_status'] == 'selected' && in_array($row['request_status'], ['completed', 'vendor_paid'])): ?>
                                                <a href="vendor-request-view.php?id=<?php echo $row['request_id']; ?>" class="btn btn-secondary btn-sm">
                                                    <i class="bi bi-eye me-1"></i>View Completed
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>No assigned requests at this time. Check back later!
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </section>
        <!-- /Dashboard Section -->

    </main>

    <?php include "includes/footer.php" ?>

    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <?php include "includes/script.php" ?>

</body>

</html>
<?php $stmt->close(); ?>
