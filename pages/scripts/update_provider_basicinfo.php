<?php
require '../../admin/pages/scripts/connection.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    session_start();

    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }

    $user_id = $_SESSION['user_id']; // Get user ID from session

    // Collect and sanitize input data
    $first_name = htmlspecialchars(trim($_POST['first_name']));
    $last_name = htmlspecialchars(trim($_POST['last_name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $phone = htmlspecialchars(trim($_POST['phone']));
    $address = htmlspecialchars(trim($_POST['address']));
    $birthday = htmlspecialchars(trim($_POST['birthday']));
    $link_facebook = htmlspecialchars(trim($_POST['link_facebook']));
    $link_linkedin = htmlspecialchars(trim($_POST['link_linkedin']));
    $link_instagram = htmlspecialchars(trim($_POST['link_instagram']));

    // Update users table
    $query = "UPDATE users SET 
                first_name = ?, 
                last_name = ?, 
                email = ?, 
                phone = ?, 
                address = ?, 
                birthday = ?, 
                link_facebook = ?, 
                link_linkedin = ?, 
                link_instagram = ?
              WHERE user_id = ?";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("sssssssssi", 
            $first_name, 
            $last_name, 
            $email, 
            $phone, 
            $address, 
            $birthday, 
            $link_facebook, 
            $link_linkedin, 
            $link_instagram, 
            $user_id
        );

        if ($stmt->execute()) {
            // Redirect to providers.php with a success message
            header("Location: ../provider-profile.php?success=ProfileUpdatedSuccessfully");
            exit();
        } else {
            // Redirect to providers.php with an error message
            header("Location: ../provider-profile.php?error=ProfileUpdateFailed");
            exit();
        }

        $stmt->close();
    } else {
        // Redirect to providers.php with a database error message
        header("Location: ../provider-profile.php?error=DatabaseError");
        exit();
    }

    $conn->close();
} else {
    // Redirect if accessed without POST request
    header("Location: ../provider-profile.php?error=InvalidAccess");
    exit();
}
?>