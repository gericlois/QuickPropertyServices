<?php
require '../../admin/pages/scripts/connection.php'; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = $_POST['role'];
    $created_at = date("Y-m-d H:i:s"); // Timestamp

    // Check if email already exists
    $checkEmail = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    $result = $checkEmail->get_result();

    if ($result->num_rows > 0) {
        header("Location: ../signup.php?error=EmailAlreadyExists");
        exit();
    }

    // Insert into users table (without status)
    $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, password, role, created_at) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $first_name, $last_name, $email, $password, $role, $created_at);

    if ($stmt->execute()) {
        $user_id = $conn->insert_id; // Get last inserted user_id

        // If role is "user", insert into clients table with "Active" status and created_at timestamp
        if ($role === "user") {
            $status = "1"; // Status for clients
            $stmtClient = $conn->prepare("INSERT INTO clients (user_id, status, created_at) VALUES (?, ?, ?)");
            $stmtClient->bind_param("iss", $user_id, $status, $created_at);
            $stmtClient->execute();
            $stmtClient->close();
        }

        header("Location: ../promp.php?success=AccountCreated");
        exit();
    } else {
        header("Location: ../signup.php?error=SignupFailed");
        exit();
    }
}
?>
