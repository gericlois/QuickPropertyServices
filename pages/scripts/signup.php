<?php
require '../../admin/pages/scripts/connection.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hashing password
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $role = $_POST['role'];
    $created_at = date("Y-m-d");

    $conn->begin_transaction(); // Start transaction

    try {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Email already exists, redirect with an error message
            header("Location: ../signup.php?error=EmailAlreadyExists");
            exit();
        }

        // Insert into users table
        $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, password, phone, address, role, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $first_name, $last_name, $email, $password, $phone, $address, $role, $created_at);
        $stmt->execute();
        $user_id = $stmt->insert_id; // Get the last inserted user ID

        // If the user is a provider, insert into providers table
        if ($role === 'provider') {
            $status = 'Active'; // Assuming 'A' means Active

            $stmt = $conn->prepare("INSERT INTO providers (provider_id, password, status, created_at) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isss", $user_id, $password, $status, $created_at);
            $stmt->execute();
        }

        $conn->commit(); // Commit transaction
        header("Location: ../promp.php?success=AccountCreated");
        exit();
    } catch (Exception $e) {
        $conn->rollback(); // Rollback on error
        header("Location: ../promp.php?error=DatabaseError");
        exit();
    }
}
?>
