<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../homeowner-login.php");
    exit;
}

require '../../admin/pages/scripts/connection.php';

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    header("Location: ../homeowner-login.php?error=MissingFields");
    exit;
}

// Look up homeowner by email
$stmt = $conn->prepare("SELECT homeowner_id, name, email, phone, password, status FROM homeowners WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $stmt->close();
    header("Location: ../homeowner-login.php?error=UserNotFound");
    exit;
}

$homeowner = $result->fetch_assoc();
$stmt->close();

// Check if account is pending approval
if ($homeowner['status'] == 2) {
    header("Location: ../homeowner-login.php?error=AccountPending");
    exit;
}

// Check if account is deactivated/rejected
if ($homeowner['status'] != 1) {
    header("Location: ../homeowner-login.php?error=AccountDeactivated");
    exit;
}

// Verify password
if (!password_verify($password, $homeowner['password'])) {
    header("Location: ../homeowner-login.php?error=InvalidPassword");
    exit;
}

// Set session variables
$_SESSION['homeowner_id'] = $homeowner['homeowner_id'];
$_SESSION['homeowner_name'] = $homeowner['name'];
$_SESSION['homeowner_email'] = $homeowner['email'];
$_SESSION['role'] = 'homeowner';

// Redirect to dashboard
header("Location: ../homeowner-dashboard.php");
exit;
