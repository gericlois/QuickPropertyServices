<?php
session_start();

// POST only
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../track.php");
    exit;
}

require '../../admin/pages/scripts/connection.php';

$tracking_code = trim($_POST['tracking_code'] ?? '');

if (empty($tracking_code)) {
    header("Location: ../track.php");
    exit;
}

// Validate: request exists and status is 'estimate_sent'
$stmt = $conn->prepare("SELECT request_id, status FROM service_requests WHERE tracking_code = ?");
$stmt->bind_param("s", $tracking_code);
$stmt->execute();
$result = $stmt->get_result();
$request = $result->fetch_assoc();
$stmt->close();

if (!$request || $request['status'] !== 'estimate_sent') {
    header("Location: ../track.php?code=" . urlencode($tracking_code));
    exit;
}

// Update status to homeowner_accepted
$update_stmt = $conn->prepare("UPDATE service_requests SET status = 'homeowner_accepted', updated_at = NOW() WHERE tracking_code = ? AND status = 'estimate_sent'");
$update_stmt->bind_param("s", $tracking_code);
$update_stmt->execute();
$update_stmt->close();

// Redirect back to tracking page with success message
header("Location: ../track.php?code=" . urlencode($tracking_code) . "&success=EstimateAccepted");
exit;
