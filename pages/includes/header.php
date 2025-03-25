<header id="header" class="header d-flex align-items-center fixed-top">
    <div class="header-container container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

        <a href="index.php" class="logo d-flex align-items-center me-auto me-xl-0">
            <h1 class="sitename">Quick Property Services</h1>
        </a>

        <?php 
        $current_page = basename($_SERVER['PHP_SELF']); // Get current page filename
        ?>

        <nav id="navmenu" class="navmenu">
            <ul>
                <?php if (isset($_SESSION['role'])): ?>
                    <?php if ($_SESSION['role'] === 'client'): ?>
                        <li><a href="client-dashboard.php" class="<?= $current_page == 'client-dashboard.php' ? 'active' : '' ?>">Dashboard</a></li>
                        <li><a href="client-services.php" class="<?= $current_page == 'client-services.php' ? 'active' : '' ?>">Services</a></li>
                        <li><a href="client-profile.php" class="<?= $current_page == 'client-profile.php' ? 'active' : '' ?>">Profile</a></li>
                    <?php elseif ($_SESSION['role'] === 'provider'): ?>
                        <li><a href="provider-dashboard.php" class="<?= $current_page == 'provider-dashboard.php' ? 'active' : '' ?>">My Dashboard</a></li>
                        <li><a href="provider-services.php" class="<?= $current_page == 'provider-services.php' ? 'active' : '' ?>">My Services</a></li>
                        <li><a href="provider-profile.php" class="<?= $current_page == 'provider-profile.php' ? 'active' : '' ?>">My Profile</a></li>
                    <?php endif; ?>
                <?php else: ?>
                    <li><a href="index.php" class="<?= $current_page == 'index.php' ? 'active' : '' ?>">Home</a></li>
                    <li><a href="about.php" class="<?= $current_page == 'about.php' ? 'active' : '' ?>">About</a></li>
                    <li><a href="services.php" class="<?= $current_page == 'services.php' ? 'active' : '' ?>">Services</a></li>
                    <li><a href="portfolio.php" class="<?= $current_page == 'portfolio.php' ? 'active' : '' ?>">Portfolio</a></li>
                    <li><a href="contact.php" class="<?= $current_page == 'contact.php' ? 'active' : '' ?>">Contact</a></li>
                <?php endif; ?>
            </ul>
            <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>

        <?php if (isset($_SESSION['user_id'])): ?>
            <!-- User Dropdown when logged in -->
            <div class="dropdown">
                <button class="btn btn-light dropdown-toggle d-flex align-items-center" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle me-2"></i> 
                    <span><?php echo $_SESSION['first_name']; ?> <?php echo $_SESSION['last_name']; ?></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    
                <li><a class="dropdown-item" href="#"><?php echo $_SESSION['first_name']; ?> <?php echo $_SESSION['last_name']; ?></a></li>
                <li><a class="dropdown-item" href="#"> <span class="badge text-bg-primary "><?php echo $_SESSION['role']; ?> #<?php echo $_SESSION['user_id']; ?></span></a></li>
                <hr>
                
                    <?php if ($_SESSION['role'] === 'client'): ?>
                        <hr>
                        <li><a class="dropdown-item" href="client-profile.php" class="<?= $current_page == 'profile_user.php' ? 'active' : '' ?>">Profile</a></li>
                    <?php elseif ($_SESSION['role'] === 'provider'): ?>
                        <li><a class="dropdown-item" href="provider-profile.php" class="<?= $current_page == 'profile_provider.php' ? 'active' : '' ?>">Profile</a></li>
                    <?php endif; ?>
                    <li><a class="dropdown-item" href="scripts/logout.php">Logout</a></li>
                </ul>
            </div>
        <?php else: ?>
            <!-- Show Login button if not logged in -->
            <a class="btn-getstarted" href="login.php">Login</a>
        <?php endif; ?>

    </div>
</header>
