<!DOCTYPE html>
<html lang="en">
<?php
session_start();
if (!isset($_SESSION['homeowner_id']) || $_SESSION['role'] !== 'homeowner') {
    header("Location: homeowner-login.php");
    exit;
}

require '../admin/pages/scripts/connection.php';

// Fetch current homeowner data
$stmt = $conn->prepare("SELECT name, email, phone FROM homeowners WHERE homeowner_id = ?");
$stmt->bind_param("i", $_SESSION['homeowner_id']);
$stmt->execute();
$homeowner = $stmt->get_result()->fetch_assoc();
$stmt->close();
?>
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>My Profile | Quick Property Services</title>
  <link href="../assets/img/logo.jpg" rel="icon">
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="../assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="../assets/css/main.css" rel="stylesheet">
</head>
<body class="index-page">
    <?php include "includes/header.php" ?>

    <main class="main">

        <section class="contact section light-background">
            <div class="container" data-aos="fade-up" data-aos-delay="100">

                <div class="text-center mb-4">
                    <h3 class="mb-1">My Profile</h3>
                    <p class="text-muted">Update your personal information and password</p>
                </div>

                <div class="row g-4 justify-content-center">

                    <!-- Profile Information -->
                    <div class="col-lg-6">
                        <div class="card shadow-sm">
                            <div class="card-body p-4">
                                <h5 class="card-title mb-3"><i class="bi bi-person me-2"></i>Personal Information</h5>

                                <?php if (isset($_GET['profile_success'])): ?>
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <b>Profile updated successfully!</b>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                <?php endif; ?>

                                <?php if (isset($_GET['profile_error'])): ?>
                                    <?php if ($_GET['profile_error'] == 'MissingFields'): ?>
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <b>Please fill in all required fields.</b>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    <?php elseif ($_GET['profile_error'] == 'UpdateFailed'): ?>
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <b>Something went wrong. Please try again.</b>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <form action="scripts/homeowner-profile-update.php" method="post">
                                    <div class="row gy-3">
                                        <div class="col-12">
                                            <label class="form-label fw-semibold">Full Name</label>
                                            <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($homeowner['name']); ?>" required>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label fw-semibold">Email Address</label>
                                            <input type="email" class="form-control" value="<?php echo htmlspecialchars($homeowner['email']); ?>" readonly disabled>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label fw-semibold">Phone Number</label>
                                            <input type="text" class="form-control" name="phone" value="<?php echo htmlspecialchars($homeowner['phone'] ?? ''); ?>" required>
                                        </div>
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary w-100"><i class="bi bi-check-lg me-1"></i>Save Changes</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Change Password -->
                    <div class="col-lg-6">
                        <div class="card shadow-sm">
                            <div class="card-body p-4">
                                <h5 class="card-title mb-3"><i class="bi bi-lock me-2"></i>Change Password</h5>

                                <?php if (isset($_GET['pw_success'])): ?>
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <b>Password changed successfully!</b>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                <?php endif; ?>

                                <?php if (isset($_GET['pw_error'])): ?>
                                    <?php if ($_GET['pw_error'] == 'MissingFields'): ?>
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <b>Please fill in all password fields.</b>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    <?php elseif ($_GET['pw_error'] == 'WrongPassword'): ?>
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <b>Current password is incorrect.</b>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    <?php elseif ($_GET['pw_error'] == 'PasswordMismatch'): ?>
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <b>New passwords do not match.</b>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    <?php elseif ($_GET['pw_error'] == 'PasswordTooShort'): ?>
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <b>New password must be at least 6 characters.</b>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    <?php elseif ($_GET['pw_error'] == 'UpdateFailed'): ?>
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <b>Something went wrong. Please try again.</b>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <form action="scripts/homeowner-password-update.php" method="post">
                                    <div class="row gy-3">
                                        <div class="col-12">
                                            <label class="form-label fw-semibold">Current Password</label>
                                            <input type="password" class="form-control" name="current_password" required>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label fw-semibold">New Password</label>
                                            <input type="password" class="form-control" name="new_password" placeholder="Minimum 6 characters" required minlength="6">
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label fw-semibold">Confirm New Password</label>
                                            <input type="password" class="form-control" name="confirm_password" required minlength="6">
                                        </div>
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-dark w-100"><i class="bi bi-shield-lock me-1"></i>Change Password</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </section>

    </main>

    <?php include "includes/footer.php" ?>

    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <?php include "includes/script.php" ?>
</body>
</html>
