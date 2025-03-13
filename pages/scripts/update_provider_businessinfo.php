<?php
session_start();
require '../../admin/pages/scripts/connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php?error=AccessDenied");
    exit();
}

$user_id = $_SESSION['user_id'];
$business_name = isset($_POST['business_name']) ? trim($_POST['business_name']) : '';
$work = isset($_POST['work']) ? trim($_POST['work']) : '';

// Fetch existing values
$stmt = $conn->prepare("SELECT business_name, work FROM providers WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$existing = $result->fetch_assoc();
$stmt->close();

if (!$existing) {
    header("Location: ../provider-profile.php?error=NoProviderFound");
    exit();
}

// Check if the new input is actually different
$changes = [];
$params = [];
$types = "";

if (!empty($business_name) && $business_name !== $existing['business_name']) {
    $changes[] = "business_name = ?";
    $params[] = $business_name;
    $types .= "s";
}
if (!empty($work) && $work !== $existing['work']) {
    $changes[] = "work = ?";
    $params[] = $work;
    $types .= "s";
}

if (empty($changes)) {
    header("Location: ../provider-profile.php?error=NoDataToUpdate");
    exit();
}

// Update only if there are changes
$query = "UPDATE providers SET " . implode(", ", $changes) . " WHERE user_id = ?";
$params[] = $user_id;
$types .= "i";

$stmt = $conn->prepare($query);
$stmt->bind_param($types, ...$params);

if ($stmt->execute()) {
    header("Location: ../provider-profile.php?success=ProviderBusinessUpdated");
} else {
    header("Location: ../provider-profile.php?error=UpdateFailed");
}

$stmt->close();
$conn->close();
?>
