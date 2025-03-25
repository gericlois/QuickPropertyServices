<footer id="footer" class="footer">
    <div class="container footer-top">
        <div class="row gy-4">
            <div class="col-lg-7 col-md-6 footer-about">
                <a href="index.php" class="logo d-flex align-items-center">
                    <span class="sitename">Quick Property Services LLC</span>
                </a>
                <p>"Your Home, Your Vision We Make It a Reality"</p>
            </div>

            <div class="col-lg-2 col-md-3 footer-links">
                <h4>Links</h4>
                <ul>
                    <?php if (isset($_SESSION['role'])): ?>
                        <?php if ($_SESSION['role'] === 'client'): ?>
                            <li><a href="client-dashboard.php">Dashboard</a></li>
                            <li><a href="client-services.php">Services</a></li>
                            <li><a href="client-profile.php">Profile</a></li>
                        <?php elseif ($_SESSION['role'] === 'provider'): ?>
                            <li><a href="provider-dashboard.php">My Dashboard</a></li>
                            <li><a href="provider-services.php">My Services</a></li>
                            <li><a href="provider-profile.php">My Profile</a></li>
                        <?php endif; ?>
                    <?php else: ?>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="about.php">About</a></li>
                        <li><a href="services.php">Services</a></li>
                        <li><a href="portfolio.php">Portfolio</a></li>
                        <li><a href="contact.php">Contact</a></li>
                    <?php endif; ?>
                </ul>
            </div>

            <div class="col-lg-3 col-md-3 footer-links">
                <h4>Contact Us</h4>
                <div class="footer-contact pt-">
                    <p class="mt-3"><strong>Phone:</strong> <span>+1 801-613-0482</span></p>
                    <p><strong>Email:</strong> <span>servicerequest@quickpropertyservices.com</span></p>
                </div>
                <div class="social-links d-flex mt-4">
                    <a href="#"><i class="bi bi-twitter-x"></i></a>
                    <a href="#"><i class="bi bi-facebook"></i></a>
                    <a href="#"><i class="bi bi-instagram"></i></a>
                    <a href="#"><i class="bi bi-linkedin"></i></a>
                </div>
            </div>
        </div>
    </div>

    <div class="container copyright text-center mt-4">
        <p>© <span>Copyright</span> <strong class="px-1 sitename">
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'provider'): ?>
                <a href="provider-dashboard.php">Quick Property Services LLC</a>
            <?php elseif (isset($_SESSION['role']) && $_SESSION['role'] === 'user'): ?>
                <a href="client-dashboard.php">Quick Property Services LLC</a>
            <?php else: ?>
                <a href="index.php">Quick Property Services LLC</a>
            <?php endif; ?>
        </strong> <span>All Rights Reserved 2025</span></p>

        <div class="credits">
            Designed by <a href="https://casugayportfolio.my.canva.site/">GL Casugay</a>
        </div>
    </div>
</footer>
