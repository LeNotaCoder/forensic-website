<?php include 'header.php'; ?>

<main id="main" class="mt-5 pt-5">
    <div class="container py-5">
        <h1 class="text-center fw-bold mb-4">Contact Us</h1>
        <p class="text-center">We’re here to assist you with any inquiries or bookings related to our forensic pathology services. Please fill out the form below.</p>

        <div class="row justify-content-center mt-4">
            <div class="col-md-8">
                <form id="contactForm" method="post" action="contact.php">
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>

                    <div class="mb-3">
                        <label for="subject" class="form-label">Subject</label>
                        <input type="text" class="form-control" id="subject" name="subject" required>
                    </div>

                    <div class="mb-3">
                        <label for="message" class="form-label">Message</label>
                        <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary btn-lg">Send Message</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<?php include 'footer.php'; ?>
