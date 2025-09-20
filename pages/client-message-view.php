<!DOCTYPE html>
<html lang="en">
<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'client') {
    header("Location: login.php?error=AccessDenied");
    exit();
} else {
    include "includes/head.php";
    include "../admin/pages/scripts/connection.php";
}

$client_id = $_SESSION['client_id']; // Logged-in client’s ID

?>
<?php

$message_id = isset($_GET['message_id']) ? intval($_GET['message_id']) : 0;

// If message_id is provided, mark as read and get provider_id
$provider_id = 0;
if ($message_id > 0) {
    $stmt = $conn->prepare("SELECT * FROM messages WHERE message_id = ? AND client_id = ?");
    $stmt->bind_param("ii", $message_id, $client_id);
    $stmt->execute();
    $message = $stmt->get_result()->fetch_assoc();

    if ($message) {
        $provider_id = $message['provider_id'];

        // Mark as read
        $update = $conn->prepare("UPDATE messages SET status = 'read' WHERE message_id = ?");
        $update->bind_param("i", $message_id);
        $update->execute();
    }
}

// If still no provider_id, fallback
if ($provider_id === 0) {
    die("No provider selected.");
}

// Fetch full conversation
$chat = $conn->prepare("
    SELECT m.*, 
           c.first_name AS client_first, c.last_name AS client_last,
           p.first_name AS provider_first, p.last_name AS provider_last
    FROM messages m
    LEFT JOIN users c ON m.client_id = c.user_id
    LEFT JOIN users p ON m.provider_id = p.user_id
    WHERE m.client_id = ? AND m.provider_id = ?
    ORDER BY m.created_at ASC
");
$chat->bind_param("ii", $client_id, $provider_id);
$chat->execute();
$messages = $chat->get_result();
?>


<?php
$role = $_SESSION['role'] ?? 'guest'; // fallback if not logged in
?>
<body class="index-page <?php echo $role; ?>">

    <?php include "includes/header.php" ?>

    <main class="main">

        <!-- Messages Section -->
        <section id="messages" class="services section light-background">

            <div class="container section-title" data-aos="fade-up">
                <h2>My Messages</h2>
            </div>

            <div class="container" data-aos="fade-up" data-aos-delay="100">
                <div class="container py-4">
                    <div class="chat-container border rounded shadow-sm p-3" style="height: auto; display: flex; flex-direction: column;">

                        <!-- Conversation Messages -->
                        <div class="chat-box flex-grow-1 overflow-auto mb-3" style="max-height: 400px;">
                            <?php if ($messages->num_rows > 0): ?>
                                <?php while ($row = $messages->fetch_assoc()) : ?>
                                    <?php
                                    $loggedInClient = $_SESSION['client_id'];
                                    $isMine = ($row['client_id'] == $loggedInClient && $row['sender_type'] === 'client');

                                    // Decide sender label
                                    if ($isMine) {
                                        $senderName = "You";
                                    } elseif ($row['sender_type'] === 'provider') {
                                        $senderName = htmlspecialchars($row['provider_first'] . " " . $row['provider_last']);
                                    } else {
                                        $senderName = htmlspecialchars($row['client_first'] . " " . $row['client_last']);
                                    }
                                    ?>
                                    
                                    <div class="d-flex flex-column mb-3 <?= $isMine ? 'align-items-end' : 'align-items-start' ?>">
                                        <!-- Sender Name -->
                                        <small class="fw-bold mb-1 <?= $isMine ? 'text-primary' : 'text-secondary' ?>">
                                            <?= $senderName ?>:
                                        </small>

                                        <!-- Message Container -->
                                        <div class="card p-2 <?= $isMine ? 'bg-primary text-white' : 'bg-light' ?>" style="max-width: 70%;">
                                            <p class="mb-1"><?= nl2br(htmlspecialchars($row['content'])) ?></p>
                                            <small class="<?= $isMine ? 'text-light' : 'text-muted' ?>">
                                                <i class="bi bi-clock"></i> <?= date("M d, Y h:i A", strtotime($row['created_at'])) ?>
                                            </small>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <p class="text-center text-muted">No messages yet. Start the conversation!</p>
                            <?php endif; ?>
                        </div>

                        <!-- Reply Box -->
                        <form action="scripts/send_message.php" method="POST" class="d-flex">
                            <input type="hidden" name="provider_id" value="<?= $provider_id ?>">
                            <input type="hidden" name="client_id" value="<?= $client_id ?>">
                            <input type="hidden" name="sender_type" value="client">

                            <input type="text" name="content" class="form-control me-2" placeholder="Type your message..." required>
                            <button type="submit" class="btn btn-primary">Send</button>
                        </form>

                    </div>
                </div>
            </div>

        </section>

    </main>

    <?php include "includes/footer.php" ?>
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
    <?php include "includes/script.php" ?>

</body>
</html>
