<?php
session_start();
require 'connection.php';

if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php?error=AccessDenied");
    exit();
}

if (isset($_GET['id']) && isset($_GET['status'])) {
    $user_id = intval($_GET['id']);
    $status  = intval($_GET['status']);

    // Only allow valid status values (1=Active, 2=Inactive, 3=Banned)
    if (!in_array($status, [1, 2, 3])) {
        header("Location: ../users.php?error=InvalidStatus");
        exit();
    }

    // Prevent admin from deactivating themselves
    if ($user_id === (int)$_SESSION['admin_id'] && $status !== 1) {
        header("Location: ../users.php?error=CannotDeactivateSelf");
        exit();
    }

    $stmt = $conn->prepare("UPDATE users SET status = ? WHERE user_id = ? AND role = 'admin'");
    $stmt->bind_param("ii", $status, $user_id);

    if ($stmt->execute() && $stmt->affected_rows > 0) {
        $stmt->close();

        // Log action
        require 'helpers.php';
        $status_labels = [1 => 'Active', 2 => 'Inactive', 3 => 'Banned'];
        logAdminAction($conn, $_SESSION['admin_id'], 'Updated Admin User Status', "Changed admin user #$user_id status to " . $status_labels[$status]);

        header("Location: ../users.php?success=StatusUpdated&user_id=$user_id");
        exit();
    } else {
        $stmt->close();
        header("Location: ../users.php?error=UpdateFailed");
        exit();
    }
} else {
    header("Location: ../users.php");
    exit();
}
