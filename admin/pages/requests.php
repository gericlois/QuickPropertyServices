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
} ?>

<body>

    <?php include "includes/header.php" ?>
    <?php include "includes/sidebar.php" ?>

    <div class="main-content">

        <?php
        // Valid statuses for filtering
        $validStatuses = [
            'new', 'reviewing', 'vendors_assigned', 'estimates_received',
            'estimate_sent', 'homeowner_accepted', 'payment_received',
            'in_progress', 'completed', 'vendor_paid'
        ];

        // Determine current filter
        $currentFilter = isset($_GET['status']) && in_array($_GET['status'], $validStatuses) ? $_GET['status'] : null;
        $pageTitle = $currentFilter ? getStatusLabel($currentFilter) . " Requests" : "All Requests";
        ?>

        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10"><?php echo htmlspecialchars($pageTitle); ?></h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item"><?php echo htmlspecialchars($pageTitle); ?></li>
                </ul>
            </div>
        </div>

        <?php
        // Success messages
        if (isset($_GET['success'])) {
            if ($_GET['success'] === 'StatusUpdated') {
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    <b>Request status has been successfully updated!</b> Review the updated details to ensure accuracy.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
            }
        }
        ?>

        <!-- Quick Filter Badges -->
        <div class="card">
            <div class="card-body py-3">
                <div class="d-flex flex-wrap gap-2 align-items-center">
                    <span class="fw-semibold me-2">Filter:</span>
                    <a href="requests.php" class="badge <?php echo !$currentFilter ? 'bg-dark' : 'bg-light text-dark border'; ?> text-decoration-none p-2">All</a>
                    <?php foreach ($validStatuses as $s): ?>
                        <a href="requests.php?status=<?php echo $s; ?>"
                           class="badge <?php echo ($currentFilter === $s) ? getStatusBadgeClass($s) : 'bg-light text-dark border'; ?> text-decoration-none p-2">
                            <?php echo getStatusLabel($s); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <section class="section dashboard">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($pageTitle); ?> Data Table</h5>
                            <p>View and manage service requests in a structured table format. This table supports sorting,
                                searching, and pagination for efficient request management.</p>

                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>Request ID</th>
                                        <th>Tracking Code</th>
                                        <th>Homeowner</th>
                                        <th>Phone</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                        <th>Payment</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Build query with optional status filter
                                    if ($currentFilter) {
                                        $sql = "SELECT request_id, tracking_code, homeowner_name, phone, description, status, payment_status, created_at
                                                FROM service_requests WHERE status = ? ORDER BY created_at DESC";
                                        $stmt = $conn->prepare($sql);
                                        $stmt->bind_param("s", $currentFilter);
                                    } else {
                                        $sql = "SELECT request_id, tracking_code, homeowner_name, phone, description, status, payment_status, created_at
                                                FROM service_requests ORDER BY created_at DESC";
                                        $stmt = $conn->prepare($sql);
                                    }

                                    $stmt->execute();
                                    $result = $stmt->get_result();

                                    if ($result && $result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                    ?>
                                            <tr>
                                                <td><?php echo intval($row['request_id']); ?></td>
                                                <td><code><?php echo htmlspecialchars($row['tracking_code']); ?></code></td>
                                                <td><?php echo htmlspecialchars($row['homeowner_name']); ?></td>
                                                <td><?php echo htmlspecialchars($row['phone']); ?></td>
                                                <td>
                                                    <?php
                                                    $desc = strip_tags($row['description']);
                                                    echo htmlspecialchars(strlen($desc) > 50 ? substr($desc, 0, 50) . "..." : $desc);
                                                    ?>
                                                </td>
                                                <td>
                                                    <span class="badge <?php echo getStatusBadgeClass($row['status']); ?>">
                                                        <?php echo getStatusLabel($row['status']); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge <?php echo getPaymentBadgeClass($row['payment_status']); ?>">
                                                        <?php echo getPaymentLabel($row['payment_status']); ?>
                                                    </span>
                                                </td>
                                                <td data-order="<?php echo date('Y-m-d H:i:s', strtotime($row['created_at'])); ?>"><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                                                <td>
                                                    <a href="request-view.php?id=<?php echo intval($row['request_id']); ?>" class="btn btn-sm btn-success">View</a>
                                                </td>
                                            </tr>
                                    <?php
                                        }
                                    }
                                    $stmt->close();
                                    ?>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>

    <?php include "includes/footer.php" ?>
    <?php include "includes/scripts.php" ?>

</body>

</html>
