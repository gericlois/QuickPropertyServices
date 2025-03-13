<?php
require 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $birthday = $_POST['birthday'];
    $link_facebook = $_POST['link_facebook'];
    $link_linkedin = $_POST['link_linkedin'];
    $link_instagram = $_POST['link_instagram'];
    $business_name = $_POST['business_name'];
    $status = $_POST['status'];
    $created_at = date("Y-m-d");

    $conn->begin_transaction();

    try {
        // Check if email already exists
        $checkEmailStmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
        $checkEmailStmt->bind_param("s", $email);
        $checkEmailStmt->execute();
        $checkEmailStmt->store_result();

        if ($checkEmailStmt->num_rows > 0) {
            $checkEmailStmt->close();
            header("Location: ../providers-add.php?error=EmailAlreadyExists");
            exit();
        }
        $checkEmailStmt->close();

        // Insert into Users table
        $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, password, phone, address, birthday, link_facebook, link_linkedin, link_instagram, role, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'provider', ?)");
        $stmt->bind_param("sssssssssss", $first_name, $last_name, $email, $password, $phone, $address, $birthday, $link_facebook, $link_linkedin, $link_instagram, $created_at);
        $stmt->execute();
        $user_id = $stmt->insert_id; // Get last inserted user_id
        $stmt->close();

        // Insert into Providers table
        $stmt = $conn->prepare("INSERT INTO providers (user_id, business_name, password, status, created_at) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $user_id, $business_name, $password, $status, $created_at);
        $stmt->execute();
        $stmt->close();

        $conn->commit();

        // Redirect after successful insertion
        header("Location: ../providers.php?success=ProviderAddedSuccessfully");
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        die("Error: " . $e->getMessage());
    }
} else {
    header("Location: ../providers.php?error=InvalidRequest");
    exit();
}
?>
