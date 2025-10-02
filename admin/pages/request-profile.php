<!DOCTYPE html>
<html lang="en">
<?php
session_start();
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php?error=AccessDenied");
    exit;
} else {
    include "includes/head.php";
    include "scripts/connection.php";
}

// Validate request id
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid request ID.");
}

$request_id = intval($_GET['id']); // Sanitize input

// Fetch request details
$stmt = $conn->prepare("SELECT * FROM job_requests WHERE request_id = ?");
$stmt->bind_param("i", $request_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Request not found.");
}

$request = $result->fetch_assoc();
$stmt->close();

// Status badge colors
$statusColors = [
    "Hot Lead" => "bg-danger",
    "Appointment for Estimate" => "bg-info",
    "Estimate Needed" => "bg-warning",
    "Estimate in Progress" => "bg-primary",
    "Estimate Follow Up" => "bg-secondary",
    "Assigned to Vendor" => "bg-dark",
    "Estimate Approved" => "bg-success",
    "Project in Progress" => "bg-primary",
    "Project Completed" => "bg-success",
    "Project Invoiced" => "bg-warning",
    "Project Done" => "bg-success"
];
$statusClass = $statusColors[$request['status']] ?? "bg-secondary";
?>

<body>

    <!-- ======= Header ======= -->
    <?php include "includes/header.php" ?>
    <!-- End Header -->

    <!-- ======= Sidebar ======= -->
    <?php include "includes/sidebar.php" ?>
    <!-- End Sidebar-->

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Request Profile</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item"><a href="request.php">Requests</a></li>
                    <li class="breadcrumb-item active">Request #<?php echo $request['request_id']; ?></li>
                    <li class="breadcrumb-item active"><?php echo $request['homeowner_name']; ?></li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section profile">
            <div class="row">
                <div class="col-xl-4">
                    <div class="card">
                        <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                            <i class="bi bi-journal-text fs-1"></i>
                            <h2>
                                Request #<?php echo htmlspecialchars($request['request_id']); ?> |
                                <?php echo $request['homeowner_name']; ?>
                            </h2>

                            <!-- Current Status Badge -->
                            <h3>
                                <span class="badge <?php echo $statusClass; ?>">
                                    <?php echo htmlspecialchars($request['status']); ?>
                                </span>
                            </h3>
                            <p class="text-muted">
                                Submitted: <?php echo htmlspecialchars($request['created_at']); ?>
                            </p>

                            <!-- Dropdown to Update Status -->
                            <form method="post" action="scripts/update-status.php" class="mt-3" id="statusForm">
                                <input type="hidden" name="request_id" value="<?php echo $request['request_id']; ?>">

                                <select name="status" class="form-select" id="statusSelect">
                                    <option disabled selected>Change Status</option>
                                    <option value="Hot Leads">Hot Leads</option>
                                    <option value="Appointment for Estimate">Appointment for Estimate</option>
                                    <option value="Estimate Needed">Estimate Needed</option>
                                    <option value="Estimate in Progress">Estimate in Progress</option>
                                    <option value="Estimate Follow Up">Estimate Follow Up</option>
                                    <option value="Assigned to Vendor">Assigned to Vendor</option>
                                    <option value="Estimate Approved">Estimate Approved</option>
                                    <option value="Project in Progress">Project in Progress</option>
                                    <option value="Project Completed">Project Completed</option>
                                    <option value="Project Invoiced">Project Invoiced</option>
                                    <option value="Project Done">Project Done</option>
                                </select>
                            </form>
                        </div>
                    </div>
                </div>

                <script>
                    document.getElementById('statusSelect').addEventListener('change', function() {
                        let selectedStatus = this.value;
                        if (confirm("Are you sure you want to change the status to: " + selectedStatus + "?")) {
                            document.getElementById('statusForm').submit();
                        } else {
                            this.selectedIndex = 0; // Reset back to "Change Status"
                        }
                    });
                </script>



                <div class="col-xl-8">

                    <div class="card">
                        <div class="card-body pt-3">
                            <ul class="nav nav-tabs nav-tabs-bordered">
                                <li class="nav-item">
                                    <button class="nav-link active" data-bs-toggle="tab"
                                        data-bs-target="#request-overview">Overview</button>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link" data-bs-toggle="tab"
                                        data-bs-target="#request-images">Images</button>
                                </li>
                            </ul>

                            <div class="tab-content pt-2">

                                <!-- Overview Tab -->
                                <div class="tab-pane fade show active profile-overview" id="request-overview">

                                    <h5 class="card-title">Request Details</h5>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Client ID</div>
                                        <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($request['client_id']); ?></div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Contact Source</div>
                                        <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($request['contact_source']); ?></div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Homeowner Name</div>
                                        <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($request['homeowner_name']); ?></div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Address</div>
                                        <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($request['address']); ?></div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Phone 1</div>
                                        <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($request['phone1']); ?></div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Phone 2</div>
                                        <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($request['phone2']); ?></div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Email</div>
                                        <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($request['email']); ?></div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Work Description</div>
                                        <div class="col-lg-9 col-md-8"><?php echo nl2br(htmlspecialchars($request['work_description'])); ?></div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Estimator Notes</div>
                                        <div class="col-lg-9 col-md-8"><?php echo nl2br(htmlspecialchars($request['estimator_notes'])); ?></div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Crew Instructions</div>
                                        <div class="col-lg-9 col-md-8"><?php echo nl2br(htmlspecialchars($request['crew_instructions'])); ?></div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Updated At</div>
                                        <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($request['updated_at']); ?></div>
                                    </div>

                                </div>
                                <!-- End Overview Tab -->

                                <!-- Images Tab -->
                                <div class="tab-pane fade profile-overview" id="request-images">
                                    <h5 class="card-title">Attached Images</h5>
                                    <div class="row">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <?php if (!empty($request["image$i"])): ?>
                                                <div class="col-md-4 mb-3">
                                                    <a href="<?php echo htmlspecialchars($request["image$i"]); ?>" target="_blank">
                                                        <img src="<?php echo htmlspecialchars($request["image$i"]); ?>" class="img-fluid rounded">
                                                    </a>
                                                </div>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <!-- End Images Tab -->

                            </div><!-- End Tabs -->

                        </div>
                    </div>

                </div>
            </div>
        </section>

    </main>

    <!-- ======= Footer ======= -->
    <?php include "includes/footer.php" ?>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center">
        <i class="bi bi-arrow-up-short"></i>
    </a>

    <!-- Vendor JS Files -->
    <?php include "includes/scripts.php" ?>

</body>

</html>