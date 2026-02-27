<?php
// Notifications: New requests, Recent status changes
include_once "scripts/connection.php";

$notifications = [];

// 1. New Requests (last 7 days)
$newSql = "SELECT request_id, homeowner_name, tracking_code, created_at FROM service_requests
    WHERE status = 'new' AND created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
    ORDER BY created_at DESC LIMIT 5";
$newRes = $conn->query($newSql);
if ($newRes) {
    while ($r = $newRes->fetch_assoc()) {
        $notifications[] = [
            'icon' => 'feather-alert-triangle text-danger',
            'title' => 'New Request',
            'text' => htmlspecialchars($r['homeowner_name']) . ' (' . $r['tracking_code'] . ')',
            'time' => $r['created_at'],
            'link' => 'request-view.php?id=' . $r['request_id']
        ];
    }
}

// 2. Recent status changes (last 3 days)
$statusSql = "SELECT h.details, h.created_at FROM admin_history h
    WHERE h.action = 'Updated Request Status' AND h.created_at >= DATE_SUB(NOW(), INTERVAL 3 DAY)
    ORDER BY h.created_at DESC LIMIT 5";
$statusRes = $conn->query($statusSql);
if ($statusRes) {
    while ($r = $statusRes->fetch_assoc()) {
        $reqId = '';
        if (preg_match('/Request ID:\s*(\d+)/', $r['details'], $m)) $reqId = $m[1];
        $notifications[] = [
            'icon' => 'feather-refresh-cw text-primary',
            'title' => 'Status Updated',
            'text' => htmlspecialchars($r['details']),
            'time' => $r['created_at'],
            'link' => $reqId ? 'request-view.php?id=' . $reqId : 'requests.php'
        ];
    }
}

// Sort all by time descending
usort($notifications, function($a, $b) {
    return strtotime($b['time']) - strtotime($a['time']);
});

// Limit to 10
$notifications = array_slice($notifications, 0, 10);
$notifCount = count($notifications);

// Time ago helper
if (!function_exists('notifTimeAgo')) {
    function notifTimeAgo($datetime) {
        $diff = time() - strtotime($datetime);
        if ($diff < 60) return $diff . ' sec ago';
        if ($diff < 3600) return floor($diff / 60) . ' min ago';
        if ($diff < 86400) return floor($diff / 3600) . ' hrs ago';
        if ($diff < 604800) return floor($diff / 86400) . ' days ago';
        return date('M d', strtotime($datetime));
    }
}
?>

<header class="nxl-header">
    <div class="header-wrapper">
        <!-- Header Left -->
        <div class="header-left d-flex align-items-center gap-4">
            <a href="javascript:void(0);" class="nxl-head-mobile-toggler" id="mobile-collapse">
                <div class="hamburger hamburger--arrowturn">
                    <div class="hamburger-box">
                        <div class="hamburger-inner"></div>
                    </div>
                </div>
            </a>
            <div class="nxl-navigation-toggle">
                <a href="javascript:void(0);" id="menu-mini-button">
                    <i class="feather-align-left"></i>
                </a>
                <a href="javascript:void(0);" id="menu-expend-button" style="display: none">
                    <i class="feather-arrow-right"></i>
                </a>
            </div>
        </div>
        <!-- Header Right -->
        <div class="header-right ms-auto">
            <div class="d-flex align-items-center">
                <!-- Fullscreen -->
                <div class="nxl-h-item d-none d-sm-flex">
                    <div class="full-screen-switcher">
                        <a href="javascript:void(0);" class="nxl-head-link me-0" onclick="$('body').fullScreenHelper('toggle');">
                            <i class="feather-maximize maximize"></i>
                            <i class="feather-minimize minimize"></i>
                        </a>
                    </div>
                </div>
                <!-- Dark/Light Mode -->
                <div class="nxl-h-item dark-light-theme">
                    <a href="javascript:void(0);" class="nxl-head-link me-0 dark-button">
                        <i class="feather-moon"></i>
                    </a>
                    <a href="javascript:void(0);" class="nxl-head-link me-0 light-button" style="display: none">
                        <i class="feather-sun"></i>
                    </a>
                </div>
                <!-- Notifications -->
                <div class="dropdown nxl-h-item">
                    <a class="nxl-head-link me-3" data-bs-toggle="dropdown" href="#" role="button" data-bs-auto-close="outside">
                        <i class="feather-bell"></i>
                        <?php if ($notifCount > 0): ?>
                            <span class="badge bg-danger nxl-h-badge"><?php echo $notifCount; ?></span>
                        <?php endif; ?>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end nxl-h-dropdown nxl-notifications-menu">
                        <div class="d-flex justify-content-between align-items-center notifications-head">
                            <h6 class="fw-bold text-dark mb-0">Notifications</h6>
                            <a href="history.php" class="fs-11 text-success text-end ms-auto">
                                <i class="feather-external-link"></i>
                                <span>View All</span>
                            </a>
                        </div>
                        <?php if ($notifCount === 0): ?>
                            <div class="d-flex justify-content-center align-items-center flex-column py-4">
                                <i class="feather-check-circle fs-1 mb-3 text-success"></i>
                                <p class="text-muted mb-0">All caught up! No new notifications.</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($notifications as $notif): ?>
                                <div class="notifications-item">
                                    <div class="notifications-desc">
                                        <a href="<?php echo $notif['link']; ?>" class="font-body text-truncate-2-line">
                                            <span class="fw-semibold text-dark"><?php echo $notif['title']; ?></span>
                                            <?php echo $notif['text']; ?>
                                        </a>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="notifications-date text-muted border-bottom border-bottom-dashed">
                                                <?php echo notifTimeAgo($notif['time']); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <div class="text-center notifications-footer">
                            <a href="history.php" class="fs-13 fw-semibold text-dark">All Notifications</a>
                        </div>
                    </div>
                </div>
                <!-- Profile -->
                <div class="dropdown nxl-h-item">
                    <a href="javascript:void(0);" data-bs-toggle="dropdown" role="button" data-bs-auto-close="outside">
                        <img src="../assets/images/avatar/1.png" alt="user-image" class="img-fluid user-avtar me-0">
                    </a>
                    <div class="dropdown-menu dropdown-menu-end nxl-h-dropdown nxl-user-dropdown">
                        <div class="dropdown-header">
                            <div class="d-flex align-items-center">
                                <img src="../assets/images/avatar/1.png" alt="user-image" class="img-fluid user-avtar">
                                <div>
                                    <h6 class="text-dark mb-0">
                                        <?php echo isset($_SESSION['first_name']) ? htmlspecialchars($_SESSION['first_name'] . ' ' . $_SESSION['last_name']) : 'Guest'; ?>
                                    </h6>
                                    <span class="fs-12 fw-medium text-muted">
                                        <?php echo isset($_SESSION['role']) ? ucfirst($_SESSION['role']) : 'User'; ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="dropdown-divider"></div>
                        <a href="logout.php" class="dropdown-item">
                            <i class="feather-log-out"></i>
                            <span>Sign Out</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Open Main Container -->
<main class="nxl-container">
    <div class="nxl-content">
