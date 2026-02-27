<!DOCTYPE html>
<html lang="en">
<?php session_start(); ?>
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Submit a Service Request | Quick Property Services</title>
  <link href="../assets/img/logo.jpg" rel="icon">
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="../assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="../assets/css/main.css" rel="stylesheet">
</head>
<body class="index-page">
    <?php include "includes/header.php" ?>

    <main class="main">

        <section id="contact" class="contact section light-background">
            <div class="container" data-aos="fade-up" data-aos-delay="100">

                <div class="row g-4 g-lg-5 justify-content-center">
                    <div class="col-lg-7 col-md-9 mx-auto">
                        <div class="contact-form p-4 shadow rounded" data-aos="fade-up" data-aos-delay="300">

                            <div class="text-center mb-4">
                                <h3 class="mb-2">Submit a Service Request</h3>
                                <p class="text-muted">Describe your project and we'll connect you with qualified vendors.</p>
                            </div>

                            <?php if (isset($_GET['success']) && $_GET['success'] === 'RequestSubmitted' && isset($_GET['code'])): ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="bi bi-check-circle me-2"></i>
                                    <strong>Your request has been submitted!</strong><br>
                                    Your tracking code is: <strong><?php echo htmlspecialchars($_GET['code']); ?></strong><br>
                                    Save this code to track your request.
                                    <a href="track.php?code=<?php echo htmlspecialchars($_GET['code']); ?>" class="alert-link">Track your request now</a>.
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif; ?>

                            <?php if (isset($_GET['error'])): ?>
                                <?php if ($_GET['error'] === 'MissingFields'): ?>
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <i class="bi bi-exclamation-triangle me-2"></i>
                                        <strong>Please fill in all required fields.</strong>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                <?php elseif ($_GET['error'] === 'SubmissionFailed'): ?>
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <i class="bi bi-exclamation-triangle me-2"></i>
                                        <strong>Something went wrong. Please try again.</strong>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                <?php elseif ($_GET['error'] === 'WrongPassword'): ?>
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <i class="bi bi-exclamation-triangle me-2"></i>
                                        <strong>An account with this email already exists but the password is incorrect.</strong> Please enter the correct password or use a different email.
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>

                            <?php
                            require '../admin/pages/scripts/connection.php';
                            $categories = $conn->query("SELECT category_id, name FROM category ORDER BY name");
                            ?>

                            <form action="scripts/submit-request.php" method="POST" enctype="multipart/form-data">
                                <div class="row gy-3">

                                    <div class="col-12">
                                        <label for="homeowner_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="homeowner_name" name="homeowner_name" placeholder="Your full name" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" id="email" name="email" placeholder="you@example.com" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="phone" name="phone" placeholder="(555) 123-4567" required>
                                    </div>

                                    <div class="col-12">
                                        <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Create a password to track your requests" required minlength="6">
                                        <div class="form-text">Already have an account? Use the same email and password to link this request.</div>
                                    </div>

                                    <div class="col-12">
                                        <label for="address" class="form-label">Property Address <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="address" name="address" placeholder="Full property address" required>
                                    </div>

                                    <div class="col-12">
                                        <label for="category_id" class="form-label">Service Category</label>
                                        <select class="form-select" id="category_id" name="category_id">
                                            <option value="">-- Select a category (optional) --</option>
                                            <?php if ($categories && $categories->num_rows > 0): ?>
                                                <?php while ($cat = $categories->fetch_assoc()): ?>
                                                    <option value="<?php echo (int)$cat['category_id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                                                <?php endwhile; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>

                                    <div class="col-12">
                                        <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                                        <textarea class="form-control" id="description" name="description" rows="5" placeholder="Describe the work needed..." required></textarea>
                                    </div>

                                    <div class="col-12">
                                        <label for="media" class="form-label">Photos / Videos</label>
                                        <input type="file" class="form-control" id="media" name="media[]" multiple accept=".jpg,.jpeg,.png,.mp4,.mov">
                                        <div class="form-text">Upload photos or videos (JPG, PNG, MP4, MOV - Max 10MB images, 50MB videos)</div>
                                    </div>

                                    <div class="col-12 text-center mt-4">
                                        <button type="submit" class="btn btn-primary w-100">Submit Request</button>
                                    </div>

                                </div>
                            </form>

                        </div>
                    </div>
                </div>

            </div>
        </section>

    </main>

    <?php include "includes/footer.php" ?>

    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <?php include "includes/script.php" ?>
</body>
</html>
