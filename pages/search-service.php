<?php
include "../admin/pages/scripts/connection.php";

if (isset($_POST['query'])) {
    $query = "%" . $_POST['query'] . "%";

    $stmt = $conn->prepare("SELECT * FROM services WHERE service_name LIKE ? AND status = 1");
    $stmt->bind_param("s", $query);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) :
?>
<div class="col-lg-6 service-item" data-aos="fade-up" data-aos-delay="100">
    <div class="service-card d-flex">
        <div class="icon flex-shrink-0">
            <i class="bi bi-diagram-3"></i>
        </div>
        <div>
            <h3>
                <?= htmlspecialchars($row['service_name']) ?>
            </h3>
            <p><strong>Price:</strong> $<?= number_format($row['base_price'], 2) ?></p>
            <p><?= nl2br(htmlspecialchars($row['description'])) ?></p>
            <a href="provider-services-inquire.php?service_id=<?= urlencode($row['service_id']); ?>"
                class="btn btn-sm btn-primary">Inquire <i class="bi bi-envelope"></i></a>
        </div>
    </div>
</div>
<?php
    endwhile;
    $stmt->close();
}
?>