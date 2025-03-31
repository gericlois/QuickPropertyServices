<aside id="sidebar" class="sidebar">

    <?php $currentPage = basename($_SERVER['PHP_SELF']); ?>

    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item">
            <a class="nav-link <?= ($currentPage == 'index.php') ? '' : 'collapsed' ?>" href="index.php">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li><!-- End Dashboard Nav -->

        <li class="nav-item">
            <a class="nav-link <?= in_array($currentPage, ['users.php', 'users-inactive.php', 'users-banned.php']) ? '' : 'collapsed' ?>" data-bs-target="#users-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-menu-button-wide"></i><span>Users</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="users-nav" class="nav-content collapse <?= in_array($currentPage, ['users.php', 'users-inactive.php', 'users-banned.php']) ? 'show' : '' ?>" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="users.php" class="<?= ($currentPage == 'users.php') ? 'active' : '' ?>">
                        <i class="bi bi-circle"></i><span>Users</span>
                    </a>
                </li>
                <li>
                    <a href="users-inactive.php" class="<?= ($currentPage == 'users-inactive.php') ? 'active' : '' ?>">
                        <i class="bi bi-circle"></i><span>Inactive Users</span>
                    </a>
                </li>
                <li>
                    <a href="users-banned.php" class="<?= ($currentPage == 'users-banned.php') ? 'active' : '' ?>">
                        <i class="bi bi-circle"></i><span>Banned Users</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Users Nav -->

        <li class="nav-item">
            <a class="nav-link <?= in_array($currentPage, ['providers.php', 'providers-pending.php', 'providers-inactive.php']) ? '' : 'collapsed' ?>" data-bs-target="#providers-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-menu-button-wide"></i><span>Providers</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="providers-nav" class="nav-content collapse <?= in_array($currentPage, ['providers.php', 'providers-pending.php', 'providers-inactive.php']) ? 'show' : '' ?>" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="providers.php" class="<?= ($currentPage == 'providers.php') ? 'active' : '' ?>">
                        <i class="bi bi-circle"></i><span>Providers</span>
                    </a>
                </li>
                <li>
                    <a href="providers-pending.php" class="<?= ($currentPage == 'providers-pending.php') ? 'active' : '' ?>">
                        <i class="bi bi-circle"></i><span>Pending Providers</span>
                    </a>
                </li>
                <li>
                    <a href="providers-inactive.php" class="<?= ($currentPage == 'providers-inactive.php') ? 'active' : '' ?>">
                        <i class="bi bi-circle"></i><span>Inactive Providers</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Providers Nav -->

        <li class="nav-item">
            <a class="nav-link <?= ($currentPage == 'services.php') ? '' : 'collapsed' ?>" data-bs-target="#services-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-menu-button-wide"></i><span>Services</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="services-nav" class="nav-content collapse <?= ($currentPage == 'services.php') ? 'show' : '' ?>" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="services.php" class="<?= ($currentPage == 'services.php') ? 'active' : '' ?>">
                        <i class="bi bi-circle"></i><span>Services</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Services Nav -->

        <li class="nav-item">
            <a class="nav-link <?= in_array($currentPage, ['bookings.php', 'transactions-accordion.html']) ? '' : 'collapsed' ?>" data-bs-target="#bookings-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-menu-button-wide"></i><span>Bookings</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="bookings-nav" class="nav-content collapse <?= in_array($currentPage, ['bookings.php', 'transactions-accordion.html']) ? 'show' : '' ?>" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="bookings.php" class="<?= ($currentPage == 'bookings.php') ? 'active' : '' ?>">
                        <i class="bi bi-circle"></i><span>Bookings</span>
                    </a>
                </li>
                <li>
                    <a href="transactions-accordion.html" class="<?= ($currentPage == 'transactions-accordion.html') ? 'active' : '' ?>">
                        <i class="bi bi-circle"></i><span>Accordion</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Transactions Nav -->

        <li class="nav-heading">Users</li>

        <li class="nav-item">
            <a class="nav-link <?= ($currentPage == 'admin.php') ? 'active' : 'collapsed' ?>" href="admin.php">
                <i class="bi bi-person"></i>
                <span>Admin</span>
            </a>
        </li><!-- End Admin Nav -->

    </ul>

</aside>
