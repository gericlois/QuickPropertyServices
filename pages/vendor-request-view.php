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
$request_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($request_id <= 0) {
    header("Location: vendor-dashboard.php");
    exit();
}

// Verify vendor is assigned to this request and fetch details
$stmt = $conn->prepare("SELECT va.assignment_id, va.status AS assignment_status, sr.request_id, sr.tracking_code, sr.homeowner_name, sr.address, sr.description, sr.status AS request_status, sr.created_at FROM vendor_assignments va INNER JOIN service_requests sr ON va.request_id = sr.request_id WHERE va.vendor_id = ? AND va.request_id = ?");
$stmt->bind_param("ii", $vendor_id, $request_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $stmt->close();
    header("Location: vendor-dashboard.php");
    exit();
}

$assignment = $result->fetch_assoc();
$stmt->close();

$assignment_id = $assignment['assignment_id'];
$assignment_status = $assignment['assignment_status'];

// Fetch request media
$stmt = $conn->prepare("SELECT file_path, file_type FROM request_media WHERE request_id = ?");
$stmt->bind_param("i", $request_id);
$stmt->execute();
$media_result = $stmt->get_result();
$request_media = [];
while ($m = $media_result->fetch_assoc()) {
    $request_media[] = $m;
}
$stmt->close();

// Check if estimate already exists
$estimate = null;
$estimate_media = [];
$stmt = $conn->prepare("SELECT estimate_id, description, estimated_price, timeline, status FROM vendor_estimates WHERE assignment_id = ? AND vendor_id = ?");
$stmt->bind_param("ii", $assignment_id, $vendor_id);
$stmt->execute();
$est_result = $stmt->get_result();
if ($est_result->num_rows > 0) {
    $estimate = $est_result->fetch_assoc();

    // Fetch estimate media
    $stmt2 = $conn->prepare("SELECT file_path, file_type FROM estimate_media WHERE estimate_id = ?");
    $stmt2->bind_param("i", $estimate['estimate_id']);
    $stmt2->execute();
    $em_result = $stmt2->get_result();
    while ($em = $em_result->fetch_assoc()) {
        $estimate_media[] = $em;
    }
    $stmt2->close();
}
$stmt->close();
?>

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>View Request #<?php echo $request_id; ?> | Quick Property Services</title>

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

        <!-- Request View Section -->
        <section class="section light-background" style="padding-top:120px;">
            <div class="container" data-aos="fade-up" data-aos-delay="100">

                <!-- Back Button -->
                <div class="row mb-3">
                    <div class="col-12">
                        <a href="vendor-dashboard.php" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i>Back to Dashboard
                        </a>
                    </div>
                </div>

                <?php if (isset($_GET['success']) && $_GET['success'] == 'EstimateSubmitted'): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <b>Your estimate has been submitted successfully!</b>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <!-- Request Details Card -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">
                                    <i class="bi bi-file-earmark-text me-2"></i>Request #<?php echo $request_id; ?>
                                    <small>| Tracking: <?php echo htmlspecialchars($assignment['tracking_code']); ?></small>
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong><i class="bi bi-person me-1"></i>Homeowner:</strong> <?php echo htmlspecialchars($assignment['homeowner_name']); ?></p>
                                        <p><strong><i class="bi bi-geo-alt me-1"></i>Address:</strong> <?php echo htmlspecialchars($assignment['address']); ?></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong><i class="bi bi-calendar me-1"></i>Submitted:</strong> <?php echo date('M d, Y', strtotime($assignment['created_at'])); ?></p>
                                        <p>
                                            <strong>Status:</strong>
                                            <span class="badge <?php echo getStatusBadgeClass($assignment['request_status']); ?>"><?php echo getStatusLabel($assignment['request_status']); ?></span>
                                            <span class="badge bg-secondary ms-1"><?php echo ucwords(str_replace('_', ' ', $assignment_status)); ?></span>
                                        </p>
                                    </div>
                                </div>
                                <hr>
                                <h6><strong>Description:</strong></h6>
                                <p><?php echo nl2br(htmlspecialchars($assignment['description'])); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Request Media -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card shadow-sm border-0">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="bi bi-images me-2"></i>Request Media</h5>
                            </div>
                            <div class="card-body">
                                <?php echo renderMediaGallery($request_media, '../'); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if ($assignment_status == 'assigned' && $estimate === null): ?>
                    <!-- Estimate Submission Form -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card shadow-sm border-0">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Submit Your Estimate</h5>
                                </div>
                                <div class="card-body">
                                    <form action="scripts/submit-estimate.php" method="post" enctype="multipart/form-data">
                                        <input type="hidden" name="assignment_id" value="<?php echo $assignment_id; ?>">
                                        <input type="hidden" name="request_id" value="<?php echo $request_id; ?>">

                                        <div class="mb-3">
                                            <label for="description" class="form-label"><strong>Description</strong></label>
                                            <textarea class="form-control" id="description" name="description" rows="4" placeholder="Describe your estimate, scope of work, materials needed, etc." required></textarea>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label for="estimated_price" class="form-label"><strong>Estimated Price ($)</strong></label>
                                                <input type="number" class="form-control" id="estimated_price" name="estimated_price" step="0.01" min="0" placeholder="0.00" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="timeline" class="form-label"><strong>Timeline</strong></label>
                                                <input type="text" class="form-control" id="timeline" name="timeline" placeholder="e.g., 2-3 weeks" required>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="media" class="form-label"><strong>Attach Files (Optional)</strong></label>
                                            <input type="file" class="form-control" id="media" name="media[]" multiple accept=".jpg,.jpeg,.png,.mp4,.mov">
                                            <div class="form-text">Supported: JPG, PNG, MP4, MOV. Max 10MB per image, 50MB per video.</div>
                                        </div>

                                        <button type="submit" class="btn btn-success">
                                            <i class="bi bi-send me-1"></i>Submit Estimate
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php elseif ($estimate !== null): ?>
                    <!-- Read-Only Estimate View -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card shadow-sm border-0">
                                <div class="card-header bg-info text-white">
                                    <h5 class="mb-0"><i class="bi bi-file-earmark-check me-2"></i>Your Submitted Estimate</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <p><strong>Estimated Price:</strong> <span class="fs-5 text-success">$<?php echo number_format($estimate['estimated_price'], 2); ?></span></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Timeline:</strong> <?php echo htmlspecialchars($estimate['timeline']); ?></p>
                                        </div>
                                    </div>
                                    <h6><strong>Description:</strong></h6>
                                    <p><?php echo nl2br(htmlspecialchars($estimate['description'])); ?></p>

                                    <?php if (!empty($estimate_media)): ?>
                                        <hr>
                                        <h6><strong>Estimate Attachments:</strong></h6>
                                        <?php echo renderMediaGallery($estimate_media, '../'); ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

            </div>
        </section>
        <!-- /Request View Section -->

    </main>

    <?php include "includes/footer.php" ?>

    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <?php include "includes/script.php" ?>

</body>

</html>
