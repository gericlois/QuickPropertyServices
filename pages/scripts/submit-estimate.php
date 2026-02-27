<?php
session_start();
if (!isset($_SESSION['vendor_id'])) {
    header("Location: ../vendor-login.php");
    exit();
}
require '../../admin/pages/scripts/connection.php';
require '../../admin/pages/scripts/helpers.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $vendor_id = $_SESSION['vendor_id'];
    $assignment_id = intval($_POST['assignment_id']);
    $request_id = intval($_POST['request_id']);
    $description = trim($_POST['description']);
    $estimated_price = floatval($_POST['estimated_price']);
    $timeline = trim($_POST['timeline']);

    // Validate assignment belongs to this vendor and status is 'assigned'
    $stmt = $conn->prepare("SELECT assignment_id, request_id FROM vendor_assignments WHERE assignment_id = ? AND vendor_id = ? AND status = 'assigned'");
    $stmt->bind_param("ii", $assignment_id, $vendor_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $stmt->close();
        header("Location: ../vendor-dashboard.php");
        exit();
    }
    $stmt->close();

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Insert estimate
        $stmt = $conn->prepare("INSERT INTO vendor_estimates (assignment_id, vendor_id, request_id, description, estimated_price, timeline) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iiisds", $assignment_id, $vendor_id, $request_id, $description, $estimated_price, $timeline);
        $stmt->execute();
        $estimate_id = $conn->insert_id;
        $stmt->close();

        // Process file uploads
        if (isset($_FILES['media']) && !empty($_FILES['media']['name'][0])) {
            $uploaded = uploadMedia($_FILES['media'], 'estimates');
            foreach ($uploaded as $file) {
                $stmt = $conn->prepare("INSERT INTO estimate_media (estimate_id, file_path, file_type) VALUES (?, ?, ?)");
                $stmt->bind_param("iss", $estimate_id, $file['file_path'], $file['file_type']);
                $stmt->execute();
                $stmt->close();
            }
        }

        // Update assignment status
        $stmt = $conn->prepare("UPDATE vendor_assignments SET status = 'estimate_submitted' WHERE assignment_id = ?");
        $stmt->bind_param("i", $assignment_id);
        $stmt->execute();
        $stmt->close();

        // Check if ALL assigned vendors have submitted estimates
        $stmt = $conn->prepare("SELECT COUNT(*) AS pending_count FROM vendor_assignments WHERE request_id = ? AND status = 'assigned'");
        $stmt->bind_param("i", $request_id);
        $stmt->execute();
        $pending_count = $stmt->get_result()->fetch_assoc()['pending_count'];
        $stmt->close();

        if ($pending_count == 0) {
            // All vendors submitted, update request status
            $stmt = $conn->prepare("UPDATE service_requests SET status = 'estimates_received' WHERE request_id = ?");
            $stmt->bind_param("i", $request_id);
            $stmt->execute();
            $stmt->close();
        }

        $conn->commit();
        header("Location: ../vendor-request-view.php?id=" . $request_id . "&success=EstimateSubmitted");
        exit();

    } catch (Exception $e) {
        $conn->rollback();
        header("Location: ../vendor-request-view.php?id=" . $request_id . "&error=SubmissionFailed");
        exit();
    }
}
?>
