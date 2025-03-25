<?php
require '../../admin/pages/scripts/connection.php'; 
session_start();
// Check if the user is logged in and has the client role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'client') {
    header("Location: ../login.php?error=AccessDenied");
    exit();
}

$user_id = $_SESSION['user_id'];

// Check if form data is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $first_name = trim($_POST["first_name"]);
    $last_name = trim($_POST["last_name"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);
    $address = trim($_POST["address"]);
    $birthday = trim($_POST["birthday"]);
    $link_facebook = trim($_POST["link_facebook"]);
    $link_linkedin = trim($_POST["link_linkedin"]);
    $link_instagram = trim($_POST["link_instagram"]);
    $new_password = !empty($_POST["password"]) ? password_hash($_POST["password"], PASSWORD_BCRYPT) : null;

    // Ensure required fields are not empty
    if (empty($first_name) || empty($last_name) || empty($email)) {
        header("Location: ../client-profile.php?error=MissingFields");
        exit();
    }

    // Prepare SQL query (conditionally updating the password)
    if ($new_password) {
        $stmt = $conn->prepare("
            UPDATE users SET 
                first_name = ?, last_name = ?, email = ?, phone = ?, address = ?, 
                birthday = ?, link_facebook = ?, link_linkedin = ?, link_instagram = ?, 
                password = ?
            WHERE user_id = ?
        ");
        $stmt->bind_param("ssssssssssi", 
            $first_name, $last_name, $email, $phone, $address, 
            $birthday, $link_facebook, $link_linkedin, $link_instagram, 
            $new_password, $user_id
        );
    } else {
        $stmt = $conn->prepare("
            UPDATE users SET 
                first_name = ?, last_name = ?, email = ?, phone = ?, address = ?, 
                birthday = ?, link_facebook = ?, link_linkedin = ?, link_instagram = ?
            WHERE user_id = ?
        ");
        $stmt->bind_param("sssssssssi", 
            $first_name, $last_name, $email, $phone, $address, 
            $birthday, $link_facebook, $link_linkedin, $link_instagram, $user_id
        );
    }

    if (!$stmt) {
        die("Database error: " . $conn->error);
    }

    // Execute the statement
    if ($stmt->execute()) {
        header("Location: ../client-profile.php?success=ProfileUpdatedSuccessfully");
    } else {
        header("Location: ../client-profile.php?error=ProfileUpdateFailed");
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: ../client-profile.php?error=InvalidRequest");
    exit();
}
?>
