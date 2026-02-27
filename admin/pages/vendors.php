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
                    <h5 class="m-b-10">Vendors</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item">Vendors</li>
                </ul>
            </div>
        </div>
        <?php
        if (isset($_GET['success'])) {
            if ($_GET["success"] == "VendorAddedSuccessfully") {
                echo '
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <b>A new Vendor account has been successfully added!</b> Please review the changes.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
            }

            if ($_GET["success"] == "VendorUpdated") {
                echo '
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <b>The Vendor account has been successfully updated!</b> Review the updated details to ensure accuracy.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
            }

            if ($_GET["success"] == "StatusUpdated") {
                echo '
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <b>The Vendor account status has been successfully updated!</b> Review the updated details to ensure accuracy.
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
                            <h5 class="card-title">Vendor Data Table</h5>
                            <p>View and manage vendors along with their associated accounts.</p>

                            <!-- Table with stripped rows -->
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Business Name</th>
                                        <th>Specialty</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT
                                        u.user_id,
                                        u.first_name,
                                        u.last_name,
                                        u.email,
                                        u.phone,
                                        v.vendor_id,
                                        v.business_name,
                                        v.specialty,
                                        v.status,
                                        v.created_at
                                    FROM users u
                                    INNER JOIN vendors v ON u.user_id = v.user_id
                                    WHERE u.role = 'vendor'
                                    ORDER BY v.created_at DESC";

                                    $result = $conn->query($sql);

                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            $status_class = "bg-secondary";
                                            $status_text = "Inactive";

                                            if ($row['status'] == "1") {
                                                $status_class = "bg-primary";
                                                $status_text = "Active";
                                            } elseif ($row['status'] == "2") {
                                                $status_class = "bg-secondary";
                                                $status_text = "Inactive";
                                            }
                                    ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($row['vendor_id']); ?></td>
                                                <td><?php echo htmlspecialchars($row['first_name'] . " " . $row['last_name']); ?></td>
                                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                                <td><?php echo htmlspecialchars($row['phone']); ?></td>
                                                <td><?php echo $row['business_name'] ? htmlspecialchars($row['business_name']) : 'N/A'; ?></td>
                                                <td><?php echo $row['specialty'] ? htmlspecialchars($row['specialty']) : 'N/A'; ?></td>
                                                <td>
                                                    <span class="badge <?php echo $status_class; ?>">
                                                        <?php echo $status_text; ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href='vendor-profile.php?id=<?php echo $row['vendor_id']; ?>'
                                                        class='btn btn-sm btn-success'>View</a>
                                                    <a href='vendor-edit.php?id=<?php echo $row['vendor_id']; ?>'
                                                        class='btn btn-sm btn-warning'>Edit</a>

                                                    <?php if ($row['status'] == "1") { ?>
                                                        <a href='scripts/vendor-update.php?id=<?php echo $row['vendor_id']; ?>&status=2'
                                                            class='btn btn-sm btn-dark'
                                                            onclick='return confirm("Are you sure you want to deactivate this vendor?")'>Deactivate</a>
                                                    <?php } else { ?>
                                                        <a href='scripts/vendor-update.php?id=<?php echo $row['vendor_id']; ?>&status=1'
                                                            class='btn btn-sm btn-primary'
                                                            onclick='return confirm("Are you sure you want to activate this vendor?")'>Activate</a>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                    <?php
                                        }
                                    } else {
                                        echo "<tr><td colspan='8' class='text-center'>No vendors found</td></tr>";
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
