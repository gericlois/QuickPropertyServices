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
$current_password = $_POST['current_password'] ?? '';
$new_password = $_POST['new_password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

// Validate required fields
if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
    header("Location: ../homeowner-profile.php?pw_error=MissingFields");
    exit;
}

// Validate new password length
if (strlen($new_password) < 6) {
    header("Location: ../homeowner-profile.php?pw_error=PasswordTooShort");
    exit;
}

// Validate new passwords match
if ($new_password !== $confirm_password) {
    header("Location: ../homeowner-profile.php?pw_error=PasswordMismatch");
    exit;
}

// Verify current password
$stmt = $conn->prepare("SELECT password FROM homeowners WHERE homeowner_id = ?");
$stmt->bind_param("i", $homeowner_id);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!password_verify($current_password, $result['password'])) {
    header("Location: ../homeowner-profile.php?pw_error=WrongPassword");
    exit;
}

// Update password
$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
$updateStmt = $conn->prepare("UPDATE homeowners SET password = ? WHERE homeowner_id = ?");
$updateStmt->bind_param("si", $hashed_password, $homeowner_id);

if ($updateStmt->execute()) {
    $updateStmt->close();
    header("Location: ../homeowner-profile.php?pw_success=1");
    exit;
} else {
    $updateStmt->close();
    header("Location: ../homeowner-profile.php?pw_error=UpdateFailed");
    exit;
}
