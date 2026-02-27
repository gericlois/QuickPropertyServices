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
                    <h5 class="m-b-10">Admin Users</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item">Admin Users</li>
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
                            <h5 class="card-title"> Admin User Data Table</h5>
                            <p>View and manage registered Admin Users in a structured table format. This table allows sorting,
                                searching, and pagination, making it easier to track user information and perform
                                administrative actions efficiently.</p>

                            <!-- Table with stripped rows -->
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>User ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Registered Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT user_id, first_name, last_name, email, phone, address, status, created_at from users
                                    WHERE role = 'admin';";
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
                                                $status_text = "Banned";
                                            }
                                    ?>
                                            <tr>
                                                <td><?php echo $row['user_id']; ?></td>
                                                <td><?php echo $row['first_name'] . " " . $row['last_name']; ?></td>
                                                <!-- Fix: Concatenate first and last name -->
                                                <td><?php echo $row['email']; ?></td>
                                                <td><?php echo $row['created_at']; ?></td> <!-- Fix: Correct column name -->
                                                <td><span
                                                        class="badge <?php echo $status_class; ?>"><?php echo $status_text; ?></span>
                                                </td>
                                                <td>
                                                    <?php if ($row['status'] == "2") { ?>
                                                        <a href='scripts/user-update.php?id=<?php echo $row['user_id']; ?>&status=1'
                                                            class='btn btn-sm btn-primary'
                                                            onclick='return confirm("Are you sure you want to activate this user?")'>Activate</a>
                                                    <?php } elseif ($row['status'] == "1") { ?>
                                                        <a href='scripts/user-update.php?id=<?php echo $row['user_id']; ?>&status=2'
                                                            class='btn btn-sm btn-dark'
                                                            onclick='return confirm("Are you sure you want to deactivate this user?")'>Deactivate</a>
                                                    <?php } ?>
                                                </td>
                                            </tr>

                                    <?php
                                        }
                                    } else {
                                        echo "<tr><td colspan='7' class='text-center'>No users found</td></tr>";
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
