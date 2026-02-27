<?php
require 'connection.php';
require 'helpers.php';
session_start();

if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php?error=AccessDenied");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name    = $_POST['first_name'];
    $last_name     = $_POST['last_name'];
    $email         = $_POST['email'];
    $password      = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $phone         = $_POST['phone'];
    $business_name = $_POST['business_name'];
    $specialty     = $_POST['specialty'];
    $status        = intval($_POST['status']);
    $created_at    = date("Y-m-d");

    $conn->begin_transaction();

    try {
        // Check if email already exists
        $checkEmailStmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
        $checkEmailStmt->bind_param("s", $email);
        $checkEmailStmt->execute();
        $checkEmailStmt->store_result();

        if ($checkEmailStmt->num_rows > 0) {
            $checkEmailStmt->close();
            header("Location: ../vendor-add.php?error=EmailAlreadyExists");
            exit();
        }
        $checkEmailStmt->close();

        // Insert into Users table
        $user_status = 1; // Active
        $stmt = $conn->prepare("INSERT INTO users
            (first_name, last_name, email, password, phone, role, status, created_at)
            VALUES (?, ?, ?, ?, ?, 'vendor', ?, ?)");
        $stmt->bind_param("sssssis", $first_name, $last_name, $email, $password, $phone, $user_status, $created_at);
        $stmt->execute();
        $user_id = $stmt->insert_id;
        $stmt->close();

        // Insert into Vendors table
        $stmt = $conn->prepare("INSERT INTO vendors (user_id, business_name, specialty, phone, email, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssss", $user_id, $business_name, $specialty, $phone, $email, $status, $created_at);
        $stmt->execute();
        $stmt->close();

        // Log to admin_history
        $admin_id = $_SESSION['admin_id'];
        $action   = "Added New Vendor";
        $details  = "Vendor: {$first_name} {$last_name}, Business: {$business_name}, Email: {$email}";
        logAdminAction($conn, $admin_id, $action, $details);

        $conn->commit();

        // Redirect after successful insertion
        header("Location: ../vendors.php?success=VendorAddedSuccessfully");
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        die("Error: " . $e->getMessage());
    }
} else {
    header("Location: ../vendors.php?error=InvalidRequest");
    exit();
}
