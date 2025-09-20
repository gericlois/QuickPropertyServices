<!DOCTYPE html>
<html lang="en">
<!DOCTYPE html>
<html lang="en">
<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'provider') {
  header("Location: login.php?error=AccessDenied");
} else {
    include "includes/head.php";
    include "../admin/pages/scripts/connection.php";
}

$user_id = $_SESSION['user_id'];

// Fetch provider details
$stmt = $conn->prepare("
    SELECT u.first_name, u.last_name, u.email, u.phone, u.address, u.birthday, u.password,
           u.link_facebook, u.link_linkedin, u.link_instagram, u.role, p.business_name, p.work, p.status 
    FROM users u
    JOIN providers p ON u.user_id = p.user_id
    WHERE u.user_id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$provider = $result->fetch_assoc();

if (!$provider) {
    echo "Provider profile not found.";
    exit();
}
?>

<?php
$role = $_SESSION['role'] ?? 'guest'; // fallback if not logged in
?>
<body class="index-page <?php echo $role; ?>">

    <?php include "includes/header.php" ?>

    <main class="main">

        <!-- Contact Section -->
        <section id="features" class="features section light-background">

            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>My Profile</h2>
                <p>Manage your personal information and account settings</p>
            </div>
            <!-- End Section Title -->

            <div class="container">

                <div class="d-flex justify-content-center">

                    <ul class="nav nav-tabs" data-aos="fade-up" data-aos-delay="100">

                        <li class="nav-item">
                            <a class="nav-link active show" data-bs-toggle="tab" data-bs-target="#features-tab-1">
                                <h4>Personal Information</h4>
                            </a>
                        </li><!-- End tab nav item -->

                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" data-bs-target="#features-tab-2">
                                <h4>Business Information</h4>
                            </a><!-- End tab nav item -->

                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" data-bs-target="#features-tab-3">
                                <h4>Settings</h4>
                            </a>
                        </li><!-- End tab nav item -->

                    </ul>

                </div>

                <div class="tab-content" data-aos="fade-up" data-aos-delay="200">

                    <div class="tab-pane fade active show" id="features-tab-1">
                        <div class="row">
                            <div
                                class="col-lg-12 order-2 order-lg-1 mt-3 mt-lg-0 d-flex flex-column justify-content-center">
                                <?php
                            if (isset($_GET['success'])) {
                                if ($_GET["success"] == "ProfileUpdatedSuccessfully") {
                                    echo '
                                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                                        <b>Profile Basic Information updated successfully! Please review the details to ensure everything is correct.</b>
                                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                        </div>';
                                }
                                if ($_GET["success"] == "ProviderBusinessUpdated") {
                                    echo '
                                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                                        <b>Profile Business Information updated successfully! Please review the details to ensure everything is correct.</b>
                                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                        </div>';
                                }
                                if ($_GET["success"] == "PasswordUpdated") {
                                    echo '
                                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                                        <b>Profile Password updated successfully! Please review the details to ensure everything is correct.</b>
                                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                        </div>';
                                }
                            }
                            ?>
                                <h3>Personal Information</h3>
                                <form action="scripts/update_provider_basicinfo.php" method="POST">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="first_name" class="form-label">First Name</label>
                                            <input type="text" class="form-control" id="first_name" name="first_name"
                                                value="<?= htmlspecialchars($provider['first_name']) ?>" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="last_name" class="form-label">Last Name</label>
                                            <input type="text" class="form-control" id="last_name" name="last_name"
                                                value="<?= htmlspecialchars($provider['last_name']) ?>" required>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="email" name="email"
                                                value="<?= htmlspecialchars($provider['email']) ?>" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="phone" class="form-label">Phone</label>
                                            <input type="text" class="form-control" id="phone" name="phone"
                                                value="<?= htmlspecialchars($provider['phone']) ?>">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="address" class="form-label">Address</label>
                                            <input type="text" class="form-control" id="address" name="address"
                                                value="<?= htmlspecialchars($provider['address']) ?>">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="birthday" class="form-label">Birthday</label>
                                            <input type="date" class="form-control" id="birthday" name="birthday"
                                                value="<?= htmlspecialchars($provider['birthday']) ?>">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="role" class="form-label">Role</label>
                                            <select class="form-control" id="role" name="role" disabled>
                                                <option value="user"
                                                    <?= ($provider['role'] == 'user') ? 'selected' : '' ?>>User</option>
                                                <option value="provider"
                                                    <?= ($provider['role'] == 'provider') ? 'selected' : '' ?>>Provider
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="link_facebook" class="form-label">Facebook</label>
                                            <input type="url" class="form-control" id="link_facebook"
                                                name="link_facebook"
                                                value="<?= htmlspecialchars($provider['link_facebook']) ?>">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="link_linkedin" class="form-label">LinkedIn</label>
                                            <input type="url" class="form-control" id="link_linkedin"
                                                name="link_linkedin"
                                                value="<?= htmlspecialchars($provider['link_linkedin']) ?>">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="link_instagram" class="form-label">Instagram</label>
                                            <input type="url" class="form-control" id="link_instagram"
                                                name="link_instagram"
                                                value="<?= htmlspecialchars($provider['link_instagram']) ?>">
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary btn-sm">Save Changes</button>
                                </form>
                            </div>
                        </div>
                    </div><!-- End tab content item -->

                    <div class="tab-pane fade" id="features-tab-2">
                        <div class="row">
                            <div
                                class="col-lg-12 order-2 order-lg-1 mt-3 mt-lg-0 d-flex flex-column justify-content-center">
                                <h3>Business Information</h3>
                                <form action="scripts/update_provider_businessinfo.php" method="POST">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="business_name" class="form-label">Business Name</label>
                                            <input type="text" class="form-control" id="business_name"
                                                name="business_name"
                                                value="<?= htmlspecialchars($provider['business_name']) ?>">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="work" class="form-label">Work</label>
                                            <input type="text" class="form-control" id="work" name="work"
                                                value="<?= htmlspecialchars($provider['work']) ?>">
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-sm">Save Changes</button>
                                </form>
                            </div>
                        </div>
                    </div><!-- End tab content item -->

                    <div class="tab-pane fade" id="features-tab-3">
                        <div class="row">
                            <div
                                class="col-lg-12 order-2 order-lg-1 mt-3 mt-lg-0 d-flex flex-column justify-content-center">
                                <h3>Settings</h3>
                                <form action="scripts/update_provider_password.php" method="POST">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="password" class="form-label">New Password</label>
                                            <input type="password" class="form-control" id="password" name="password"
                                                required>
                                            <br>
                                            <button type="submit" class="btn btn-primary btn-sm">Save Changes</button>
                                        </div>
                                </form>
                                <div class="col-md-6 mb-3 text-center">
                                    <hr>
                                    <label for="last_name" class="form-label">Account Deactivation:</label>
                                    <a href='scripts/update_provider_status.php?id=<?php echo $user_id; ?>&status=2'
                                        class='btn btn-sm btn-danger'
                                        onclick='return confirm("Are you sure you want to Deactivate this account?")'>Deactivate</a>
                                    <hr>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- End tab content item -->

            </div>

            </div>

        </section><!-- /Features Section -->
        <!-- /Contact Section -->

    </main>

    <?php include "includes/footer.php" ?>

    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <?php include "includes/script.php" ?>

</body>

</html>