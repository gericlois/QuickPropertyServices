<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'provider') {
    header("Location: ../login.php?error=AccessDenied");
    exit();
}

require '../../admin/pages/scripts/connection.php';

// Check if form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $service_id = $_POST['service_id'];
    $provider_id = $_SESSION['provider_id']; // Get provider ID from session
    $service_name = trim($_POST['service_name']);
    $description = trim($_POST['description']);
    $category_id = $_POST['category_id'];
    $base_price = $_POST['base_price'];

    // Validate inputs
    if (empty($service_name) || empty($description) || empty($category_id) || !is_numeric($base_price)) {
        header("Location: ../provider-services-edit?service_id=$service_id&error=InvalidInput");
        exit();
    }

    // Update query
    $query = "UPDATE services 
              SET service_name = ?, description = ?, category_id = ?, base_price = ? 
              WHERE service_id = ? AND provider_id = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssiiii", $service_name, $description, $category_id, $base_price, $service_id, $provider_id);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header("Location: ../provider-services.php?success=ServiceUpdated");
        exit();
    } else {
        $stmt->close();
        $conn->close();
        header("Location: ../provider-services-edit?service_id=$service_id&error=UpdateFailed");
        exit();
    }
} else {
    header("Location: ../provider-services.php?error=InvalidAccess");
    exit();
}
?>
