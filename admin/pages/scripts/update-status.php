<?php
include "connection.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $request_id = intval($_POST['request_id']);
    $status = $_POST['status'];

    // Update job request status
    $stmt = $conn->prepare("UPDATE job_requests SET status = ? WHERE request_id = ?");
    $stmt->bind_param("si", $status, $request_id);

    if ($stmt->execute()) {

        // ✅ Log activity in history table
        if (isset($_SESSION['admin_id'])) {
            $admin_id   = $_SESSION['admin_id']; // logged-in admin
            $action     = "Updated Job Request Status";
            $details    = "Request ID: $request_id, New Status: $status";
            $ip_address = $_SERVER['REMOTE_ADDR'] ?? '';
            $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';

            $log = $conn->prepare("INSERT INTO admin_history 
                (admin_id, action, details, ip_address, user_agent) 
                VALUES (?, ?, ?, ?, ?)");

            if ($log === false) {
                die("Prepare failed: " . $conn->error);
            }

            $log->bind_param("issss", $admin_id, $action, $details, $ip_address, $user_agent);

            if (!$log->execute()) {
                die("Log insert failed: " . $log->error);
            }
        }

        header("Location: ../request-profile.php?id=" . $request_id . "&success=1");
        exit;
    } else {
        echo "Error updating status: " . $stmt->error;
    }
}
