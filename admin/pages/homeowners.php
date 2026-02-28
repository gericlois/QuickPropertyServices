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
} ?>

<body>

    <?php include "includes/header.php" ?>
    <?php include "includes/sidebar.php" ?>

    <div class="main-content">

        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">Homeowners</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item">Homeowners</li>
                </ul>
            </div>
        </div>

        <?php
        if (isset($_GET['success'])) {
            if ($_GET["success"] == "StatusUpdated") {
                echo '
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <b>The homeowner account status has been successfully updated!</b>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
            }
        }
        if (isset($_GET['error'])) {
            if ($_GET["error"] == "InvalidStatus") {
                echo '
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <b>Invalid status value.</b>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
            }
            if ($_GET["error"] == "InvalidRequest") {
                echo '
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <b>Invalid request.</b>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
            }
        }
        ?>

        <section class="section dashboard">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Homeowner Accounts</h5>
                            <p>View and manage homeowner accounts. Approve pending registrations or deactivate accounts.</p>

                            <!-- Table with stripped rows -->
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Registered</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT homeowner_id, name, email, phone, status, created_at
                                            FROM homeowners
                                            ORDER BY created_at DESC";

                                    $result = $conn->query($sql);

                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            $status_class = "bg-secondary";
                                            $status_text = "Unknown";

                                            if ($row['status'] == 1) {
                                                $status_class = "bg-primary";
                                                $status_text = "Active";
                                            } elseif ($row['status'] == 2) {
                                                $status_class = "bg-warning";
                                                $status_text = "Pending";
                                            } elseif ($row['status'] == 3) {
                                                $status_class = "bg-danger";
                                                $status_text = "Rejected";
                                            }
                                    ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($row['homeowner_id']); ?></td>
                                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                                <td><?php echo htmlspecialchars($row['phone'] ?? 'N/A'); ?></td>
                                                <td><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                                                <td>
                                                    <span class="badge <?php echo $status_class; ?>">
                                                        <?php echo $status_text; ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php if ($row['status'] == 2) { ?>
                                                        <a href='scripts/homeowner-update.php?id=<?php echo $row['homeowner_id']; ?>&status=1'
                                                            class='btn btn-sm btn-success'
                                                            onclick='return confirm("Approve this homeowner?")'>Approve</a>
                                                        <a href='scripts/homeowner-update.php?id=<?php echo $row['homeowner_id']; ?>&status=3'
                                                            class='btn btn-sm btn-danger'
                                                            onclick='return confirm("Reject this homeowner?")'>Reject</a>
                                                    <?php } elseif ($row['status'] == 1) { ?>
                                                        <a href='scripts/homeowner-update.php?id=<?php echo $row['homeowner_id']; ?>&status=3'
                                                            class='btn btn-sm btn-dark'
                                                            onclick='return confirm("Deactivate this homeowner?")'>Deactivate</a>
                                                    <?php } elseif ($row['status'] == 3) { ?>
                                                        <a href='scripts/homeowner-update.php?id=<?php echo $row['homeowner_id']; ?>&status=1'
                                                            class='btn btn-sm btn-primary'
                                                            onclick='return confirm("Activate this homeowner?")'>Activate</a>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                    <?php
                                        }
                                    } else {
                                        echo "<tr><td colspan='7' class='text-center'>No homeowners found</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                            <!-- End Table -->
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>

    <?php include "includes/footer.php" ?>

    <!-- Vendor JS Files -->
    <?php include "includes/scripts.php" ?>

</body>

</html>
