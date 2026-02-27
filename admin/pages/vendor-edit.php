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
}

if (!isset($_GET['id'])) {
    die("Vendor ID not provided.");
}

$vendor_id = intval($_GET['id']);

// Fetch vendor and user details
$sql = "SELECT
            v.vendor_id,
            u.user_id,
            u.first_name,
            u.last_name,
            u.email,
            u.phone,
            v.business_name,
            v.specialty,
            v.status
        FROM vendors v
        JOIN users u ON v.user_id = u.user_id
        WHERE v.vendor_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $vendor_id);
$stmt->execute();
$result = $stmt->get_result();
$vendor = $result->fetch_assoc();
$stmt->close();

if (!$vendor) {
    die("Vendor not found.");
}
?>

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
                    <li class="breadcrumb-item"><a href="vendors.php">Vendors</a></li>
                    <li class="breadcrumb-item">Edit Vendor</li>
                </ul>
            </div>
        </div>


        <section class="section">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Edit Vendor</h5>

                            <form action="scripts/vendor-update.php" method="POST">
                                <input type="hidden" name="vendor_id" value="<?php echo htmlspecialchars($vendor['vendor_id']); ?>">
                                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($vendor['user_id']); ?>">

                                <div class="row mb-3">
                                    <label for="first_name" class="col-sm-3 col-form-label">First Name</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="first_name" name="first_name"
                                            value="<?php echo htmlspecialchars($vendor['first_name']); ?>" required>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="last_name" class="col-sm-3 col-form-label">Last Name</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="last_name" name="last_name"
                                            value="<?php echo htmlspecialchars($vendor['last_name']); ?>" required>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="email" class="col-sm-3 col-form-label">Email</label>
                                    <div class="col-sm-9">
                                        <input type="email" class="form-control" id="email" name="email"
                                            value="<?php echo htmlspecialchars($vendor['email']); ?>" required>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="new_password" class="col-sm-3 col-form-label">New Password</label>
                                    <div class="col-sm-9">
                                        <input type="password" class="form-control" id="new_password" name="new_password"
                                            placeholder="Leave blank to keep current password">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="phone" class="col-sm-3 col-form-label">Phone</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="phone" name="phone"
                                            value="<?php echo htmlspecialchars($vendor['phone']); ?>">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="business_name" class="col-sm-3 col-form-label">Business Name</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="business_name" name="business_name"
                                            value="<?php echo htmlspecialchars($vendor['business_name']); ?>" required>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="specialty" class="col-sm-3 col-form-label">Specialty</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="specialty" name="specialty"
                                            value="<?php echo htmlspecialchars($vendor['specialty']); ?>">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="status" class="col-sm-3 col-form-label">Status</label>
                                    <div class="col-sm-9">
                                        <select class="form-select" id="status" name="status">
                                            <option value="1" <?php echo ($vendor['status'] == '1') ? 'selected' : ''; ?>>Active</option>
                                            <option value="2" <?php echo ($vendor['status'] == '2') ? 'selected' : ''; ?>>Inactive</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-9 offset-sm-3">
                                        <button type="submit" class="btn btn-success">Update Vendor</button>
                                        <a href="vendors.php" class="btn btn-secondary">Cancel</a>
                                    </div>
                                </div>
                            </form>

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
