<?php
require 'connection.php';
require 'helpers.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../requests.php?error=InvalidRequest");
    exit();
}

$request_id = intval($_POST['request_id'] ?? 0);

if ($request_id <= 0) {
    header("Location: ../requests.php?error=InvalidRequest");
    exit();
}

$admin_id = $_SESSION['admin_id'] ?? 0;

// Fetch request data
$stmt = $conn->prepare("SELECT tracking_code, email, homeowner_name, final_price, status FROM service_requests WHERE request_id = ?");
$stmt->bind_param("i", $request_id);
$stmt->execute();
$request = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$request) {
    header("Location: ../requests.php?error=InvalidRequest");
    exit();
}

// Ensure there is a final price set
if (empty($request['final_price'])) {
    header("Location: ../request-view.php?id=" . $request_id . "&error=InvalidRequest");
    exit();
}

// Update status to 'estimate_sent' if not already past that stage
$estimateSentStatuses = ['estimate_sent', 'homeowner_accepted', 'payment_received', 'in_progress', 'completed', 'vendor_paid'];
if (!in_array($request['status'], $estimateSentStatuses)) {
    $updateStmt = $conn->prepare("UPDATE service_requests SET status = 'estimate_sent', updated_at = NOW() WHERE request_id = ?");
    $updateStmt->bind_param("i", $request_id);
    $updateStmt->execute();
    $updateStmt->close();

    logAdminAction($conn, $admin_id, "Updated Request Status", "Request ID: $request_id, New Status: estimate_sent");
}

// Log the email send action
// PHPMailer integration can be configured later
logAdminAction($conn, $admin_id, "Sent Estimate to Homeowner", "Request ID: $request_id, Email: " . $request['email'] . ", Homeowner: " . $request['homeowner_name'] . ", Final Price: $" . number_format($request['final_price'], 2));

header("Location: ../request-view.php?id=" . $request_id . "&success=EstimateSent");
exit();
?>
