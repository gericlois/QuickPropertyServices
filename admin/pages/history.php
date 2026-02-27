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
                    <h5 class="m-b-10">Activity History</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item">Activity History</li>
                </ul>
            </div>
        </div>
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
                            <h5 class="card-title"> Activity History Table</h5>
                            <p>View Activity History in a structured table format. This table allows sorting,
                                searching, and pagination, making it easier to track user information and perform
                                administrative actions efficiently.</p>

                            <!-- Table with stripped rows -->
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>History ID</th>
                                        <th>Admin</th>
                                        <th>Action</th>
                                        <th>Details</th>
                                        <th>IP Address</th>
                                        <th>User Agent</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Fetch history with admin username
                                    $sql = "SELECT h.history_id, h.action, h.details, h.ip_address, h.user_agent, h.created_at, 
                                        u.first_name, u.last_name 
                                    FROM admin_history h
                                    JOIN users u ON h.admin_id = u.user_id
                                    ORDER BY h.created_at DESC";

                                    $result = $conn->query($sql);

                                    if ($result && $result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                    ?>
                                            <tr>
                                                <td><?php echo $row['history_id']; ?></td>
                                                <td><?php echo $row['first_name'] . " " . $row['last_name']; ?></td>
                                                <td><?php echo $row['action']; ?></td>
                                                <td><?php echo $row['details'] ?? '-'; ?></td>
                                                <td><?php echo $row['ip_address'] ?? '-'; ?></td>
                                                <td><?php echo $row['user_agent'] ?? '-'; ?></td>
                                                <td><?php echo $row['created_at']; ?></td>
                                            </tr>
                                    <?php
                                        }
                                    } else {
                                        echo "<tr><td colspan='7' class='text-center'>No history found</td></tr>";
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

    </div>

    <?php include "includes/footer.php" ?>

    <!-- Vendor JS Files -->
    <?php include "includes/scripts.php" ?>

</body>

</html>
