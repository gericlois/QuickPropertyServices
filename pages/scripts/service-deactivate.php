<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'provider') {
    header("Location: ../login.php?error=AccessDenied");
    exit();
}
require '../../admin/pages/scripts/connection.php';

if (isset($_GET['service_id']) && isset($_SESSION['provider_id'])) {
    $service_id = $_GET['service_id'];
    $provider_id = $_SESSION['provider_id'];

    $query = "UPDATE services SET status = 2 WHERE service_id = ? AND provider_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $service_id, $provider_id);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header("Location: ../provider-services.php?success=ServiceDeactivated");
        exit();
    } else {
        $stmt->close();
        $conn->close();
        header("Location: ../provider-services.php?error=DeactivationFailed");
        exit();
    }
} else {
    header("Location: ../provider-services.php?error=InvalidAccess");
    exit();
}
?>
