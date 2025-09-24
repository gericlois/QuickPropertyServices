<!DOCTYPE html>
<html lang="en">
<?php
session_start();
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php?error=AccessDenied");
} else {
    include "includes/head.php";
    include "scripts/connection.php";
} ?>

<body>

    <!-- ======= Header ======= -->
    <?php include "includes/header.php" ?>
    <!-- End Header -->

    <!-- ======= Sidebar ======= -->
    <?php include "includes/sidebar.php" ?>
    <!-- End Sidebar-->

    <main id="main" class="main">

        <?php
        // status mapping
        $statusMap = [
            "hotleads" => "Hot Lead",
            "appointment" => "Appointment for Estimate",
            "needed" => "Estimate Needed",
            "inprogress" => "Estimate in Progress",
            "followup" => "Estimate Follow Up",
            "vendor" => "Assigned to Vendor",
            "approved" => "Estimate Approved",
            "projectprogress" => "Project in Progress",
            "completed" => "Project Completed",
            "invoiced" => "Project Invoiced",
            "done" => "Project Done"
        ];

        // determine title
        $currentStatusKey = $_GET['id'] ?? null;
        $pageTitle = isset($statusMap[$currentStatusKey]) ? $statusMap[$currentStatusKey] . " Requests" : "All Requests";
        ?>
        <div class="pagetitle">
            <h1><?php echo $pageTitle; ?></h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item active"><?php echo $pageTitle; ?></li>
                </ol>
            </nav>
        </div><!-- End Page Title -->
        </div><!-- End Page Title -->
        <?php
        if (isset($_GET['success'])) {
            if (isset($_GET["success"]) && $_GET["success"] == "UserUpdated") {
                echo '
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <b>The User Account #00' . htmlspecialchars($_GET["user_id"]) . ' has been successfully updated! Please review the changes.</b>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>';
            }

            if ($_GET["success"] == "StatusUpdated") {
                echo '
                                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                                            <b>The  User Account #00' . htmlspecialchars($_GET["user_id"]) . ' has been successfully updated!</b> Review the updated details to ensure accuracy.
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
                            <h5 class="card-title">Request Data Table</h5>
                            <p>View and manage job requests in a structured table format. This table supports sorting,
                                searching, and pagination, making it easy to monitor request details, track progress,
                                and take administrative actions efficiently.</p>


                            <!-- Table with stripped rows -->
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>Request ID</th>
                                        <th>Homeowner</th>
                                        <th>Address</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>Work Description</th>
                                        <th>Status</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Map URL values to actual ENUM status text
                                    $statusMap = [
                                        "hotleads" => "Hot Lead",
                                        "appointment" => "Appointment for Estimate",
                                        "needed" => "Estimate Needed",
                                        "inprogress" => "Estimate in Progress",
                                        "followup" => "Estimate Follow Up",
                                        "vendor" => "Assigned to Vendor",
                                        "approved" => "Estimate Approved",
                                        "projectprogress" => "Project in Progress",
                                        "completed" => "Project Completed",
                                        "invoiced" => "Project Invoiced",
                                        "done" => "Project Done"
                                    ];

                                    // Check if ?id= exists in URL and matches one of the keys
                                    $filter = isset($_GET['id']) && array_key_exists($_GET['id'], $statusMap) ? $statusMap[$_GET['id']] : null;

                                    // Build SQL
                                    $sql = "SELECT request_id, homeowner_name, address, phone1, email, work_description, status, created_at 
                                        FROM job_requests ";
                                    if ($filter) {
                                        $sql .= "WHERE status = '" . $conn->real_escape_string($filter) . "' ";
                                    }
                                    $sql .= "ORDER BY created_at DESC;";

                                    $result = $conn->query($sql);

                                    if ($result && $result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            // default badge style
                                            $status_class = "bg-secondary";
                                            $status_text = $row['status'];

                                            switch ($row['status']) {
                                                case "Hot Lead":
                                                    $status_class = "bg-danger";
                                                    break;
                                                case "Appointment for Estimate":
                                                    $status_class = "bg-info";
                                                    break;
                                                case "Estimate Needed":
                                                    $status_class = "bg-warning";
                                                    break;
                                                case "Estimate in Progress":
                                                    $status_class = "bg-primary";
                                                    break;
                                                case "Estimate Follow Up":
                                                    $status_class = "bg-secondary";
                                                    break;
                                                case "Assigned to Vendor":
                                                    $status_class = "bg-dark";
                                                    break;
                                                case "Estimate Approved":
                                                    $status_class = "bg-success";
                                                    break;
                                                case "Project in Progress":
                                                    $status_class = "bg-primary";
                                                    break;
                                                case "Project Completed":
                                                    $status_class = "bg-success";
                                                    break;
                                                case "Project Invoiced":
                                                    $status_class = "bg-warning";
                                                    break;
                                                case "Project Done":
                                                    $status_class = "bg-success";
                                                    break;
                                            }
                                    ?>
                                            <tr>
                                                <td><?php echo $row['request_id']; ?></td>
                                                <td><?php echo htmlspecialchars($row['homeowner_name']); ?></td>
                                                <td><?php echo htmlspecialchars($row['address']); ?></td>
                                                <td><?php echo htmlspecialchars($row['phone1']); ?></td>
                                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                                <td>
                                                    <?php
                                                    $desc = strip_tags($row['work_description']);
                                                    echo strlen($desc) > 50 ? substr($desc, 0, 50) . "..." : $desc;
                                                    ?>
                                                </td>
                                                <td><span class="badge <?php echo $status_class; ?>"><?php echo $status_text; ?></span></td>
                                                <td><?php echo $row['created_at']; ?></td>
                                                <td>
                                                    <a href="request-profile.php?id=<?php echo $row['request_id']; ?>" class="btn btn-sm btn-success">See More</a>
                                                </td>
                                            </tr>
                                    <?php
                                        }
                                    } else {
                                        echo "<tr><td colspan='9' class='text-center'>No Requests found</td></tr>";
                                    }
                                    ?>
                                </tbody>

                            </table>


                            <!-- End Table with stripped rows -->

                        </div>
                    </div>

                </div>
            </div>
        </section>

    </main>
    <!-- End #main -->

    <!-- ======= Footer ======= -->
    <?php include "includes/footer.php" ?>
    <!-- End Footer -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <?php include "includes/scripts.php" ?>

</body>

</html>