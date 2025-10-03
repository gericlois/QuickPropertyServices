<?php
session_start();
include "connection.php";

if (isset($_GET['id']) && isset($_GET['status'])) {
    $client_id = intval($_GET['id']); // Ensure it's an integer
    $status    = $_GET['status'];

    // Update client status
    $sql = "UPDATE clients SET status = ? WHERE client_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $client_id);

    if ($stmt->execute()) {
        // ✅ Log activity to admin_history
        if (isset($_SESSION['admin_id'])) {
            $admin_id   = $_SESSION['admin_id'];
            $action     = "Changed Client Status";
            $status_text = ($status == '1') ? 'Active' : 'Inactive';
            $details    = "Client ID: $client_id, New Status: $status_text";
            $ip_address = $_SERVER['REMOTE_ADDR'] ?? '';
            $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';

            $log = $conn->prepare("INSERT INTO admin_history 
                (admin_id, action, details, ip_address, user_agent) 
                VALUES (?, ?, ?, ?, ?)");
            if ($log !== false) {
                $log->bind_param("issss", $admin_id, $action, $details, $ip_address, $user_agent);
                $log->execute();
                $log->close();
            }
        }

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
