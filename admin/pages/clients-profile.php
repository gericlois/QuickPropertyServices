<!DOCTYPE html>
<html lang="en">
<?php
session_start();
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php?error=AccessDenied");
} else {
    include "includes/head.php";
    include "scripts/connection.php";
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid employee listing.");
}

$employee_id = intval($_GET['id']); // Sanitize input

// Fetch employee details
$stmt = $conn->prepare("SELECT *, u.user_id, u.first_name, u.last_name, u.email, u.phone, u.address, u.created_at, c.status 
                                    FROM users u
                                    LEFT JOIN clients c ON u.user_id = c.user_id WHERE u.user_id = ?");
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("employee not found.");
}

$employee = $result->fetch_assoc(); // Now, $employee is properly set
$stmt->close();

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
            <h1>Profile</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item"><a href="clients.php">Users</a></li>
                    <li class="breadcrumb-item active">User's Profile</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section profile">
            <div class="row">
                <div class="col-xl-4">

                    <div class="card">
                        <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                            <i class="bi bi-person-circle fs-4"></i>
                            <h2><?php echo htmlspecialchars($employee['first_name']); ?>
                                <?php echo htmlspecialchars($employee['last_name']); ?></h2>
                            <h3><?php
                                if ($employee['status'] == "1") {
                                    echo ' <span class="badge bg-primary"><i class="bi bi-check-circle me-1"></i> Active</span>';
                                } else if ($employee['status'] == "2") {
                                    echo ' <span class="badge bg-primary"><i class="bi bi-exclamation-octagon me-1"></i> Inactive</span>';
                                }
                            ?></h3>
                            <div class="social-links mt-2">
                                <a href="<?php echo htmlspecialchars($employee['link_facebook']); ?>" class="facebook"
                                    target="_blank" rel="noopener noreferrer"><i class="bi bi-facebook"></i> </a>
                                <a href="<?php echo htmlspecialchars($employee['link_instagram']); ?>" class="instagram"
                                    target="_blank" rel="noopener noreferrer"><i class="bi bi-instagram"></i></a>
                                <a href="<?php echo htmlspecialchars($employee['link_linkedin']); ?>" class="linkedin"
                                    target="_blank" rel="noopener noreferrer"><i class="bi bi-linkedin"></i></a>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="col-xl-8">

                    <div class="card">
                        <div class="card-body pt-3">
                            <!-- Bordered Tabs -->
                            <ul class="nav nav-tabs nav-tabs-bordered">

                                <li class="nav-item">
                                    <button class="nav-link active" data-bs-toggle="tab"
                                        data-bs-target="#profile-overview">Overview</button>
                                </li>

                                <li class="nav-item">
                                    <button class="nav-link" data-bs-toggle="tab"
                                        data-bs-target="#profile-activity">Activity</button>
                                </li>

                            </ul>
                            <div class="tab-content pt-2">

                                <div class="tab-pane fade show active profile-overview" id="profile-overview">

                                    <h5 class="card-title">Profile Details</h5>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label ">Email</div>
                                        <div class="col-lg-9 col-md-8">
                                            <?php echo htmlspecialchars($employee['email']); ?></div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Phone</div>
                                        <div class="col-lg-9 col-md-8">
                                            <?php echo htmlspecialchars($employee['phone']); ?></div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Address</div>
                                        <div class="col-lg-9 col-md-8">
                                            <?php echo htmlspecialchars($employee['address']); ?></div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Created At</div>
                                        <div class="col-lg-9 col-md-8">
                                            <?php echo htmlspecialchars($employee['created_at']); ?></div>
                                    </div>

                                </div>


                            </div><!-- End Bordered Tabs -->

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