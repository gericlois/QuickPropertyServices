<?php
require 'connection.php';
require 'helpers.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../requests.php?error=InvalidRequest");
    exit();
}

$request_id = intval($_POST['request_id'] ?? 0);
$action = $_POST['action'] ?? '';

if ($request_id <= 0) {
    header("Location: ../requests.php?error=InvalidRequest");
    exit();
}

$admin_id = $_SESSION['admin_id'] ?? 0;

// Handle vendor removal
if ($action === 'remove') {
    $assignment_id = intval($_POST['assignment_id'] ?? 0);

    if ($assignment_id <= 0) {
        header("Location: ../request-view.php?id=" . $request_id . "&error=InvalidRequest");
        exit();
    }

    // Only allow removing assignments with status 'assigned'
    $checkStmt = $conn->prepare("SELECT assignment_id FROM vendor_assignments WHERE assignment_id = ? AND request_id = ? AND status = 'assigned'");
    $checkStmt->bind_param("ii", $assignment_id, $request_id);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows === 0) {
        $checkStmt->close();
        header("Location: ../request-view.php?id=" . $request_id . "&error=InvalidRequest");
        exit();
    }
    $checkStmt->close();

    $delStmt = $conn->prepare("DELETE FROM vendor_assignments WHERE assignment_id = ? AND request_id = ?");
    $delStmt->bind_param("ii", $assignment_id, $request_id);
    $delStmt->execute();
    $delStmt->close();

    logAdminAction($conn, $admin_id, "Removed Vendor Assignment", "Request ID: $request_id, Assignment ID: $assignment_id");

    header("Location: ../request-view.php?id=" . $request_id . "&success=VendorAssigned");
    exit();
}

// Handle vendor assignment
if ($action === 'assign') {
    $vendor_id = intval($_POST['vendor_id'] ?? 0);

    if ($vendor_id <= 0) {
        header("Location: ../request-view.php?id=" . $request_id . "&error=InvalidRequest");
        exit();
    }

    // Check total assignments < 5
    $countStmt = $conn->prepare("SELECT COUNT(*) as total FROM vendor_assignments WHERE request_id = ?");
    $countStmt->bind_param("i", $request_id);
    $countStmt->execute();
    $count = $countStmt->get_result()->fetch_assoc()['total'];
    $countStmt->close();

    if ($count >= 5) {
        header("Location: ../request-view.php?id=" . $request_id . "&error=MaxVendors");
        exit();
    }

    // Check vendor is not already assigned
    $dupStmt = $conn->prepare("SELECT assignment_id FROM vendor_assignments WHERE request_id = ? AND vendor_id = ?");
    $dupStmt->bind_param("ii", $request_id, $vendor_id);
    $dupStmt->execute();
    if ($dupStmt->get_result()->num_rows > 0) {
        $dupStmt->close();
        header("Location: ../request-view.php?id=" . $request_id . "&error=InvalidRequest");
        exit();
    }
    $dupStmt->close();

    // Insert assignment
    $insertStmt = $conn->prepare("INSERT INTO vendor_assignments (request_id, vendor_id, status, assigned_at) VALUES (?, ?, 'assigned', NOW())");
    $insertStmt->bind_param("ii", $request_id, $vendor_id);
    $insertStmt->execute();
    $insertStmt->close();

    // Update service_requests status to 'vendors_assigned' if currently 'new' or 'reviewing'
    $statusStmt = $conn->prepare("UPDATE service_requests SET status = 'vendors_assigned', updated_at = NOW() WHERE request_id = ? AND status IN ('new', 'reviewing')");
    $statusStmt->bind_param("i", $request_id);
    $statusStmt->execute();
    $statusStmt->close();

    // Log action
    logAdminAction($conn, $admin_id, "Assigned Vendor", "Request ID: $request_id, Vendor ID: $vendor_id");

    // If status was changed, log that too
    if ($conn->affected_rows > 0) {
        logAdminAction($conn, $admin_id, "Updated Request Status", "Request ID: $request_id, New Status: vendors_assigned");
    }

    header("Location: ../request-view.php?id=" . $request_id . "&success=VendorAssigned");
    exit();
}

// Fallback
header("Location: ../request-view.php?id=" . $request_id . "&error=InvalidRequest");
exit();
?>
