<?php
session_start();
require '../../admin/pages/scripts/connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php?error=AccessDenied");
    exit();
}

$user_id = $_SESSION['user_id'];
$new_password = isset($_POST['first_name']) ? trim($_POST['first_name']) : '';

// Validate password input
if (empty($new_password)) {
    header("Location: ../provider-profile.php?error=MissingPassword");
    exit();
}

// Fetch existing password
$stmt = $conn->prepare("SELECT password FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$existing = $result->fetch_assoc();
$stmt->close();

if (!$existing) {
    header("Location: ../provider-profile.php?error=UserNotFound");
    exit();
}

// Check if the new password is the same as the existing one
if (password_verify($new_password, $existing['password'])) {
    header("Location: ../provider-profile.php?error=SamePassword");
    exit();
}

// Hash the new password
$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

// Update the password
$stmt = $conn->prepare("UPDATE users SET password = ? WHERE user_id = ?");
$stmt->bind_param("si", $hashed_password, $user_id);

if ($stmt->execute()) {
    header("Location: ../provider-profile.php?success=PasswordUpdated");
} else {
    header("Location: ../provider-profile.php?error=UpdateFailed");
}

$stmt->close();
$conn->close();
?>
