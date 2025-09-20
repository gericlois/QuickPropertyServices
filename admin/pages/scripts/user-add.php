<?php
require 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name  = $_POST['last_name'];
    $email      = $_POST['email'];
    $password   = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $phone      = $_POST['phone'];
    $address    = $_POST['address'];
    $birthday   = $_POST['birthday'];
    $created_at = date("Y-m-d");

    try {
        // Check if email already exists
        $checkEmailStmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
        $checkEmailStmt->bind_param("s", $email);
        $checkEmailStmt->execute();
        $checkEmailStmt->store_result();

        if ($checkEmailStmt->num_rows > 0) {
            $checkEmailStmt->close();
            header("Location: ../users-add.php?error=EmailAlreadyExists");
            exit();
        }
        $checkEmailStmt->close();

        // Insert into Users table only
        $stmt = $conn->prepare("INSERT INTO users 
            (first_name, last_name, email, password, phone, address, birthday, role, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, 'admin', ?)");
        $stmt->bind_param("ssssssss", $first_name, $last_name, $email, $password, $phone, $address, $birthday, $created_at);
        $stmt->execute();
        $stmt->close();

        // Redirect after successful insertion
        header("Location: ../users.php?success=UserAddedSuccessfully");
        exit();

    } catch (Exception $e) {
        die("Error: " . $e->getMessage());
    }
} else {
    header("Location: ../users.php?error=InvalidRequest");
    exit();
}
?>
