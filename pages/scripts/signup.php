<?php

require '../../admin/pages/scripts/connection.php'; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = trim($_POST['role']); // Ensure no unwanted spaces
    $created_at = date("Y-m-d"); // Date format for consistency

    // Debugging
    echo "Role before insertion: " . $role . "<br>";
    print_r($_POST);

    // Check if email already exists
    $checkEmail = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    $result = $checkEmail->get_result();

    if ($result->num_rows > 0) {
        header("Location: ../signup.php?error=EmailAlreadyExists");
        exit();
    }

    // Insert into users table
    $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, password, role, created_at) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $first_name, $last_name, $email, $password, $role, $created_at);

    if (!$stmt->execute()) {
        echo "Error inserting user: " . $stmt->error;
        exit();
    }

    $user_id = $conn->insert_id; // Get last inserted user_id
    $status = "1"; // Default active status

    if ($role === "client") {
        $stmtClient = $conn->prepare("INSERT INTO clients (user_id, status, created_at) VALUES (?, ?, ?)");
        $stmtClient->bind_param("iss", $user_id, $status, $created_at);
        if (!$stmtClient->execute()) {
            echo "Error inserting client: " . $stmtClient->error;
            exit();
        }
        $stmtClient->close();
    } elseif ($role === "provider") {
        $business_name = trim($_POST['business_name']);
        $stmtProvider = $conn->prepare("INSERT INTO providers (user_id, business_name, status, created_at) VALUES (?, ?, ?, ?)");
        $stmtProvider->bind_param("isss", $user_id, $business_name, $status, $created_at);
        if (!$stmtProvider->execute()) {
            echo "Error inserting provider: " . $stmtProvider->error;
            exit();
        }
        $stmtProvider->close();
    }

    header("Location: ../promp.php?success=AccountCreated");
    exit();
}

?>
