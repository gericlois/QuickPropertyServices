<?php
include "connection.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $request_id = intval($_POST['request_id']);
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE job_requests SET status = ? WHERE request_id = ?");
    $stmt->bind_param("si", $status, $request_id);

    if ($stmt->execute()) {
        header("Location: ../request-profile.php?id=" . $request_id . "&success=1");
        exit;
    } else {
        echo "Error updating status.";
    }
}
