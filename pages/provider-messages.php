<!DOCTYPE html>
<html lang="en">
<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'provider') {
    header("Location: login.php?error=AccessDenied");
    exit();
} else {
    include "includes/head.php";
    include "../admin/pages/scripts/connection.php";
}

$provider_id = $_SESSION['provider_id']; // Logged-in provider's ID

// Pagination setup
$limit = 10; // messages per page
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;


// Fetch latest message per client
$query = "
    SELECT 
        m.message_id,
        m.client_id,
        m.provider_id,
        m.sender_type,   -- <--- add this
        m.content,
        m.status,
        m.created_at,
        u.first_name,
        u.last_name
    FROM messages m
    INNER JOIN clients c ON m.client_id = c.client_id
    INNER JOIN users u ON c.user_id = u.user_id
    WHERE m.provider_id = ?
      AND NOT EXISTS (
        SELECT 1
        FROM messages m2
        WHERE m2.provider_id = m.provider_id
          AND m2.client_id   = m.client_id
          AND (
               m2.created_at > m.created_at
            OR (m2.created_at = m.created_at AND m2.message_id > m.message_id)
          )
      )
    ORDER BY m.created_at DESC
    LIMIT ? OFFSET ?
";


$stmt = $conn->prepare($query);
$stmt->bind_param("iii", $provider_id, $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();

// Count threads (one per client)
$count_query = "
    SELECT COUNT(DISTINCT client_id) AS total
    FROM messages
    WHERE provider_id = ?
";
$count_stmt = $conn->prepare($count_query);
$count_stmt->bind_param("i", $provider_id);
$count_stmt->execute();
$count_result    = $count_stmt->get_result();
$total_messages  = (int)$count_result->fetch_assoc()['total'];
$total_pages     = (int)ceil($total_messages / $limit);

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
                <p>Here are all your client messages.</p>
            </div>
            <div class="container">
                <?php if ($result->num_rows > 0): ?>
                    <ul class="list-group">
                        <?php while ($row = $result->fetch_assoc()):
                            // Check if this message was sent by the logged-in user
                            $isYou = ($row['sender_type'] === $_SESSION['role']);
                        ?>
                            <li class="list-group-item d-flex justify-content-between align-items-start <?= $row['status'] === 'unread' ? 'list-group-item-warning' : '' ?>">
                                <div class="ms-2 me-auto">
                                    <div class="fw-bold">
                                        <?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?>
                                        <span class="badge <?= $row['status'] === 'unread' ? 'bg-warning text-dark' : 'bg-success' ?>">
                                            <?= ucfirst($row['status']) ?>
                                        </span>
                                    </div>

                                    <p class="mb-1">
                                        <i><?= $isYou ? 'You: ' : '' ?></i>
                                        <?= nl2br(htmlspecialchars($row['content'])) ?>
                                    </p>

                                    <small>
                                        <?= date("M d, Y h:i A", strtotime($row['created_at'])) ?>
                                    </small>
                                </div>

                                <div class="btn-group">
                                    <a href="provider-message-view.php?message_id=<?= urlencode($row['message_id']); ?>" class="btn btn-sm btn-primary">View</a>
                                </div>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                <?php else: ?>
                    <div class="alert alert-info">No messages yet.</div>
                <?php endif; ?>
            </div>




            <!-- Pagination -->
            <div class="container mt-4 text-center">
                <nav>
                    <ul class="pagination justify-content-center">
                        <?php if ($page > 1): ?>
                            <li class="page-item"><a class="page-link" href="?page=<?= $page - 1 ?>">Previous</a></li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($page < $total_pages): ?>
                            <li class="page-item"><a class="page-link" href="?page=<?= $page + 1 ?>">Next</a></li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        </section>
    </main>

    <?php include "includes/footer.php" ?>
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center">
        <i class="bi bi-arrow-up-short"></i>
    </a>
    <?php include "includes/script.php" ?>
</body>

</html>