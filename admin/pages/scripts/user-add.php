<?php
session_start();
require 'connection.php';

if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php?error=AccessDenied");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $first_name = trim($_POST['first_name']);
    $last_name  = trim($_POST['last_name']);
    $email      = trim($_POST['email']);
    $password   = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $phone      = trim($_POST['phone'] ?? '');
    $address    = trim($_POST['address'] ?? '');
    $birthday   = !empty($_POST['birthday']) ? $_POST['birthday'] : null;

    // Check if email already exists
    $check = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    if ($check->get_result()->num_rows > 0) {
        $check->close();
        header("Location: ../users-add.php?error=EmailAlreadyExists");
        exit();
    }
    $check->close();

    $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, password, phone, address, birthday, role, status) VALUES (?, ?, ?, ?, ?, ?, ?, 'admin', 1)");
    $stmt->bind_param("sssssss", $first_name, $last_name, $email, $password, $phone, $address, $birthday);

    if ($stmt->execute()) {
        $new_id = $stmt->insert_id;
        $stmt->close();

        // Log action
        require 'helpers.php';
        logAdminAction($conn, $_SESSION['admin_id'], 'Created Admin User', "Created admin user #$new_id ($first_name $last_name)");

        header("Location: ../users.php?success=UserAdded&user_id=$new_id");
        exit();
    } else {
        $stmt->close();
        header("Location: ../users-add.php?error=Failed");
        exit();
    }
} else {
    header("Location: ../users-add.php");
    exit();
}
