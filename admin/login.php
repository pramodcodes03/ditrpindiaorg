<?php
include('include/common/html_header.php');
include('include/controller/account/login.php');
include('include/controller/account/logout.php');
include('include/controller/account/forgetpass.php');


ini_set('display_errors', 1);
?>

<style>
  .alert {
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 15px;
  }

  .alert-success {
    color: #155724;
    background-color: #d4edda;
    border-color: #c3e6cb;
  }

  .alert-danger {
    color: #721c24;
    background-color: #f8d7da;
    border-color: #f5c6cb;
  }

  .error {
    color: red;
    font-size: 0.9em;
  }

  .success {
    color: green;
    font-size: 0.9em;
  }
</style>

<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth px-0">
        <div class="row w-100 mx-0">
          <div class="col-lg-4 mx-auto">
            <div class="auth-form-light text-left py-5 px-4 px-sm-5">
              <div class="brand-logo">
                <img src="<?= $logo ?>" alt="logo">
              </div>
              <h4>Welcome! let's get started</h4>
              <h6 class="font-weight-light">Sign in to continue.</h6>

              <?php
              if (isset($success)) {
              ?>
                <div class="row">
                  <div class="col-sm-12">
                    <div class="alert alert-<?= ($success == true) ? 'success' : 'danger' ?> alert-dismissible" id="messages">
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>
                      <h4><i class="icon fa fa-check"></i> <?= ($success == true) ? 'Success' : 'Error' ?>:</h4>
                      <?= isset($errors['message']) ? $errors['message'] : 'Please correct the errors.'; ?>
                    </div>
                  </div>
                </div>
              <?php
              }
              ?>

              <?php
              //forget password error message
              if (isset($success_f)) {
              ?>
                <div class="row">
                  <div class="col-sm-12">
                    <div class="alert alert-<?= ($success_f == true) ? 'success' : 'danger' ?> alert-dismissible" id="messages">
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>
                      <h4><i class="icon fa fa-check"></i> <?= ($success_f == true) ? 'Success' : 'Error' ?>:</h4>
                      <?= isset($message_f) ? $message_f : "Please enter valid forget password form details. <a href='javascript:void(0)' class='btn btn-link' title='Forgot Password' data-toggle='modal' data-target='.forgot-pass'>Try again!</a>"; ?>
                    </div>
                  </div>
                </div>
              <?php
              }
              ?>
              <form class="pt-3" action="" method="post">
                <div class="form-group">
                  <input type="text" class="form-control form-control-lg" id="exampleInputEmail1" placeholder="Username" name="uname">
                  <span class="help-block"><?= isset($errors['uname']) ? $errors['uname'] : '' ?></span>
                </div>
                <div class="form-group">
                  <input type="password" class="form-control form-control-lg" id="exampleInputPassword1" placeholder="Password" name="pword">
                  <span class="help-block"><?= isset($errors['pword']) ? $errors['pword'] : '' ?></span>
                </div>
                <div class="mt-3">
                  <input type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" name="login" value="SIGN IN" />
                </div>
                <div class="my-2 d-flex justify-content-between align-items-center">

                  <a href='javascript:void(0)' href="#" class="auth-link text-black" title='Forgot Password' data-toggle='modal' data-target='.forgot-pass'>Forgot password?</a>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  <!-- plugins:js -->
  <script src="/admin/resources/vendors/js/vendor.bundle.base.js"></script>
  <!-- endinject -->
  <!-- Plugin js for this page -->
  <!-- End plugin js for this page -->
  <!-- inject:js -->
  <script src="/admin/resources/js/off-canvas.js"></script>
  <script src="/admin/resources/js/hoverable-collapse.js"></script>
  <script src="/admin/resources/js/template.js"></script>
  <script src="/admin/resources/js/settings.js"></script>
  <script src="/admin/resources/js/todolist.js"></script>
  <!-- endinject -->

  <div class="modal fade forgot-pass" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="box box-primary modal-body">
          <div>
            <div class="box-header with-border">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h3 class="box-title">Forgot Password</h3>
            </div>
            <div id="modal-message" class="alert" style="display: none; margin-top: 10px;"></div>
            <!-- /.box-header -->
            <form id="forgot-password-form" action="forgot_password.php" onsubmit="return validatePassword()" method="post">
              <input type="hidden" name="action" id="action" value="send_otp" />
              <div class="box-body">
                <!-- Step 1: Select User Type and Enter Email -->
                <div id="step-1">
                  <div class="form-group">
                    <label>Select User Type:</label>
                    <select class="form-control" name="role">
                      <?php
                      echo $db->MenuItemsDropdown('user_role_master', 'USER_ROLE_ID', 'STATUS_NAME', 'USER_ROLE_ID,STATUS_NAME', '8', ' WHERE USER_ROLE_ID NOT IN (1,6,3,4,5,7,2)');
                      ?>
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Registered Enter Email:</label>
                    <input class="form-control" placeholder="Enter email address" id="email" name="email" type="email" required>
                  </div>
                </div>

                <!-- Step 2: Enter OTP -->
                <div id="step-2" style="display: none;">
                  <div class="form-group">
                    <label>Enter OTP:</label>
                    <input class="form-control" placeholder="Enter OTP" id="otp" name="otp" type="text" required>
                  </div>
                </div>

                <!-- Step 3: Set New Password -->
                <div id="step-3" style="display: none;">
                  <div class="form-group">
                    <label>New Password:</label>
                    <input class="form-control" placeholder="New Password" oninput="validatePassword()" id="new-password" name="new_password" type="password" required>
                    <div id="error-message" class="error"></div>
                  </div>
                  <div class="form-group">
                    <label>Confirm Password:</label>
                    <input class="form-control" placeholder="Confirm Password" id="confirm-password" name="confirm_password" type="password" required>
                  </div>
                </div>
              </div>

              <!-- Footer -->
              <div class="box-footer">
                <div class="pull-right">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                  <button type="button" id="next-button" class="btn btn-primary">Next</button>
                  <button type="submit" id="submit-button" class="btn btn-primary" style="display: none;">Submit</button>
                </div>
              </div>
            </form>
            <!-- /.box-footer -->
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade maintainance_modal" tabindex="-1" role="dialog" aria-labelledby="maintainance_modalModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content" style="border-radius: 15px; overflow: hidden; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);">
        <div class="box-header with-border" style="background-color: #d80027; color: white; padding: 15px; display: flex; justify-content: space-between; align-items: center;">
          <h5 class="modal-title" id="maintainance_modalModalLabel" style="margin: 0; font-size: 20px; font-weight: bold;">
            Scheduled Maintenance
          </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="font-size: 24px; color: white; background: none; border: none; cursor: pointer;">&times;</button>
        </div>
        <div class="modal-body" style="padding: 30px; font-family: Arial, sans-serif; background-color: #fff7f8;">
          <div style="text-align: center; padding: 20px; border-radius: 10px; background-color: #ffe5e9; border: 2px solid #f5c2c7;">
            <h1 style="font-size: 24px; font-weight: bold; color: #d80027; margin-bottom: 15px;">🚧 Scheduled Maintenance Alert 🚧</h1>
            <p style="font-size: 18px; color: #5a1f2c; margin-bottom: 15px;">
              Dear Users,<br>
              Our login service will be <strong>temporarily unavailable</strong> on <strong>Monday, 27th January 2025</strong>, between <strong>10:00 AM and 2:00 PM</strong>.
            </p>
            <p style="font-size: 18px; color: #5a1f2c; margin-bottom: 15px;">
              This downtime is necessary to perform essential maintenance and upgrades to improve your experience.
            </p>
            <p style="font-size: 18px; font-weight: bold; color: #d80027;">
              We apologize for any inconvenience and appreciate your understanding.
            </p>
            <p style="margin-top: 20px; font-size: 16px; font-style: italic; color: #5a1f2c;">
              Thank you for being with us! — <strong>The DITRP Team</strong>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>



  <script>
    document.addEventListener('DOMContentLoaded', () => {
      let step = 1;

      const form = document.getElementById('forgot-password-form');
      const nextButton = document.getElementById('next-button');
      const submitButton = document.getElementById('submit-button');
      const messageBox = document.getElementById('modal-message');

      function showMessage(type, message) {
        messageBox.style.display = 'block';
        messageBox.className = `alert alert-${type}`;
        messageBox.textContent = message;
      }

      function clearMessage() {
        messageBox.style.display = 'none';
        messageBox.textContent = '';
      }

      nextButton.addEventListener('click', () => {
        clearMessage();
        if (step === 1) {
          const formData = new FormData(form);

          // Send OTP
          fetch('forgot_password.php', {
              method: 'POST',
              body: formData,
            })
            .then((response) => response.json())
            .then((data) => {
              if (data.status === 'success') {
                showMessage('success', data.message); // Display success message
                step = 2;
                document.getElementById('action').value = 'verify_otp';
                document.getElementById('step-1').style.display = 'none';
                document.getElementById('step-2').style.display = 'block';
              } else if (data.status === 'error') {
                showMessage('danger', data.message); // Display error message from backend
              }
            })
            .catch((error) => {
              showMessage('danger', 'An error occurred. Please try again.'); // Catch network or server errors
              console.error(error);
            });
        } else if (step === 2) {
          const formData = new FormData(form);

          // Verify OTP
          fetch('forgot_password.php', {
              method: 'POST',
              body: formData,
            })
            .then((response) => response.json())
            .then((data) => {
              if (data.status === 'success') {
                showMessage('success', data.message); // Display success message
                step = 3;
                document.getElementById('action').value = 'reset_password';
                document.getElementById('step-2').style.display = 'none';
                document.getElementById('step-3').style.display = 'block';
                nextButton.style.display = 'none';
                submitButton.style.display = 'block';
              } else if (data.status === 'error') {
                showMessage('danger', data.message); // Display error message from backend
              }
            })
            .catch((error) => {
              showMessage('danger', 'An error occurred. Please try again.'); // Catch network or server errors
              console.error(error);
            });
        }
      });

      form.addEventListener('submit', (event) => {
        event.preventDefault();
        clearMessage();

        const formData = new FormData(form);

        // Submit new password
        fetch('forgot_password.php', {
            method: 'POST',
            body: formData,
          })
          .then((response) => response.json())
          .then((data) => {
            if (data.status === 'success') {
              showMessage('success', data.message); // Display success message
              setTimeout(() => {
                $('.forgot-pass').modal('hide'); // Hide modal after success
              }, 2000);
            } else if (data.status === 'error') {
              showMessage('danger', data.message); // Display error message from backend
            }
          })
          .catch((error) => {
            showMessage('danger', 'An error occurred. Please try again.'); // Catch network or server errors
            console.error(error);
          });
      });
    });

    function validatePassword() {
      const password = document.getElementById('new-password').value;
      const errorMessage = document.getElementById('error-message');
      const specialCharPattern = /[!@#$%^&*(),.?":{}|<>]/;
      const digitPattern = /\d/g;

      errorMessage.textContent = ''; // Clear previous error message

      if (password.length < 8) {
        errorMessage.textContent = 'Password must be at least 8 characters long.';
        return false;
      }
      if (!specialCharPattern.test(password)) {
        errorMessage.textContent = 'Password must contain at least one special character.';
        return false;
      }
      if ((password.match(digitPattern) || []).length < 3) {
        errorMessage.textContent = 'Password must contain at least three numbers.';
        return false;
      }

      errorMessage.textContent = 'Password is valid.';
      errorMessage.className = 'success';
      return true;
    }
  </script>

  <!-- <script>
    document.addEventListener('DOMContentLoaded', () => {
      // Initialize and show the modal
      $('.maintainance_modal').modal({
        // backdrop: 'static', // Prevent closing by clicking outside
        // keyboard: false // Prevent closing with the Escape key
      });

      $('.maintainance_modal').modal('show'); // Show the modal when the page loads
    });
  </script> -->


</body>

</html>