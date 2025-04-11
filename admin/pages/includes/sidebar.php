<?php $currentPage = basename($_SERVER['PHP_SELF']); ?>

<aside id="sidebar" class="sidebar">

    <?php $currentPage = basename($_SERVER['PHP_SELF']); ?>

    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item">
            <a class="nav-link <?= $currentPage == 'index.php' ? '' : 'collapsed' ?>" href="index.php">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <!-- Clients Menu -->
        <li class="nav-item">
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
                    </a>
                </li>
            </ul>
        </li>

        <!-- Providers Menu -->
        <li class="nav-item">
            <a class="nav-link <?= in_array($currentPage, ['providers.php', 'providers-blocked.php']) ? '' : 'collapsed' ?>"
               data-bs-target="#providers-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-menu-button-wide"></i><span>Providers</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="providers-nav" class="nav-content collapse <?= in_array($currentPage, ['providers.php', 'providers-blocked.php']) ? 'show' : '' ?>" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="providers.php" class="<?= $currentPage == 'providers.php' ? 'active' : '' ?>">
                        <i class="bi bi-circle"></i><span>Providers</span>
                    </a>
                </li>
                <li>
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

        <li class="nav-heading">Users</li>

        <li class="nav-item">
            <a class="nav-link <?= $currentPage == 'admin.php' ? '' : 'collapsed' ?>" href="admin.php">
                <i class="bi bi-person"></i>
                <span>Admin</span>
            </a>
        </li>

    </ul>

</aside>
