<!DOCTYPE html>
<html lang="en">

<?php
session_start();

// Redirect vendors to their dashboard
if (isset($_SESSION['role']) && $_SESSION['role'] === 'vendor') {
    header("Location: vendor-dashboard.php");
    exit();
}
?>

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Quick Property Services - A Better Way to Take Care of Your Home</title>
  <meta name="description" content="Quick Property Services connects homeowners with vetted professionals. Get transparent estimates, escrow payment protection, and real-time project tracking.">
  <meta name="keywords" content="home remodeling, home repair, property services, vetted contractors, transparent pricing, escrow payment, Utah contractor, service request">

  <!-- Favicons -->
  <link href="../assets/img/logo.jpg" rel="icon">
  <link href="../assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Inter:wght@100;200;300;400;500;600;700;800;900&family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="../assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="../assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="../assets/css/main.css" rel="stylesheet">

  <!-- Homepage Revamp Override -->
  <link href="../assets/css/homepage-revamp.css" rel="stylesheet">
</head>

<body class="index-page">

    <?php include "includes/header.php" ?>

    <main class="main">

        <!-- ==================== HERO SECTION ==================== -->
        <section id="hero" class="hero section">
            <div class="container" data-aos="fade-up" data-aos-delay="100">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="hero-content" data-aos="fade-up" data-aos-delay="200">
                            <div class="company-badge mb-4">
                                <i class="bi bi-house-heart me-2"></i>
                                Quick Property Services
                            </div>

                            <h1 class="mb-4">
                                A better way to take care of your home
                            </h1>

                            <p class="hero-subtitle mb-5">
                                Submit a request, get matched with vetted professionals, receive transparent estimates, and pay only when the job is done right.
                            </p>

                            <div class="hero-buttons">
                                <a href="submit-request.php" class="btn btn-primary btn-lg">Submit a Request</a>
                                <br>
                                <a href="track.php" class="btn btn-link mt-3">
                                    <i class="bi bi-search me-1"></i>
                                    Track Your Request
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section><!-- /Hero Section -->

        <!-- ==================== TRUST CARDS SECTION ==================== -->
        <section id="trust" class="trust-section section">
            <div class="container">
                <div class="section-title text-center" data-aos="fade-up">
                    <h2>Why Homeowners Trust Us</h2>
                    <p>Built around transparency, quality, and your peace of mind</p>
                </div>

                <div class="trust-cards-row" data-aos="fade-up" data-aos-delay="200">

                    <!-- Card 1: Transparent Pricing -->
                    <div class="trust-card active">
                        <div class="trust-card-inner">
                            <div class="trust-card-preview">
                                <i class="bi bi-currency-dollar"></i>
                                <h3>Transparent pricing.<br>No surprises.</h3>
                            </div>
                            <div class="trust-card-expanded">
                                <div class="trust-card-image" style="background-image: url('../assets/img/1.png');"></div>
                                <div class="trust-card-content">
                                    <h3>Transparent pricing. No surprises.</h3>
                                    <p>We collect multiple estimates from qualified vendors and present you with the best options. You see exact pricing with no hidden fees -- just honest, competitive quotes you can trust.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2: Home Repairs Made Easy -->
                    <div class="trust-card">
                        <div class="trust-card-inner">
                            <div class="trust-card-preview">
                                <i class="bi bi-tools"></i>
                                <h3>Home repairs<br>made easy.</h3>
                            </div>
                            <div class="trust-card-expanded">
                                <div class="trust-card-image" style="background-image: url('../assets/img/2.png');"></div>
                                <div class="trust-card-content">
                                    <h3>Home repairs made easy.</h3>
                                    <p>Describe your project, upload photos, and we handle the rest. From matching you with the right vendor to tracking completion -- the entire process is seamless and stress-free.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card 3: Professionals You Can Count On -->
                    <div class="trust-card">
                        <div class="trust-card-inner">
                            <div class="trust-card-preview">
                                <i class="bi bi-shield-check"></i>
                                <h3>Professionals you<br>can count on.</h3>
                            </div>
                            <div class="trust-card-expanded">
                                <div class="trust-card-image" style="background-image: url('../assets/img/3.png');"></div>
                                <div class="trust-card-content">
                                    <h3>Professionals you can count on.</h3>
                                    <p>Every vendor is vetted and qualified. Your payment is held in escrow until the work is completed to your satisfaction -- so you only pay for results.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section><!-- /Trust Cards Section -->

        <!-- ==================== HOW IT WORKS SECTION ==================== -->
        <section id="how-it-works" class="how-it-works section light-background">
            <div class="container">
                <div class="section-title text-center" data-aos="fade-up">
                    <h2>How It Works</h2>
                    <p>From request to completion in 5 simple steps</p>
                </div>

                <div class="row align-items-center" data-aos="fade-up" data-aos-delay="200">
                    <div class="col-lg-5">
                        <div class="steps-list">

                            <div class="step-item active" data-step="1">
                                <div class="step-number">1</div>
                                <div class="step-text">
                                    <h4>Describe Your Project</h4>
                                    <p>Tell us what you need. Submit a request with photos and details -- no account required.</p>
                                </div>
                            </div>

                            <div class="step-item" data-step="2">
                                <div class="step-number">2</div>
                                <div class="step-text">
                                    <h4>Get Matched with Vendors</h4>
                                    <p>We assign qualified, vetted professionals to review your project and prepare estimates.</p>
                                </div>
                            </div>

                            <div class="step-item" data-step="3">
                                <div class="step-number">3</div>
                                <div class="step-text">
                                    <h4>Review Estimates</h4>
                                    <p>Receive competitive, transparent estimates. Compare pricing and scope before committing.</p>
                                </div>
                            </div>

                            <div class="step-item" data-step="4">
                                <div class="step-number">4</div>
                                <div class="step-text">
                                    <h4>Accept &amp; Pay Securely</h4>
                                    <p>Choose the best estimate. Your payment is held in escrow -- vendors get paid only when you approve.</p>
                                </div>
                            </div>

                            <div class="step-item" data-step="5">
                                <div class="step-number">5</div>
                                <div class="step-text">
                                    <h4>Track to Completion</h4>
                                    <p>Monitor your project in real time with your unique tracking code. Stay updated every step of the way.</p>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="col-lg-7">
                        <div class="step-images-wrapper">
                            <img src="../assets/img/1.png" class="step-image active" data-step="1" alt="Describe Your Project">
                            <img src="../assets/img/2.png" class="step-image" data-step="2" alt="Get Matched with Vendors">
                            <img src="../assets/img/3.png" class="step-image" data-step="3" alt="Review Estimates">
                            <img src="../assets/img/4.png" class="step-image" data-step="4" alt="Accept and Pay Securely">
                            <img src="../assets/img/5.png" class="step-image" data-step="5" alt="Track to Completion">
                        </div>
                    </div>
                </div>
            </div>
        </section><!-- /How It Works Section -->

        <!-- ==================== PROTECTION / BENEFITS SECTION ==================== -->
        <section id="protection" class="protection-section section">
            <div class="container">
                <div class="section-title text-center" data-aos="fade-up">
                    <h2 class="text-white">Your Protection, Guaranteed</h2>
                    <p class="text-white">Every project comes with built-in safeguards for your peace of mind</p>
                </div>

                <div class="row gy-4" data-aos="fade-up" data-aos-delay="200">

                    <div class="col-xl-4 col-md-6">
                        <div class="protection-card">
                            <div class="icon-wrap">
                                <i class="bi bi-shield-lock"></i>
                            </div>
                            <h4>Escrow Payment Protection</h4>
                            <p>Your payment is held securely until the job is done to your satisfaction. Vendors get paid only after you approve.</p>
                        </div>
                    </div>

                    <div class="col-xl-4 col-md-6">
                        <div class="protection-card">
                            <div class="icon-wrap">
                                <i class="bi bi-headset"></i>
                            </div>
                            <h4>Real Human Support</h4>
                            <p>Talk to a real person. Our dedicated team is here to help you at every step of your project.</p>
                        </div>
                    </div>

                    <div class="col-xl-4 col-md-6">
                        <div class="protection-card">
                            <div class="icon-wrap">
                                <i class="bi bi-person-check"></i>
                            </div>
                            <h4>Vetted Professionals Only</h4>
                            <p>Every vendor is verified, qualified, and reviewed. We only work with trusted professionals.</p>
                        </div>
                    </div>

                    <div class="col-xl-4 col-md-6">
                        <div class="protection-card">
                            <div class="icon-wrap">
                                <i class="bi bi-receipt"></i>
                            </div>
                            <h4>Compare Multiple Estimates</h4>
                            <p>Get competitive quotes from several vendors. Choose the best fit for your budget and timeline.</p>
                        </div>
                    </div>

                    <div class="col-xl-4 col-md-6">
                        <div class="protection-card">
                            <div class="icon-wrap">
                                <i class="bi bi-door-open"></i>
                            </div>
                            <h4>No Account Required</h4>
                            <p>Submit a request as a guest. Track your project anytime with just your unique tracking code.</p>
                        </div>
                    </div>

                    <div class="col-xl-4 col-md-6">
                        <div class="protection-card">
                            <div class="icon-wrap">
                                <i class="bi bi-cash-stack"></i>
                            </div>
                            <h4>Transparent, Fair Pricing</h4>
                            <p>No hidden fees or surprise charges. What you see in the estimate is exactly what you pay.</p>
                        </div>
                    </div>

                </div>
            </div>
        </section><!-- /Protection Section -->

        <!-- ==================== TESTIMONIALS SECTION ==================== -->
        <section id="testimonials" class="testimonials section light-background">
            <div class="container">
                <div class="section-title text-center" data-aos="fade-up">
                    <h2>What Homeowners Say</h2>
                    <p>Real stories from real homeowners who trusted us with their projects</p>
                </div>

                <div class="init-swiper" data-aos="fade-up" data-aos-delay="200">
                    <script type="application/json" class="swiper-config">{
                        "loop": true,
                        "speed": 600,
                        "autoplay": { "delay": 5000 },
                        "slidesPerView": 1,
                        "spaceBetween": 24,
                        "breakpoints": {
                            "768": { "slidesPerView": 2 },
                            "1024": { "slidesPerView": 3 }
                        },
                        "pagination": { "el": ".swiper-pagination", "type": "bullets", "clickable": true }
                    }</script>

                    <div class="swiper-wrapper">

                        <div class="swiper-slide">
                            <div class="testimonial-card">
                                <div class="stars">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                </div>
                                <p class="quote">"The whole process was incredibly smooth. I submitted my request on Monday and had a vetted plumber at my door by Wednesday. Transparent pricing, no surprises."</p>
                                <div class="author">
                                    <img src="../assets/img/avatar-1.webp" alt="Emily D.">
                                    <div class="author-info">
                                        <h5>Emily D.</h5>
                                        <span>Salt Lake City, UT</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="swiper-slide">
                            <div class="testimonial-card">
                                <div class="stars">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                </div>
                                <p class="quote">"I loved being able to compare multiple estimates. The escrow payment gave me peace of mind knowing I wouldn't pay until the work was done right."</p>
                                <div class="author">
                                    <img src="../assets/img/avatar-2.webp" alt="Michael B.">
                                    <div class="author-info">
                                        <h5>Michael B.</h5>
                                        <span>Draper, UT</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="swiper-slide">
                            <div class="testimonial-card">
                                <div class="stars">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                </div>
                                <p class="quote">"Our kitchen renovation turned out amazing. The tracking feature let me stay updated on every stage. Highly recommend Quick Property Services!"</p>
                                <div class="author">
                                    <img src="../assets/img/avatar-3.webp" alt="Jessica T.">
                                    <div class="author-info">
                                        <h5>Jessica T.</h5>
                                        <span>Murray, UT</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="swiper-slide">
                            <div class="testimonial-card">
                                <div class="stars">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-half"></i>
                                </div>
                                <p class="quote">"No account needed to submit a request -- that was a nice surprise. Got my tracking code instantly and could follow the whole process online."</p>
                                <div class="author">
                                    <img src="../assets/img/avatar-4.webp" alt="Daniel A.">
                                    <div class="author-info">
                                        <h5>Daniel A.</h5>
                                        <span>Taylorsville, UT</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="swiper-slide">
                            <div class="testimonial-card">
                                <div class="stars">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                </div>
                                <p class="quote">"Professional from start to finish. The vendor did an outstanding job on our roof repair, and the price was exactly what was quoted. No hidden fees."</p>
                                <div class="author">
                                    <img src="../assets/img/avatar-5.webp" alt="Karen W.">
                                    <div class="author-info">
                                        <h5>Karen W.</h5>
                                        <span>Sandy, UT</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="swiper-pagination"></div>
                </div>

            </div>
        </section><!-- /Testimonials Section -->

        <!-- ==================== FAQ SECTION ==================== -->
        <section class="faq-9 faq section" id="faq">
            <div class="container">
                <div class="row">

                    <div class="col-lg-5" data-aos="fade-up">
                        <h2 class="faq-title">Have Questions?<br>We've Got Answers.</h2>
                        <p class="faq-description">Browse through our frequently asked questions to find detailed answers about our services, process, and how we protect your investment.</p>
                    </div>

                    <div class="col-lg-7" data-aos="fade-up" data-aos-delay="300">
                        <div class="faq-container">

                            <div class="faq-item faq-active">
                                <h3>How does the process work?</h3>
                                <div class="faq-content">
                                    <p>Simply submit a service request describing what you need, along with photos or videos. We'll assign qualified vendors who will provide estimates. You'll receive the best estimate with transparent pricing, and once you accept and pay, work begins!</p>
                                </div>
                                <i class="faq-toggle bi bi-chevron-right"></i>
                            </div>

                            <div class="faq-item">
                                <h3>Do I need to create an account?</h3>
                                <div class="faq-content">
                                    <p>No! You can submit a request as a guest. You'll receive a unique tracking code to monitor your project's progress at any time. An account is automatically created if you want to manage multiple requests.</p>
                                </div>
                                <i class="faq-toggle bi bi-chevron-right"></i>
                            </div>

                            <div class="faq-item">
                                <h3>How is pricing determined?</h3>
                                <div class="faq-content">
                                    <p>We collect estimates from multiple qualified vendors and present you with the best option. Pricing is transparent with no hidden fees -- what you see is what you pay.</p>
                                </div>
                                <i class="faq-toggle bi bi-chevron-right"></i>
                            </div>

                            <div class="faq-item">
                                <h3>Is my payment secure?</h3>
                                <div class="faq-content">
                                    <p>Absolutely. Your payment is held in escrow until the project is completed to your satisfaction. The vendor only gets paid after the work is done and you approve it.</p>
                                </div>
                                <i class="faq-toggle bi bi-chevron-right"></i>
                            </div>

                            <div class="faq-item">
                                <h3>What types of services do you offer?</h3>
                                <div class="faq-content">
                                    <p>We cover a wide range of home services including plumbing, electrical, painting, carpentry, roofing, HVAC, landscaping, cleaning, flooring, and general maintenance.</p>
                                </div>
                                <i class="faq-toggle bi bi-chevron-right"></i>
                            </div>

                            <div class="faq-item">
                                <h3>How can I track my project?</h3>
                                <div class="faq-content">
                                    <p>Use the tracking code provided when you submitted your request. Visit our "Track Request" page to see real-time updates on your project status, from vendor assignment to completion.</p>
                                </div>
                                <i class="faq-toggle bi bi-chevron-right"></i>
                            </div>

                            <div class="faq-item">
                                <h3>What if I'm not satisfied with the work?</h3>
                                <div class="faq-content">
                                    <p>Your payment is held in escrow until you approve the completed work. If there are issues, our support team will work with the vendor to resolve them before any payment is released.</p>
                                </div>
                                <i class="faq-toggle bi bi-chevron-right"></i>
                            </div>

                            <div class="faq-item">
                                <h3>How can I contact support?</h3>
                                <div class="faq-content">
                                    <p>You can reach us via phone at +1 801-613-0482 or email at servicerequest@fixingtechs.com. We're here to help!</p>
                                </div>
                                <i class="faq-toggle bi bi-chevron-right"></i>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </section><!-- /FAQ Section -->

        <!-- ==================== CTA SECTION ==================== -->
        <section id="call-to-action" class="call-to-action section">
            <div class="container" data-aos="fade-up" data-aos-delay="100">
                <div class="row content justify-content-center align-items-center position-relative">
                    <div class="col-lg-8 mx-auto text-center">
                        <h2 class="display-4 mb-4">Ready to Start Your Project?</h2>
                        <p class="mb-4">Submit a service request today and let us connect you with vetted professionals. Transparent estimates, escrow protection, and real-time tracking -- all included.</p>
                        <a href="submit-request.php" class="btn btn-cta">Submit a Service Request</a>
                    </div>
                </div>
            </div>
        </section><!-- /CTA Section -->

    </main>

    <?php include "includes/footer.php" ?>

    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <?php include "includes/script.php" ?>

    <!-- Homepage Revamp JS -->
    <script src="../assets/js/homepage-revamp.js"></script>

</body>

</html>
