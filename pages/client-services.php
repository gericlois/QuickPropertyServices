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

// Pagination setup
$limit = 10; // Number of services per page
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Fetch services where status = 1 with pagination
$query = "SELECT * FROM services s inner join providers p ON s.provider_id = p.provider_id inner join users u ON p.user_id = u.user_id WHERE s.status = 1 ORDER BY s.created_at DESC LIMIT ? OFFSET ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();

// Count total services with status = 1 for pagination
$count_query = "SELECT COUNT(*) AS total FROM services s inner join providers p ON s.provider_id = p.provider_id inner join users u ON p.user_id = u.user_id WHERE s.status = 1";
$count_stmt = $conn->prepare($count_query);
$count_stmt->execute();
$count_result = $count_stmt->get_result();
$total_services = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_services / $limit);
?>



<body>

    <?php include "includes/header.php" ?>

    <main class="main">

        <!-- Services Section -->
        <section id="services" class="services section light-background">

            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>Our Services</h2>
                <p>Explore the range of services we offer and find the perfect solution for your needs.</p>
            </div>

            <!-- Live Search Input -->
            <div class="container mb-3">
                <input type="text" id="searchService" class="form-control" placeholder="Search services..."
                    autocomplete="off">
            </div>
            <div class="container">
                <?php
                if (isset($_GET['success'])) {
                    if ($_GET["success"] == "BookingConfirmed") {
                        echo '
                                                        <div class="alert alert-primary alert-dismissible fade show" role="alert">
                                                        <b>Thank you for booking a service with us! Your appointment has been successfully scheduled. You can check your bookings here: <a href="">LINK </a></b>
                                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                        </div>';
                    }
                }
                ?>

                <?php
                if (isset($_GET['error'])) {
                    if ($_GET["error"] == "AlreadyBooked") {
                        echo '
                                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                        <b>Thank you for booking a service with us! Your appointment has been successfully scheduled. You can check your bookings here: <a href="">LINK </a></b>
                                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                        </div>';
                    }
                }
                ?>
            </div>

            <!-- Services List -->
            <div class="container" data-aos="fade-up" data-aos-delay="100">
                <div class="row g-4" id="serviceList">
                    <?php while ($row = $result->fetch_assoc()) : ?>
                    <div class="col-lg-6 service-item" data-aos="fade-up" data-aos-delay="100">
                        <div class="service-card d-flex">
                            <div class="icon flex-shrink-0">
                                <i class="bi bi-diagram-3"></i>
                            </div>
                            <div class="w-100">
                                <h3><?= htmlspecialchars($row['service_name']) ?></h3>
                                <p><strong>$<?= number_format($row['base_price'], 2) ?></strong> ||
                                    <?= htmlspecialchars($row['first_name']) ?>
                                    <?= htmlspecialchars($row['last_name']) ?></p>
                                <p class="mb-4 mb-md-3"><?= (htmlspecialchars($row['description'])) ?></p>


                                <a href="client-services-view.php?service_id=<?= $row['service_id'] ?>"
                                    class="btn btn-sm btn-primary">View</a>
                                <!-- Accordion Button -->
                                <button class="btn btn-sm btn-primary toggle-form"
                                    data-target="form-<?= $row['service_id']; ?>">
                                    Purchase
                                </button>

                                <!-- Accordion Form -->
                                <div class="accordion-content" id="form-<?= $row['service_id']; ?>"
                                    style="display: none;">
                                    <form action="scripts/process_purchase.php" method="POST" class="mt-3">
                                        <input type="hidden" name="service_id"
                                            value="<?= htmlspecialchars($row['service_id']); ?>">

                                        <label for="date_<?= $row['service_id']; ?>">Select Date:</label>
                                        <input type="date" id="date_<?= $row['service_id']; ?>" name="date"
                                            class="form-control" required>

                                        <label for="time_<?= $row['service_id']; ?>" class="mt-2">Select Time:</label>
                                        <input type="time" id="time_<?= $row['service_id']; ?>" name="time"
                                            class="form-control" required>


                                        <div class="mt-3">
                                            <button type="submit" class="btn btn-primary">Confirm Purchase</button>
                                            <button type="button" class="btn btn-danger cancel-form"
                                                data-target="form-<?= $row['service_id']; ?>">Cancel</button>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>

            <!-- JavaScript for Toggle & Validation -->
            <script>
            document.addEventListener("DOMContentLoaded", function() {
                // Toggle Form Functionality
                document.querySelectorAll(".toggle-form").forEach(button => {
                    button.addEventListener("click", function() {
                        let target = document.getElementById(this.getAttribute("data-target"));
                        if (target) {
                            target.style.display = (target.style.display === "none" || target
                                .style.display === "") ? "block" : "none";
                        }
                    });
                });

                document.querySelectorAll(".cancel-form").forEach(button => {
                    button.addEventListener("click", function() {
                        let target = document.getElementById(this.getAttribute("data-target"));
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
                            let currentTime = now.getHours().toString().padStart(2, "0") + ":" +
                                now.getMinutes().toString().padStart(2, "0");
                            timeInput.setAttribute("min", currentTime);
                        } else {
                            timeInput.removeAttribute("min");
                        }
                    });
                });
            });
            </script>

            <!-- Pagination -->
            <div class="container mt-4 text-center">
                <nav>
                    <ul class="pagination justify-content-center">
                        <?php if ($page > 1): ?>
                        <li class="page-item"><a class="page-link" href="?page=<?= $page - 1 ?>">Previous</a></li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                        </li>
                        <?php endfor; ?>

                        <?php if ($page < $total_pages): ?>
                        <li class="page-item"><a class="page-link" href="?page=<?= $page + 1 ?>">Next</a></li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>

        </section><!-- /Services Section -->

        <script>
        document.getElementById("searchService").addEventListener("keyup", function() {
            let searchQuery = this.value.trim();
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "search-service.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    document.getElementById("serviceList").innerHTML = xhr.responseText;
                }
            };
            xhr.send("query=" + encodeURIComponent(searchQuery));
        });
        </script>

        <!-- /Services Section -->

    </main>

    <?php include "includes/footer.php" ?>

    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <?php include "includes/script.php" ?>

</body>

</html>