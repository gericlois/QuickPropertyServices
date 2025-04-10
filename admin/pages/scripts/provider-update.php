<?php
session_start();
include "connection.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../../vendor/autoload.php'; // Ensure PHPMailer is installed via Composer

if (isset($_GET['id']) && isset($_GET['status'])) {
    $provider_id = intval($_GET['id']); // Ensure it's an integer
    $status = $_GET['status'];

    // Fetch provider details including email
    $query = "SELECT u.email, u.first_name, u.last_name FROM providers p 
              JOIN users u ON p.user_id = u.user_id WHERE p.provider_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $provider_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $provider = $result->fetch_assoc();
    
    if (!$provider) {
        header("Location: ../providers.php?error=ProviderNotFound");
        exit();
    }

    $email = $provider['email'];
    $name = $provider['first_name'] . ' ' . $provider['last_name'];

    // Update provider status
    $sql = "UPDATE providers SET status = ? WHERE provider_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $provider_id);

    if ($stmt->execute()) {
        // Send email notification
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.example.com'; // Replace with your SMTP host
            $mail->SMTPAuth = true;
            $mail->Username = 'your_email@example.com'; // Replace with your SMTP username
            $mail->Password = 'your_email_password'; // Replace with your SMTP password
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('no-reply@example.com', 'Service Platform');
            $mail->addAddress($email, $name);
            
            $mail->Subject = "Your Provider Status Has Been Updated";
            $status_text = ($status == '1') ? 'Active' : 'Inactive';
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
