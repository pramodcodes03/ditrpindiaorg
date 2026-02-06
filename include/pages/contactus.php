<?php
$action = isset($_POST['submit_contact']) ? $_POST['submit_contact'] : '';
if ($action != '') {


    if (isset($_POST["verify_contact"]) && $_POST["verify_contact"] != "" && $_SESSION["code"] == $_POST["verify_contact"]) {
        $result = $access->contact();
        $result = json_decode($result, true);
        $success = isset($result['success']) ? $result['success'] : '';
        $message = isset($result['message']) ? $result['message'] : '';
        $errors = isset($result['errors']) ? $result['errors'] : '';
        if ($success == true) {
            $_SESSION['msg'] = $message;
            $_SESSION['msg_flag'] = $success;
            unset($_POST);
        }
    } else {
        $message = "Entered captcha content not matched! Please try again!";
        $success = false;
    }
}

?>
<?php
$res = $websiteManage->list_headimages('', '');
if ($res != '') {
    while ($data = $res->fetch_assoc()) {
        extract($data);
        $image = 'resources/default_images/about_default.jpg';
        if ($contact != '')
            $image     = BANNERS_PATH . '/' . $id . '/' . $contact;
?>
        <!-- Breadcrumbs Start -->
        <div class="rs-breadcrumbs bg7 breadcrumbs-overlay" style="background-image: url(<?= $image ?>);">
            <div class="breadcrumbs-inner">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <h1 class="page-title">Contact Us</h1>
                            <ul>
                                <li>
                                    <a class="active" href="index.php">Home</a>
                                </li>
                                <li>Contact Us</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div><!-- .breadcrumbs-inner end -->
        </div>
<?php
    }
}
?>
<!-- Breadcrumbs End -->
<style>
    /* Contact Form Section */
    .contact-comment-section {
        background: #fff;
        padding: 50px;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .contact-comment-section h3 {
        font-size: 32px;
        color: #2c3e50;
        margin-bottom: 30px;
        text-align: center;
        font-weight: 600;
    }

    /* Alert Messages */
    .alert {
        padding: 15px 20px;
        border-radius: 8px;
        margin-bottom: 25px;
        display: none;
        animation: slideDown 0.4s ease-out;
        position: relative;
    }

    .alert.show {
        display: block;
    }

    .alert-success {
        background: #d4edda;
        border: 1px solid #c3e6cb;
        color: #155724;
    }

    .alert-error {
        background: #f8d7da;
        border: 1px solid #f5c6cb;
        color: #721c24;
    }

    .alert i {
        margin-right: 10px;
    }

    .alert-close {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        font-size: 20px;
        line-height: 1;
        opacity: 0.5;
        transition: opacity 0.3s;
        background: none;
        border: none;
        color: inherit;
    }

    .alert-close:hover {
        opacity: 1;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Form Row */
    .contact-comment-section .row {
        display: flex;
        flex-wrap: wrap;
        margin: 0 -10px;
    }

    .contact-comment-section .row .col-md-6,
    .contact-comment-section .row .col-md-12 {
        padding: 0 10px;
        margin-bottom: 20px;
    }

    .contact-comment-section .row .col-md-6 {
        flex: 0 0 50%;
        max-width: 50%;
    }

    .contact-comment-section .row .col-md-12 {
        flex: 0 0 100%;
        max-width: 100%;
    }

    /* Form Group */
    .contact-comment-section .form-group {
        margin-bottom: 20px;
    }

    .contact-comment-section .form-group label {
        display: block;
        margin-bottom: 8px;
        color: #2c3e50;
        font-weight: 500;
        font-size: 14px;
    }

    .contact-comment-section .form-control {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 15px;
        transition: border-color 0.3s, box-shadow 0.3s;
        font-family: inherit;
    }

    .contact-comment-section .form-control:focus {
        outline: none;
        border-color: #4CAF50;
        box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.1);
    }

    .contact-comment-section .form-control.error {
        border-color: #e74c3c;
    }

    .contact-comment-section textarea.form-control {
        resize: vertical;
        min-height: 150px;
    }

    /* Checkbox Group */
    .contact-comment-section .form-group label input[type="checkbox"] {
        width: 18px;
        height: 18px;
        margin-right: 8px;
        cursor: pointer;
        accent-color: #4CAF50;
        vertical-align: middle;
    }

    .contact-comment-section .form-group label {
        cursor: pointer;
        user-select: none;
    }

    /* Submit Button */
    .contact-comment-section .btn-send {
        background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
        color: white;
        padding: 15px 40px;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: transform 0.3s, box-shadow 0.3s;
        width: 100%;
        max-width: 200px;
        display: block;
        margin: 30px auto 0;
    }

    .contact-comment-section .btn-send:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(76, 175, 80, 0.3);
    }

    .contact-comment-section .btn-send:active {
        transform: translateY(0);
    }

    .contact-comment-section .btn-send:disabled {
        background: #ccc;
        cursor: not-allowed;
        transform: none;
    }

    /* Loading Spinner */
    .spinner {
        display: inline-block;
        width: 16px;
        height: 16px;
        border: 2px solid #fff;
        border-radius: 50%;
        border-top-color: transparent;
        animation: spin 0.8s linear infinite;
        margin-left: 10px;
        vertical-align: middle;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    /* Responsive */
    @media (max-width: 768px) {
        .contact-comment-section {
            padding: 30px 20px;
        }

        .contact-comment-section h3 {
            font-size: 24px;
        }

        .contact-comment-section .row .col-md-6,
        .contact-comment-section .row .col-md-12 {
            flex: 0 0 100%;
            max-width: 100%;
        }

        .contact-comment-section .btn-send {
            max-width: 100%;
        }
    }
</style>

<div class="contact-comment-section">
    <h3>Contact Us Form</h3>

    <!-- Alert Messages Container -->
    <div id="form-messages"></div>

    <form id="contact-form" method="post" action="resources/mailer.php">
        <fieldset>
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                        <label>Institute Name*</label>
                        <input name="fname" id="fname" class="form-control" type="text" required>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                        <label>Owner Name*</label>
                        <input name="lname" id="lname" class="form-control" type="text" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                        <label>Email*</label>
                        <input name="email" id="email" class="form-control" type="email" required>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                        <label>Contact No *</label>
                        <input
                            name="subject"
                            id="subject"
                            class="form-control"
                            type="text"
                            required
                            pattern="[0-9]{10}"
                            maxlength="10"
                            minlength="10"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                            placeholder="Enter 10-digit number">
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div class="form-group">
                        <label>Message *</label>
                        <textarea cols="40" rows="10" id="message" name="message" class="textarea form-control" required></textarea>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>
                    <input type="checkbox" name="rcs_agree" id="rcs_agree" value="1" required>
                    I agree to receive messages for communication via RCS.
                </label>
            </div>
            <div class="form-group mb-0">
                <input class="btn-send" type="submit" value="Submit Now" id="submit-btn">
            </div>
        </fieldset>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('contact-form');
        const submitBtn = document.getElementById('submit-btn');
        const formMessages = document.getElementById('form-messages');

        // Show alert function
        function showAlert(message, type) {
            const alertHTML = `
            <div class="alert alert-${type} show">
                <button class="alert-close" type="button">&times;</button>
                <i class="fa fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
                <span>${message}</span>
            </div>
        `;
            formMessages.innerHTML = alertHTML;

            // Scroll to alert
            formMessages.scrollIntoView({
                behavior: 'smooth',
                block: 'nearest'
            });

            // Add close functionality
            const closeBtn = formMessages.querySelector('.alert-close');
            if (closeBtn) {
                closeBtn.addEventListener('click', function() {
                    const alert = formMessages.querySelector('.alert');
                    if (alert) {
                        alert.classList.remove('show');
                    }
                });
            }

            // Auto hide after 5 seconds
            setTimeout(() => {
                const alert = formMessages.querySelector('.alert');
                if (alert) {
                    alert.classList.remove('show');
                }
            }, 5000);
        }

        // Form validation
        function validateForm() {
            let isValid = true;
            const inputs = form.querySelectorAll('.form-control');

            inputs.forEach(input => {
                if (input.hasAttribute('required') && !input.value.trim()) {
                    input.classList.add('error');
                    isValid = false;
                } else {
                    input.classList.remove('error');
                }
            });

            // Email validation
            const emailInput = document.getElementById('email');
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (emailInput.value && !emailPattern.test(emailInput.value)) {
                emailInput.classList.add('error');
                isValid = false;
            }

            // Checkbox validation
            const checkbox = document.getElementById('rcs_agree');
            if (!checkbox.checked) {
                isValid = false;
                showAlert('Please agree to receive messages via RCS.', 'error');
            }

            return isValid;
        }

        // Form submission
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            // Clear previous alerts
            formMessages.innerHTML = '';

            // Validate form
            if (!validateForm()) {
                showAlert('Please fill in all required fields correctly.', 'error');
                return;
            }

            // Disable submit button and show loading
            submitBtn.disabled = true;
            const originalValue = submitBtn.value;
            submitBtn.value = 'Sending...';
            submitBtn.insertAdjacentHTML('afterend', '<span class="spinner"></span>');

            // Get form data
            const formData = new FormData(form);

            // Submit via AJAX
            fetch(form.action, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showAlert(data.message || 'Thank you! Your message has been sent successfully. We will get back to you soon.', 'success');
                        form.reset();
                    } else {
                        showAlert(data.message || 'Sorry, there was an error sending your message. Please try again.', 'error');
                    }
                })
                .catch(error => {
                    showAlert('An error occurred. Please try again later.', 'error');
                    console.error('Error:', error);
                })
                .finally(() => {
                    // Re-enable submit button
                    submitBtn.disabled = false;
                    submitBtn.value = originalValue;
                    const spinner = document.querySelector('.spinner');
                    if (spinner) {
                        spinner.remove();
                    }
                });
        });

        // Remove error class on input
        const inputs = form.querySelectorAll('.form-control');
        inputs.forEach(input => {
            input.addEventListener('input', function() {
                this.classList.remove('error');
            });
        });
    });
</script>
<!-- Contact Section End -->
<!-- Contact Section Start -->
<div class="contact-page-section sec-spacer">
    <div class="container">
        <?php
        $res = $websiteManage->list_contact('', '');
        if ($res != '') {
            while ($data = $res->fetch_assoc()) {
                extract($data);
        ?>
                <div>
                    <?= html_entity_decode($map); ?>
                </div>


                <div class="row contact-address-section">
                    <div class="col-md-4 pl-0">
                        <div class="contact-info contact-address">
                            <i class="fa fa-map-marker"></i>
                            <h4>Address</h4>
                            <p><?= $address ?></p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="contact-info contact-phone">
                            <i class="fa fa-phone"></i>
                            <h4>Phone Number</h4>
                            <a href="tel:<?= $contact_number1 ?>"><?= $contact_number1 ?></a>
                            <a href="tel:<?= $contact_number2 ?>"><?= $contact_number2 ?></a>
                        </div>
                    </div>
                    <div class="col-md-4 pr-0">
                        <div class="contact-info contact-email">
                            <i class="fa fa-envelope"></i>
                            <h4>Email Address</h4>
                            <a href="mailto:<?= $email_id ?>">
                                <p><?= $email_id ?></p>
                            </a>
                        </div>
                    </div>
                </div>
        <?php
            }
        }
        ?>