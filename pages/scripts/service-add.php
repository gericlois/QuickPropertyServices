<?php
session_start();
require '../../admin/pages/scripts/connection.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $provider_id = $_SESSION['provider_id'];
    $service_name = trim($_POST['service_name']);
    $description = trim($_POST['description']);
    $base_price = trim($_POST['base_price']);

    // Check if service already exists for this provider
    $check_query = "SELECT service_id FROM services WHERE provider_id = ? AND service_name = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("is", $provider_id, $service_name);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        header("Location: ../service-add.php?error=ServiceNameAlreadyExists");
        exit();
    }
    $stmt->close();

    // Insert new service
    $query = "INSERT INTO services (provider_id, service_name, description, base_price, created_at) VALUES (?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("isss", $provider_id, $service_name, $description, $base_price);

    if ($stmt->execute()) {
        header("Location: ../provider-services.php?success=ServiceAddedSuccessfully");
    } else {
        header("Location: ../service-add.php?error=Error adding service. Please try again.");
    }
    $stmt->close();
    $conn->close();
    exit();
} else {
    header("Location: ../service-add.php");
    exit();
}
?>