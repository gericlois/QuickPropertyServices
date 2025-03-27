<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'provider') {
    header("Location: login.php?error=AccessDenied");
    exit();
}

require '../../admin/pages/scripts/connection.php'; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['booking_id'], $_POST['action'])) {
    $booking_id = intval($_POST['booking_id']);
    $action = $_POST['action'];

    // Determine new status based on action
    if ($action === "accepted") {
        $new_status = 2; // Accepted
    } elseif ($action === "declined") {
        $new_status = 4; // Declined
    } else {
        header("Location: ../provider-dashboard.php?error=InvalidAction");
        exit();
    }

    // Update the booking status
    $query = "UPDATE bookings SET status = ? WHERE booking_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $new_status, $booking_id);

    if ($stmt->execute()) {
        header("Location: ../provider-dashboard.php?success=BookingUpdated");
    } else {
        header("Location: ../provider-dashboard.php?error=UpdateFailed");
    }

    $stmt->close();
} else {
    header("Location: ../provider-dashboard.php?error=InvalidRequest");
    exit();
}
?>
