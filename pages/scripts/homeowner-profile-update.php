<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../homeowner-profile.php");
    exit;
}

if (!isset($_SESSION['homeowner_id']) || $_SESSION['role'] !== 'homeowner') {
    header("Location: ../homeowner-login.php");
    exit;
}

require '../../admin/pages/scripts/connection.php';

$homeowner_id = $_SESSION['homeowner_id'];
$name = trim($_POST['name'] ?? '');
$phone = trim($_POST['phone'] ?? '');

// Validate required fields
if (empty($name) || empty($phone)) {
    header("Location: ../homeowner-profile.php?profile_error=MissingFields");
    exit;
}

// Update profile (name and phone only, email is not editable)
$updateStmt = $conn->prepare("UPDATE homeowners SET name = ?, phone = ? WHERE homeowner_id = ?");
$updateStmt->bind_param("ssi", $name, $phone, $homeowner_id);

if ($updateStmt->execute()) {
    $updateStmt->close();

    // Update session variables
    $_SESSION['homeowner_name'] = $name;

    header("Location: ../homeowner-profile.php?profile_success=1");
    exit;
} else {
    $updateStmt->close();
    header("Location: ../homeowner-profile.php?profile_error=UpdateFailed");
    exit;
}
