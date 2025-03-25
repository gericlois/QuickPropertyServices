<?php
session_start();
require '../../admin/pages/scripts/connection.php'; // Database connection

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
        $user_id = $user['user_id'];
        $role = $user['role'];

        // Prevent admin from logging in
        if ($role === 'admin') {
            header("Location: ../login.php?error=UnauthorizedAccess");
            exit();
        }

        // Get status for provider or client
        $status = null;
        $provider_id = null;

        if ($role === 'provider') {
            $status_stmt = $conn->prepare("SELECT provider_id, status FROM providers WHERE user_id = ?");
        } elseif ($role === 'client') {  // Ensure this matches your database roles
            $status_stmt = $conn->prepare("SELECT client_id, status FROM clients WHERE user_id = ?");
        } else {
            $status_stmt = null;
        }

        if ($status_stmt) {
            $status_stmt->bind_param("i", $user_id);
            $status_stmt->execute();
            $status_result = $status_stmt->get_result();
            if ($status_result->num_rows === 1) {
                $status_data = $status_result->fetch_assoc();
                $status = $status_data['status'];
                if ($role === 'provider') {
                    $provider_id = $status_data['provider_id']; 
                }
            }
            $status_stmt->close();
        }

        // If status is 2, deny access
        if ($status == 2) {
            header("Location: ../login.php?error=AccountDeactivated");
            exit();
        }

        // Verify password
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user_id;
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['last_name'] = $user['last_name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $role;


            if ($role === 'provider') {
                $_SESSION['provider_id'] = $provider_id ?? null;
                header("Location: ../provider-dashboard.php?success=LoginSuccessfully");
            } elseif ($role === 'client') {
                $_SESSION['client_id'] = $client_id ?? null;
                header("Location: ../client-services.php?success=LoginSuccessfully");
            } else {
                header("Location: ../login.php?error=RoleNotAllowed");
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
