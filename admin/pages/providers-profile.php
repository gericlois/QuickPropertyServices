<?php
session_start();
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php?error=AccessDenied");
    exit();
}

include "includes/head.php";
include "scripts/connection.php";

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid provider listing.");
}

$provider_id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT u.first_name, u.last_name, u.email, u.phone, u.address, u.created_at, 
                               u.link_facebook, u.link_instagram, u.link_linkedin,
                               p.status
                        FROM users u
                        LEFT JOIN providers p ON u.user_id = p.user_id
                        WHERE u.user_id = ?");
$stmt->bind_param("i", $provider_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Provider not found.");
}

$provider = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<?php include "includes/head.php"; ?>

<body>
    <?php include "includes/header.php"; ?>
    <?php include "includes/sidebar.php"; ?>

    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Provider Profile</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item"><a href="providers.php">Providers</a></li>
                    <li class="breadcrumb-item active">Provider's Profile</li>
                </ol>
            </nav>
        </div>

        <section class="section profile">
            <div class="row">
                <div class="col-xl-4">
                    <div class="card">
                        <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                            <i class="bi bi-person-circle fs-4"></i>
                            <h2><?php echo htmlspecialchars($provider['first_name']); ?> <?php echo htmlspecialchars($provider['last_name']); ?></h2>

                            <h3>
                                <?php
                                if ($provider['status'] == "1") {
                                    echo '<span class="badge bg-primary"><i class="bi bi-check-circle me-1"></i> Active</span>';
                                } else {
                                    echo '<span class="badge bg-secondary"><i class="bi bi-exclamation-octagon me-1"></i> Inactive</span>';
                                }
                                ?>
                            </h3>
                            <div class="social-links mt-2">
                                <a href="<?php echo htmlspecialchars($provider['link_facebook']); ?>" class="facebook" target="_blank"><i class="bi bi-facebook"></i></a>
                                <a href="<?php echo htmlspecialchars($provider['link_instagram']); ?>" class="instagram" target="_blank"><i class="bi bi-instagram"></i></a>
                                <a href="<?php echo htmlspecialchars($provider['link_linkedin']); ?>" class="linkedin" target="_blank"><i class="bi bi-linkedin"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-8">
                    <div class="card">
                        <div class="card-body pt-3">
                            <ul class="nav nav-tabs nav-tabs-bordered">
                                <li class="nav-item">
                                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">Overview</button>
                                </li>
                            </ul>

                            <div class="tab-content pt-2">
                                <div class="tab-pane fade show active profile-overview" id="profile-overview">
                                    <h5 class="card-title">Provider Details</h5>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Email</div>
                                        <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($provider['email']); ?></div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Phone</div>
                                        <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($provider['phone']); ?></div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Address</div>
                                        <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($provider['address']); ?></div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Created At</div>
                                        <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($provider['created_at']); ?></div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Services</div>
                                        <div class="col-lg-9 col-md-8">
                                            <ul>
                                                <?php
                                                $services_stmt = $conn->prepare("SELECT * FROM services WHERE provider_id = ?");
                                                $services_stmt->bind_param("i", $provider_id);
                                                $services_stmt->execute();
                                                $services_result = $services_stmt->get_result();
                                                while ($service = $services_result->fetch_assoc()) {
                                                    echo '<li><a href="services-profile.php?id=' . urlencode($service['service_id']) . '">' . htmlspecialchars($service['service_name']) . '</a></li>';

                                                }
                                                $services_stmt->close();
                                                ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include "includes/footer.php"; ?>
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
    <?php include "includes/scripts.php"; ?>
</body>
</html>
