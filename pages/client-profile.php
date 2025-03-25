<!DOCTYPE html>
<html lang="en">
<?php
session_start();
include "includes/head.php"; // Include head after validation
include "../admin/pages/scripts/connection.php"; // Ensure this is correctly included

// Check if the user is logged in and has the correct role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'client') {
    header("Location: login.php?error=AccessDenied");
    exit();
}

$user_id = $_SESSION['user_id']; // Use dynamic user ID from session

// Prepare the statement to fetch client details
$stmt = $conn->prepare("
    SELECT first_name, last_name, email, phone, address, birthday, password,
           link_facebook, link_linkedin, link_instagram, role
    FROM users
    WHERE user_id = ?
");

if (!$stmt) {
    die("Database error: " . $conn->error);
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$client = $result->fetch_assoc();

// If no client is found
if (!$client) {
    echo "<p style='color: red;'>Client profile not found.</p>";
    exit();
}

?>


<body class="index-page">
    <?php include "includes/header.php" ?>

    <main class="main">
        <section id="features" class="features section light-background">
            <div class="container section-title" data-aos="fade-up">
                <h2>My Profile</h2>
                <p>Manage your personal information and account settings</p>
            </div>

            <div class="container">
                <div class="d-flex justify-content-center">
                    <ul class="nav nav-tabs" data-aos="fade-up" data-aos-delay="100">
                        <li class="nav-item">
                            <a class="nav-link active show" data-bs-toggle="tab" data-bs-target="#profile-tab-1">
                                <h4>Personal Information</h4>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-tab-2">
                                <h4>Settings</h4>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="tab-content" data-aos="fade-up" data-aos-delay="200">
                    <div class="tab-pane fade active show" id="profile-tab-1">
                        <div class="row">
                            <div class="col-lg-12 mt-3 d-flex flex-column justify-content-center">
                                <?php
                                if (isset($_GET['success'])) {
                                    if ($_GET["success"] == "ProfileUpdatedSuccessfully") {
                                        echo '
                                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                                <b>Profile information updated successfully!</b>
                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                            </div>';
                                    }
                                    if ($_GET["success"] == "PasswordUpdated") {
                                        echo '
                                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                                <b>Password updated successfully!</b>
                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                            </div>';
                                    }
                                }
                                ?>
                                <h3>Personal Information</h3>
                                <form action="scripts/update_client_basicinfo.php" method="POST">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="first_name" class="form-label">First Name</label>
                                            <input type="text" class="form-control" id="first_name" name="first_name"
                                                value="<?= htmlspecialchars($client['first_name']) ?>" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="last_name" class="form-label">Last Name</label>
                                            <input type="text" class="form-control" id="last_name" name="last_name"
                                                value="<?= htmlspecialchars($client['last_name']) ?>" required>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="email" name="email"
                                                value="<?= htmlspecialchars($client['email']) ?>" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="phone" class="form-label">Phone</label>
                                            <input type="text" class="form-control" id="phone" name="phone"
                                                value="<?= htmlspecialchars($client['phone']) ?>">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="address" class="form-label">Address</label>
                                            <input type="text" class="form-control" id="address" name="address"
                                                value="<?= htmlspecialchars($client['address']) ?>">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="birthday" class="form-label">Birthday</label>
                                            <input type="date" class="form-control" id="birthday" name="birthday"
                                                value="<?= htmlspecialchars($client['birthday']) ?>">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="role" class="form-label">Role</label>
                                            <select class="form-control" id="role" name="role" disabled>
                                                <option value="client" selected>Client</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="link_facebook" class="form-label">Facebook</label>
                                            <input type="url" class="form-control" id="link_facebook"
                                                name="link_facebook"
                                                value="<?= htmlspecialchars($client['link_facebook']) ?>">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="link_linkedin" class="form-label">LinkedIn</label>
                                            <input type="url" class="form-control" id="link_linkedin"
                                                name="link_linkedin"
                                                value="<?= htmlspecialchars($client['link_linkedin']) ?>">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="link_instagram" class="form-label">Instagram</label>
                                            <input type="url" class="form-control" id="link_instagram"
                                                name="link_instagram"
                                                value="<?= htmlspecialchars($client['link_instagram']) ?>">
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary btn-sm">Save Changes</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="profile-tab-2">
                        <div class="row">
                            <div class="col-lg-12 mt-3 d-flex flex-column justify-content-center">
                                <h3>Settings</h3>
                                <p>Settings options will be added here.</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </main>
    <?php include "includes/footer.php" ?>

    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <?php include "includes/script.php" ?>

</body>

</html>