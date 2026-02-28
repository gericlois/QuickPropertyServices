<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../homeowner-register.php");
    exit;
}

require '../../admin/pages/scripts/connection.php';

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

// Validate required fields
if (empty($name) || empty($email) || empty($phone) || empty($password) || empty($confirm_password)) {
    header("Location: ../homeowner-register.php?error=MissingFields");
    exit;
}

// Validate password length
if (strlen($password) < 6) {
    header("Location: ../homeowner-register.php?error=PasswordTooShort");
    exit;
}

// Validate password match
if ($password !== $confirm_password) {
    header("Location: ../homeowner-register.php?error=PasswordMismatch");
    exit;
}

// Check if email already exists
$stmt = $conn->prepare("SELECT homeowner_id FROM homeowners WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $stmt->close();
    header("Location: ../homeowner-register.php?error=EmailExists");
    exit;
}
$stmt->close();

// Insert new homeowner with status=2 (Pending approval)
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
$pending_status = 2;

$insertStmt = $conn->prepare("INSERT INTO homeowners (name, email, phone, password, status) VALUES (?, ?, ?, ?, ?)");
$insertStmt->bind_param("ssssi", $name, $email, $phone, $hashed_password, $pending_status);

if ($insertStmt->execute()) {
    $insertStmt->close();
    header("Location: ../homeowner-register.php?success=Registered");
    exit;
} else {
    $insertStmt->close();
    header("Location: ../homeowner-register.php?error=RegistrationFailed");
    exit;
}
