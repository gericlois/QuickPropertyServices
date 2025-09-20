<!DOCTYPE html>
<html lang="en">

<?php include "includes/head.php" ?>

<?php
$role = $_SESSION['role'] ?? 'guest'; // fallback if not logged in
?>

<body class="index-page <?php echo $role; ?>">

    <?php include "includes/header.php" ?>

    <main class="main">

        <!-- Contact Section -->
        <section id="contact" class="contact section light-background">

            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>Request a Job</h2>
                <p>Submit your job details below and our team will review your request promptly.</p>
            </div><!-- End Section Title -->


            <div class="container" data-aos="fade-up" data-aos-delay="100">

                <div class="row g-4 g-lg-5">
                    <div class="col-lg-5">
                        <div class="info-box" data-aos="fade-up" data-aos-delay="200">
                            <h3>Job Request Information</h3>
                            <p>If you’re submitting a job request, please fill out the form. For additional help or urgent requests, you can also reach us directly below:</p>

                            <div class="info-item" data-aos="fade-up" data-aos-delay="300">
                                <div class="icon-box">
                                    <i class="bi bi-geo-alt"></i>
                                </div>
                                <div class="content">
                                    <h4>Service Area</h4>
                                    <p>Highland, Utah County</p>
                                </div>
                            </div>

                            <div class="info-item" data-aos="fade-up" data-aos-delay="400">
                                <div class="icon-box">
                                    <i class="bi bi-telephone"></i>
                                </div>
                                <div class="content">
                                    <h4>Call Us for Immediate Assistance</h4>
                                    <p>+801-613-0482</p>
                                </div>
                            </div>

                            <div class="info-item" data-aos="fade-up" data-aos-delay="500">
                                <div class="icon-box">
                                    <i class="bi bi-envelope"></i>
                                </div>
                                <div class="content">
                                    <h4>Submit Job Details via Email</h4>
                                    <p>servicerequest@quickpropertyservices.com</p>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-lg-7">
                        <div class="contact-form" data-aos="fade-up" data-aos-delay="300">
                            <h3>Get In Touch</h3>
                            <p>Send us your message.</p>

                            <?php
                            if (isset($_GET['error'])) {
                                if ($_GET["error"] == "EmailAlreadyExists" || $_GET["error"] == "emailtaken") {
                                    echo '
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <b>Email has been taken, select another email!</b>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
                                }
                            }

                            if (isset($_GET['success']) && $_GET["success"] == "JobRequestSubmitted") {
                                echo '
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <b>Your job request has been submitted successfully!</b>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
                            }
                            ?>

                           <form action="scripts/request.php" method="post" data-aos="fade-up" data-aos-delay="200" enctype="multipart/form-data">

                                <div class="row gy-4">

                                    <!-- Home Owner Information -->
                                    <h5>Home Owner Information</h5>
                                    <!-- Hidden input, only if session client_id is set -->
                                    <?php if (isset($_SESSION['client_id'])): ?>
                                        <input type="hidden" name="client_id" value="<?php echo $_SESSION['client_id']; ?>">
                                    <?php endif; ?>

                                    <div class="col-12">
                                        <input type="text" name="contact_source" class="form-control" placeholder="Contact Source" required>
                                    </div>

                                    <div class="col-md-6">
                                        <input type="text" name="homeowner_name" class="form-control" placeholder="Name" required>
                                    </div>

                                    <div class="col-md-6">
                                        <input type="text" name="address" class="form-control" placeholder="Address" required>
                                    </div>

                                    <div class="col-md-6">
                                        <input type="text" name="phone1" class="form-control" placeholder="Phone Number 1" required>
                                    </div>

                                    <div class="col-md-6">
                                        <input type="text" name="phone2" class="form-control" placeholder="Phone Number 2">
                                    </div>

                                    <div class="col-12">
                                        <input type="email" name="email" class="form-control" placeholder="Email Address" required>
                                    </div>

                                    <div class="col-12">
                                        <textarea class="form-control" name="work_description" rows="4" placeholder="Work Description" required></textarea>
                                    </div>

                                    <div class="col-12">
                                        <textarea class="form-control" name="estimator_notes" rows="4" placeholder="Estimator Notes"></textarea>
                                    </div>

                                    <div class="col-12">
                                        <textarea class="form-control" name="crew_instructions" rows="4" placeholder="Instruction to Working Crew"></textarea>
                                    </div>

                                    <!-- Upload Images -->
                                    <div class="col-12">
                                        <label class="form-label">Upload Images (Max 5)</label>
                                        <input type="file" name="images[]" class="form-control" accept="image/*" multiple required onchange="if(this.files.length > 5){ alert('You can only upload a maximum of 5 images'); this.value=''; }">
                                    </div>

                                    <!-- Submit -->
                                    <div class="col-12 text-center">
                                        <button type="submit" class="btn">Submit Job Request</button>
                                    </div>

                                </div>
                            </form>



                        </div>
                    </div>

                </div>

            </div>

        </section><!-- /Contact Section -->

        <!-- Call To Action Section -->
        <section id="call-to-action" class="call-to-action section">

            <div class="container" data-aos="fade-up" data-aos-delay="100">

                <div class="row content justify-content-center align-items-center position-relative">
                    <div class="col-lg-8 mx-auto text-center">
                        <h2 class="display-4 mb-4">Get the Services You Need, Anytime, Anywhere!</h2>
                        <p class="mb-4">Our platform connects you with top professionals for hassle-free service
                            booking. Enjoy seamless, secure, and efficient solutions at your fingertips.</p>
                        <a href="login.php" class="btn btn-cta">Book a Service Now ⟶ </a>
                    </div>

                    <!-- Abstract Background Elements -->
                    <div class="shape shape-1">
                        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M47.1,-57.1C59.9,-45.6,68.5,-28.9,71.4,-10.9C74.2,7.1,71.3,26.3,61.5,41.1C51.7,55.9,35,66.2,16.9,69.2C-1.3,72.2,-21,67.8,-36.9,57.9C-52.8,48,-64.9,32.6,-69.1,15.1C-73.3,-2.4,-69.5,-22,-59.4,-37.1C-49.3,-52.2,-32.8,-62.9,-15.7,-64.9C1.5,-67,34.3,-68.5,47.1,-57.1Z"
                                transform="translate(100 100)"></path>
                        </svg>
                    </div>

                    <div class="shape shape-2">
                        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M41.3,-49.1C54.4,-39.3,66.6,-27.2,71.1,-12.1C75.6,3,72.4,20.9,63.3,34.4C54.2,47.9,39.2,56.9,23.2,62.3C7.1,67.7,-10,69.4,-24.8,64.1C-39.7,58.8,-52.3,46.5,-60.1,31.5C-67.9,16.4,-70.9,-1.4,-66.3,-16.6C-61.8,-31.8,-49.7,-44.3,-36.3,-54C-22.9,-63.7,-8.2,-70.6,3.6,-75.1C15.4,-79.6,28.2,-58.9,41.3,-49.1Z"
                                transform="translate(100 100)"></path>
                        </svg>
                    </div>

                    <!-- Dot Pattern Groups -->
                    <div class="dots dots-1">
                        <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                            <pattern id="dot-pattern" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
                                <circle cx="2" cy="2" r="2" fill="currentColor"></circle>
                            </pattern>
                            <rect width="100" height="100" fill="url(#dot-pattern)"></rect>
                        </svg>
                    </div>

                    <div class="dots dots-2">
                        <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                            <pattern id="dot-pattern-2" x="0" y="0" width="20" height="20"
                                patternUnits="userSpaceOnUse">
                                <circle cx="2" cy="2" r="2" fill="currentColor"></circle>
                            </pattern>
                            <rect width="100" height="100" fill="url(#dot-pattern-2)"></rect>
                        </svg>
                    </div>

                    <div class="shape shape-3">
                        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M43.3,-57.1C57.4,-46.5,71.1,-32.6,75.3,-16.2C79.5,0.2,74.2,19.1,65.1,35.3C56,51.5,43.1,65,27.4,71.7C11.7,78.4,-6.8,78.3,-23.9,72.4C-41,66.5,-56.7,54.8,-65.4,39.2C-74.1,23.6,-75.8,4,-71.7,-13.2C-67.6,-30.4,-57.7,-45.2,-44.3,-56.1C-30.9,-67,-15.5,-74,0.7,-74.9C16.8,-75.8,33.7,-70.7,43.3,-57.1Z"
                                transform="translate(100 100)"></path>
                        </svg>
                    </div>
                </div>

            </div>

        </section><!-- /Call To Action Section -->

        <!-- Faq Section -->
        <section class="faq-9 faq section light-background" id="faq">

            <div class="container">
                <div class="row">

                    <div class="col-lg-5" data-aos="fade-up">
                        <h2 class="faq-title"> Find Answers to Common Questions</h2>
                        <p class="faq-description">Have questions? We've got you covered! Browse through our frequently
                            asked questions to find detailed answers about our services, features, and how we can assist
                            you. If you don’t see your question here, feel free to reach out to our support team for
                            further assistance.</p>
                        <div class="faq-arrow d-none d-lg-block" data-aos="fade-up" data-aos-delay="200">
                            <svg class="faq-arrow" width="200" height="211" viewBox="0 0 200 211" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M198.804 194.488C189.279 189.596 179.529 185.52 169.407 182.07L169.384 182.049C169.227 181.994 169.07 181.939 168.912 181.884C166.669 181.139 165.906 184.546 167.669 185.615C174.053 189.473 182.761 191.837 189.146 195.695C156.603 195.912 119.781 196.591 91.266 179.049C62.5221 161.368 48.1094 130.695 56.934 98.891C84.5539 98.7247 112.556 84.0176 129.508 62.667C136.396 53.9724 146.193 35.1448 129.773 30.2717C114.292 25.6624 93.7109 41.8875 83.1971 51.3147C70.1109 63.039 59.63 78.433 54.2039 95.0087C52.1221 94.9842 50.0776 94.8683 48.0703 94.6608C30.1803 92.8027 11.2197 83.6338 5.44902 65.1074C-1.88449 41.5699 14.4994 19.0183 27.9202 1.56641C28.6411 0.625793 27.2862 -0.561638 26.5419 0.358501C13.4588 16.4098 -0.221091 34.5242 0.896608 56.5659C1.8218 74.6941 14.221 87.9401 30.4121 94.2058C37.7076 97.0203 45.3454 98.5003 53.0334 98.8449C47.8679 117.532 49.2961 137.487 60.7729 155.283C87.7615 197.081 139.616 201.147 184.786 201.155L174.332 206.827C172.119 208.033 174.345 211.287 176.537 210.105C182.06 207.125 187.582 204.122 193.084 201.144C193.346 201.147 195.161 199.887 195.423 199.868C197.08 198.548 193.084 201.144 195.528 199.81C196.688 199.192 197.846 198.552 199.006 197.935C200.397 197.167 200.007 195.087 198.804 194.488ZM60.8213 88.0427C67.6894 72.648 78.8538 59.1566 92.1207 49.0388C98.8475 43.9065 106.334 39.2953 114.188 36.1439C117.295 34.8947 120.798 33.6609 124.168 33.635C134.365 33.5511 136.354 42.9911 132.638 51.031C120.47 77.4222 86.8639 93.9837 58.0983 94.9666C58.8971 92.6666 59.783 90.3603 60.8213 88.0427Z"
                                    fill="currentColor"></path>
                            </svg>
                        </div>
                    </div>

                    <div class="col-lg-7" data-aos="fade-up" data-aos-delay="300">
                        <div class="faq-container">

                            <div class="faq-item faq-active">
                                <h3>How do I book a service?</h3>
                                <div class="faq-content">
                                    <p>Simply visit our services page, choose the service you need, select a date and
                                        time, and confirm your booking. Our professionals will take care of the rest!
                                    </p>
                                </div>
                                <i class="faq-toggle bi bi-chevron-right"></i>
                            </div><!-- End Faq item-->

                            <div class="faq-item">
                                <h3>Are the service providers qualified and insured?</h3>
                                <div class="faq-content">
                                    <p>Yes! We carefully vet all service providers to ensure they are qualified,
                                        experienced, and fully insured for your peace of mind.</p>
                                </div>
                                <i class="faq-toggle bi bi-chevron-right"></i>
                            </div><!-- End Faq item-->

                            <div class="faq-item">
                                <h3> How much do the services cost?</h3>
                                <div class="faq-content">
                                    <p>Prices vary depending on the type of service and complexity of the task. You can
                                        view pricing details on each service page before booking.
                                    </p>
                                </div>
                                <i class="faq-toggle bi bi-chevron-right"></i>
                            </div><!-- End Faq item-->

                            <div class="faq-item">
                                <h3> What if I need to reschedule or cancel a booking?</h3>
                                <div class="faq-content">
                                    <p>You can easily reschedule or cancel your booking through your account. Please
                                        check our cancellation policy for more details.</p>
                                </div>
                                <i class="faq-toggle bi bi-chevron-right"></i>
                            </div><!-- End Faq item-->

                            <div class="faq-item">
                                <h3>How can I contact customer support?</h3>
                                <div class="faq-content">
                                    <p> If you have any questions or need assistance, you can reach us via email, phone,
                                        or live chat on our website. We're here to help!
                                    </p>
                                </div>
                                <i class="faq-toggle bi bi-chevron-right"></i>
                            </div><!-- End Faq item-->

                            <div class="faq-item">
                                <h3>Do you offer same-day services?</h3>
                                <div class="faq-content">
                                    <p> Yes! Depending on availability, we offer same-day service for urgent requests.
                                        Check the service details or contact our team to confirm availability.</p>
                                </div>
                                <i class="faq-toggle bi bi-chevron-right"></i>
                            </div><!-- End Faq item-->

                        </div>
                    </div>

                </div>
            </div>
        </section><!-- /Faq Section -->

    </main>

    <?php include "includes/footer.php" ?>

    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <?php include "includes/script.php" ?>

</body>

</html>