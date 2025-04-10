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

<<<<<<< HEAD
  
=======
    // Fetch the provider_id, base_price, and job status
>>>>>>> 8e5adf97b8dfe4aaff6b269ca0bc333d2fcd55d1
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
<<<<<<< HEAD
    $total_price = $service['price']; 
    $appointment_date = $date . ' ' . $time; // Combine date & time
    $status = 'P'; 
    $created_at = date('Y-m-d');

=======
    $total_price = $service['base_price']; // Price from `services` table
    $appointment_datetime = $date . ' ' . $time; // Combine date & time
    $status = '1'; // '1' for Pending
    $created_at = date('Y-m-d');

    // Check if the user has already booked this service with job status 1
    $stmt = $conn->prepare("
        SELECT COUNT(*) AS count FROM bookings 
        WHERE client_id = ? AND service_id = ? AND status = '1'
    ");
    $stmt->bind_param("ii", $client_id, $service_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row['count'] > 0) {
        header("Location: ../client-services.php?error=AlreadyBooked");
        exit();
    }

    // Check for overlapping appointments within 2 hours for the same provider
    $stmt = $conn->prepare("
        SELECT COUNT(*) AS count FROM bookings 
        WHERE provider_id = ? 
        AND ABS(TIMESTAMPDIFF(MINUTE, appointment_date, ?)) < 120
    ");
    $stmt->bind_param("is", $provider_id, $appointment_datetime);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row['count'] > 0) {
        header("Location: ../client-services.php?error=TimeSlotUnavailable");
        exit();
    }

    // Insert into bookings table
>>>>>>> 8e5adf97b8dfe4aaff6b269ca0bc333d2fcd55d1
    $stmt = $conn->prepare("
        INSERT INTO bookings (client_id, provider_id, service_id, appointment_date, status, total_price, created_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("iiissss", $client_id, $provider_id, $service_id, $appointment_datetime, $status, $total_price, $created_at);

    if ($stmt->execute()) {
        header("Location: ../client-services.php?success=BookingConfirmed");
    } else {
        header("Location: ../client-services.php?error=BookingFailed");
    }

    $stmt->close();
    $conn->close();
}
?>
