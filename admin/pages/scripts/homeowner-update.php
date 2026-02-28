<?php
require 'connection.php';
require 'helpers.php';
session_start();

// Admin auth guard
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php?error=AccessDenied");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['id']) && isset($_GET['status'])) {
    $homeowner_id = intval($_GET['id']);
    $new_status = intval($_GET['status']);

    // Validate status value (1=Active, 2=Pending, 3=Rejected)
    if (!in_array($new_status, [1, 2, 3])) {
        header("Location: ../homeowners.php?error=InvalidStatus");
        exit();
    }

    $stmt = $conn->prepare("UPDATE homeowners SET status = ? WHERE homeowner_id = ?");
    $stmt->bind_param("ii", $new_status, $homeowner_id);

    if ($stmt->execute()) {
        $stmt->close();

        // Log to admin_history
        $admin_id = $_SESSION['admin_id'];
        $status_labels = [1 => 'Active', 2 => 'Pending', 3 => 'Rejected'];
        $action = "Changed Homeowner Status";
        $details = "Homeowner ID: {$homeowner_id}, New Status: {$status_labels[$new_status]}";
        logAdminAction($conn, $admin_id, $action, $details);

        header("Location: ../homeowners.php?success=StatusUpdated");
        exit();
    } else {
        $stmt->close();
        header("Location: ../homeowners.php?error=UpdateFailed");
        exit();
    }
} else {
    header("Location: ../homeowners.php?error=InvalidRequest");
    exit();
}
