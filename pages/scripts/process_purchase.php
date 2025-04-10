<?php
session_start();
require '../../admin/pages/scripts/connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'client') {
    header("Location: ../login.php?error=AccessDenied");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $client_id = $_SESSION['user_id'];
    $service_id = $_POST['service_id'];
    $date = $_POST['date'];
    $time = $_POST['time'];

  
    $stmt = $conn->prepare("SELECT provider_id, base_price FROM services WHERE service_id = ?");
    $stmt->bind_param("i", $service_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $service = $result->fetch_assoc();

    if (!$service) {
        header("Location: ../client-services.php?error=InvalidService");
        exit();
    }

    $provider_id = $service['provider_id'];
    $total_price = $service['price']; 
    $appointment_date = $date . ' ' . $time; // Combine date & time
    $status = 'P'; 
    $created_at = date('Y-m-d');

    $stmt = $conn->prepare("
        INSERT INTO bookings (client_id, provider_id, service_id, appointment_date, status, total_price, created_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("iiissss", $client_id, $provider_id, $service_id, $appointment_date, $status, $total_price, $created_at);

    if ($stmt->execute()) {
        header("Location: ../client-services.php?success=BookingConfirmed");
    } else {
        header("Location: ../client-services.php?error=BookingFailed");
    }

    $stmt->close();
    $conn->close();
}
?>
