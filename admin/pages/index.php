<!DOCTYPE html>
<html lang="en">
<?php
session_start();
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php?error=AccessDenied");
} else {
    include "includes/head.php";
    include "scripts/connection.php";
} ?>

<body>

    <!-- ======= Header ======= -->
    <?php include "includes/header.php" ?>
    <!-- End Header -->

    <!-- ======= Sidebar ======= -->
    <?php include "includes/sidebar.php" ?>
    <!-- End Sidebar-->

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Dashboard</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section dashboard">
            <div class="row">

                <!-- Left side columns -->
                <div class="col-lg-8">
                    <div class="row">
                        <!-- Hot Leads Card -->
                        <div class="col-xxl-4 col-md-6">
                            <div class="card info-card sales-card">

                                <div class="filter">
                                    <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                        <li class="dropdown-header text-start">
                                            <h6>Filter</h6>
                                        </li>
                                        <li><a class="dropdown-item" href="?filter=today">Today</a></li>
                                        <li><a class="dropdown-item" href="?filter=month">This Month</a></li>
                                        <li><a class="dropdown-item" href="?filter=year">This Year</a></li>
                                    </ul>
                                </div>

                                <div class="card-body">
                                    <h5 class="card-title">Hot Leads
                                        <?php if (isset($_GET['filter'])): ?>
                                            <span>| <?php echo ucfirst($_GET['filter']); ?></span>
                                        <?php endif; ?>
                                    </h5>

                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-fire text-danger"></i>
                                        </div>
                                        <div class="ps-3">
                                            <?php
                                            // Example DB query
                                            require '../../admin/pages/scripts/connection.php';

                                            // Base query
                                            $sql = "SELECT COUNT(*) AS total FROM job_requests WHERE status = 'Hot Lead'";

                                            // Optional filter
                                            if (isset($_GET['filter'])) {
                                                if ($_GET['filter'] === 'today') {
                                                    $sql .= " AND DATE(created_at) = CURDATE()";
                                                } elseif ($_GET['filter'] === 'month') {
                                                    $sql .= " AND MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())";
                                                } elseif ($_GET['filter'] === 'year') {
                                                    $sql .= " AND YEAR(created_at) = YEAR(CURDATE())";
                                                }
                                            }

                                            $result = $conn->query($sql);
                                            $row = $result->fetch_assoc();
                                            $hotLeadCount = $row['total'];
                                            ?>

                                            <h6><?php echo $hotLeadCount; ?></h6>
                                            <span class="text-muted small pt-2 ps-1">Active Leads</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Hot Leads Card -->


                        <!-- Project in Progress Card -->
                        <div class="col-xxl-4 col-md-6">
                            <div class="card info-card revenue-card">

                                <div class="filter">
                                    <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                        <li class="dropdown-header text-start">
                                            <h6>Filter</h6>
                                        </li>
                                        <li><a class="dropdown-item" href="?filter=today">Today</a></li>
                                        <li><a class="dropdown-item" href="?filter=month">This Month</a></li>
                                        <li><a class="dropdown-item" href="?filter=year">This Year</a></li>
                                    </ul>
                                </div>

                                <div class="card-body">
                                    <h5 class="card-title">Project in Progress
                                        <?php if (isset($_GET['filter'])): ?>
                                            <span>| <?php echo ucfirst($_GET['filter']); ?></span>
                                        <?php else: ?>
                                            <span>| All Time</span>
                                        <?php endif; ?>
                                    </h5>

                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-hammer text-warning"></i>
                                        </div>
                                        <div class="ps-3">
                                            <?php
                                            // DB Connection
                                            require '../../admin/pages/scripts/connection.php';

                                            // Base query for Project in Progress
                                            $sql = "SELECT COUNT(*) AS total FROM job_requests WHERE status = 'Project in Progress'";

                                            // Apply filter
                                            if (isset($_GET['filter'])) {
                                                if ($_GET['filter'] === 'today') {
                                                    $sql .= " AND DATE(created_at) = CURDATE()";
                                                } elseif ($_GET['filter'] === 'month') {
                                                    $sql .= " AND MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())";
                                                } elseif ($_GET['filter'] === 'year') {
                                                    $sql .= " AND YEAR(created_at) = YEAR(CURDATE())";
                                                }
                                            }

                                            $result = $conn->query($sql);
                                            $row = $result->fetch_assoc();
                                            $progressCount = $row['total'];
                                            ?>

                                            <h6><?php echo $progressCount; ?></h6>
                                            <span class="text-muted small pt-2 ps-1">Ongoing Projects</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Project in Progress Card -->


                        <!-- Project Done Card -->
                        <div class="col-xxl-4 col-md-6">
                            <div class="card info-card revenue-card">

                                <div class="filter">
                                    <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                        <li class="dropdown-header text-start">
                                            <h6>Filter</h6>
                                        </li>
                                        <li><a class="dropdown-item" href="?filter=today">Today</a></li>
                                        <li><a class="dropdown-item" href="?filter=month">This Month</a></li>
                                        <li><a class="dropdown-item" href="?filter=year">This Year</a></li>
                                    </ul>
                                </div>

                                <div class="card-body">
                                    <h5 class="card-title">Project Done
                                        <?php if (isset($_GET['filter'])): ?>
                                            <span>| <?php echo ucfirst($_GET['filter']); ?></span>
                                        <?php else: ?>
                                            <span>| All Time</span>
                                        <?php endif; ?>
                                    </h5>

                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-check2-circle text-success"></i>
                                        </div>
                                        <div class="ps-3">
                                            <?php
                                            // Example DB query
                                            require '../../admin/pages/scripts/connection.php';

                                            // Base query
                                            $sql = "SELECT COUNT(*) AS total FROM job_requests WHERE status = 'Project Done'";

                                            // Optional filter
                                            if (isset($_GET['filter'])) {
                                                if ($_GET['filter'] === 'today') {
                                                    $sql .= " AND DATE(created_at) = CURDATE()";
                                                } elseif ($_GET['filter'] === 'month') {
                                                    $sql .= " AND MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())";
                                                } elseif ($_GET['filter'] === 'year') {
                                                    $sql .= " AND YEAR(created_at) = YEAR(CURDATE())";
                                                }
                                            }

                                            $result = $conn->query($sql);
                                            $row = $result->fetch_assoc();
                                            $doneCount = $row['total'];
                                            ?>

                                            <h6><?php echo $doneCount; ?></h6>
                                            <span class="text-muted small pt-2 ps-1">Completed Projects</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Project Done Card -->


                        <!-- Reports -->
                        <div class="col-12">
                            <div class="card">

                                <div class="filter">
                                    <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                        <li class="dropdown-header text-start">
                                            <h6>Filter</h6>
                                        </li>
                                        <li><a class="dropdown-item" href="?filter=year">This Year</a></li>
                                    </ul>
                                </div>

                                <div class="card-body">
                                    <h5 class="card-title">Requests <span>| Per Month</span></h5>

                                    <!-- Line Chart -->
                                    <div id="reportsChart"></div>

                                    <?php
                                    require '../../admin/pages/scripts/connection.php';

                                    // Query: count requests per month for current year
                                    $sql = "SELECT DATE_FORMAT(created_at, '%Y-%m') AS month, COUNT(*) AS total
                    FROM job_requests
                    WHERE YEAR(created_at) = YEAR(CURDATE())
                    GROUP BY month
                    ORDER BY month ASC";

                                    $result = $conn->query($sql);

                                    $months = [];
                                    $totals = [];

                                    while ($row = $result->fetch_assoc()) {
                                        // Format month as YYYY-MM-01 (so ApexCharts treats it as a date)
                                        $months[] = $row['month'] . "-01";
                                        $totals[] = $row['total'];
                                    }
                                    ?>

                                    <script>
                                        document.addEventListener("DOMContentLoaded", () => {
                                            new ApexCharts(document.querySelector("#reportsChart"), {
                                                series: [{
                                                    name: 'Requests',
                                                    data: <?php echo json_encode($totals); ?>
                                                }],
                                                chart: {
                                                    height: 350,
                                                    type: 'area',
                                                    toolbar: {
                                                        show: false
                                                    },
                                                },
                                                markers: {
                                                    size: 4
                                                },
                                                colors: ['#4154f1'],
                                                fill: {
                                                    type: "gradient",
                                                    gradient: {
                                                        shadeIntensity: 1,
                                                        opacityFrom: 0.3,
                                                        opacityTo: 0.4,
                                                        stops: [0, 90, 100]
                                                    }
                                                },
                                                dataLabels: {
                                                    enabled: false
                                                },
                                                stroke: {
                                                    curve: 'smooth',
                                                    width: 2
                                                },
                                                xaxis: {
                                                    type: 'datetime',
                                                    categories: <?php echo json_encode($months); ?>
                                                },
                                                tooltip: {
                                                    x: {
                                                        format: 'MMM yyyy'
                                                    },
                                                }
                                            }).render();
                                        });
                                    </script>
                                    <!-- End Line Chart -->

                                </div>

                            </div>
                        </div>
                        <!-- End Reports -->


                        <!-- Latest Requests -->
                        <div class="col-12">
                            <div class="card recent-sales overflow-auto">

                                <div class="filter">
                                    <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                        <li class="dropdown-header text-start">
                                            <h6>Filter</h6>
                                        </li>
                                        <li><a class="dropdown-item" href="?filter=today">Today</a></li>
                                        <li><a class="dropdown-item" href="?filter=month">This Month</a></li>
                                        <li><a class="dropdown-item" href="?filter=year">This Year</a></li>
                                    </ul>
                                </div>

                                <div class="card-body">
                                    <h5 class="card-title">Latest Requests <span>| Recent</span></h5>

                                    <table class="table table-borderless datatable">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Homeowner</th>
                                                <th scope="col">Contact Source</th>
                                                <th scope="col">Submitted</th>
                                                <th scope="col">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            require '../../admin/pages/scripts/connection.php';

                                            // You can change LIMIT 10 to however many rows you want
                                            $sql = "SELECT request_id, homeowner_name, contact_source, created_at, status 
                            FROM job_requests 
                            ORDER BY created_at DESC 
                            LIMIT 10";
                                            $result = $conn->query($sql);

                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    // Pick bootstrap badge class based on status
                                                    switch ($row['status']) {
                                                        case 'Hot Lead':
                                                            $statusClass = 'bg-warning';
                                                            break;
                                                        case 'Project in Progress':
                                                            $statusClass = 'bg-info';
                                                            break;
                                                        case 'Project Done':
                                                            $statusClass = 'bg-success';
                                                            break;
                                                        default:
                                                            $statusClass = 'bg-secondary';
                                                    }
                                                    echo "<tr>
                                <th scope='row'><a href='request.php?id=" . $row['request_id'] . "'>#" . $row['request_id'] . "</a></th>
                                <td>" . htmlspecialchars($row['homeowner_name']) . "</td>
                                <td>" . htmlspecialchars($row['contact_source']) . "</td>
                                <td>" . date('M d, Y H:i', strtotime($row['created_at'])) . "</td>
                                <td><span class='badge $statusClass'>" . htmlspecialchars($row['status']) . "</span></td>
                              </tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='5' class='text-center'>No requests found</td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>

                                </div>

                            </div>
                        </div>
                        <!-- End Latest Requests -->

                        <!-- News & Updates Traffic -->
                        <div class="col-12">
                            <div class="card">
                                <div class="filter">
                                    <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                        <li class="dropdown-header text-start">
                                            <h6>Filter</h6>
                                        </li>

                                        <li><a class="dropdown-item" href="#">Today</a></li>
                                        <li><a class="dropdown-item" href="#">This Month</a></li>
                                        <li><a class="dropdown-item" href="#">This Year</a></li>
                                    </ul>
                                </div>

                                <div class="card-body pb-0">
                                    <h5 class="card-title">News &amp; Updates <span>| Today</span></h5>

                                    <div class="news">
                                        <div class="post-item clearfix">
                                            <img src="../assets/img/news-1.jpg" alt="">
                                            <h4><a href="#">Nihil blanditiis at in nihil autem</a></h4>
                                            <p>Sit recusandae non aspernatur laboriosam. Quia enim eligendi sed ut harum...</p>
                                        </div>

                                        <div class="post-item clearfix">
                                            <img src="../assets/img/news-2.jpg" alt="">
                                            <h4><a href="#">Quidem autem et impedit</a></h4>
                                            <p>Illo nemo neque maiores vitae officiis cum eum turos elan dries werona nande...
                                            </p>
                                        </div>

                                        <div class="post-item clearfix">
                                            <img src="../assets/img/news-3.jpg" alt="">
                                            <h4><a href="#">Id quia et et ut maxime similique occaecati ut</a></h4>
                                            <p>Fugiat voluptas vero eaque accusantium eos. Consequuntur sed ipsam et totam...
                                            </p>
                                        </div>

                                        <div class="post-item clearfix">
                                            <img src="../assets/img/news-4.jpg" alt="">
                                            <h4><a href="#">Laborum corporis quo dara net para</a></h4>
                                            <p>Qui enim quia optio. Eligendi aut asperiores enim repellendusvel rerum cuder...
                                            </p>
                                        </div>

                                        <div class="post-item clearfix">
                                            <img src="../assets/img/news-5.jpg" alt="">
                                            <h4><a href="#">Et dolores corrupti quae illo quod dolor</a></h4>
                                            <p>Odit ut eveniet modi reiciendis. Atque cupiditate libero beatae dignissimos
                                                eius...</p>
                                        </div>

                                    </div><!-- End sidebar recent posts-->

                                </div>
                            </div><!-- End News & Updates -->
                        </div>




                    </div>
                </div><!-- End Left side columns -->

                <!-- Right side columns -->
                <div class="col-lg-4">

                    <!-- Recent Activity -->
                    <div class="card">
                        <div class="filter">
                            <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                <li class="dropdown-header text-start">
                                    <h6>Filter</h6>
                                </li>

                                <li><a class="dropdown-item" href="#">Today</a></li>
                                <li><a class="dropdown-item" href="#">This Month</a></li>
                                <li><a class="dropdown-item" href="#">This Year</a></li>
                            </ul>
                        </div>

                        <div class="card-body">
                            <h5 class="card-title">Recent Activity <span>| Today</span></h5>

                            <div class="activity">

                                <div class="activity-item d-flex">
                                    <div class="activite-label">32 min</div>
                                    <i class='bi bi-circle-fill activity-badge text-success align-self-start'></i>
                                    <div class="activity-content">
                                        Quia quae rerum <a href="#" class="fw-bold text-dark">explicabo officiis</a>
                                        beatae
                                    </div>
                                </div><!-- End activity item-->

                                <div class="activity-item d-flex">
                                    <div class="activite-label">56 min</div>
                                    <i class='bi bi-circle-fill activity-badge text-danger align-self-start'></i>
                                    <div class="activity-content">
                                        Voluptatem blanditiis blanditiis eveniet
                                    </div>
                                </div><!-- End activity item-->

                                <div class="activity-item d-flex">
                                    <div class="activite-label">2 hrs</div>
                                    <i class='bi bi-circle-fill activity-badge text-primary align-self-start'></i>
                                    <div class="activity-content">
                                        Voluptates corrupti molestias voluptatem
                                    </div>
                                </div><!-- End activity item-->

                                <div class="activity-item d-flex">
                                    <div class="activite-label">1 day</div>
                                    <i class='bi bi-circle-fill activity-badge text-info align-self-start'></i>
                                    <div class="activity-content">
                                        Tempore autem saepe <a href="#" class="fw-bold text-dark">occaecati
                                            voluptatem</a> tempore
                                    </div>
                                </div><!-- End activity item-->

                                <div class="activity-item d-flex">
                                    <div class="activite-label">2 days</div>
                                    <i class='bi bi-circle-fill activity-badge text-warning align-self-start'></i>
                                    <div class="activity-content">
                                        Est sit eum reiciendis exercitationem
                                    </div>
                                </div><!-- End activity item-->

                                <div class="activity-item d-flex">
                                    <div class="activite-label">4 weeks</div>
                                    <i class='bi bi-circle-fill activity-badge text-muted align-self-start'></i>
                                    <div class="activity-content">
                                        Dicta dolorem harum nulla eius. Ut quidem quidem sit quas
                                    </div>
                                </div><!-- End activity item-->

                            </div>

                        </div>
                    </div>
                    <!-- End Recent Activity -->

                    <!-- Request Status Report -->
                    <div class="card">
                        <div class="filter">
                            <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                <li class="dropdown-header text-start">
                                    <h6>Filter</h6>
                                </li>
                                <li><a class="dropdown-item" href="#">Today</a></li>
                                <li><a class="dropdown-item" href="#">This Month</a></li>
                                <li><a class="dropdown-item" href="#">This Year</a></li>
                            </ul>
                        </div>

                        <div class="card-body pb-0">
                            <h5 class="card-title">Request Status Report <span>| This Month</span></h5>

                            <div id="requestRadarChart" style="min-height: 400px;" class="echart"></div>

                            <?php
                            require '../../admin/pages/scripts/connection.php';

                            // Count requests by status
                            $sql = "SELECT status, COUNT(*) as total FROM job_requests GROUP BY status";
                            $result = $conn->query($sql);

                            $statuses = ["Hot Lead", "Appointment for Estimate", "Estimate Sent", "In Progress", "Project Done"];
                            $statusCounts = array_fill_keys($statuses, 0);

                            while ($row = $result->fetch_assoc()) {
                                if (isset($statusCounts[$row['status']])) {
                                    $statusCounts[$row['status']] = (int)$row['total'];
                                }
                            }

                            // Example Allocated (Plan) values for comparison
                            $allocated = [50, 40, 35, 30, 25];

                            // Actual from DB
                            $actual = array_values($statusCounts);
                            ?>

                            <script>
                                document.addEventListener("DOMContentLoaded", () => {
                                    echarts.init(document.querySelector("#requestRadarChart")).setOption({
                                        legend: {
                                            data: ['Planned Requests', 'Actual Requests']
                                        },
                                        radar: {
                                            indicator: [{
                                                    name: 'Hot Lead',
                                                    max: 100
                                                },
                                                {
                                                    name: 'Appointment',
                                                    max: 100
                                                },
                                                {
                                                    name: 'Estimate Sent',
                                                    max: 100
                                                },
                                                {
                                                    name: 'In Progress',
                                                    max: 100
                                                },
                                                {
                                                    name: 'Project Done',
                                                    max: 100
                                                }
                                            ]
                                        },
                                        series: [{
                                            name: 'Planned vs Actual',
                                            type: 'radar',
                                            data: [{
                                                    value: <?php echo json_encode($allocated); ?>,
                                                    name: 'Planned Requests'
                                                },
                                                {
                                                    value: <?php echo json_encode($actual); ?>,
                                                    name: 'Actual Requests'
                                                }
                                            ]
                                        }]
                                    });
                                });
                            </script>
                        </div>
                    </div>
                    <!-- End Request Status Report -->


                    <!-- Users by Role -->
<div class="card">
    <div class="filter">
        <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
            <li class="dropdown-header text-start">
                <h6>Filter</h6>
            </li>
            <li><a class="dropdown-item" href="#">Today</a></li>
            <li><a class="dropdown-item" href="#">This Month</a></li>
            <li><a class="dropdown-item" href="#">This Year</a></li>
        </ul>
    </div>

    <div class="card-body pb-0">
        <h5 class="card-title">Users by Role <span>| This Month</span></h5>

        <div id="userRoleChart" style="min-height: 400px;" class="echart"></div>

        <?php
        require '../../admin/pages/scripts/connection.php';

        // Count users by role
        $sql = "SELECT role, COUNT(*) as total FROM users GROUP BY role";
        $result = $conn->query($sql);

        $roleCounts = [
            'admin' => 0,
            'provider' => 0,
            'client' => 0
        ];

        while ($row = $result->fetch_assoc()) {
            if (isset($roleCounts[$row['role']])) {
                $roleCounts[$row['role']] = (int)$row['total'];
            }
        }
        ?>

        <script>
            document.addEventListener("DOMContentLoaded", () => {
                echarts.init(document.querySelector("#userRoleChart")).setOption({
                    tooltip: {
                        trigger: 'item'
                    },
                    legend: {
                        top: '5%',
                        left: 'center'
                    },
                    series: [{
                        name: 'Users',
                        type: 'pie',
                        radius: ['40%', '70%'],
                        avoidLabelOverlap: false,
                        label: {
                            show: false,
                            position: 'center'
                        },
                        emphasis: {
                            label: {
                                show: true,
                                fontSize: '18',
                                fontWeight: 'bold'
                            }
                        },
                        labelLine: {
                            show: false
                        },
                        data: [
                            { value: <?php echo $roleCounts['admin']; ?>, name: 'Admin' },
                            { value: <?php echo $roleCounts['provider']; ?>, name: 'Provider' },
                            { value: <?php echo $roleCounts['client']; ?>, name: 'Client' }
                        ]
                    }]
                });
            });
        </script>

    </div>
</div>
<!-- End Users by Role -->




                </div><!-- End Right side columns -->

            </div>
        </section>

    </main>
    <!-- End #main -->

    <!-- ======= Footer ======= -->
    <?php include "includes/footer.php" ?>
    <!-- End Footer -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <?php include "includes/scripts.php" ?>

</body>

</html>