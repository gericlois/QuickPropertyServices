<?php
require 'connection.php';
require 'helpers.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../requests.php?error=InvalidRequest");
    exit();
}

$estimate_id = intval($_POST['estimate_id'] ?? 0);
$request_id = intval($_POST['request_id'] ?? 0);
$markup_percentage = floatval($_POST['markup_percentage'] ?? 0);

if ($estimate_id <= 0 || $request_id <= 0) {
    header("Location: ../requests.php?error=InvalidRequest");
    exit();
}

$admin_id = $_SESSION['admin_id'] ?? 0;

// Check that no estimate is already selected for this request
$checkStmt = $conn->prepare("SELECT selected_estimate_id FROM service_requests WHERE request_id = ?");
$checkStmt->bind_param("i", $request_id);
$checkStmt->execute();
$checkResult = $checkStmt->get_result()->fetch_assoc();
$checkStmt->close();

if (!$checkResult) {
    header("Location: ../requests.php?error=InvalidRequest");
    exit();
}

if (!empty($checkResult['selected_estimate_id'])) {
    header("Location: ../request-view.php?id=" . $request_id . "&error=AlreadySelected");
    exit();
}

// Fetch estimated_price from vendor_estimates
$priceStmt = $conn->prepare("SELECT estimated_price, vendor_id FROM vendor_estimates WHERE estimate_id = ? AND request_id = ?");
$priceStmt->bind_param("ii", $estimate_id, $request_id);
$priceStmt->execute();
$priceResult = $priceStmt->get_result()->fetch_assoc();
$priceStmt->close();

if (!$priceResult) {
    header("Location: ../request-view.php?id=" . $request_id . "&error=InvalidRequest");
    exit();
}

$estimated_price = floatval($priceResult['estimated_price']);
$selected_vendor_id = intval($priceResult['vendor_id']);

// Calculate markup
$markup_amount = $estimated_price * ($markup_percentage / 100);
$final_price = $estimated_price + $markup_amount;

// Begin transaction
$conn->begin_transaction();

try {
    // 1. Mark this estimate as selected
    $stmt1 = $conn->prepare("UPDATE vendor_estimates SET status = 'selected' WHERE estimate_id = ?");
    $stmt1->bind_param("i", $estimate_id);
    $stmt1->execute();
    $stmt1->close();

    // 2. Mark all other estimates for this request as not_selected
    $stmt2 = $conn->prepare("UPDATE vendor_estimates SET status = 'not_selected' WHERE request_id = ? AND estimate_id != ?");
    $stmt2->bind_param("ii", $request_id, $estimate_id);
    $stmt2->execute();
    $stmt2->close();

    // 3. Mark the assignment for the selected vendor as 'selected'
    $stmt3 = $conn->prepare("UPDATE vendor_assignments SET status = 'selected' WHERE vendor_id = (SELECT vendor_id FROM vendor_estimates WHERE estimate_id = ?) AND request_id = ?");
    $stmt3->bind_param("ii", $estimate_id, $request_id);
    $stmt3->execute();
    $stmt3->close();

    // 4. Mark other assignments as not_selected (only those with estimate_submitted status)
    $stmt4 = $conn->prepare("UPDATE vendor_assignments SET status = 'not_selected' WHERE request_id = ? AND status NOT IN ('selected', 'assigned')");
    $stmt4->bind_param("i", $request_id);
    $stmt4->execute();
    $stmt4->close();

    // 5. Update service_requests with selected estimate, markup, and status
    $stmt5 = $conn->prepare("UPDATE service_requests SET selected_estimate_id = ?, markup_percentage = ?, markup_amount = ?, final_price = ?, status = 'estimate_sent', updated_at = NOW() WHERE request_id = ?");
    $stmt5->bind_param("idddi", $estimate_id, $markup_percentage, $markup_amount, $final_price, $request_id);
    $stmt5->execute();
    $stmt5->close();

    $conn->commit();

    // Log actions
    logAdminAction($conn, $admin_id, "Selected Estimate", "Request ID: $request_id, Estimate ID: $estimate_id, Vendor ID: $selected_vendor_id, Markup: $markup_percentage%, Final Price: $" . number_format($final_price, 2));
    logAdminAction($conn, $admin_id, "Updated Request Status", "Request ID: $request_id, New Status: estimate_sent");

    header("Location: ../request-view.php?id=" . $request_id . "&success=EstimateSelected");
    exit();

} catch (Exception $e) {
    $conn->rollback();
    header("Location: ../request-view.php?id=" . $request_id . "&error=InvalidRequest");
    exit();
}
?>
