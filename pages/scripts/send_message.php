<?php
require '../../admin/pages/scripts/connection.php';

// Check login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'provider') {
    header("Location: login.php?error=AccessDenied");
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $provider_id = intval($_POST['provider_id']);
    $client_id   = intval($_POST['client_id']);
    $sender_type = "provider"; // force provider since this script is for providers
    $content     = trim($_POST['content']);

    if (!empty($content)) {
        $stmt = $conn->prepare("INSERT INTO messages (provider_id, client_id, sender_type, content, status, created_at) VALUES (?, ?, ?, ?, 'unread', NOW())");
        $stmt->bind_param("iiss", $provider_id, $client_id, $sender_type, $content);
        
        if ($stmt->execute()) {
            // redirect back to conversation
            header("Location: ../provider-message-view.php?message_id=" . $_POST['message_id'] . "&success=MessageSent");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        header("Location: ../provider-message-view.php?message_id=" . $_POST['message_id'] . "&error=EmptyMessage");
        exit();
    }
}
?>
