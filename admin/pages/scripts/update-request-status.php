<?php
require 'connection.php';
require 'helpers.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../requests.php?error=InvalidRequest");
    exit();
}

$request_id = intval($_POST['request_id'] ?? 0);
$status = $_POST['status'] ?? '';
$payment_status = $_POST['payment_status'] ?? null;
$admin_notes_update = isset($_POST['admin_notes_update']);
$admin_notes = $_POST['admin_notes'] ?? null;

if ($request_id <= 0) {
    header("Location: ../requests.php?error=InvalidRequest");
    exit();
}

$admin_id = $_SESSION['admin_id'] ?? 0;

// Validate status value
$validStatuses = [
    'new', 'reviewing', 'vendors_assigned', 'estimates_received',
    'estimate_sent', 'homeowner_accepted', 'payment_received',
    'in_progress', 'completed', 'vendor_paid'
];

$validPaymentStatuses = ['pending', 'paid_escrow', 'released'];

if (!in_array($status, $validStatuses)) {
    header("Location: ../request-view.php?id=" . $request_id . "&error=InvalidRequest");
    exit();
}

// Block changes if current status is 'vendor_paid'
$checkStmt = $conn->prepare("SELECT status FROM service_requests WHERE request_id = ?");
$checkStmt->bind_param("i", $request_id);
$checkStmt->execute();
$current = $checkStmt->get_result()->fetch_assoc();
$checkStmt->close();

if (!$current) {
    header("Location: ../requests.php?error=InvalidRequest");
    exit();
}

if ($current['status'] === 'vendor_paid') {
    header("Location: ../request-view.php?id=" . $request_id . "&error=locked");
    exit();
}

// Handle admin notes update only (no status change)
if ($admin_notes_update && $admin_notes !== null) {
    $notesStmt = $conn->prepare("UPDATE service_requests SET admin_notes = ?, updated_at = NOW() WHERE request_id = ?");
    $notesStmt->bind_param("si", $admin_notes, $request_id);
    $notesStmt->execute();
    $notesStmt->close();

    logAdminAction($conn, $admin_id, "Updated Admin Notes", "Request ID: $request_id");

    header("Location: ../request-view.php?id=" . $request_id . "&success=NotesSaved");
    exit();
}

// Build the update query
if ($payment_status && in_array($payment_status, $validPaymentStatuses)) {
    // Update both status and payment_status
    $updateStmt = $conn->prepare("UPDATE service_requests SET status = ?, payment_status = ?, updated_at = NOW() WHERE request_id = ?");
    $updateStmt->bind_param("ssi", $status, $payment_status, $request_id);
} else {
    // Update status only
    $updateStmt = $conn->prepare("UPDATE service_requests SET status = ?, updated_at = NOW() WHERE request_id = ?");
    $updateStmt->bind_param("si", $status, $request_id);
}

if ($updateStmt->execute()) {
    $updateStmt->close();

    // Log the status change
    $details = "Request ID: $request_id, New Status: $status";
    if ($payment_status && in_array($payment_status, $validPaymentStatuses)) {
        $details .= ", Payment Status: $payment_status";
    }
    logAdminAction($conn, $admin_id, "Updated Request Status", $details);

    header("Location: ../request-view.php?id=" . $request_id . "&success=StatusUpdated");
    exit();
} else {
    header("Location: ../request-view.php?id=" . $request_id . "&error=InvalidRequest");
    exit();
}
?>
