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
    $request_id = intval($_POST['request_id']);
    $description = trim($_POST['description']);

    // Validate vendor is selected for this request and request is in_progress
    $stmt = $conn->prepare("SELECT va.assignment_id FROM vendor_assignments va INNER JOIN service_requests sr ON va.request_id = sr.request_id WHERE va.vendor_id = ? AND va.request_id = ? AND va.status = 'selected' AND sr.status = 'in_progress'");
    $stmt->bind_param("ii", $vendor_id, $request_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $stmt->close();
        header("Location: ../vendor-dashboard.php");
        exit();
    }
    $stmt->close();

    // Check if completion report already exists
    $stmt = $conn->prepare("SELECT report_id FROM completion_reports WHERE request_id = ? AND vendor_id = ?");
    $stmt->bind_param("ii", $request_id, $vendor_id);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        $stmt->close();
        header("Location: ../vendor-completion.php?id=" . $request_id);
        exit();
    }
    $stmt->close();

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Insert completion report
        $stmt = $conn->prepare("INSERT INTO completion_reports (request_id, vendor_id, description) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $request_id, $vendor_id, $description);
        $stmt->execute();
        $report_id = $conn->insert_id;
        $stmt->close();

        // Process file uploads
        if (isset($_FILES['media']) && !empty($_FILES['media']['name'][0])) {
            $uploaded = uploadMedia($_FILES['media'], 'completions');
            foreach ($uploaded as $file) {
                $stmt = $conn->prepare("INSERT INTO completion_media (report_id, file_path, file_type) VALUES (?, ?, ?)");
                $stmt->bind_param("iss", $report_id, $file['file_path'], $file['file_type']);
                $stmt->execute();
                $stmt->close();
            }
        }

        // Update service request status to completed
        $stmt = $conn->prepare("UPDATE service_requests SET status = 'completed' WHERE request_id = ?");
        $stmt->bind_param("i", $request_id);
        $stmt->execute();
        $stmt->close();

        $conn->commit();
        header("Location: ../vendor-dashboard.php?success=CompletionSubmitted");
        exit();

    } catch (Exception $e) {
        $conn->rollback();
        header("Location: ../vendor-completion.php?id=" . $request_id . "&error=SubmissionFailed");
        exit();
    }
}
?>
