<header id="header" class="header d-flex align-items-center fixed-top">
    <div class="header-container container-fluid container-xl position-relative d-flex align-items-center justify-content-between">
        <a href="index.php" class="logo d-flex align-items-center me-auto me-xl-0">
            <h1 class="sitename">Quick Property Services</h1>
        </a>

        <?php $current_page = basename($_SERVER['PHP_SELF']); ?>

        <nav id="navmenu" class="navmenu">
            <ul>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'vendor'): ?>
                    <li><a href="vendor-dashboard.php" class="<?= $current_page == 'vendor-dashboard.php' ? 'active' : '' ?>">Dashboard</a></li>
                    <li><a href="vendor-profile.php" class="<?= $current_page == 'vendor-profile.php' ? 'active' : '' ?>">Profile</a></li>
                <?php elseif (isset($_SESSION['role']) && $_SESSION['role'] === 'homeowner'): ?>
                    <li><a href="homeowner-dashboard.php" class="<?= $current_page == 'homeowner-dashboard.php' ? 'active' : '' ?>">Dashboard</a></li>
                    <li><a href="submit-request.php" class="<?= $current_page == 'submit-request.php' ? 'active' : '' ?>">Submit Request</a></li>
                    <li><a href="track.php" class="<?= $current_page == 'track.php' ? 'active' : '' ?>">Track Request</a></li>
                <?php else: ?>
                    <li><a href="index.php" class="<?= $current_page == 'index.php' ? 'active' : '' ?>">Home</a></li>
                    <li><a href="submit-request.php" class="<?= $current_page == 'submit-request.php' ? 'active' : '' ?>">Submit Request</a></li>
                    <li><a href="track.php" class="<?= $current_page == 'track.php' ? 'active' : '' ?>">Track Request</a></li>
                    <li><a href="about.php" class="<?= $current_page == 'about.php' ? 'active' : '' ?>">About</a></li>
                    <li><a href="contact.php" class="<?= $current_page == 'contact.php' ? 'active' : '' ?>">Contact</a></li>
                <?php endif; ?>
            </ul>
            <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>

        <?php if (isset($_SESSION['vendor_id'])): ?>
            <div class="dropdown">
                <button class="btn btn-light dropdown-toggle d-flex align-items-center" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle me-2"></i>
                    <span><?php echo htmlspecialchars($_SESSION['first_name'] . ' ' . $_SESSION['last_name']); ?></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#"><?php echo htmlspecialchars($_SESSION['business_name'] ?? ''); ?></a></li>
                    <li><a class="dropdown-item" href="vendor-dashboard.php">Dashboard</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="scripts/vendor-logout.php">Logout</a></li>
                </ul>
            </div>
        <?php elseif (isset($_SESSION['homeowner_id'])): ?>
            <div class="dropdown">
                <button class="btn btn-light dropdown-toggle d-flex align-items-center" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle me-2"></i>
                    <span><?php echo htmlspecialchars($_SESSION['homeowner_name']); ?></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="homeowner-dashboard.php"><i class="bi bi-grid me-2"></i>Dashboard</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="scripts/homeowner-logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                </ul>
            </div>
        <?php else: ?>
            <a class="btn-getstarted" href="homeowner-login.php">Login</a>
        <?php endif; ?>
    </div>
</header>
