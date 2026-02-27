<!DOCTYPE html>
<html lang="en">
<?php session_start(); ?>
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Track Your Request | Quick Property Services</title>
  <link href="../assets/img/logo.jpg" rel="icon">
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="../assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="../assets/css/main.css" rel="stylesheet">
  <style>
    /* Progress Stepper Styles */
    .stepper {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        position: relative;
        padding: 0;
        margin: 30px 0 40px;
    }
    .stepper::before {
        content: '';
        position: absolute;
        top: 20px;
        left: 0;
        right: 0;
        height: 3px;
        background: #dee2e6;
        z-index: 0;
    }
    .stepper .step {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        z-index: 1;
        flex: 1;
    }
    .stepper .step .step-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #dee2e6;
        color: #6c757d;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 14px;
        border: 3px solid #dee2e6;
        transition: all 0.3s;
    }
    .stepper .step.active .step-circle {
        background: #0d6efd;
        color: #fff;
        border-color: #0d6efd;
        box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.2);
    }
    .stepper .step.completed .step-circle {
        background: #198754;
        color: #fff;
        border-color: #198754;
    }
    .stepper .step .step-label {
        margin-top: 8px;
        font-size: 11px;
        font-weight: 500;
        color: #6c757d;
        text-align: center;
        max-width: 90px;
        line-height: 1.3;
    }
    .stepper .step.active .step-label,
    .stepper .step.completed .step-label {
        color: #212529;
        font-weight: 600;
    }
    @media (max-width: 576px) {
        .stepper .step .step-circle {
            width: 30px;
            height: 30px;
            font-size: 11px;
        }
        .stepper .step .step-label {
            font-size: 9px;
            max-width: 60px;
        }
    }
  </style>
</head>
<body class="index-page">
    <?php include "includes/header.php" ?>

    <main class="main">

        <section class="contact section light-background">
            <div class="container" data-aos="fade-up" data-aos-delay="100">

<?php
require '../admin/pages/scripts/connection.php';
require '../admin/pages/scripts/helpers.php';

$tracking_code = trim($_GET['code'] ?? '');
$request = null;

if (!empty($tracking_code)) {
    $stmt = $conn->prepare("SELECT * FROM service_requests WHERE tracking_code = ?");
    $stmt->bind_param("s", $tracking_code);
    $stmt->execute();
    $result = $stmt->get_result();
    $request = $result->fetch_assoc();
    $stmt->close();
}

// If no code provided or not found, show lookup form
if (empty($tracking_code) || !$request):
?>
                <div class="row justify-content-center">
                    <div class="col-lg-5 col-md-7 mx-auto">
                        <div class="contact-form text-center p-4 shadow rounded" data-aos="fade-up" data-aos-delay="300">
                            <h3 class="mb-2">Track Your Request</h3>
                            <p class="text-muted">Enter your tracking code to check the status of your service request.</p>

                            <?php if (!empty($tracking_code) && !$request): ?>
                                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    <strong>Tracking code not found.</strong> Please check and try again.
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif; ?>

                            <form action="track.php" method="GET">
                                <div class="row gy-3">
                                    <div class="col-12">
                                        <input type="text" class="form-control form-control-lg text-center" name="code" placeholder="QPS-XXXXXXXX" required style="letter-spacing: 2px; font-weight: 600;">
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary w-100">Track Request</button>
                                    </div>
                                </div>
                            </form>

                            <div class="mt-4">
                                <p class="text-muted small">Don't have a tracking code? <a href="submit-request.php">Submit a new request</a></p>
                            </div>
                        </div>
                    </div>
                </div>

<?php else:
    // Request found - display tracking information
    $status = $request['status'];

    // Define stepper steps and map statuses
    $steps = [
        1 => ['label' => 'Request Submitted', 'icon' => '1'],
        2 => ['label' => 'Under Review', 'icon' => '2'],
        3 => ['label' => 'Estimate Ready', 'icon' => '3'],
        4 => ['label' => 'Accepted', 'icon' => '4'],
        5 => ['label' => 'Payment', 'icon' => '5'],
        6 => ['label' => 'In Progress', 'icon' => '6'],
        7 => ['label' => 'Completed', 'icon' => '7'],
    ];

    // Map status to current step number
    $status_step_map = [
        'new' => 1,
        'reviewing' => 2,
        'vendors_assigned' => 3,
        'estimates_received' => 3,
        'estimate_sent' => 3,
        'homeowner_accepted' => 4,
        'payment_received' => 5,
        'in_progress' => 6,
        'completed' => 7,
        'vendor_paid' => 7,
    ];

    $current_step = $status_step_map[$status] ?? 1;

    // Success messages
    if (isset($_GET['success'])):
        if ($_GET['success'] === 'RequestSubmitted'): ?>
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="bi bi-check-circle me-2"></i>
                <strong>Your request has been submitted!</strong> Your tracking code is: <strong><?php echo htmlspecialchars($tracking_code); ?></strong>. Save this code to track your request.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php elseif ($_GET['success'] === 'EstimateAccepted'): ?>
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="bi bi-check-circle me-2"></i>
                <strong>You have accepted the estimate.</strong> We will contact you regarding payment.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif;
    endif; ?>

                <?php if (isset($_SESSION['homeowner_id'])): ?>
                    <div class="mb-3">
                        <a href="homeowner-dashboard.php" class="text-decoration-none"><i class="bi bi-arrow-left me-1"></i>Back to Dashboard</a>
                    </div>
                <?php endif; ?>

                <!-- Tracking Header -->
                <div class="text-center mb-4">
                    <h3 class="mb-1">Track Your Request</h3>
                    <p class="text-muted mb-1">Tracking Code: <strong class="text-primary" style="letter-spacing:1px"><?php echo htmlspecialchars($tracking_code); ?></strong></p>
                    <span class="badge <?php echo getStatusBadgeClass($status); ?> fs-6"><?php echo getStatusLabel($status); ?></span>
                </div>

                <!-- Progress Stepper -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body px-4 py-4">
                        <div class="stepper">
                            <?php foreach ($steps as $step_num => $step_info): ?>
                                <?php
                                $step_class = '';
                                if ($step_num < $current_step) {
                                    $step_class = 'completed';
                                } elseif ($step_num == $current_step) {
                                    $step_class = 'active';
                                }
                                ?>
                                <div class="step <?php echo $step_class; ?>">
                                    <div class="step-circle">
                                        <?php if ($step_num < $current_step): ?>
                                            <i class="bi bi-check-lg"></i>
                                        <?php else: ?>
                                            <?php echo $step_info['icon']; ?>
                                        <?php endif; ?>
                                    </div>
                                    <span class="step-label"><?php echo $step_info['label']; ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <div class="row g-4">

                    <!-- Request Summary -->
                    <div class="col-lg-8">
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-white">
                                <h5 class="card-title mb-0"><i class="bi bi-clipboard-data me-2"></i>Request Summary</h5>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-sm-4 text-muted fw-semibold">Name</div>
                                    <div class="col-sm-8"><?php echo htmlspecialchars($request['homeowner_name']); ?></div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-4 text-muted fw-semibold">Address</div>
                                    <div class="col-sm-8"><?php echo htmlspecialchars($request['address']); ?></div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-4 text-muted fw-semibold">Phone</div>
                                    <div class="col-sm-8"><?php echo htmlspecialchars($request['phone']); ?></div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-4 text-muted fw-semibold">Description</div>
                                    <div class="col-sm-8"><?php echo nl2br(htmlspecialchars($request['description'])); ?></div>
                                </div>
                                <div class="row mb-0">
                                    <div class="col-sm-4 text-muted fw-semibold">Submitted</div>
                                    <div class="col-sm-8"><?php echo date('M d, Y \a\t g:i A', strtotime($request['created_at'])); ?></div>
                                </div>
                            </div>
                        </div>

                        <!-- Request Media -->
                        <?php
                        $media_stmt = $conn->prepare("SELECT file_path, file_type FROM request_media WHERE request_id = ?");
                        $media_stmt->bind_param("i", $request['request_id']);
                        $media_stmt->execute();
                        $media_result = $media_stmt->get_result();
                        $media_items = [];
                        while ($m = $media_result->fetch_assoc()) {
                            $media_items[] = $m;
                        }
                        $media_stmt->close();

                        if (!empty($media_items)):
                        ?>
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-white">
                                <h5 class="card-title mb-0"><i class="bi bi-images me-2"></i>Attached Media</h5>
                            </div>
                            <div class="card-body">
                                <?php echo renderMediaGallery($media_items, '../'); ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Estimate Section (shown when status >= estimate_sent) -->
                        <?php
                        $estimate_statuses = ['estimate_sent', 'homeowner_accepted', 'payment_received', 'in_progress', 'completed', 'vendor_paid'];
                        if (in_array($status, $estimate_statuses) && !empty($request['selected_estimate_id'])):
                            $est_stmt = $conn->prepare("SELECT * FROM vendor_estimates WHERE estimate_id = ?");
                            $est_stmt->bind_param("i", $request['selected_estimate_id']);
                            $est_stmt->execute();
                            $estimate = $est_stmt->get_result()->fetch_assoc();
                            $est_stmt->close();

                            if ($estimate):
                        ?>
                        <div class="card shadow-sm mb-4 border-primary">
                            <div class="card-header bg-primary text-white">
                                <h5 class="card-title mb-0"><i class="bi bi-receipt me-2"></i>Your Estimate</h5>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-sm-4 text-muted fw-semibold">Total Price</div>
                                    <div class="col-sm-8">
                                        <span class="fs-4 fw-bold text-success">$<?php echo number_format($request['final_price'], 2); ?></span>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-4 text-muted fw-semibold">Description</div>
                                    <div class="col-sm-8"><?php echo nl2br(htmlspecialchars($estimate['description'])); ?></div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-4 text-muted fw-semibold">Timeline</div>
                                    <div class="col-sm-8"><?php echo htmlspecialchars($estimate['timeline']); ?></div>
                                </div>

                                <?php if ($status === 'estimate_sent'): ?>
                                <hr>
                                <div class="d-flex gap-3 justify-content-center mt-3">
                                    <form action="scripts/accept-estimate.php" method="POST" class="d-inline">
                                        <input type="hidden" name="tracking_code" value="<?php echo htmlspecialchars($tracking_code); ?>">
                                        <button type="submit" class="btn btn-success btn-lg px-4">
                                            <i class="bi bi-check-circle me-2"></i>Accept Estimate
                                        </button>
                                    </form>
                                    <button type="button" class="btn btn-outline-secondary btn-lg px-4" data-bs-toggle="modal" data-bs-target="#declineModal">
                                        <i class="bi bi-x-circle me-2"></i>Decline
                                    </button>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php
                            endif;
                        endif;
                        ?>

                        <!-- Payment Section (shown when status >= homeowner_accepted) -->
                        <?php
                        $payment_statuses = ['homeowner_accepted', 'payment_received', 'in_progress', 'completed', 'vendor_paid'];
                        if (in_array($status, $payment_statuses)):
                            $payment_status = $request['payment_status'] ?? 'pending';
                        ?>
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-white">
                                <h5 class="card-title mb-0"><i class="bi bi-credit-card me-2"></i>Payment Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <span class="me-2 fw-semibold">Payment Status:</span>
                                    <span class="badge <?php echo getPaymentBadgeClass($payment_status); ?> fs-6"><?php echo getPaymentLabel($payment_status); ?></span>
                                </div>

                                <?php if ($payment_status === 'pending'): ?>
                                    <div class="alert alert-warning mb-0">
                                        <i class="bi bi-info-circle me-2"></i>
                                        Please arrange payment of <strong>$<?php echo number_format($request['final_price'], 2); ?></strong>.
                                        Contact us at <strong>+1 801-613-0482</strong> or <strong>servicerequest@fixingtechs.com</strong> to complete payment.
                                    </div>
                                <?php elseif ($payment_status === 'paid_escrow'): ?>
                                    <div class="alert alert-info mb-0">
                                        <i class="bi bi-shield-check me-2"></i>
                                        Payment received and held in escrow. Work will begin shortly.
                                    </div>
                                <?php elseif ($payment_status === 'released'): ?>
                                    <div class="alert alert-success mb-0">
                                        <i class="bi bi-check-circle me-2"></i>
                                        Payment has been processed.
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Project Status / Completion (shown when status >= in_progress) -->
                        <?php
                        $progress_statuses = ['in_progress', 'completed', 'vendor_paid'];
                        if (in_array($status, $progress_statuses)):
                            // Check for completion reports
                            $comp_stmt = $conn->prepare("SELECT cr.*, v.business_name FROM completion_reports cr LEFT JOIN vendors v ON cr.vendor_id = v.vendor_id WHERE cr.request_id = ? ORDER BY cr.created_at DESC");
                            $comp_stmt->bind_param("i", $request['request_id']);
                            $comp_stmt->execute();
                            $comp_result = $comp_stmt->get_result();
                            $completion_reports = [];
                            while ($cr = $comp_result->fetch_assoc()) {
                                $completion_reports[] = $cr;
                            }
                            $comp_stmt->close();
                        ?>
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-white">
                                <h5 class="card-title mb-0"><i class="bi bi-gear me-2"></i>Project Status</h5>
                            </div>
                            <div class="card-body">
                                <?php if (in_array($status, ['completed', 'vendor_paid'])): ?>
                                    <div class="alert alert-success mb-3">
                                        <i class="bi bi-trophy me-2"></i>
                                        <strong>Your project has been completed!</strong> Thank you for choosing Quick Property Services.
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($completion_reports)): ?>
                                    <?php foreach ($completion_reports as $report): ?>
                                        <div class="border rounded p-3 mb-3">
                                            <h6 class="fw-bold mb-2">Completion Report</h6>
                                            <p class="mb-2"><?php echo nl2br(htmlspecialchars($report['description'])); ?></p>
                                            <small class="text-muted">Submitted on <?php echo date('M d, Y \a\t g:i A', strtotime($report['created_at'])); ?></small>

                                            <?php
                                            // Get completion media
                                            $cm_stmt = $conn->prepare("SELECT file_path, file_type FROM completion_media WHERE report_id = ?");
                                            $cm_stmt->bind_param("i", $report['report_id']);
                                            $cm_stmt->execute();
                                            $cm_result = $cm_stmt->get_result();
                                            $comp_media = [];
                                            while ($cm = $cm_result->fetch_assoc()) {
                                                $comp_media[] = $cm;
                                            }
                                            $cm_stmt->close();

                                            if (!empty($comp_media)):
                                            ?>
                                                <div class="mt-3">
                                                    <?php echo renderMediaGallery($comp_media, '../'); ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                <?php elseif ($status === 'in_progress'): ?>
                                    <div class="alert alert-info mb-0">
                                        <i class="bi bi-hammer me-2"></i>
                                        Work is currently in progress. You will be notified when the project is completed.
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?>

                    </div>

                    <!-- Sidebar -->
                    <div class="col-lg-4">
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-white">
                                <h5 class="card-title mb-0"><i class="bi bi-info-circle me-2"></i>Quick Info</h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled mb-0">
                                    <li class="mb-3">
                                        <div class="text-muted small">Tracking Code</div>
                                        <div class="fw-bold" style="letter-spacing:1px"><?php echo htmlspecialchars($tracking_code); ?></div>
                                    </li>
                                    <li class="mb-3">
                                        <div class="text-muted small">Status</div>
                                        <div><span class="badge <?php echo getStatusBadgeClass($status); ?>"><?php echo getStatusLabel($status); ?></span></div>
                                    </li>
                                    <li class="mb-3">
                                        <div class="text-muted small">Submitted</div>
                                        <div><?php echo date('M d, Y', strtotime($request['created_at'])); ?></div>
                                    </li>
                                    <?php if (!empty($request['updated_at'])): ?>
                                    <li class="mb-3">
                                        <div class="text-muted small">Last Updated</div>
                                        <div><?php echo date('M d, Y', strtotime($request['updated_at'])); ?></div>
                                    </li>
                                    <?php endif; ?>
                                    <?php if (!empty($request['final_price'])): ?>
                                    <li class="mb-0">
                                        <div class="text-muted small">Total Price</div>
                                        <div class="fw-bold text-success fs-5">$<?php echo number_format($request['final_price'], 2); ?></div>
                                    </li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>

                        <div class="card shadow-sm">
                            <div class="card-header bg-white">
                                <h5 class="card-title mb-0"><i class="bi bi-telephone me-2"></i>Need Help?</h5>
                            </div>
                            <div class="card-body">
                                <p class="mb-2">Have questions about your request?</p>
                                <p class="mb-1"><i class="bi bi-phone me-2"></i><strong>+1 801-613-0482</strong></p>
                                <p class="mb-0"><i class="bi bi-envelope me-2"></i><strong>servicerequest@fixingtechs.com</strong></p>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Decline Modal -->
                <div class="modal fade" id="declineModal" tabindex="-1" aria-labelledby="declineModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="declineModalLabel">Decline Estimate</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>If you'd like to decline this estimate or discuss alternatives, please contact us:</p>
                                <p class="mb-1"><i class="bi bi-phone me-2"></i><strong>+1 801-613-0482</strong></p>
                                <p class="mb-0"><i class="bi bi-envelope me-2"></i><strong>servicerequest@fixingtechs.com</strong></p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

<?php endif; ?>

            </div>
        </section>

    </main>

    <?php include "includes/footer.php" ?>

    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <?php include "includes/script.php" ?>
</body>
</html>
