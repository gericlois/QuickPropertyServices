<?php $currentPage = basename($_SERVER['PHP_SELF']); ?>

<nav class="nxl-navigation">
    <div class="navbar-wrapper">
        <div class="m-header">
            <a href="index.php" class="b-brand">
                <span class="logo logo-lg" style="font-size:16px;font-weight:700;color:#333;">QuickPropertyServices</span>
                <span class="logo logo-sm" style="font-size:14px;font-weight:700;color:#333;">QPS</span>
            </a>
        </div>
        <div class="navbar-content">
            <ul class="nxl-navbar">
                <li class="nxl-item nxl-caption">
                    <label>Navigation</label>
                </li>

                <!-- Dashboard -->
                <li class="nxl-item <?= $currentPage == 'index.php' ? 'active' : '' ?>">
                    <a href="index.php" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-airplay"></i></span>
                        <span class="nxl-mtext">Dashboard</span>
                    </a>
                </li>

                <!-- Requests -->
                <li class="nxl-item <?= $currentPage == 'requests.php' || $currentPage == 'request-view.php' ? 'active' : '' ?>">
                    <a href="requests.php" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-file-text"></i></span>
                        <span class="nxl-mtext">Requests</span>
                    </a>
                </li>

                <!-- Vendors -->
                <li class="nxl-item nxl-hasmenu">
                    <a href="javascript:void(0);" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-tool"></i></span>
                        <span class="nxl-mtext">Vendors</span>
                        <span class="nxl-arrow"><i class="feather-chevron-right"></i></span>
                    </a>
                    <ul class="nxl-submenu">
                        <li class="nxl-item"><a class="nxl-link <?= $currentPage == 'vendors.php' ? 'active' : '' ?>" href="vendors.php">All Vendors</a></li>
                        <li class="nxl-item"><a class="nxl-link <?= $currentPage == 'vendor-add.php' ? 'active' : '' ?>" href="vendor-add.php">Add Vendor</a></li>
                    </ul>
                </li>

                <li class="nxl-item nxl-caption">
                    <label>Administration</label>
                </li>

                <!-- Admin Users -->
                <li class="nxl-item nxl-hasmenu">
                    <a href="javascript:void(0);" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-shield"></i></span>
                        <span class="nxl-mtext">Admin Users</span>
                        <span class="nxl-arrow"><i class="feather-chevron-right"></i></span>
                    </a>
                    <ul class="nxl-submenu">
                        <li class="nxl-item"><a class="nxl-link <?= $currentPage == 'users.php' ? 'active' : '' ?>" href="users.php">All Admin Users</a></li>
                        <li class="nxl-item"><a class="nxl-link <?= $currentPage == 'users-banned.php' ? 'active' : '' ?>" href="users-banned.php">Banned Users</a></li>
                        <li class="nxl-item"><a class="nxl-link <?= $currentPage == 'users-inactive.php' ? 'active' : '' ?>" href="users-inactive.php">Inactive Users</a></li>
                    </ul>
                </li>

                <!-- History -->
                <li class="nxl-item <?= $currentPage == 'history.php' ? 'active' : '' ?>">
                    <a href="history.php" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-clock"></i></span>
                        <span class="nxl-mtext">History</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
