<!DOCTYPE html>
<html lang="en">
<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'client') {
    header("Location: login.php?error=AccessDenied");
    exit();
}

include "includes/head.php";
include "../admin/pages/scripts/connection.php";

// Validate service_id
if (!isset($_GET['service_id']) || !is_numeric($_GET['service_id'])) {
    die('Invalid service ID.');
}
$service_id = intval($_GET['service_id']);

// Fetch service details
$sql = "SELECT s.*, p.business_name, u.first_name, u.last_name, u.email, u.phone
        FROM services s
        JOIN providers p ON s.provider_id = p.provider_id
        JOIN users u ON p.user_id = u.user_id
        WHERE s.service_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $service_id);
$stmt->execute();
$result = $stmt->get_result();
$service = $result->fetch_assoc();

if (!$service) {
    die('Service not found.');
}
?>

<body class="index-page">

    <?php include "includes/header.php"; ?>

    <main class="main">
        <section id="services" class="services section light-background">
            <div class="container section-title" data-aos="fade-up">
                <h2>Service Details</h2>
            </div>

            <div class="container" data-aos="fade-up" data-aos-delay="100">
                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="service-card">

                            <h3>Service Details</h3>
                            <hr>
                            <h3><?= htmlspecialchars($service['service_name']) ?>
                                <span class="badge <?= ($service['status'] == 1) ? 'bg-success' : 'bg-danger' ?>">
                                    <?= ($service['status'] == 1) ? 'Active' : 'Inactive' ?>
                                </span>
                            </h3>
                            <p><strong>Business Name:</strong> <?= htmlspecialchars($service['business_name']) ?></p>
                            <p><strong>Price:</strong> $<?= number_format($service['base_price'], 2) ?></p>
                            <p><strong>Description:</strong> <?= nl2br(htmlspecialchars($service['description'])) ?></p>
                            <a href="client-services.php" class="btn btn-secondary">Back to Services</a>
                            <!-- Accordion Button -->
                            <button class="btn btn-sm btn-primary toggle-form" data-target="form-<?= $service_id; ?>">
                                Purchase
                            </button>

                            <!-- Accordion Form -->
                            <div class="accordion-content" id="form-<?= $service_id; ?>" style="display: none;">
                                <form action="scripts/process_purchase.php" method="POST" class="mt-3">
                                    <input type="hidden" name="service_id"
                                        value="<?= htmlspecialchars($service_id); ?>">

                                    <label for="date_<?= $service_id; ?>">Select Date:</label>
                                    <input type="date" id="date_<?= $service_id; ?>" name="date" class="form-control"
                                        required>

                                    <label for="time_<?= $service_id; ?>" class="mt-2">Select Time:</label>
                                    <input type="time" id="time_<?= $service_id; ?>" name="time" class="form-control"
                                        required>


                                    <div class="mt-3">
                                        <button type="submit" class="btn btn-primary">Confirm Purchase</button>
                                        <button type="button" class="btn btn-danger cancel-form"
                                            data-target="form-<?= $service_id; ?>">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- JavaScript for Toggle & Validation -->
                    <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        // Toggle Form Functionality
                        document.querySelectorAll(".toggle-form").forEach(button => {
                            button.addEventListener("click", function() {
                                let target = document.getElementById(this.getAttribute(
                                    "data-target"));
                                if (target) {
                                    target.style.display = (target.style.display === "none" ||
                                        target.style.display === "") ? "block" : "none";
                                }
                            });
                        });

                        document.querySelectorAll(".cancel-form").forEach(button => {
                            button.addEventListener("click", function() {
                                let target = document.getElementById(this.getAttribute(
                                    "data-target"));
                                if (target) target.style.display = "none";
                            });
                        });

                        // Date and Time Validation
                        document.querySelectorAll("input[type='date']").forEach(dateInput => {
                            let timeInput = dateInput.parentElement.querySelector("input[type='time']");

                            let today = new Date().toISOString().split("T")[0];
                            dateInput.setAttribute("min", today);

                            dateInput.addEventListener("change", function() {
                                let selectedDate = new Date(dateInput.value);
                                let now = new Date();

                                if (dateInput.value === today) {
                                    let currentTime = now.getHours().toString().padStart(2,
                                        "0") + ":" + now.getMinutes().toString().padStart(2,
                                        "0");
                                    timeInput.setAttribute("min", currentTime);
                                } else {
                                    timeInput.removeAttribute("min");
                                }
                            });
                        });
                    });
                    </script>


                    <div class="col-lg-4">
                        <div class="service-card">
                            <h3>Provider Details</h3>
                            <hr>
                            <p><strong>Name:</strong>
                                <?= htmlspecialchars($service['first_name'] . ' ' . $service['last_name']) ?></p>
                            <p><strong>Email:</strong> <?= htmlspecialchars($service['email']) ?></p>
                            <p><strong>Phone:</strong> <?= htmlspecialchars($service['phone']) ?></p>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </main>

    <?php include "includes/footer.php"; ?>
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center">
        <i class="bi bi-arrow-up-short"></i>
    </a>

    <?php include "includes/script.php"; ?>
</body>

</html>