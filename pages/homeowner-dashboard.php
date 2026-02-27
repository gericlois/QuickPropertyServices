<!DOCTYPE html>
<html lang="en">
<?php
session_start();
if (!isset($_SESSION['homeowner_id']) || $_SESSION['role'] !== 'homeowner') {
    header("Location: homeowner-login.php");
    exit;
}
?>
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>My Requests | Quick Property Services</title>
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

                <?php
                require '../admin/pages/scripts/connection.php';
                require '../admin/pages/scripts/helpers.php';

                $homeowner_id = $_SESSION['homeowner_id'];

                // Get request counts
                $countStmt = $conn->prepare("SELECT
                    COUNT(*) as total,
                    SUM(CASE WHEN status IN ('in_progress','payment_received') THEN 1 ELSE 0 END) as in_progress,
                    SUM(CASE WHEN status IN ('completed','vendor_paid') THEN 1 ELSE 0 END) as completed
                    FROM service_requests WHERE homeowner_id = ?");
                $countStmt->bind_param("i", $homeowner_id);
                $countStmt->execute();
                $counts = $countStmt->get_result()->fetch_assoc();
                $countStmt->close();
                ?>

                <div class="text-center mb-4">
                    <h3 class="mb-1">My Requests</h3>
                    <p class="text-muted">Welcome back, <?php echo htmlspecialchars($_SESSION['homeowner_name']); ?>!</p>
                </div>

                <!-- Summary Cards -->
                <div class="row g-3 mb-4 justify-content-center">
                    <div class="col-md-3 col-6">
                        <div class="card shadow-sm text-center">
                            <div class="card-body py-3">
                                <h2 class="fw-bold text-primary mb-0"><?php echo (int)$counts['total']; ?></h2>
                                <small class="text-muted">Total Requests</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="card shadow-sm text-center">
                            <div class="card-body py-3">
                                <h2 class="fw-bold text-warning mb-0"><?php echo (int)$counts['in_progress']; ?></h2>
                                <small class="text-muted">In Progress</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="card shadow-sm text-center">
                            <div class="card-body py-3">
                                <h2 class="fw-bold text-success mb-0"><?php echo (int)$counts['completed']; ?></h2>
                                <small class="text-muted">Completed</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Request List -->
                <?php
                $reqStmt = $conn->prepare("SELECT request_id, tracking_code, description, status, payment_status, final_price, created_at
                    FROM service_requests
                    WHERE homeowner_id = ?
                    ORDER BY created_at DESC");
                $reqStmt->bind_param("i", $homeowner_id);
                $reqStmt->execute();
                $reqResult = $reqStmt->get_result();
                ?>

                <?php if ($reqResult->num_rows === 0): ?>
                    <div class="text-center py-5">
                        <i class="bi bi-inbox fs-1 text-muted"></i>
                        <h5 class="mt-3 text-muted">No requests yet</h5>
                        <p class="text-muted">Submit your first service request to get started.</p>
                        <a href="submit-request.php" class="btn btn-primary"><i class="bi bi-plus-circle me-2"></i>Submit Request</a>
                    </div>
                <?php else: ?>
                    <div class="row g-3">
                        <?php while ($req = $reqResult->fetch_assoc()): ?>
                            <div class="col-lg-6">
                                <div class="card shadow-sm h-100">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <code class="fs-6"><?php echo htmlspecialchars($req['tracking_code']); ?></code>
                                            <span class="badge <?php echo getStatusBadgeClass($req['status']); ?>"><?php echo getStatusLabel($req['status']); ?></span>
                                        </div>
                                        <p class="text-muted mb-2">
                                            <?php
                                            $desc = strip_tags($req['description']);
                                            echo htmlspecialchars(strlen($desc) > 100 ? substr($desc, 0, 100) . '...' : $desc);
                                            ?>
                                        </p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <small class="text-muted"><i class="bi bi-calendar me-1"></i><?php echo date('M d, Y', strtotime($req['created_at'])); ?></small>
                                                <?php if (!empty($req['final_price'])): ?>
                                                    <span class="ms-2 fw-bold text-success">$<?php echo number_format($req['final_price'], 2); ?></span>
                                                <?php endif; ?>
                                            </div>
                                            <a href="track.php?code=<?php echo htmlspecialchars($req['tracking_code']); ?>" class="btn btn-sm btn-primary">
                                                <i class="bi bi-eye me-1"></i>View
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php endif;
                $reqStmt->close();
                ?>

                <!-- Submit New Request Button -->
                <?php if ($reqResult->num_rows > 0): ?>
                    <div class="text-center mt-4">
                        <a href="submit-request.php" class="btn btn-primary"><i class="bi bi-plus-circle me-2"></i>Submit New Request</a>
                    </div>
                <?php endif; ?>

            </div>
        </section>

    </main>

    <?php include "includes/footer.php" ?>

    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <?php include "includes/script.php" ?>
</body>
</html>
