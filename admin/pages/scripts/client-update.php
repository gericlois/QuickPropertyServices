<?php
session_start();
include "connection.php";

if (isset($_GET['id']) && isset($_GET['status'])) {
    $client_id = intval($_GET['id']); // Ensure it's an integer
    $status = $_GET['status'];

    $sql = "UPDATE clients SET status = ? WHERE client_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $client_id); // Fixed variable name

    if ($stmt->execute()) {
        header("Location: ../clients.php?success=StatusUpdated&user_id=" . urlencode($client_id)); 
        exit();
    } else {
        header("Location: ../clients.php?error=UpdateFailed"); 
        exit();
    }

    $stmt->close();
}

$conn->close();
?>
