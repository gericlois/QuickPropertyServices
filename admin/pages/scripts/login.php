<?php
session_start();
require 'connection.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Check if email exists
    $stmt = $conn->prepare("SELECT user_id, first_name, last_name, email, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        // Verify password
        if (password_verify($password, $user['password'])) {
            $_SESSION['admin_id'] = $user['user_id'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['last_name'] = $user['last_name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            // Redirect based on user role
            if ($user['role'] === 'admin') {
                header("Location: ../index.php?success=LoginSuccesfully"); // Redirect to admin page
            } else {
                header("Location: ../index.php"); // Redirect non-admins to home
            }
            exit();
        } else {
            header("Location: ../login.php?error=InvalidPassword");
            exit();
        }
    } else {
        header("Location: ../login.php?error=UserNotFound");
        exit();
    }
}
?>