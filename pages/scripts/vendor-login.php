<?php
session_start();
require '../../admin/pages/scripts/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Query user and vendor details
    $stmt = $conn->prepare("SELECT u.user_id, u.first_name, u.last_name, u.email, u.password, u.phone, v.vendor_id, v.business_name, v.status AS vendor_status FROM users u INNER JOIN vendors v ON u.user_id = v.user_id WHERE u.email = ? AND u.role = 'vendor'");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Check if vendor account is deactivated (status 2)
        if ($user['vendor_status'] == 2) {
            $stmt->close();
            header("Location: ../vendor-login.php?error=AccountDeactivated");
            exit();
        }

        // Verify password
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id']       = $user['user_id'];
            $_SESSION['vendor_id']     = $user['vendor_id'];
            $_SESSION['first_name']    = $user['first_name'];
            $_SESSION['last_name']     = $user['last_name'];
            $_SESSION['email']         = $user['email'];
            $_SESSION['role']          = 'vendor';
            $_SESSION['business_name'] = $user['business_name'];

            $stmt->close();
            header("Location: ../vendor-dashboard.php");
            exit();
        } else {
            $stmt->close();
            header("Location: ../vendor-login.php?error=InvalidPassword");
            exit();
        }
    } else {
        $stmt->close();
        header("Location: ../vendor-login.php?error=UserNotFound");
        exit();
    }
}
?>
