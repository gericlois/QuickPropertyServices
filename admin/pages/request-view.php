<!DOCTYPE html>
<html lang="en">
<?php
session_start();
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php?error=AccessDenied");
    exit();
} else {
    include "includes/head.php";
    include "scripts/connection.php";
    include "scripts/helpers.php";
}

// Validate request id
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: requests.php");
    exit();
}

$request_id = intval($_GET['id']);

// Fetch request details
$stmt = $conn->prepare("SELECT sr.*, c.name AS category_name
    FROM service_requests sr
    LEFT JOIN category c ON sr.category_id = c.category_id
    WHERE sr.request_id = ?");
$stmt->bind_param("i", $request_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<h3 class='text-center mt-5'>Request not found.</h3>";
    exit();
}

$request = $result->fetch_assoc();
$stmt->close();

// All valid statuses
$allStatuses = [
    'new', 'reviewing', 'vendors_assigned', 'estimates_received',
    'estimate_sent', 'homeowner_accepted', 'payment_received',
    'in_progress', 'completed', 'vendor_paid'
];

$paymentStatuses = ['pending', 'paid_escrow', 'released'];
$hasSelectedEstimate = !empty($request['selected_estimate_id']);
?>

<body>

    <?php include "includes/header.php" ?>
    <?php include "includes/sidebar.php" ?>

    <div class="main-content">

        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">Request #<?php echo intval($request['request_id']); ?></h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item"><a href="requests.php">Requests</a></li>
                    <li class="breadcrumb-item">Request #<?php echo intval($request['request_id']); ?></li>
                    <li class="breadcrumb-item"><?php echo htmlspecialchars($request['homeowner_name']); ?></li>
                </ul>
            </div>
        </div>

        <?php
        // Success messages
        if (isset($_GET['success'])) {
            $successMessages = [
                'StatusUpdated' => 'Request status has been successfully updated!',
                'VendorAssigned' => 'Vendor has been successfully assigned to this request!',
                'EstimateSelected' => 'Estimate has been selected and markup applied successfully!',
                'EstimateSent' => 'Estimate has been sent to the homeowner!',
                'NotesSaved' => 'Admin notes have been saved successfully!',
                'PaymentUpdated' => 'Payment status has been updated!'
            ];
            $msg = $successMessages[$_GET['success']] ?? 'Action completed successfully!';
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                <b>' . htmlspecialchars($msg) . '</b>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
        }
        if (isset($_GET['error'])) {
            $errorMessages = [
                'locked' => 'This request is locked. Status cannot be changed after vendor is paid.',
                'MaxVendors' => 'Maximum of 5 vendors can be assigned to a request.',
                'AlreadySelected' => 'An estimate has already been selected for this request.',
                'InvalidRequest' => 'Invalid request. Please try again.'
            ];
            $msg = $errorMessages[$_GET['error']] ?? 'An error occurred.';
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <b>' . htmlspecialchars($msg) . '</b>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
        }
        ?>

        <section class="section profile">
            <div class="row">

                <!-- LEFT COLUMN -->
                <div class="col-xl-4">

                    <!-- Profile Card -->
                    <div class="card">
                        <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                            <i class="feather-file-text fs-1"></i>
                            <h2 class="mt-2">Request #<?php echo intval($request['request_id']); ?></h2>
                            <p class="text-muted mb-1">Tracking Code: <code><?php echo htmlspecialchars($request['tracking_code']); ?></code></p>
                            <h3>
                                <span class="badge <?php echo getStatusBadgeClass($request['status']); ?>">
                                    <?php echo getStatusLabel($request['status']); ?>
                                </span>
                            </h3>
                            <p class="text-muted">
                                Submitted: <?php echo date('M d, Y h:i A', strtotime($request['created_at'])); ?>
                            </p>
                        </div>
                    </div>

                    <?php if (!in_array($request['status'], ['new', 'vendors_assigned'])): ?>
                    <!-- Status Update Form -->
                    <div class="card">
                        <div class="card-body pt-3">
                            <h5 class="card-title">Update Status</h5>
                            <?php if ($request['status'] === 'vendor_paid'): ?>
                                <p class="text-muted"><i class="feather-lock"></i> Status is locked -- vendor has been paid.</p>
                            <?php else: ?>
                                <form method="post" action="scripts/update-request-status.php" id="statusForm">
                                    <input type="hidden" name="request_id" value="<?php echo intval($request['request_id']); ?>">
                                    <select name="status" class="form-select" id="statusSelect">
                                        <option disabled selected>Change Status</option>
                                        <?php foreach ($allStatuses as $s):
                                            $disabled = ($s === $request['status']) ? 'disabled' : '';
                                        ?>
                                            <option value="<?php echo $s; ?>" <?php echo $disabled; ?>>
                                                <?php echo getStatusLabel($s); ?><?php echo $disabled ? ' (Current)' : ''; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Payment Status Update -->
                    <div class="card">
                        <div class="card-body pt-3">
                            <h5 class="card-title">Payment Status</h5>
                            <?php if ($request['status'] === 'vendor_paid'): ?>
                                <p class="text-muted"><i class="feather-lock"></i> Payment is locked -- vendor has been paid.</p>
                            <?php else: ?>
                                <form method="post" action="scripts/update-request-status.php" id="paymentForm">
                                    <input type="hidden" name="request_id" value="<?php echo intval($request['request_id']); ?>">
                                    <input type="hidden" name="status" value="<?php echo htmlspecialchars($request['status']); ?>">
                                    <select name="payment_status" class="form-select" id="paymentSelect">
                                        <option disabled selected>Change Payment Status</option>
                                        <?php foreach ($paymentStatuses as $ps):
                                            $disabled = ($ps === $request['payment_status']) ? 'disabled' : '';
                                        ?>
                                            <option value="<?php echo $ps; ?>" <?php echo $disabled; ?>>
                                                <?php echo getPaymentLabel($ps); ?><?php echo $disabled ? ' (Current)' : ''; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Status History -->
                    <div class="card">
                        <div class="card-body pt-3">
                            <h5 class="card-title">Status History</h5>
                            <?php
                            $histStmt = $conn->prepare("SELECT details, created_at FROM admin_history
                                WHERE action = 'Updated Request Status' AND details LIKE CONCAT('Request ID: ', ?, ',%')
                                ORDER BY created_at DESC");
                            $histStmt->bind_param("i", $request_id);
                            $histStmt->execute();
                            $histResult = $histStmt->get_result();

                            if ($histResult->num_rows > 0): ?>
                                <div class="activity">
                                    <?php while ($histRow = $histResult->fetch_assoc()):
                                        $newStatus = '';
                                        if (preg_match('/New Status:\s*(.+)$/', $histRow['details'], $m)) {
                                            $newStatus = trim($m[1]);
                                        }
                                        $histBadge = getStatusBadgeClass($newStatus);
                                    ?>
                                    <div class="activity-item d-flex">
                                        <div class="activite-label"><?php echo date('M d, Y h:i A', strtotime($histRow['created_at'])); ?></div>
                                        <i class="feather-circle activity-badge text-primary align-self-start"></i>
                                        <div class="activity-content">
                                            <span class="badge <?php echo $histBadge; ?>"><?php echo htmlspecialchars(getStatusLabel($newStatus)); ?></span>
                                        </div>
                                    </div>
                                    <?php endwhile; ?>
                                </div>
                            <?php else: ?>
                                <p class="text-muted text-center">No status changes recorded yet.</p>
                            <?php endif;
                            $histStmt->close();
                            ?>
                        </div>
                    </div>

                </div>
                <!-- END LEFT COLUMN -->

                <!-- RIGHT COLUMN -->
                <div class="col-xl-8">

                    <div class="card">
                        <div class="card-body pt-3">

                            <!-- Nav Tabs -->
                            <ul class="nav nav-tabs nav-tabs-bordered">
                                <li class="nav-item">
                                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-details">Request Details</button>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-vendors">Vendor Assignments</button>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-estimates">Estimates</button>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-homeowner">Homeowner View</button>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-payment">Payment &amp; Completion</button>
                                </li>
                            </ul>

                            <div class="tab-content pt-2">

                                <!-- TAB 1: Request Details -->
                                <div class="tab-pane fade show active profile-overview" id="tab-details">

                                    <h5 class="card-title">Homeowner Information</h5>

                                    <div class="row mb-2">
                                        <div class="col-lg-3 col-md-4 label fw-semibold">Name</div>
                                        <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($request['homeowner_name']); ?></div>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-lg-3 col-md-4 label fw-semibold">Email</div>
                                        <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($request['email']); ?></div>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-lg-3 col-md-4 label fw-semibold">Phone</div>
                                        <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($request['phone']); ?></div>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-lg-3 col-md-4 label fw-semibold">Address</div>
                                        <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($request['address']); ?></div>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-lg-3 col-md-4 label fw-semibold">Category</div>
                                        <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($request['category_name'] ?? 'Not assigned'); ?></div>
                                    </div>

                                    <h5 class="card-title">Description</h5>
                                    <div class="mb-3 p-3 bg-light rounded">
                                        <?php echo nl2br(htmlspecialchars($request['description'])); ?>
                                    </div>

                                    <h5 class="card-title">Admin Notes</h5>
                                    <form method="post" action="scripts/update-request-status.php">
                                        <input type="hidden" name="request_id" value="<?php echo intval($request['request_id']); ?>">
                                        <input type="hidden" name="status" value="<?php echo htmlspecialchars($request['status']); ?>">
                                        <input type="hidden" name="admin_notes_update" value="1">
                                        <div class="mb-3">
                                            <textarea name="admin_notes" class="form-control" rows="4" placeholder="Add internal notes about this request..."><?php echo htmlspecialchars($request['admin_notes'] ?? ''); ?></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-sm btn-primary">Save Notes</button>
                                    </form>

                                    <h5 class="card-title mt-4">Media Gallery</h5>
                                    <?php
                                    $mediaStmt = $conn->prepare("SELECT file_path, file_type FROM request_media WHERE request_id = ?");
                                    $mediaStmt->bind_param("i", $request_id);
                                    $mediaStmt->execute();
                                    $mediaResult = $mediaStmt->get_result();
                                    $mediaItems = [];
                                    while ($m = $mediaResult->fetch_assoc()) {
                                        $mediaItems[] = $m;
                                    }
                                    $mediaStmt->close();
                                    echo renderMediaGallery($mediaItems);
                                    ?>

                                </div>
                                <!-- END TAB 1 -->

                                <!-- TAB 2: Vendor Assignments -->
                                <div class="tab-pane fade" id="tab-vendors">

                                    <?php
                                    // Count current assignments
                                    $countStmt = $conn->prepare("SELECT COUNT(*) as total FROM vendor_assignments WHERE request_id = ?");
                                    $countStmt->bind_param("i", $request_id);
                                    $countStmt->execute();
                                    $assignmentCount = $countStmt->get_result()->fetch_assoc()['total'];
                                    $countStmt->close();
                                    ?>

                                    <h5 class="card-title"><?php echo intval($assignmentCount); ?>/5 Vendors Assigned</h5>

                                    <!-- Current Assignments Table -->
                                    <?php
                                    $assignStmt = $conn->prepare("SELECT va.assignment_id, va.status AS assignment_status, va.assigned_at,
                                            v.vendor_id, v.business_name, v.specialty,
                                            u.first_name, u.last_name,
                                            ve.estimate_id, ve.estimated_price, ve.timeline
                                        FROM vendor_assignments va
                                        JOIN vendors v ON va.vendor_id = v.vendor_id
                                        JOIN users u ON v.user_id = u.user_id
                                        LEFT JOIN vendor_estimates ve ON ve.request_id = va.request_id AND ve.vendor_id = va.vendor_id
                                        WHERE va.request_id = ?
                                        ORDER BY va.assigned_at DESC");
                                    $assignStmt->bind_param("i", $request_id);
                                    $assignStmt->execute();
                                    $assignResult = $assignStmt->get_result();
                                    ?>

                                    <?php if ($assignResult->num_rows > 0): ?>
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Vendor Name</th>
                                                        <th>Business</th>
                                                        <th>Specialty</th>
                                                        <th>Status</th>
                                                        <th>Assigned Date</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php while ($aRow = $assignResult->fetch_assoc()): ?>
                                                        <tr>
                                                            <td><?php echo htmlspecialchars($aRow['first_name'] . ' ' . $aRow['last_name']); ?></td>
                                                            <td><?php echo htmlspecialchars($aRow['business_name']); ?></td>
                                                            <td><?php echo htmlspecialchars($aRow['specialty']); ?></td>
                                                            <td>
                                                                <?php
                                                                $aStatusClass = 'bg-secondary';
                                                                $aStatusLabel = ucwords(str_replace('_', ' ', $aRow['assignment_status']));
                                                                if ($aRow['assignment_status'] === 'assigned') $aStatusClass = 'bg-info';
                                                                elseif ($aRow['assignment_status'] === 'estimate_submitted') $aStatusClass = 'bg-warning';
                                                                elseif ($aRow['assignment_status'] === 'selected') $aStatusClass = 'bg-success';
                                                                elseif ($aRow['assignment_status'] === 'not_selected') $aStatusClass = 'bg-secondary';
                                                                ?>
                                                                <span class="badge <?php echo $aStatusClass; ?>"><?php echo $aStatusLabel; ?></span>
                                                            </td>
                                                            <td><?php echo date('M d, Y h:i A', strtotime($aRow['assigned_at'])); ?></td>
                                                            <td>
                                                                <?php if ($aRow['assignment_status'] === 'assigned'): ?>
                                                                    <form method="post" action="scripts/assign-vendors.php" style="display:inline;" onsubmit="return confirm('Are you sure you want to remove this vendor assignment?');">
                                                                        <input type="hidden" name="request_id" value="<?php echo intval($request_id); ?>">
                                                                        <input type="hidden" name="assignment_id" value="<?php echo intval($aRow['assignment_id']); ?>">
                                                                        <input type="hidden" name="action" value="remove">
                                                                        <button type="submit" class="btn btn-sm btn-danger">Remove</button>
                                                                    </form>
                                                                <?php elseif ($aRow['assignment_status'] === 'estimate_submitted' && !empty($aRow['estimate_id'])): ?>
                                                                    <a href="#tab-estimates" class="btn btn-sm btn-info" onclick="document.querySelector('[data-bs-target=\'#tab-estimates\']').click();">View Estimate</a>
                                                                    <?php if (!$hasSelectedEstimate): ?>
                                                                        <a href="#tab-estimates" class="btn btn-sm btn-success ms-1" onclick="document.querySelector('[data-bs-target=\'#tab-estimates\']').click();">Select</a>
                                                                    <?php endif; ?>
                                                                <?php elseif ($aRow['assignment_status'] === 'selected'): ?>
                                                                    <span class="badge bg-success">Selected</span>
                                                                <?php elseif ($aRow['assignment_status'] === 'not_selected'): ?>
                                                                    <span class="text-muted">Not Selected</span>
                                                                <?php else: ?>
                                                                    <span class="text-muted">--</span>
                                                                <?php endif; ?>
                                                            </td>
                                                        </tr>
                                                    <?php endwhile; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php else: ?>
                                        <p class="text-muted">No vendors assigned yet.</p>
                                    <?php endif;
                                    $assignStmt->close();
                                    ?>

                                    <!-- Assign Vendor Form -->
                                    <?php if ($assignmentCount < 5): ?>
                                        <h5 class="card-title">Assign a Vendor</h5>
                                        <?php
                                        // Get active vendors NOT already assigned to this request
                                        $vendorStmt = $conn->prepare("SELECT v.vendor_id, v.business_name, v.specialty, u.first_name, u.last_name
                                            FROM vendors v
                                            JOIN users u ON v.user_id = u.user_id
                                            WHERE v.status = 1
                                            AND v.vendor_id NOT IN (SELECT vendor_id FROM vendor_assignments WHERE request_id = ?)
                                            ORDER BY v.business_name ASC");
                                        $vendorStmt->bind_param("i", $request_id);
                                        $vendorStmt->execute();
                                        $vendorOptions = $vendorStmt->get_result();
                                        ?>

                                        <?php if ($vendorOptions->num_rows > 0): ?>
                                            <form method="post" action="scripts/assign-vendors.php">
                                                <input type="hidden" name="request_id" value="<?php echo intval($request_id); ?>">
                                                <input type="hidden" name="action" value="assign">
                                                <div class="row g-3 align-items-end">
                                                    <div class="col-md-8">
                                                        <label class="form-label">Select Vendor</label>
                                                        <select name="vendor_id" class="form-select" required>
                                                            <option value="" disabled selected>Choose a vendor...</option>
                                                            <?php while ($vRow = $vendorOptions->fetch_assoc()): ?>
                                                                <option value="<?php echo intval($vRow['vendor_id']); ?>">
                                                                    <?php echo htmlspecialchars($vRow['first_name'] . ' ' . $vRow['last_name'] . ' - ' . $vRow['business_name'] . ' (' . $vRow['specialty'] . ')'); ?>
                                                                </option>
                                                            <?php endwhile; ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <button type="submit" class="btn btn-primary w-100">Assign Vendor</button>
                                                    </div>
                                                </div>
                                            </form>
                                        <?php else: ?>
                                            <p class="text-muted">No available vendors to assign.</p>
                                        <?php endif;
                                        $vendorStmt->close();
                                        ?>
                                    <?php else: ?>
                                        <div class="alert alert-info mt-3">Maximum of 5 vendors have been assigned.</div>
                                    <?php endif; ?>

                                </div>
                                <!-- END TAB 2 -->

                                <!-- TAB 3: Estimates -->
                                <div class="tab-pane fade" id="tab-estimates">

                                    <?php
                                    // Fetch all estimates for this request
                                    $estStmt = $conn->prepare("SELECT ve.estimate_id, ve.assignment_id, ve.vendor_id, ve.description AS est_description,
                                            ve.estimated_price, ve.timeline, ve.status AS est_status, ve.created_at AS est_created,
                                            v.business_name, u.first_name, u.last_name
                                        FROM vendor_estimates ve
                                        JOIN vendors v ON ve.vendor_id = v.vendor_id
                                        JOIN users u ON v.user_id = u.user_id
                                        WHERE ve.request_id = ?
                                        ORDER BY ve.created_at DESC");
                                    $estStmt->bind_param("i", $request_id);
                                    $estStmt->execute();
                                    $estResult = $estStmt->get_result();
                                    ?>

                                    <h5 class="card-title">Vendor Estimates</h5>

                                    <?php if ($estResult->num_rows === 0): ?>
                                        <p class="text-muted text-center">No estimates submitted yet.</p>
                                    <?php else: ?>
                                        <?php while ($est = $estResult->fetch_assoc()): ?>
                                            <div class="card mb-3 border <?php echo ($est['est_status'] === 'selected') ? 'border-success' : ''; ?>">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between align-items-start">
                                                        <div>
                                                            <h6 class="mb-1">
                                                                <?php echo htmlspecialchars($est['first_name'] . ' ' . $est['last_name']); ?>
                                                                <small class="text-muted">- <?php echo htmlspecialchars($est['business_name']); ?></small>
                                                            </h6>
                                                            <p class="mb-1"><strong>Price:</strong> $<?php echo number_format($est['estimated_price'], 2); ?></p>
                                                            <p class="mb-1"><strong>Timeline:</strong> <?php echo htmlspecialchars($est['timeline']); ?></p>
                                                            <p class="mb-1"><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($est['est_description'])); ?></p>
                                                            <small class="text-muted">Submitted: <?php echo date('M d, Y h:i A', strtotime($est['est_created'])); ?></small>
                                                        </div>
                                                        <div class="text-end">
                                                            <?php
                                                            $estBadgeClass = 'bg-secondary';
                                                            if ($est['est_status'] === 'submitted') $estBadgeClass = 'bg-warning';
                                                            elseif ($est['est_status'] === 'selected') $estBadgeClass = 'bg-success';
                                                            elseif ($est['est_status'] === 'not_selected') $estBadgeClass = 'bg-secondary';
                                                            ?>
                                                            <span class="badge <?php echo $estBadgeClass; ?>"><?php echo ucwords(str_replace('_', ' ', $est['est_status'])); ?></span>
                                                        </div>
                                                    </div>

                                                    <?php
                                                    // Estimate media
                                                    $emStmt = $conn->prepare("SELECT file_path, file_type FROM estimate_media WHERE estimate_id = ?");
                                                    $emStmt->bind_param("i", $est['estimate_id']);
                                                    $emStmt->execute();
                                                    $emResult = $emStmt->get_result();
                                                    $emItems = [];
                                                    while ($emRow = $emResult->fetch_assoc()) {
                                                        $emItems[] = $emRow;
                                                    }
                                                    $emStmt->close();
                                                    if (!empty($emItems)):
                                                    ?>
                                                        <hr>
                                                        <p class="fw-semibold mb-2">Estimate Media</p>
                                                        <?php echo renderMediaGallery($emItems); ?>
                                                    <?php endif; ?>

                                                    <?php if (!$hasSelectedEstimate && $est['est_status'] === 'submitted'): ?>
                                                        <hr>
                                                        <button type="button" class="btn btn-sm btn-success" data-bs-toggle="collapse" data-bs-target="#selectEstimate<?php echo intval($est['estimate_id']); ?>">
                                                            Select This Estimate
                                                        </button>
                                                        <div class="collapse mt-3" id="selectEstimate<?php echo intval($est['estimate_id']); ?>">
                                                            <form method="post" action="scripts/select-estimate.php">
                                                                <input type="hidden" name="estimate_id" value="<?php echo intval($est['estimate_id']); ?>">
                                                                <input type="hidden" name="request_id" value="<?php echo intval($request_id); ?>">
                                                                <div class="row g-3">
                                                                    <div class="col-md-4">
                                                                        <label class="form-label">Estimate Price</label>
                                                                        <input type="text" class="form-control" value="$<?php echo number_format($est['estimated_price'], 2); ?>" disabled>
                                                                        <input type="hidden" name="estimated_price" value="<?php echo $est['estimated_price']; ?>">
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <label class="form-label">Markup Percentage (%)</label>
                                                                        <input type="number" name="markup_percentage" class="form-control markup-pct-input"
                                                                            step="0.01" min="0" max="100" value="0" required
                                                                            data-price="<?php echo $est['estimated_price']; ?>"
                                                                            data-target="markupDisplay<?php echo intval($est['estimate_id']); ?>">
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <label class="form-label">Final Price</label>
                                                                        <div id="markupDisplay<?php echo intval($est['estimate_id']); ?>" class="form-control bg-light">
                                                                            $<?php echo number_format($est['estimated_price'], 2); ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <button type="submit" class="btn btn-primary mt-3" onclick="return confirm('Are you sure you want to select this estimate and apply this markup?');">
                                                                    Save Markup &amp; Send to Homeowner
                                                                </button>
                                                            </form>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endwhile; ?>
                                    <?php endif;
                                    $estStmt->close();
                                    ?>

                                    <!-- Markup Summary (shown when estimate IS selected) -->
                                    <?php if ($hasSelectedEstimate): ?>
                                        <div class="card border border-success mt-3">
                                            <div class="card-body">
                                                <h5 class="card-title text-success">Selected Estimate Summary</h5>
                                                <?php
                                                $selStmt = $conn->prepare("SELECT ve.estimated_price, ve.timeline, ve.description,
                                                        v.business_name, u.first_name, u.last_name
                                                    FROM vendor_estimates ve
                                                    JOIN vendors v ON ve.vendor_id = v.vendor_id
                                                    JOIN users u ON v.user_id = u.user_id
                                                    WHERE ve.estimate_id = ?");
                                                $selStmt->bind_param("i", $request['selected_estimate_id']);
                                                $selStmt->execute();
                                                $selEst = $selStmt->get_result()->fetch_assoc();
                                                $selStmt->close();
                                                ?>
                                                <?php if ($selEst): ?>
                                                    <div class="row mb-2">
                                                        <div class="col-md-4 fw-semibold">Vendor</div>
                                                        <div class="col-md-8"><?php echo htmlspecialchars($selEst['first_name'] . ' ' . $selEst['last_name'] . ' - ' . $selEst['business_name']); ?></div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col-md-4 fw-semibold">Vendor Price</div>
                                                        <div class="col-md-8">$<?php echo number_format($selEst['estimated_price'], 2); ?></div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col-md-4 fw-semibold">Markup</div>
                                                        <div class="col-md-8"><?php echo number_format($request['markup_percentage'], 2); ?>% ($<?php echo number_format($request['markup_amount'], 2); ?>)</div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col-md-4 fw-semibold">Final Price to Homeowner</div>
                                                        <div class="col-md-8"><strong class="text-success fs-5">$<?php echo number_format($request['final_price'], 2); ?></strong></div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                </div>
                                <!-- END TAB 3 -->

                                <!-- TAB 4: Homeowner View -->
                                <div class="tab-pane fade" id="tab-homeowner">

                                    <h5 class="card-title">Homeowner Preview</h5>

                                    <?php if ($hasSelectedEstimate && $request['final_price']): ?>
                                        <div class="card border mb-3">
                                            <div class="card-body">
                                                <h6>Service Estimate for <?php echo htmlspecialchars($request['homeowner_name']); ?></h6>
                                                <hr>
                                                <?php
                                                $previewStmt = $conn->prepare("SELECT description, timeline FROM vendor_estimates WHERE estimate_id = ?");
                                                $previewStmt->bind_param("i", $request['selected_estimate_id']);
                                                $previewStmt->execute();
                                                $previewEst = $previewStmt->get_result()->fetch_assoc();
                                                $previewStmt->close();
                                                ?>
                                                <?php if ($previewEst): ?>
                                                    <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($previewEst['description'])); ?></p>
                                                    <p><strong>Timeline:</strong> <?php echo htmlspecialchars($previewEst['timeline']); ?></p>
                                                <?php endif; ?>
                                                <p class="fs-4"><strong>Total Price: </strong><span class="text-success">$<?php echo number_format($request['final_price'], 2); ?></span></p>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="alert alert-warning">No estimate has been selected yet. Select an estimate in the Estimates tab first.</div>
                                    <?php endif; ?>

                                    <!-- Tracking Link -->
                                    <h5 class="card-title">Tracking Link</h5>
                                    <?php
                                    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
                                    $host = $_SERVER['HTTP_HOST'];
                                    $trackingUrl = $protocol . '://' . $host . '/QuickPropertyServices/track.php?code=' . urlencode($request['tracking_code']);
                                    ?>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" id="trackingLinkInput" value="<?php echo htmlspecialchars($trackingUrl); ?>" readonly>
                                        <button class="btn btn-outline-primary" type="button" id="copyTrackingBtn">Copy Link</button>
                                    </div>

                                    <!-- Send Email -->
                                    <?php if ($hasSelectedEstimate && $request['final_price']): ?>
                                        <h5 class="card-title">Send to Homeowner</h5>
                                        <form method="post" action="scripts/send-estimate.php" onsubmit="return confirm('Are you sure you want to send the estimate to the homeowner?');">
                                            <input type="hidden" name="request_id" value="<?php echo intval($request_id); ?>">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="feather-mail"></i> Send Email to Homeowner
                                            </button>
                                        </form>
                                    <?php endif; ?>

                                    <!-- Status Info -->
                                    <h5 class="card-title mt-4">Homeowner Status</h5>
                                    <div class="row mb-2">
                                        <div class="col-md-4 fw-semibold">Estimate Sent</div>
                                        <div class="col-md-8">
                                            <?php if (in_array($request['status'], ['estimate_sent', 'homeowner_accepted', 'payment_received', 'in_progress', 'completed', 'vendor_paid'])): ?>
                                                <span class="badge bg-success">Yes</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Not yet</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-md-4 fw-semibold">Homeowner Accepted</div>
                                        <div class="col-md-8">
                                            <?php if (in_array($request['status'], ['homeowner_accepted', 'payment_received', 'in_progress', 'completed', 'vendor_paid'])): ?>
                                                <span class="badge bg-success">Yes</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Not yet</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                </div>
                                <!-- END TAB 4 -->

                                <!-- TAB 5: Payment & Completion -->
                                <div class="tab-pane fade" id="tab-payment">

                                    <h5 class="card-title">Payment Information</h5>

                                    <div class="row mb-2">
                                        <div class="col-md-4 fw-semibold">Payment Status</div>
                                        <div class="col-md-8">
                                            <span class="badge <?php echo getPaymentBadgeClass($request['payment_status']); ?>">
                                                <?php echo getPaymentLabel($request['payment_status']); ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4 fw-semibold">Escrow Amount</div>
                                        <div class="col-md-8">
                                            <?php if ($request['final_price']): ?>
                                                <strong>$<?php echo number_format($request['final_price'], 2); ?></strong>
                                            <?php else: ?>
                                                <span class="text-muted">Not determined yet</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <hr>

                                    <h5 class="card-title">Completion Report</h5>

                                    <?php
                                    // Check for completion reports
                                    $compStmt = $conn->prepare("SELECT cr.report_id, cr.vendor_id, cr.description AS comp_description, cr.created_at AS comp_created,
                                            v.business_name, u.first_name, u.last_name
                                        FROM completion_reports cr
                                        JOIN vendors v ON cr.vendor_id = v.vendor_id
                                        JOIN users u ON v.user_id = u.user_id
                                        WHERE cr.request_id = ?
                                        ORDER BY cr.created_at DESC");
                                    $compStmt->bind_param("i", $request_id);
                                    $compStmt->execute();
                                    $compResult = $compStmt->get_result();
                                    ?>

                                    <?php if ($compResult->num_rows > 0): ?>
                                        <?php while ($comp = $compResult->fetch_assoc()): ?>
                                            <div class="card border mb-3">
                                                <div class="card-body">
                                                    <h6><?php echo htmlspecialchars($comp['first_name'] . ' ' . $comp['last_name'] . ' - ' . $comp['business_name']); ?></h6>
                                                    <p><?php echo nl2br(htmlspecialchars($comp['comp_description'])); ?></p>
                                                    <small class="text-muted">Submitted: <?php echo date('M d, Y h:i A', strtotime($comp['comp_created'])); ?></small>

                                                    <?php
                                                    // Completion media
                                                    $cmStmt = $conn->prepare("SELECT file_path, file_type FROM completion_media WHERE report_id = ?");
                                                    $cmStmt->bind_param("i", $comp['report_id']);
                                                    $cmStmt->execute();
                                                    $cmResult = $cmStmt->get_result();
                                                    $cmItems = [];
                                                    while ($cmRow = $cmResult->fetch_assoc()) {
                                                        $cmItems[] = $cmRow;
                                                    }
                                                    $cmStmt->close();
                                                    if (!empty($cmItems)):
                                                    ?>
                                                        <hr>
                                                        <p class="fw-semibold mb-2">Completion Media</p>
                                                        <?php echo renderMediaGallery($cmItems); ?>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <div class="alert alert-info">Waiting for vendor to submit completion proof.</div>
                                    <?php endif;
                                    $compStmt->close();
                                    ?>

                                    <!-- Mark Vendor Paid -->
                                    <?php if ($request['status'] === 'completed' && $request['payment_status'] !== 'released'): ?>
                                        <hr>
                                        <form method="post" action="scripts/update-request-status.php" onsubmit="return confirm('Are you sure you want to mark the vendor as paid and release funds? This action cannot be undone.');">
                                            <input type="hidden" name="request_id" value="<?php echo intval($request_id); ?>">
                                            <input type="hidden" name="status" value="vendor_paid">
                                            <input type="hidden" name="payment_status" value="released">
                                            <button type="submit" class="btn btn-success btn-lg">
                                                <i class="feather-check-circle"></i> Mark Vendor Paid &amp; Release Funds
                                            </button>
                                        </form>
                                    <?php elseif ($request['status'] === 'vendor_paid'): ?>
                                        <div class="alert alert-success">
                                            <i class="feather-check-circle"></i> Vendor has been paid and funds released. This request is complete.
                                        </div>
                                    <?php endif; ?>

                                </div>
                                <!-- END TAB 5 -->

                            </div>
                            <!-- End Tab Content -->

                        </div>
                    </div>

                </div>
                <!-- END RIGHT COLUMN -->

            </div>
        </section>

    </div>

    <?php include "includes/footer.php" ?>
    <?php include "includes/scripts.php" ?>

    <script>
    // Status dropdown auto-submit with confirm
    var statusSelect = document.getElementById('statusSelect');
    if (statusSelect) {
        statusSelect.addEventListener('change', function() {
            var selectedStatus = this.options[this.selectedIndex].text.replace(' (Current)', '');
            if (confirm("Are you sure you want to change the status to: " + selectedStatus + "?")) {
                document.getElementById('statusForm').submit();
            } else {
                this.selectedIndex = 0;
            }
        });
    }

    // Payment dropdown auto-submit with confirm
    var paymentSelect = document.getElementById('paymentSelect');
    if (paymentSelect) {
        paymentSelect.addEventListener('change', function() {
            var selectedPayment = this.options[this.selectedIndex].text.replace(' (Current)', '');
            if (confirm("Are you sure you want to change the payment status to: " + selectedPayment + "?")) {
                document.getElementById('paymentForm').submit();
            } else {
                this.selectedIndex = 0;
            }
        });
    }

    // Markup calculator for all estimate forms
    document.querySelectorAll('.markup-pct-input').forEach(function(input) {
        input.addEventListener('input', function() {
            var price = parseFloat(this.getAttribute('data-price'));
            var pct = parseFloat(this.value) || 0;
            var markupAmount = price * (pct / 100);
            var finalPrice = price + markupAmount;
            var targetId = this.getAttribute('data-target');
            var targetEl = document.getElementById(targetId);
            if (targetEl) {
                targetEl.textContent = '$' + finalPrice.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            }
        });
    });

    // Copy tracking link to clipboard
    var copyBtn = document.getElementById('copyTrackingBtn');
    if (copyBtn) {
        copyBtn.addEventListener('click', function() {
            var linkInput = document.getElementById('trackingLinkInput');
            linkInput.select();
            linkInput.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(linkInput.value).then(function() {
                copyBtn.textContent = 'Copied!';
                copyBtn.classList.remove('btn-outline-primary');
                copyBtn.classList.add('btn-success');
                setTimeout(function() {
                    copyBtn.textContent = 'Copy Link';
                    copyBtn.classList.remove('btn-success');
                    copyBtn.classList.add('btn-outline-primary');
                }, 2000);
            });
        });
    }
    </script>

</body>

</html>
