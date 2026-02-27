<?php
session_start();

// POST only
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../submit-request.php");
    exit;
}

require '../../admin/pages/scripts/connection.php';
require '../../admin/pages/scripts/helpers.php';

// Get form fields
$homeowner_name = trim($_POST['homeowner_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$password = $_POST['password'] ?? '';
$address = trim($_POST['address'] ?? '');
$description = trim($_POST['description'] ?? '');
$category_id = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;

// Validate required fields
if (empty($homeowner_name) || empty($email) || empty($phone) || empty($password) || empty($address) || empty($description)) {
    header("Location: ../submit-request.php?error=MissingFields");
    exit;
}

// Check if homeowner account exists
$homeowner_id = null;
$checkStmt = $conn->prepare("SELECT homeowner_id, password FROM homeowners WHERE email = ?");
$checkStmt->bind_param("s", $email);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();

if ($checkResult->num_rows > 0) {
    // Existing account — verify password
    $existing = $checkResult->fetch_assoc();
    if (!password_verify($password, $existing['password'])) {
        $checkStmt->close();
        header("Location: ../submit-request.php?error=WrongPassword");
        exit;
    }
    $homeowner_id = $existing['homeowner_id'];
} else {
    // New account — create it
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $createStmt = $conn->prepare("INSERT INTO homeowners (name, email, phone, password) VALUES (?, ?, ?, ?)");
    $createStmt->bind_param("ssss", $homeowner_name, $email, $phone, $hashed_password);
    $createStmt->execute();
    $homeowner_id = $conn->insert_id;
    $createStmt->close();
}
$checkStmt->close();

// Generate tracking code
$tracking_code = generateTrackingCode($conn);

// Begin transaction
$conn->begin_transaction();

try {
    // Insert service request with homeowner_id
    $stmt = $conn->prepare("INSERT INTO service_requests (homeowner_id, tracking_code, homeowner_name, email, phone, address, description, category_id, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'new')");
    $stmt->bind_param("issssssi", $homeowner_id, $tracking_code, $homeowner_name, $email, $phone, $address, $description, $category_id);
    $stmt->execute();
    $request_id = $conn->insert_id;
    $stmt->close();

    // Process file uploads if any
    if (isset($_FILES['media']) && !empty($_FILES['media']['name'][0])) {
        $uploaded_files = uploadMedia($_FILES['media'], 'requests');

        foreach ($uploaded_files as $file) {
            $media_stmt = $conn->prepare("INSERT INTO request_media (request_id, file_path, file_type) VALUES (?, ?, ?)");
            $media_stmt->bind_param("iss", $request_id, $file['file_path'], $file['file_type']);
            $media_stmt->execute();
            $media_stmt->close();
        }
    }

    // Commit transaction
    $conn->commit();

    // Auto-login the homeowner
    $_SESSION['homeowner_id'] = $homeowner_id;
    $_SESSION['homeowner_name'] = $homeowner_name;
    $_SESSION['homeowner_email'] = $email;
    $_SESSION['role'] = 'homeowner';

    // Redirect to tracking page with success message
    header("Location: ../track.php?code=" . urlencode($tracking_code) . "&success=RequestSubmitted");
    exit;

} catch (Exception $e) {
    $conn->rollback();
    header("Location: ../submit-request.php?error=SubmissionFailed");
    exit;
}
