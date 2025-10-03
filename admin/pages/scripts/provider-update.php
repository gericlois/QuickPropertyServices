<?php
session_start();
include "connection.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../../vendor/autoload.php'; // Ensure PHPMailer is installed via Composer

if (isset($_GET['id']) && isset($_GET['status'])) {
    $provider_id = intval($_GET['id']); // Ensure it's an integer
    $status      = $_GET['status'];

    // Fetch provider details including email
    $query = "SELECT u.user_id, u.email, u.first_name, u.last_name 
              FROM providers p 
              JOIN users u ON p.user_id = u.user_id 
              WHERE p.provider_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $provider_id);
    $stmt->execute();
    $result   = $stmt->get_result();
    $provider = $result->fetch_assoc();
    
    if (!$provider) {
        header("Location: ../providers.php?error=ProviderNotFound");
        exit();
    }

    $email = $provider['email'];
    $name  = $provider['first_name'] . ' ' . $provider['last_name'];
    $user_id = $provider['user_id'];

    // Update provider status
    $sql = "UPDATE providers SET status = ? WHERE provider_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $provider_id);

    if ($stmt->execute()) {
        // ✅ Log activity to admin_history
        if (isset($_SESSION['admin_id'])) {
            $admin_id   = $_SESSION['admin_id'];
            $action     = "Changed Provider Status";
            $status_text = ($status == '1') ? 'Active' : 'Inactive';
            $details    = "Provider ID: $provider_id, User ID: $user_id, Name: $name, New Status: $status_text";
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

        // Send email notification
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.example.com'; // Replace with your SMTP host
            $mail->SMTPAuth   = true;
            $mail->Username   = 'your_email@example.com'; // Replace with your SMTP username
            $mail->Password   = 'your_email_password';    // Replace with your SMTP password
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            $mail->setFrom('no-reply@example.com', 'Service Platform');
            $mail->addAddress($email, $name);
            
            $mail->Subject = "Your Provider Status Has Been Updated";
            $mail->Body = "Hello $name,\n\nYour provider account status has been updated to: $status_text.\n\nIf you have any questions, please contact support.\n\nBest regards,\nService Platform Team";

            $mail->send();
        } catch (Exception $e) {
            header("Location: ../providers.php?success=StatusUpdated&provider_id=" . urlencode($provider_id) . "&email_error=" . urlencode($mail->ErrorInfo));
            exit();
        }

        header("Location: ../providers.php?success=StatusUpdated&provider_id=" . urlencode($provider_id));
        exit();
    } else {
        header("Location: ../providers.php?error=UpdateFailed");
        exit();
    }
    
    $stmt->close();
}

$conn->close();
?>
