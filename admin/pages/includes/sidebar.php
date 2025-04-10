<?php $currentPage = basename($_SERVER['PHP_SELF']); ?>

<aside id="sidebar" class="sidebar">

    <?php $currentPage = basename($_SERVER['PHP_SELF']); ?>

    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item">
<<<<<<< HEAD
            <a class="nav-link <?= $currentPage == 'index.php' ? '' : 'collapsed' ?>" href="index.php">
=======
            <a class="nav-link <?= ($currentPage == 'index.php') ? '' : 'collapsed' ?>" href="index.php">
>>>>>>> 8e5adf97b8dfe4aaff6b269ca0bc333d2fcd55d1
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <!-- Clients Menu -->
        <li class="nav-item">
<<<<<<< HEAD
            <a class="nav-link <?= in_array($currentPage, ['clients.php', 'clients-blocked.php']) ? '' : 'collapsed' ?>"
               data-bs-target="#clients-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-menu-button-wide"></i><span>Clients</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="clients-nav" class="nav-content collapse <?= in_array($currentPage, ['clients.php', 'clients-blocked.php']) ? 'show' : '' ?>" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="clients.php" class="<?= $currentPage == 'clients.php' ? 'active' : '' ?>">
                        <i class="bi bi-circle"></i><span>Clients</span>
                    </a>
                </li>
                <li>
                    <a href="clients-blocked.php" class="<?= $currentPage == 'clients-blocked.php' ? 'active' : '' ?>">
                        <i class="bi bi-circle"></i><span>Blocked Clients</span>
=======
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
>>>>>>> 8e5adf97b8dfe4aaff6b269ca0bc333d2fcd55d1
                    </a>
                </li>
            </ul>
        </li>

        <!-- Providers Menu -->
        <li class="nav-item">
<<<<<<< HEAD
            <a class="nav-link <?= in_array($currentPage, ['providers.php', 'providers-blocked.php']) ? '' : 'collapsed' ?>"
               data-bs-target="#providers-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-menu-button-wide"></i><span>Providers</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="providers-nav" class="nav-content collapse <?= in_array($currentPage, ['providers.php', 'providers-blocked.php']) ? 'show' : '' ?>" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="providers.php" class="<?= $currentPage == 'providers.php' ? 'active' : '' ?>">
=======
            <a class="nav-link <?= in_array($currentPage, ['providers.php', 'providers-pending.php', 'providers-inactive.php']) ? '' : 'collapsed' ?>" data-bs-target="#providers-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-menu-button-wide"></i><span>Providers</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="providers-nav" class="nav-content collapse <?= in_array($currentPage, ['providers.php', 'providers-pending.php', 'providers-inactive.php']) ? 'show' : '' ?>" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="providers.php" class="<?= ($currentPage == 'providers.php') ? 'active' : '' ?>">
>>>>>>> 8e5adf97b8dfe4aaff6b269ca0bc333d2fcd55d1
                        <i class="bi bi-circle"></i><span>Providers</span>
                    </a>
                </li>
                <li>
<<<<<<< HEAD
                    <a href="providers-blocked.php" class="<?= $currentPage == 'providers-blocked.php' ? 'active' : '' ?>">
                        <i class="bi bi-circle"></i><span>Blocked Providers</span>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Services Menu -->
        <li class="nav-item">
            <a class="nav-link <?= in_array($currentPage, ['services.php', 'services-inactive.php']) ? '' : 'collapsed' ?>"
               data-bs-target="#services-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-menu-button-wide"></i><span>Services</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="services-nav" class="nav-content collapse <?= in_array($currentPage, ['services.php', 'services-inactive.php']) ? 'show' : '' ?>" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="services.php" class="<?= $currentPage == 'services.php' ? 'active' : '' ?>">
                        <i class="bi bi-circle"></i><span>Services</span>
                    </a>
                </li>
                <li>
                    <a href="services-inactive.php" class="<?= $currentPage == 'services-inactive.php' ? 'active' : '' ?>">
                        <i class="bi bi-circle"></i><span>Inactive Services</span>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Transactions Menu -->
        <li class="nav-item">
            <a class="nav-link <?= in_array($currentPage, ['transactions.php', 'transactions-accordion.html']) ? '' : 'collapsed' ?>"
               data-bs-target="#transactions-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-menu-button-wide"></i><span>Transactions</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="transactions-nav" class="nav-content collapse <?= in_array($currentPage, ['transactions.php', 'transactions-accordion.html']) ? 'show' : '' ?>" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="transactions.php" class="<?= $currentPage == 'transactions.php' ? 'active' : '' ?>">
                        <i class="bi bi-circle"></i><span>Transactions</span>
                    </a>
                </li>
                <li>
                    <a href="transactions-accordion.html" class="<?= $currentPage == 'transactions-accordion.html' ? 'active' : '' ?>">
                        <i class="bi bi-circle"></i><span>Accordion</span>
                    </a>
                </li>
            </ul>
        </li>
=======
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
>>>>>>> 8e5adf97b8dfe4aaff6b269ca0bc333d2fcd55d1

        <li class="nav-heading">Users</li>

        <li class="nav-item">
<<<<<<< HEAD
            <a class="nav-link <?= $currentPage == 'admin.php' ? '' : 'collapsed' ?>" href="admin.php">
                <i class="bi bi-person"></i>
                <span>Admin</span>
            </a>
        </li>
=======
            <a class="nav-link <?= ($currentPage == 'admin.php') ? 'active' : 'collapsed' ?>" href="admin.php">
                <i class="bi bi-person"></i>
                <span>Admin</span>
            </a>
        </li><!-- End Admin Nav -->
>>>>>>> 8e5adf97b8dfe4aaff6b269ca0bc333d2fcd55d1

    </ul>

</aside>
