<?php
/**
 * Shared utility functions for QuickPropertyServices
 */

/**
 * Upload media files (images/videos) to specified directory
 * @param array $files $_FILES array element (supports multiple files)
 * @param string $upload_dir Target subdirectory under /uploads/ (e.g., 'requests', 'estimates', 'completions')
 * @return array Array of ['file_path' => relative path, 'file_type' => 'image'|'video']
 */
function uploadMedia($files, $upload_dir) {
    $allowed_images = ['jpg', 'jpeg', 'png'];
    $allowed_videos = ['mp4', 'mov'];
    $max_image_size = 10 * 1024 * 1024; // 10MB
    $max_video_size = 50 * 1024 * 1024; // 50MB
    $uploaded = [];

    $base_path = realpath(__DIR__ . '/../../../uploads') ?: __DIR__ . '/../../../uploads';
    $target_dir = $base_path . '/' . $upload_dir . '/';

    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }

    // Normalize $_FILES array for multiple uploads
    if (!is_array($files['name'])) {
        $files = [
            'name' => [$files['name']],
            'type' => [$files['type']],
            'tmp_name' => [$files['tmp_name']],
            'error' => [$files['error']],
            'size' => [$files['size']]
        ];
    }

    for ($i = 0; $i < count($files['name']); $i++) {
        if ($files['error'][$i] !== UPLOAD_ERR_OK || empty($files['name'][$i])) {
            continue;
        }

        $original_name = basename($files['name'][$i]);
        $ext = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));

        // Determine file type
        if (in_array($ext, $allowed_images)) {
            $file_type = 'image';
            if ($files['size'][$i] > $max_image_size) continue;
        } elseif (in_array($ext, $allowed_videos)) {
            $file_type = 'video';
            if ($files['size'][$i] > $max_video_size) continue;
        } else {
            continue; // Skip unsupported types
        }

        // Generate unique filename
        $new_name = time() . '_' . uniqid() . '.' . $ext;
        $target_path = $target_dir . $new_name;

        if (move_uploaded_file($files['tmp_name'][$i], $target_path)) {
            $uploaded[] = [
                'file_path' => 'uploads/' . $upload_dir . '/' . $new_name,
                'file_type' => $file_type
            ];
        }
    }

    return $uploaded;
}

/**
 * Generate a unique tracking code for service requests
 * @param mysqli $conn Database connection
 * @return string Tracking code like "QPS-A3F2B1C9"
 */
function generateTrackingCode($conn) {
    do {
        $code = 'QPS-' . strtoupper(substr(bin2hex(random_bytes(4)), 0, 8));
        $check = $conn->prepare("SELECT 1 FROM service_requests WHERE tracking_code = ?");
        $check->bind_param("s", $code);
        $check->execute();
        $exists = $check->get_result()->num_rows > 0;
        $check->close();
    } while ($exists);
    return $code;
}

/**
 * Render media gallery HTML for images and videos
 * @param array $mediaItems Array of ['file_path' => ..., 'file_type' => ...]
 * @param string $base_url Base URL prefix for file paths
 * @return string HTML output
 */
function renderMediaGallery($mediaItems, $base_url = '../../') {
    if (empty($mediaItems)) {
        return '<p class="text-muted">No media attached.</p>';
    }

    $html = '<div class="row g-3">';
    foreach ($mediaItems as $item) {
        $path = htmlspecialchars($base_url . $item['file_path']);
        if ($item['file_type'] === 'image') {
            $html .= '<div class="col-md-4 col-6">';
            $html .= '<a href="' . $path . '" target="_blank">';
            $html .= '<img src="' . $path . '" class="img-fluid rounded shadow-sm" style="max-height:200px;object-fit:cover;width:100%">';
            $html .= '</a></div>';
        } else {
            $html .= '<div class="col-md-6">';
            $html .= '<video controls class="w-100 rounded shadow-sm" style="max-height:250px">';
            $html .= '<source src="' . $path . '" type="video/mp4">';
            $html .= 'Your browser does not support the video tag.</video></div>';
        }
    }
    $html .= '</div>';
    return $html;
}

/**
 * Log an admin action to the audit history
 * @param mysqli $conn Database connection
 * @param int $admin_id Admin user ID
 * @param string $action Action description
 * @param string $details Additional details
 */
function logAdminAction($conn, $admin_id, $action, $details) {
    $ip = $_SERVER['REMOTE_ADDR'] ?? '';
    $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';
    $stmt = $conn->prepare("INSERT INTO admin_history (admin_id, action, details, ip_address, user_agent) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $admin_id, $action, $details, $ip, $ua);
    $stmt->execute();
    $stmt->close();
}

/**
 * Get status badge CSS class for a request status
 * @param string $status Request status value
 * @return string Bootstrap badge class
 */
function getStatusBadgeClass($status) {
    $colors = [
        'new' => 'bg-danger',
        'reviewing' => 'bg-info',
        'vendors_assigned' => 'bg-primary',
        'estimates_received' => 'bg-warning',
        'estimate_sent' => 'bg-secondary',
        'homeowner_accepted' => 'bg-success',
        'payment_received' => 'bg-info',
        'in_progress' => 'bg-primary',
        'completed' => 'bg-success',
        'vendor_paid' => 'bg-dark'
    ];
    return $colors[$status] ?? 'bg-secondary';
}

/**
 * Get human-readable status label
 * @param string $status Raw status enum value
 * @return string Formatted label
 */
function getStatusLabel($status) {
    $labels = [
        'new' => 'New',
        'reviewing' => 'Reviewing',
        'vendors_assigned' => 'Vendors Assigned',
        'estimates_received' => 'Estimates Received',
        'estimate_sent' => 'Estimate Sent',
        'homeowner_accepted' => 'Homeowner Accepted',
        'payment_received' => 'Payment Received',
        'in_progress' => 'In Progress',
        'completed' => 'Completed',
        'vendor_paid' => 'Vendor Paid'
    ];
    return $labels[$status] ?? ucwords(str_replace('_', ' ', $status));
}

/**
 * Get payment status badge class
 * @param string $status Payment status
 * @return string Bootstrap badge class
 */
function getPaymentBadgeClass($status) {
    $colors = [
        'pending' => 'bg-warning',
        'paid_escrow' => 'bg-info',
        'released' => 'bg-success'
    ];
    return $colors[$status] ?? 'bg-secondary';
}

/**
 * Get payment status label
 * @param string $status Payment status
 * @return string Formatted label
 */
function getPaymentLabel($status) {
    $labels = [
        'pending' => 'Pending',
        'paid_escrow' => 'Paid (Escrow)',
        'released' => 'Released'
    ];
    return $labels[$status] ?? ucwords(str_replace('_', ' ', $status));
}
