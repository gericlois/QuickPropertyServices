<?php
session_start();
require '../../admin/pages/scripts/connection.php'; // Database connection

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized Access");
}

if (isset($_GET['id']) && isset($_GET['status'])) {
    $user_id = intval($_GET['id']);
    $status = intval($_GET['status']);

    if ($status !== 1 && $status !== 2) {
        die("Invalid Status");
    }

    $stmt = $conn->prepare("UPDATE providers SET status = ? WHERE user_id = ?");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ii", $status, $user_id);
    if (!$stmt->execute()) {
        die("Execution failed: " . $stmt->error);
    }

    header("Location: logout.php");
    exit();
} else {
    die("Missing Parameters");
}

$conn->close();
?>
