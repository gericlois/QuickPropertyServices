<?php
require 'connection.php'; 
session_start(); // Needed to get logged-in admin_id

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize input values
    $provider_id   = $_POST['provider_id'];
    $user_id       = $_POST['user_id'];
    $first_name    = trim($_POST['first_name']);
    $last_name     = trim($_POST['last_name']);
    $email         = trim($_POST['email']);
    $phone         = trim($_POST['phone']);
    $address       = trim($_POST['address']);
    $business_name = trim($_POST['business_name']);
    $status        = $_POST['status'];

    // Validate required fields
    if (empty($first_name) || empty($last_name) || empty($email) || empty($business_name)) {
        die("Error: Required fields are missing.");
    }

    $conn->begin_transaction();

    try {
        // Update the Users table
        $sql_update_user = "UPDATE users 
                            SET first_name = ?, last_name = ?, email = ?, phone = ?, address = ? 
                            WHERE user_id = ?";
        $stmt_user = $conn->prepare($sql_update_user);
        $stmt_user->bind_param("sssssi", $first_name, $last_name, $email, $phone, $address, $user_id);

        if (!$stmt_user->execute()) {
            throw new Exception("Error updating user: " . $stmt_user->error);
        }

        // Update the Providers table
        $sql_update_provider = "UPDATE providers 
                                SET business_name = ?, status = ? 
                                WHERE provider_id = ?";
        $stmt_provider = $conn->prepare($sql_update_provider);
        $stmt_provider->bind_param("sii", $business_name, $status, $provider_id);

        if (!$stmt_provider->execute()) {
            throw new Exception("Error updating provider: " . $stmt_provider->error);
        }

        // ✅ Log activity to admin_history
        if (isset($_SESSION['admin_id'])) {
            $admin_id   = $_SESSION['admin_id'];
            $action     = "Updated Provider";
            $details    = "Provider ID: $provider_id, User ID: $user_id, Name: $first_name $last_name, Business: $business_name, Status: $status";
            $ip_address = $_SERVER['REMOTE_ADDR'] ?? '';
            $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';

            $log = $conn->prepare("INSERT INTO admin_history 
                (admin_id, action, details, ip_address, user_agent) 
                VALUES (?, ?, ?, ?, ?)");
            if ($log === false) {
                throw new Exception("Log prepare failed: " . $conn->error);
            }
            $log->bind_param("issss", $admin_id, $action, $details, $ip_address, $user_agent);
            if (!$log->execute()) {
                throw new Exception("Log insert failed: " . $log->error);
            }
            $log->close();
        }

        // Commit transaction
        $conn->commit();

        // Close statements and connection
        $stmt_user->close();
        $stmt_provider->close();
        $conn->close();

        // Redirect back to providers list
        header("Location: ../providers.php?success=ProviderUpdated&provider_id=$provider_id");
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        die("Error: " . $e->getMessage());
    }
} else {
    die("Invalid request.");
}
