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
if (!isset($_GET['id'])) {
    die("Provider ID not provided.");
}

$provider_id = $_GET['id'];

// Fetch provider and user details
$sql = "SELECT 
            p.provider_id, 
            u.user_id, 
            u.first_name, 
            u.last_name, 
            u.email, 
            u.phone, 
            u.address, 
            p.business_name, 
            p.status
        FROM providers p
        JOIN users u ON p.user_id = u.user_id
        WHERE p.provider_id = ?";
        
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $provider_id);
$stmt->execute();
$result = $stmt->get_result();
$provider = $result->fetch_assoc();

if (!$provider) {
    die("Provider not found.");
}
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
            <h1>Providers
                <a href="providers-add.php" class="btn btn-primary rounded-pill">
                    <i class="bi bi-plus-circle me-1"></i> Add Provider
                </a>
            </h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item"><a href="providers.php">Providers</a></li>
                    <li class="breadcrumb-item active">Edit Providers</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->


        <!-- ======= Form ======= -->
        <section class="section">
            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Edit Provider</h5>

                            <form action="scripts/provider-edit.php" method="POST">
                                <input type="hidden" name="provider_id" value="<?php echo $provider['provider_id']; ?>">
                                <input type="hidden" name="user_id" value="<?php echo $provider['user_id']; ?>">

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">First Name</label>
                                            <input type="text" name="first_name" class="form-control"
                                                value="<?php echo $provider['first_name']; ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Last Name</label>
                                            <input type="text" name="last_name" class="form-control"
                                                value="<?php echo $provider['last_name']; ?>" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control"
                                        value="<?php echo $provider['email']; ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Phone</label>
                                    <input type="text" name="phone" class="form-control"
                                        value="<?php echo $provider['phone']; ?>">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Address</label>
                                    <input type="text" name="address" class="form-control"
                                        value="<?php echo $provider['address']; ?>">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Business Name</label>
                                    <input type="text" name="business_name" class="form-control"
                                        value="<?php echo $provider['business_name']; ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select">
                                        <option value="1" <?php echo ($provider['status'] == '1') ? 'selected' : ''; ?>>
                                            Active</option>
                                        <option value="0" <?php echo ($provider['status'] == '0') ? 'selected' : ''; ?>>
                                            Inactive</option>
                                    </select>
                                </div>

                                <div class="d-flex justify-content-between">
                                    <a href="providers.php" class="btn btn-secondary">Cancel</a>
                                    <button type="submit" class="btn btn-success">Update Provider</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Provider Stats</h5>

                            <!-- Polar Area Chart -->
                            <canvas id="polarAreaChart" style="max-height: 400px;"></canvas>
                            <script>
                            document.addEventListener("DOMContentLoaded", () => {
                                new Chart(document.querySelector('#polarAreaChart'), {
                                    type: 'polarArea',
                                    data: {
                                        labels: [
                                            'Red', 'Green', 'Yellow', 'Grey', 'Blue'
                                        ],
                                        datasets: [{
                                            label: 'My First Dataset',
                                            data: [11, 16, 7, 3, 14],
                                            backgroundColor: [
                                                'rgb(255, 99, 132)',
                                                'rgb(75, 192, 192)',
                                                'rgb(255, 205, 86)',
                                                'rgb(201, 203, 207)',
                                                'rgb(54, 162, 235)'
                                            ]
                                        }]
                                    }
                                });
                            });
                            </script>
                            <!-- End Polar Area Chart -->

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