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

    <?php include "includes/header.php" ?>
    <?php include "includes/sidebar.php" ?>

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Services</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item active">Services</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <?php
        if (isset($_GET['success'])) {
            if ($_GET["success"] == "ServiceUpdated") {
                echo '
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <b>The Service #' . htmlspecialchars($_GET["service_id"]) . ' has been updated successfully!</b>
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
                            <h5 class="card-title">Services Table</h5>
                            <p>This table provides a full list of registered services and their associated providers.
                                You can sort, search, and manage service details directly from here.</p>

                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>Service ID</th>
                                        <th>Service Name</th>
                                        <th>Provider</th>
                                        <th>Base Price</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT s.service_id, s.service_name, s.description, s.base_price, s.status, s.created_at,
                                                    p.business_name,
                                                    u.first_name, u.last_name
                                            FROM services s
                                            LEFT JOIN providers p ON s.provider_id = p.provider_id
                                            LEFT JOIN users u ON p.user_id = u.user_id";

                                    $result = $conn->query($sql);

                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {

                                            $status_class = "bg-secondary";
                                            $status_text = "Unknown";

                                            if ($row['status'] == "1") {
                                                $status_class = "bg-primary";
                                                $status_text = "Active";
                                            } elseif ($row['status'] == "2") {
                                                $status_class = "bg-danger";
                                                $status_text = "Inactive";
                                            }

                                            $provider_name = $row['business_name'] ? $row['business_name'] : $row['first_name'] . ' ' . $row['last_name'];
                                            ?>
                                    <tr>
                                        <td><?php echo $row['service_id']; ?></td>
                                        <td><?php echo $row['service_name']; ?></td>
                                        <td>
                                            <a href='providers-profile.php?id=<?php echo $row['provider_id']; ?>'
                                                class='text-primary fw-bold'>
                                                <?php echo htmlspecialchars($provider_name); ?>
                                            </a>
                                        </td>
                                        <td>₱<?php echo number_format($row['base_price'], 2); ?></td>
                                        <td><span
                                                class="badge <?php echo $status_class; ?>"><?php echo $status_text; ?></span>
                                        </td>
                                        <td><?php echo $row['created_at']; ?></td>
                                        <td>
                                            <a href='services-profile.php?id=<?php echo $row['service_id']; ?>'
                                                class='btn btn-sm btn-success'>
                                                View
                                            </a>
                                            <a href='services-edit.php?id=<?php echo $row['service_id']; ?>'
                                                class='btn btn-sm btn-warning'>Edit</a>
                                            <?php if ($row['status'] == "1") { ?>
                                            <a href='scripts/service-update.php?id=<?php echo $row['service_id']; ?>&status=2'
                                                class='btn btn-sm btn-dark'
                                                onclick='return confirm("Deactivate this service?")'>Deactivate</a>
                                            <?php } else { ?>
                                            <a href='scripts/service-update.php?id=<?php echo $row['service_id']; ?>&status=1'
                                                class='btn btn-sm btn-primary'
                                                onclick='return confirm("Activate this service?")'>Activate</a>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <?php
                                        }
                                    } else {
                                        echo "<tr><td colspan='8' class='text-center'>No services found</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main><!-- End #main -->

    <?php include "includes/footer.php" ?>
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>
    <?php include "includes/scripts.php" ?>

</body>

</html>