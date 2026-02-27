<?php
session_start();
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php?error=AccessDenied");
    exit();
}

include "includes/head.php";
include "scripts/connection.php";

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid vendor listing.");
}

$vendor_id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT
    u.first_name, u.last_name, u.email, u.phone, u.created_at,
    v.vendor_id, v.business_name, v.specialty, v.status
    FROM users u
    INNER JOIN vendors v ON u.user_id = v.user_id
    WHERE v.vendor_id = ?");
$stmt->bind_param("i", $vendor_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Vendor not found.");
}

$vendor = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">


<body>
    <?php include "includes/header.php"; ?>
    <?php include "includes/sidebar.php"; ?>

    <div class="main-content">
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">Vendor Profile</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item"><a href="vendors.php">Vendors</a></li>
                    <li class="breadcrumb-item">Vendor Profile</li>
                </ul>
            </div>
        </div>

        <section class="section profile">
            <div class="row">
                <div class="col-xl-4">
                    <div class="card">
                        <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                            <i class="feather-user fs-4"></i>
                            <h2><?php echo htmlspecialchars($vendor['first_name']); ?> <?php echo htmlspecialchars($vendor['last_name']); ?></h2>
                            <h5 class="text-muted"><?php echo htmlspecialchars($vendor['business_name']); ?></h5>
                            <h3>
                                <?php
                                if ($vendor['status'] == "1") {
                                    echo '<span class="badge bg-primary"><i class="feather-check-circle me-1"></i> Active</span>';
                                } else {
                                    echo '<span class="badge bg-secondary"><i class="feather-alert-octagon me-1"></i> Inactive</span>';
                                }
                                ?>
                            </h3>
                        </div>
                    </div>
                </div>

                <div class="col-xl-8">
                    <div class="card">
                        <div class="card-body pt-3">
                            <ul class="nav nav-tabs nav-tabs-bordered">
                                <li class="nav-item">
                                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">Overview</button>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-assignments">Assignments</button>
                                </li>
                            </ul>

                            <div class="tab-content pt-2">
                                <!-- Overview Tab -->
                                <div class="tab-pane fade show active profile-overview" id="profile-overview">
                                    <h5 class="card-title">Vendor Details</h5>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Email</div>
                                        <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($vendor['email']); ?></div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Phone</div>
                                        <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($vendor['phone']); ?></div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Business Name</div>
                                        <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($vendor['business_name']); ?></div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Specialty</div>
                                        <div class="col-lg-9 col-md-8"><?php echo $vendor['specialty'] ? htmlspecialchars($vendor['specialty']) : 'N/A'; ?></div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Status</div>
                                        <div class="col-lg-9 col-md-8">
                                            <?php
                                            if ($vendor['status'] == "1") {
                                                echo '<span class="badge bg-primary">Active</span>';
                                            } else {
                                                echo '<span class="badge bg-secondary">Inactive</span>';
                                            }
                                            ?>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Created At</div>
                                        <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($vendor['created_at']); ?></div>
                                    </div>
                                </div>

                                <!-- Assignments Tab -->
                                <div class="tab-pane fade" id="profile-assignments">
                                    <h5 class="card-title">Vendor Assignments</h5>

                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Request ID</th>
                                                <th>Homeowner</th>
                                                <th>Status</th>
                                                <th>Assigned At</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $assign_stmt = $conn->prepare("SELECT
                                                va.assignment_id,
                                                va.status AS assignment_status,
                                                va.assigned_at,
                                                sr.request_id,
                                                sr.homeowner_name
                                                FROM vendor_assignments va
                                                INNER JOIN service_requests sr ON va.request_id = sr.request_id
                                                WHERE va.vendor_id = ?
                                                ORDER BY va.assigned_at DESC");
                                            $assign_stmt->bind_param("i", $vendor_id);
                                            $assign_stmt->execute();
                                            $assign_result = $assign_stmt->get_result();

                                            if ($assign_result->num_rows > 0) {
                                                while ($assignment = $assign_result->fetch_assoc()) {
                                                    $assign_status_class = "bg-secondary";
                                                    $assign_status_text = htmlspecialchars($assignment['assignment_status']);

                                                    if ($assignment['assignment_status'] == "1" || strtolower($assignment['assignment_status']) == "active") {
                                                        $assign_status_class = "bg-primary";
                                                        $assign_status_text = "Active";
                                                    } elseif ($assignment['assignment_status'] == "2" || strtolower($assignment['assignment_status']) == "completed") {
                                                        $assign_status_class = "bg-success";
                                                        $assign_status_text = "Completed";
                                                    } elseif ($assignment['assignment_status'] == "0" || strtolower($assignment['assignment_status']) == "pending") {
                                                        $assign_status_class = "bg-warning";
                                                        $assign_status_text = "Pending";
                                                    }
                                            ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($assignment['request_id']); ?></td>
                                                        <td><?php echo htmlspecialchars($assignment['homeowner_name']); ?></td>
                                                        <td><span class="badge <?php echo $assign_status_class; ?>"><?php echo $assign_status_text; ?></span></td>
                                                        <td><?php echo htmlspecialchars($assignment['assigned_at']); ?></td>
                                                    </tr>
                                            <?php
                                                }
                                            } else {
                                                echo "<tr><td colspan='4' class='text-center'>No assignments found</td></tr>";
                                            }
                                            $assign_stmt->close();
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <?php include "includes/footer.php"; ?>
    <?php include "includes/scripts.php"; ?>
</body>
</html>
