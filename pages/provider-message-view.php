<!DOCTYPE html>
<html lang="en">
<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'provider') {
    header("Location: login.php?error=AccessDenied");
    exit();
} else {
    
    include "../admin/pages/scripts/connection.php";
}

$provider_id = $_SESSION['provider_id']; // Logged-in provider's ID

?>
<?php

$message_id = isset($_GET['message_id']) ? intval($_GET['message_id']) : 0;
$provider_id = $_SESSION['provider_id'];

// If message_id is provided, mark as read and get client_id
$client_id = 0;
if ($message_id > 0) {
    $stmt = $conn->prepare("SELECT * FROM messages WHERE message_id = ? AND provider_id = ?");
    $stmt->bind_param("ii", $message_id, $provider_id);
    $stmt->execute();
    $message = $stmt->get_result()->fetch_assoc();

    if ($message) {
        $client_id = $message['client_id'];

        // Mark as read
        $update = $conn->prepare("UPDATE messages SET status = 'read' WHERE message_id = ?");
        $update->bind_param("i", $message_id);
        $update->execute();
    }
}

// If still no client_id, fallback (optional: redirect back to inbox)
if ($client_id === 0) {
    die("No client selected.");
}

// Fetch full conversation
$chat = $conn->prepare("
    SELECT m.*, 
           c.first_name AS client_first, c.last_name AS client_last,
           p.first_name AS provider_first, p.last_name AS provider_last
    FROM messages m
    LEFT JOIN users c ON m.client_id = c.user_id
    LEFT JOIN users p ON m.provider_id = p.user_id
    WHERE m.provider_id = ? AND m.client_id = ?
    ORDER BY m.created_at ASC
");
$chat->bind_param("ii", $provider_id, $client_id);
$chat->execute();
$messages = $chat->get_result();
?>


<?php
$role = $_SESSION['role'] ?? 'guest'; // fallback if not logged in
?>

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>View Message | Provider Inbox – Fixing Techs</title>
  <meta name="description" content="Read detailed messages from clients and admin in the Fixing Techs provider portal. Securely view and respond to your assigned communications.">
  <meta name="keywords" content="provider message view, Fixing Techs provider inbox, view message, provider communication, job messages, provider client chat, secure messages">

  <!-- Favicons -->
  <link href="../assets/img/logo.jpg" rel="icon">
  <link href="../assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Inter:wght@100;200;300;400;500;600;700;800;900&family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="../assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="../assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="../assets/css/main.css" rel="stylesheet">
</head>

<body class="index-page <?php echo $role; ?>">

    <?php include "includes/header.php" ?>

    <main class="main">

        <!-- Messages Section -->
        <section id="services" class="services section light-background">

            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>My Messages</h2>
            </div>

            <div class="container">
                <?php
                if (isset($_GET['success'])) {
                    if ($_GET["success"] == "ServiceUpdated") {
                        echo '
                                                        <div class="alert alert-primary alert-dismissible fade show" role="alert">
                                                        <b>Service Updated. Please double-check your entry for possible mistake!</b>
                                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                        </div>';
                    }
                    if ($_GET["success"] == "ServiceDeactivated") {
                        echo '
                                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                        <b>Service Deactivated. Service is not available for clients as of this time!</b>
                                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                        </div>';
                    }
                    if ($_GET["success"] == "ServiceActivated") {
                        echo '
                                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                                        <b>Service Activated. Service will be available for clients as of this time!</b>
                                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                        </div>';
                    }
                }
                ?>
            </div>

            <!-- Messages List -->
            <div class="container" data-aos="fade-up" data-aos-delay="100">
                <div class="container py-4">
                    <div class="chat-container border rounded shadow-sm p-3" style="height: auto; display: flex; flex-direction: column;">

                        <!-- Conversation Messages -->
                        <div class="chat-box flex-grow-1 overflow-auto mb-3" style="max-height: 400px;">
    <?php if ($messages->num_rows > 0): ?>
        <?php while ($row = $messages->fetch_assoc()) : ?>
            <?php
            $loggedInProvider = $_SESSION['provider_id'];
            $isMine = ($row['provider_id'] == $loggedInProvider && $row['sender_type'] === 'provider');

            // Decide sender label
            if ($isMine) {
                $senderName = "You";
            } elseif ($row['sender_type'] === 'client') {
                $senderName = htmlspecialchars($row['client_first'] . " " . $row['client_last']);
            } else {
                $senderName = htmlspecialchars($row['provider_first'] . " " . $row['provider_last']);
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
                            <input type="hidden" name="sender_type" value="provider">

                            <input type="text" name="content" class="form-control me-2" placeholder="Type your message..." required>
                            <button type="submit" class="btn btn-primary">Send</button>
                        </form>

                    </div>
                </div>





            </div>

        </section><!-- /Messages Section -->

    </main>

    <?php include "includes/footer.php" ?>

    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <?php include "includes/script.php" ?>

</body>

</html>