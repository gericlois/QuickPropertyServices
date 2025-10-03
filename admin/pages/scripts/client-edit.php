<?php
session_start();
require 'connection.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $birthday = trim($_POST['birthday']);
    $link_facebook = trim($_POST['link_facebook']);
    $link_linkedin = trim($_POST['link_linkedin']);
    $link_instagram = trim($_POST['link_instagram']);
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

    if (empty($user_id) || empty($first_name) || empty($last_name) || empty($email)) {
        header("Location: ../clients.php?error=MissingFields");
        exit();
    }

    if ($password) {
        $sql = "UPDATE users 
                SET first_name = ?, last_name = ?, email = ?, phone = ?, address = ?, birthday = ?, 
                    link_facebook = ?, link_linkedin = ?, link_instagram = ?, password = ? 
                WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "ssssssssssi",
            $first_name, $last_name, $email, $phone, $address, $birthday,
            $link_facebook, $link_linkedin, $link_instagram, $password, $user_id
        );
    } else {
        $sql = "UPDATE users 
                SET first_name = ?, last_name = ?, email = ?, phone = ?, address = ?, birthday = ?, 
                    link_facebook = ?, link_linkedin = ?, link_instagram = ? 
                WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "sssssssssi",
            $first_name, $last_name, $email, $phone, $address, $birthday,
            $link_facebook, $link_linkedin, $link_instagram, $user_id
        );
    }

    if ($stmt->execute()) {
        // ✅ Log activity to admin_history
        if (isset($_SESSION['admin_id'])) {
            $admin_id   = $_SESSION['admin_id'];
            $action     = "Updated Client Profile";
            $details    = "User ID: $user_id, Name: $first_name $last_name, Email: $email";
            $ip_address = $_SERVER['REMOTE_ADDR'] ?? '';
            $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';

            $log = $conn->prepare("INSERT INTO admin_history 
                (admin_id, action, details, ip_address, user_agent) 
                VALUES (?, ?, ?, ?, ?)");
            if ($log !== false) {
                $log->bind_param("issss", $admin_id, $action, $details, $ip_address, $user_agent);
                $log->execute();
                $log->close();
            }
        }

        header("Location: ../clients.php?success=UserUpdated&user_id=" . urlencode($user_id));
    } else {
        header("Location: ../clients.php?error=UpdateFailed");
    }

    $stmt->close();
}
?>
