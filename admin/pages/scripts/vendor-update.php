<?php
require 'connection.php';
require 'helpers.php';
session_start();

if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php?error=AccessDenied");
    exit();
}

// ============================================================
// Mode A: GET request - Simple status toggle from vendors list
// ============================================================
if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['id']) && isset($_GET['status'])) {
    $vendor_id = intval($_GET['id']);
    $new_status = intval($_GET['status']);

    // Validate status value
    if ($new_status !== 1 && $new_status !== 2) {
        header("Location: ../vendors.php?error=InvalidStatus");
        exit();
    }

    $stmt = $conn->prepare("UPDATE vendors SET status = ? WHERE vendor_id = ?");
    $stmt->bind_param("ii", $new_status, $vendor_id);

    if ($stmt->execute()) {
        $stmt->close();

        // Log to admin_history
        $admin_id = $_SESSION['admin_id'];
        $action   = "Changed Vendor Status";
        $status_label = ($new_status == 1) ? "Active" : "Inactive";
        $details  = "Vendor ID: {$vendor_id}, New Status: {$status_label}";
        logAdminAction($conn, $admin_id, $action, $details);

        header("Location: ../vendors.php?success=StatusUpdated");
        exit();
    } else {
        die("Error updating status: " . $stmt->error);
    }
}

// ============================================================
// Mode B: POST request - Full update from edit form
// ============================================================
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $vendor_id     = intval($_POST['vendor_id']);
    $user_id       = intval($_POST['user_id']);
    $first_name    = $_POST['first_name'];
    $last_name     = $_POST['last_name'];
    $email         = $_POST['email'];
    $phone         = $_POST['phone'];
    $business_name = $_POST['business_name'];
    $specialty     = $_POST['specialty'];
    $status        = intval($_POST['status']);
    $new_password  = isset($_POST['new_password']) ? trim($_POST['new_password']) : '';

    $conn->begin_transaction();

    try {
        // Check if email already exists for a different user
        $checkEmailStmt = $conn->prepare("SELECT user_id FROM users WHERE email = ? AND user_id != ?");
        $checkEmailStmt->bind_param("si", $email, $user_id);
        $checkEmailStmt->execute();
        $checkEmailStmt->store_result();

        if ($checkEmailStmt->num_rows > 0) {
            $checkEmailStmt->close();
            $conn->rollback();
            header("Location: ../vendor-edit.php?id=" . $vendor_id . "&error=EmailAlreadyExists");
            exit();
        }
        $checkEmailStmt->close();

        // Update users table
        $stmt = $conn->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ?, phone = ? WHERE user_id = ?");
        $stmt->bind_param("ssssi", $first_name, $last_name, $email, $phone, $user_id);
        $stmt->execute();
        $stmt->close();

        // If new password provided, update it
        if (!empty($new_password)) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $pwd_stmt = $conn->prepare("UPDATE users SET password = ? WHERE user_id = ?");
            $pwd_stmt->bind_param("si", $hashed_password, $user_id);
            $pwd_stmt->execute();
            $pwd_stmt->close();
        }

        // Update vendors table
        $stmt = $conn->prepare("UPDATE vendors SET business_name = ?, specialty = ?, status = ? WHERE vendor_id = ?");
        $stmt->bind_param("ssii", $business_name, $specialty, $status, $vendor_id);
        $stmt->execute();
        $stmt->close();

        // Log to admin_history
        $admin_id = $_SESSION['admin_id'];
        $action   = "Updated Vendor";
        $details  = "Vendor ID: {$vendor_id}, Name: {$first_name} {$last_name}, Business: {$business_name}, Email: {$email}";
        logAdminAction($conn, $admin_id, $action, $details);

        $conn->commit();

        header("Location: ../vendors.php?success=VendorUpdated");
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        die("Error: " . $e->getMessage());
    }
} else {
    // Neither valid GET nor POST
    header("Location: ../vendors.php?error=InvalidRequest");
    exit();
}
