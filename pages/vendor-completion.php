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

// Verify this vendor is the SELECTED vendor for this request
$stmt = $conn->prepare("SELECT va.assignment_id, va.status AS assignment_status, sr.request_id, sr.tracking_code, sr.homeowner_name, sr.address, sr.description, sr.status AS request_status FROM vendor_assignments va INNER JOIN service_requests sr ON va.request_id = sr.request_id WHERE va.vendor_id = ? AND va.request_id = ? AND va.status = 'selected'");
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

// Check if completion report already exists
$completion = null;
$completion_media = [];
$stmt = $conn->prepare("SELECT report_id, description, created_at FROM completion_reports WHERE request_id = ? AND vendor_id = ?");
$stmt->bind_param("ii", $request_id, $vendor_id);
$stmt->execute();
$comp_result = $stmt->get_result();
if ($comp_result->num_rows > 0) {
    $completion = $comp_result->fetch_assoc();

    // Fetch completion media
    $stmt2 = $conn->prepare("SELECT file_path, file_type FROM completion_media WHERE report_id = ?");
    $stmt2->bind_param("i", $completion['report_id']);
    $stmt2->execute();
    $cm_result = $stmt2->get_result();
    while ($cm = $cm_result->fetch_assoc()) {
        $completion_media[] = $cm;
    }
    $stmt2->close();
}
$stmt->close();
?>

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Completion Report - Request #<?php echo $request_id; ?> | Quick Property Services</title>

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

        <!-- Completion Report Section -->
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

                <!-- Request Summary -->
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
                                        <p>
                                            <strong>Request Status:</strong>
                                            <span class="badge <?php echo getStatusBadgeClass($assignment['request_status']); ?>"><?php echo getStatusLabel($assignment['request_status']); ?></span>
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

                <?php if ($completion !== null): ?>
                    <!-- Read-Only Completion Report -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card shadow-sm border-0">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0"><i class="bi bi-check-circle me-2"></i>Completion Report Submitted</h5>
                                </div>
                                <div class="card-body">
                                    <p><strong>Submitted:</strong> <?php echo date('M d, Y g:i A', strtotime($completion['created_at'])); ?></p>
                                    <hr>
                                    <h6><strong>Work Description:</strong></h6>
                                    <p><?php echo nl2br(htmlspecialchars($completion['description'])); ?></p>

                                    <?php if (!empty($completion_media)): ?>
                                        <hr>
                                        <h6><strong>Completion Media:</strong></h6>
                                        <?php echo renderMediaGallery($completion_media, '../'); ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php else: ?>
                    <!-- Completion Report Form -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card shadow-sm border-0">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Submit Completion Report</h5>
                                </div>
                                <div class="card-body">
                                    <form action="scripts/submit-completion.php" method="post" enctype="multipart/form-data">
                                        <input type="hidden" name="request_id" value="<?php echo $request_id; ?>">

                                        <div class="mb-3">
                                            <label for="description" class="form-label"><strong>Description</strong></label>
                                            <textarea class="form-control" id="description" name="description" rows="5" placeholder="Describe the work completed, materials used, any notes for the homeowner..." required></textarea>
                                        </div>

                                        <div class="mb-3">
                                            <label for="media" class="form-label"><strong>Attach Photos/Videos (Optional)</strong></label>
                                            <input type="file" class="form-control" id="media" name="media[]" multiple accept=".jpg,.jpeg,.png,.mp4,.mov">
                                            <div class="form-text">Supported: JPG, PNG, MP4, MOV. Max 10MB per image, 50MB per video.</div>
                                        </div>

                                        <button type="submit" class="btn btn-success">
                                            <i class="bi bi-check2-square me-1"></i>Submit Completion Report
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

            </div>
        </section>
        <!-- /Completion Report Section -->

    </main>

    <?php include "includes/footer.php" ?>

    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <?php include "includes/script.php" ?>

</body>

</html>
