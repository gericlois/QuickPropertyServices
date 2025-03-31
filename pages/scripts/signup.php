<?php

require '../../admin/pages/scripts/connection.php'; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = trim($_POST['role']); // Ensure no unwanted spaces
    $created_at = date("Y-m-d"); // Date format for consistency

    // Handle optional fields
    $phone = !empty(trim($_POST['phone'])) ? trim($_POST['phone']) : NULL;
    $address = !empty(trim($_POST['address'])) ? trim($_POST['address']) : NULL;

    // Check if email already exists
    $checkEmail = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    $result = $checkEmail->get_result();

    if ($result->num_rows > 0) {
        header("Location: ../signup.php?error=EmailAlreadyExists");
        exit();
    }

    // Insert into users table (including address & phone)
    $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, password, phone, address, role, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $first_name, $last_name, $email, $password, $phone, $address, $role, $created_at);

    if (!$stmt->execute()) {
        die("Error inserting user: " . $stmt->error);
    }

    $user_id = $conn->insert_id; // Get last inserted user_id
    $status = ($role === "provider") ? "2" : "1"; // Default status: 2 for provider, 1 for client

    if ($role === "client") {
        $stmtClient = $conn->prepare("INSERT INTO clients (user_id, status, created_at) VALUES (?, ?, ?)");
        $stmtClient->bind_param("iss", $user_id, $status, $created_at);
        if (!$stmtClient->execute()) {
            die("Error inserting client: " . $stmtClient->error);
        }
        $stmtClient->close();
    } elseif ($role === "provider") {
        $business_name = isset($_POST['business_name']) ? trim($_POST['business_name']) : NULL;

        $stmtProvider = $conn->prepare("INSERT INTO providers (user_id, business_name, status, created_at) VALUES (?, ?, ?, ?)");
        $stmtProvider->bind_param("isss", $user_id, $business_name, $status, $created_at);
        if (!$stmtProvider->execute()) {
            die("Error inserting provider: " . $stmtProvider->error);
        }
        $stmtProvider->close();
    }

    header("Location: ../promp.php?success=AccountCreated");
    exit();
}

?>
