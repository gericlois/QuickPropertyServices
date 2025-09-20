<?php
require '../../admin/pages/scripts/connection.php'; // DB connection file
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Handle client_id (from session or POST, allow NULL)
    $client_id = $_SESSION['client_id'] ?? ($_POST['client_id'] ?? null);
    $client_id = !empty($client_id) ? intval($client_id) : null;

    // Sanitize form data
    $contact_source    = $conn->real_escape_string($_POST['contact_source']);
    $homeowner_name    = $conn->real_escape_string($_POST['homeowner_name']);
    $address           = $conn->real_escape_string($_POST['address']);
    $phone1            = $conn->real_escape_string($_POST['phone1']);
    $phone2            = $conn->real_escape_string($_POST['phone2']);
    $email             = $conn->real_escape_string($_POST['email']);
    $work_description  = $conn->real_escape_string($_POST['work_description']);
    $estimator_notes   = $conn->real_escape_string($_POST['estimator_notes']);
    $crew_instructions = $conn->real_escape_string($_POST['crew_instructions']);

    // Handle image uploads
    $uploadDir = "../../uploads/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $imagePaths = [];
    if (!empty($_FILES['images']['name'][0])) {
        $fileCount = count($_FILES['images']['name']);
        if ($fileCount > 5) $fileCount = 5;

        for ($i = 0; $i < $fileCount; $i++) {
            $fileName   = time() . "_" . basename($_FILES['images']['name'][$i]);
            $targetFile = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['images']['tmp_name'][$i], $targetFile)) {
                $imagePaths[] = "uploads/" . $fileName; // relative path
            } else {
                $imagePaths[] = NULL;
            }
        }
    }

    // Pad image array to 5 slots
    for ($i = count($imagePaths); $i < 5; $i++) {
        $imagePaths[] = NULL;
    }

    // Insert into database
    $sql = "INSERT INTO job_requests 
        (client_id, contact_source, homeowner_name, address, phone1, phone2, email, 
         work_description, estimator_notes, crew_instructions, 
         image1, image2, image3, image4, image5) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        header("Location: ../request.php?error=PrepareFailed");
        exit();
    }

    // If no client_id, bind as NULL
    if ($client_id === null) {
        $stmt->bind_param(
            "issssssssssssss",
            $client_id, $contact_source, $homeowner_name, $address, $phone1, $phone2, $email,
            $work_description, $estimator_notes, $crew_instructions,
            $imagePaths[0], $imagePaths[1], $imagePaths[2], $imagePaths[3], $imagePaths[4]
        );
    } else {
        $stmt->bind_param(
            "issssssssssssss",
            $client_id, $contact_source, $homeowner_name, $address, $phone1, $phone2, $email,
            $work_description, $estimator_notes, $crew_instructions,
            $imagePaths[0], $imagePaths[1], $imagePaths[2], $imagePaths[3], $imagePaths[4]
        );
    }

    if ($stmt->execute()) {
        header("Location: ../request.php?success=JobRequestSubmitted");
        exit();
    } else {
        header("Location: ../request.php?error=ErrorSubmittingJobRequest");
        exit();
    }

    $stmt->close();
}

$conn->close();
?>
