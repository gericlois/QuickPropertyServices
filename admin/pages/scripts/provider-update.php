<?php
session_start();
include "connection.php";

if (isset($_GET['id']) && isset($_GET['status'])) {
    $provider_id = intval($_GET['id']); // Ensure it's an integer
    $status = $_GET['status'];

    $sql = "UPDATE providers SET status = ? WHERE provider_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $provider_id); // Fixed variable name

    if ($stmt->execute()) {
        header("Location: ../providers.php?success=StatusUpdated&provider_id=" . urlencode($provider_id)); 
        exit();
    } else {
        header("Location: ../providers.php?error=UpdateFailed"); 
        exit();
    }

    $stmt->close();
}

$conn->close();
?>
