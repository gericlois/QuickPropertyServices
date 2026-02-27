<!DOCTYPE html>
<html lang="en">
<?php
session_start();
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php?error=AccessDenied");
    exit();
} else {
    include "includes/head.php";
    include "scripts/connection.php";
    include "scripts/helpers.php";
} ?>

<body>

    <?php include "includes/header.php" ?>
    <?php include "includes/sidebar.php" ?>

    <div class="main-content">
        <div class="row">

            <!-- New Requests Card -->
            <div class="col-xxl-3 col-md-6">
                <div class="card stretch stretch-full">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between mb-4">
                            <div class="d-flex gap-4 align-items-center">
                                <div class="avatar-text avatar-lg bg-danger bg-opacity-10 rounded">
                                    <i class="feather-alert-triangle text-danger fs-4"></i>
                                </div>
                                <div>
                                    <?php
                                    $sql = "SELECT COUNT(*) AS total FROM service_requests WHERE status = 'new'";
                                    $result = $conn->query($sql);
                                    $newCount = $result->fetch_assoc()['total'];
                                    ?>
                                    <div class="fs-4 fw-bold text-dark"><?php echo $newCount; ?></div>
                                    <h3 class="fs-13 fw-semibold text-truncate-1-line">New Requests</h3>
                                </div>
                            </div>
                        </div>
                        <a href="requests.php?status=new" class="fs-12 fw-semibold text-primary">View New <i class="feather-arrow-right fs-11 ms-1"></i></a>
                    </div>
                </div>
            </div>

            <!-- In Progress Card -->
            <div class="col-xxl-3 col-md-6">
                <div class="card stretch stretch-full">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between mb-4">
                            <div class="d-flex gap-4 align-items-center">
                                <div class="avatar-text avatar-lg bg-warning bg-opacity-10 rounded">
                                    <i class="feather-loader text-warning fs-4"></i>
                                </div>
                                <div>
                                    <?php
                                    $sql = "SELECT COUNT(*) AS total FROM service_requests WHERE status IN ('reviewing','vendors_assigned','estimates_received','estimate_sent','homeowner_accepted','payment_received','in_progress')";
                                    $result = $conn->query($sql);
                                    $progressCount = $result->fetch_assoc()['total'];
                                    ?>
                                    <div class="fs-4 fw-bold text-dark"><?php echo $progressCount; ?></div>
                                    <h3 class="fs-13 fw-semibold text-truncate-1-line">In Progress</h3>
                                </div>
                            </div>
                        </div>
                        <a href="requests.php?status=in_progress" class="fs-12 fw-semibold text-primary">View Active <i class="feather-arrow-right fs-11 ms-1"></i></a>
                    </div>
                </div>
            </div>

            <!-- Completed Card -->
            <div class="col-xxl-3 col-md-6">
                <div class="card stretch stretch-full">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between mb-4">
                            <div class="d-flex gap-4 align-items-center">
                                <div class="avatar-text avatar-lg bg-success bg-opacity-10 rounded">
                                    <i class="feather-check-circle text-success fs-4"></i>
                                </div>
                                <div>
                                    <?php
                                    $sql = "SELECT COUNT(*) AS total FROM service_requests WHERE status IN ('completed','vendor_paid')";
                                    $result = $conn->query($sql);
                                    $doneCount = $result->fetch_assoc()['total'];
                                    ?>
                                    <div class="fs-4 fw-bold text-dark"><?php echo $doneCount; ?></div>
                                    <h3 class="fs-13 fw-semibold text-truncate-1-line">Completed</h3>
                                </div>
                            </div>
                        </div>
                        <a href="requests.php?status=completed" class="fs-12 fw-semibold text-primary">View Completed <i class="feather-arrow-right fs-11 ms-1"></i></a>
                    </div>
                </div>
            </div>

            <!-- Pending Payment Card -->
            <div class="col-xxl-3 col-md-6">
                <div class="card stretch stretch-full">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between mb-4">
                            <div class="d-flex gap-4 align-items-center">
                                <div class="avatar-text avatar-lg bg-info bg-opacity-10 rounded">
                                    <i class="feather-dollar-sign text-info fs-4"></i>
                                </div>
                                <div>
                                    <?php
                                    $sql = "SELECT COUNT(*) AS total FROM service_requests WHERE payment_status = 'pending' AND status NOT IN ('new','reviewing')";
                                    $result = $conn->query($sql);
                                    $payCount = $result->fetch_assoc()['total'];
                                    ?>
                                    <div class="fs-4 fw-bold text-dark"><?php echo $payCount; ?></div>
                                    <h3 class="fs-13 fw-semibold text-truncate-1-line">Pending Payment</h3>
                                </div>
                            </div>
                        </div>
                        <a href="requests.php" class="fs-12 fw-semibold text-primary">View All <i class="feather-arrow-right fs-11 ms-1"></i></a>
                    </div>
                </div>
            </div>

            <!-- Request Status Bar Chart -->
            <div class="col-xxl-8">
                <div class="card stretch stretch-full">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Request Status Report</h5>
                    </div>
                    <div class="card-body">
                        <div id="requestBarChart"></div>

                        <?php
                        $barSql = "SELECT status, COUNT(*) as total FROM service_requests GROUP BY status";
                        $barResult = $conn->query($barSql);

                        $allStatuses = [
                            'new', 'reviewing', 'vendors_assigned', 'estimates_received',
                            'estimate_sent', 'homeowner_accepted', 'payment_received',
                            'in_progress', 'completed', 'vendor_paid'
                        ];
                        $barCounts = array_fill_keys($allStatuses, 0);

                        if ($barResult) {
                            while ($barRow = $barResult->fetch_assoc()) {
                                if (isset($barCounts[$barRow['status']])) {
                                    $barCounts[$barRow['status']] = (int)$barRow['total'];
                                }
                            }
                        }

                        $barLabels = array_map('getStatusLabel', array_keys($barCounts));
                        $barValues = array_values($barCounts);

                        $barColors = [
                            '#dc3545', '#17a2b8', '#0d6efd', '#ffc107', '#6c757d',
                            '#198754', '#17a2b8', '#0d6efd', '#198754', '#343a40'
                        ];
                        ?>

                        <script>
                            document.addEventListener("DOMContentLoaded", () => {
                                var barCategories = <?php echo json_encode($barLabels); ?>;
                                var barStatusKeys = <?php echo json_encode(array_keys($barCounts)); ?>;
                                new ApexCharts(document.querySelector("#requestBarChart"), {
                                    series: [{
                                        name: 'Requests',
                                        data: <?php echo json_encode($barValues); ?>
                                    }],
                                    chart: {
                                        type: 'bar',
                                        height: 380,
                                        toolbar: { show: false },
                                        events: {
                                            dataPointSelection: function(event, chartContext, config) {
                                                var status = barStatusKeys[config.dataPointIndex];
                                                window.location.href = 'requests.php?status=' + encodeURIComponent(status);
                                            }
                                        }
                                    },
                                    plotOptions: {
                                        bar: { distributed: true, borderRadius: 4, columnWidth: '60%' }
                                    },
                                    colors: <?php echo json_encode($barColors); ?>,
                                    dataLabels: {
                                        enabled: true,
                                        style: { fontSize: '12px', fontWeight: 'bold' }
                                    },
                                    legend: { show: false },
                                    xaxis: {
                                        categories: barCategories,
                                        labels: { rotate: -45, rotateAlways: true, style: { fontSize: '11px' } }
                                    },
                                    yaxis: { title: { text: 'Count' } },
                                    tooltip: {
                                        y: { formatter: function(val) { return val + " requests (click to view)"; } }
                                    }
                                }).render();
                            });
                        </script>
                    </div>
                </div>
            </div>

            <!-- Vendor Stats (Donut Chart) -->
            <div class="col-xxl-4">
                <div class="card stretch stretch-full">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Vendor Stats</h5>
                    </div>
                    <div class="card-body">
                        <div id="vendorChart"></div>

                        <?php
                        $activeVendors = 0;
                        $inactiveVendors = 0;
                        $vSql = "SELECT status, COUNT(*) as total FROM vendors GROUP BY status";
                        $vResult = $conn->query($vSql);
                        if ($vResult) {
                            while ($vRow = $vResult->fetch_assoc()) {
                                if ($vRow['status'] == 1) $activeVendors = (int)$vRow['total'];
                                if ($vRow['status'] == 2) $inactiveVendors = (int)$vRow['total'];
                            }
                        }
                        ?>

                        <script>
                            document.addEventListener("DOMContentLoaded", () => {
                                new ApexCharts(document.querySelector("#vendorChart"), {
                                    series: [<?php echo $activeVendors; ?>, <?php echo $inactiveVendors; ?>],
                                    chart: { type: 'donut', height: 320 },
                                    labels: ['Active', 'Inactive'],
                                    colors: ['#28a745', '#6c757d'],
                                    legend: { position: 'bottom' },
                                    plotOptions: {
                                        pie: {
                                            donut: {
                                                size: '65%',
                                                labels: { show: true, total: { show: true, label: 'Total Vendors' } }
                                            }
                                        }
                                    }
                                }).render();
                            });
                        </script>
                    </div>
                </div>
            </div>

            <!-- Requests Per Month Area Chart -->
            <div class="col-xxl-8">
                <div class="card stretch stretch-full">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Requests Per Month</h5>
                    </div>
                    <div class="card-body">
                        <div id="reportsChart"></div>

                        <?php
                        $sql = "SELECT DATE_FORMAT(created_at, '%Y-%m') AS month, COUNT(*) AS total
                            FROM service_requests
                            WHERE YEAR(created_at) = YEAR(CURDATE())
                            GROUP BY month ORDER BY month ASC";
                        $result = $conn->query($sql);
                        $months = [];
                        $totals = [];
                        if ($result) {
                            while ($row = $result->fetch_assoc()) {
                                $months[] = $row['month'] . "-01";
                                $totals[] = $row['total'];
                            }
                        }
                        ?>

                        <script>
                            document.addEventListener("DOMContentLoaded", () => {
                                new ApexCharts(document.querySelector("#reportsChart"), {
                                    series: [{ name: 'Requests', data: <?php echo json_encode($totals); ?> }],
                                    chart: { height: 350, type: 'area', toolbar: { show: false } },
                                    markers: { size: 4 },
                                    colors: ['#3454d1'],
                                    fill: {
                                        type: "gradient",
                                        gradient: { shadeIntensity: 1, opacityFrom: 0.3, opacityTo: 0.4, stops: [0, 90, 100] }
                                    },
                                    dataLabels: { enabled: false },
                                    stroke: { curve: 'smooth', width: 2 },
                                    xaxis: { type: 'datetime', categories: <?php echo json_encode($months); ?> },
                                    tooltip: { x: { format: 'MMM yyyy' } }
                                }).render();
                            });
                        </script>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="col-xxl-4">
                <div class="card stretch stretch-full">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Recent Activity</h5>
                    </div>
                    <div class="card-body">
                        <?php
                        $actSql = "SELECT h.action, h.details, h.created_at, u.first_name, u.last_name
                            FROM admin_history h
                            JOIN users u ON h.admin_id = u.user_id
                            ORDER BY h.created_at DESC LIMIT 8";
                        $actResult = $conn->query($actSql);

                        if ($actResult && $actResult->num_rows > 0) {
                            while ($actRow = $actResult->fetch_assoc()) {
                                $diff = time() - strtotime($actRow['created_at']);
                                if ($diff < 60) $timeAgo = $diff . ' sec ago';
                                elseif ($diff < 3600) $timeAgo = floor($diff / 60) . ' min ago';
                                elseif ($diff < 86400) $timeAgo = floor($diff / 3600) . ' hrs ago';
                                elseif ($diff < 604800) $timeAgo = floor($diff / 86400) . ' days ago';
                                else $timeAgo = floor($diff / 604800) . ' weeks ago';
                                ?>
                                <div class="d-flex align-items-start gap-3 mb-4">
                                    <div class="avatar-text avatar-md rounded bg-gray-200">
                                        <i class="feather-activity fs-6"></i>
                                    </div>
                                    <div>
                                        <span class="fw-semibold text-dark d-block fs-13"><?php echo htmlspecialchars($actRow['first_name'] . ' ' . $actRow['last_name']); ?></span>
                                        <span class="fs-12 text-muted"><?php echo htmlspecialchars($actRow['action']); ?><?php echo $actRow['details'] ? ': ' . htmlspecialchars($actRow['details']) : ''; ?></span>
                                        <div class="fs-11 text-muted mt-1"><?php echo $timeAgo; ?></div>
                                    </div>
                                </div>
                                <?php
                            }
                        } else {
                            echo '<p class="text-muted text-center">No recent activity</p>';
                        }
                        ?>
                    </div>
                    <a href="history.php" class="card-footer fs-11 fw-bold text-uppercase text-center py-4">View All History</a>
                </div>
            </div>

            <!-- Latest Requests Table -->
            <div class="col-12">
                <div class="card stretch stretch-full">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Latest Requests</h5>
                    </div>
                    <div class="card-body custom-card-action">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Tracking Code</th>
                                        <th scope="col">Homeowner</th>
                                        <th scope="col">Phone</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Payment</th>
                                        <th scope="col">Created</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT request_id, tracking_code, homeowner_name, phone, status, payment_status, created_at
                                        FROM service_requests ORDER BY created_at DESC LIMIT 10";
                                    $result = $conn->query($sql);

                                    if ($result && $result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            $sc = getStatusBadgeClass($row['status']);
                                            $pc = getPaymentBadgeClass($row['payment_status']);
                                            echo "<tr>
                                                <td><a href='request-view.php?id={$row['request_id']}' class='fw-semibold'>#{$row['request_id']}</a></td>
                                                <td><code>{$row['tracking_code']}</code></td>
                                                <td>" . htmlspecialchars($row['homeowner_name']) . "</td>
                                                <td>" . htmlspecialchars($row['phone']) . "</td>
                                                <td><span class='badge $sc'>" . getStatusLabel($row['status']) . "</span></td>
                                                <td><span class='badge $pc'>" . getPaymentLabel($row['payment_status']) . "</span></td>
                                                <td>" . date('M d, Y', strtotime($row['created_at'])) . "</td>
                                            </tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='7' class='text-center text-muted'>No requests found</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <a href="requests.php" class="card-footer fs-11 fw-bold text-uppercase text-center py-4">View All Requests</a>
                </div>
            </div>

        </div>
    </div>

    <?php include "includes/footer.php" ?>
    <?php include "includes/scripts.php" ?>

</body>

</html>