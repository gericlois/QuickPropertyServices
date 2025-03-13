<!DOCTYPE html>
<html lang="en">
<?php
session_start();
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
  header("Location: login.php?error=AccessDenied");
} else {
    include "includes/head.php";
    include "scripts/connection.php";
}?>

<body>

    <!-- ======= Header ======= -->
    <?php include "includes/header.php" ?>
    <!-- End Header -->

    <!-- ======= Sidebar ======= -->
    <?php include "includes/sidebar.php" ?>
    <!-- End Sidebar-->

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Providers
                <a href="providers-add.php" class="btn btn-primary rounded-pill">
                    <i class="bi bi-plus-circle me-1"></i> Add Provider
                </a>
            </h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item active">Providers</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->
                            <?php
                            if (isset($_GET['success'])) {
                                if (isset($_GET["success"]) && $_GET["success"] == "ProviderAddedSuccessfully") {
                                    echo '
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <b>A Provider Account  #00' . htmlspecialchars($_GET["provider_id"]) . ' has been successfully added! Please review the changes.</b>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>';
                                }
                                
                                if ($_GET["success"] == "ProviderUpdated") {
                                    echo '
                                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                                            <b>The Provider Account #00' . htmlspecialchars($_GET["provider_id"]) . ' has been successfully updated!</b> Review the updated details to ensure accuracy.
                                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                        </div>';
                                }
                                if ($_GET["success"] == "StatusUpdated") {
                                    echo '
                                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                                            <b>The Provider Account #00' . htmlspecialchars($_GET["provider_id"]) . ' has been successfully updated its STATUS!</b> Review the updated details to ensure accuracy.
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
                            <h5 class="card-title">Provider Data Table</h5>
                            <p>View and manage providers along with their associated Provider accounts.</p>

                            <!-- Table with stripped rows -->
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>Provider ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Business Name</th>
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
                                        u.address, 
                                        u.role, 
                                        u.created_at AS user_created_at,
                                        p.provider_id, 
                                        p.business_name, 
                                        p.status, 
                                        p.created_at AS provider_created_at
                                    FROM users u
                                    LEFT JOIN providers p ON u.user_id = p.user_id 
                                    where u.role = 'provider'";

                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $status_class = "bg-secondary"; 
                                    $status_text = "Unknown"; // Default text in case of unexpected values
                            
                                    if ($row['status'] == "1") {
                                        $status_class = "bg-primary";
                                        $status_text = "Active";
                                    } elseif ($row['status'] == "2") { 
                                        $status_class = "bg-danger";
                                        $status_text = "Inactive";
                                    } elseif ($row['status'] == "3") {
                                        $status_class = "bg-warning";
                                        $status_text = "Pending";
                                    }
                            ?>
                                    <tr>
                                        <td><?php echo $row['provider_id']; ?></td>
                                        <td><?php echo $row['first_name'] . " " . $row['last_name']; ?></td>
                                        <td><?php echo $row['email']; ?></td>
                                        <td><?php echo $row['phone']; ?></td>
                                        <td><?php echo $row['business_name'] ? $row['business_name'] : 'N/A'; ?></td>
                                        <td>
                                            <span class="badge <?php echo $status_class; ?>">
                                                <?php echo $status_text; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href='providers-profile.php?id=<?php echo $row['provider_id']; ?>'
                                                class='btn btn-sm btn-success'>View</a>
                                            <a href='providers-edit.php?id=<?php echo $row['provider_id']; ?>'
                                                class='btn btn-sm btn-warning'>Edit</a>

                                            <?php if ($row['status'] == "1") { ?>
                                            <a href='scripts/provider-update.php?id=<?php echo $row['provider_id']; ?>&status=2'
                                                class='btn btn-sm btn-dark'
                                                onclick='return confirm("Are you sure you want to deactivate this provider?")'>Deactivate</a>
                                            <?php } else if ($row['status'] == "2") { ?>
                                            <a href='scripts/provider-update.php?id=<?php echo $row['provider_id']; ?>&status=1'
                                                class='btn btn-sm btn-primary'
                                                onclick='return confirm("Are you sure you want to activate this provider?")'>Activate</a>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                echo "<tr><td colspan='10' class='text-center'>No providers found</td></tr>";
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