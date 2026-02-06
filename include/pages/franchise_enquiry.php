<?php
if (empty($_SESSION['csrf_token'])) {
	$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$action = isset($_POST['add_institute']) ? $_POST['add_institute'] : '';

if ($action != '') {
	include_once('include/classes/account.class.php');
	$account = new account();
	$result	= $account->add_institute_enquiry();
	$result = json_decode($result, true);
	$success = isset($result['success']) ? $result['success'] : '';
	$message = isset($result['message']) ? $result['message'] : '';
	$errors = isset($result['errors']) ? $result['errors'] : '';
	if ($success == true) {
		$_SESSION['msg'] = $message;
		$_SESSION['msg_flag'] = $success;

		//header('location:FranchiseEnquirySuccess');
	}
}
?>
<!-- rs-check-out Here-->
<div class="rs-check-out sec-spacer">
	<div class="container">
		<div class="row">
			<div class="col-lg-12 col-md-12 form1">
				<h3 class="title-bg">Franchise Enquiry</h3>
				<?php

				if (isset($_SESSION['msg'])) {

					$message = isset($_SESSION['msg']) ? $_SESSION['msg'] : '';

					$msg_flag = $_SESSION['msg_flag'];

				?>

					<div class="row">

						<div class="col-sm-12">

							<div class="alert alert-<?= ($msg_flag == true) ? 'success' : 'danger' ?> alert-dismissible" id="messages">

								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>

								<h4><i class="icon fa fa-check"></i> <?= ($msg_flag == true) ? 'Success' : 'Error' ?>:</h4>

								<?= ($message != '') ? $message : 'Sorry! Something went wrong!'; ?>

							</div>

						</div>

					</div>

				<?php

					unset($_SESSION['msg']);

					unset($_SESSION['msg_flag']);
				}

				?>
				<div class="check-out-box">
					<form id="contact-form" method="post">
						<fieldset>
							<div class="row">
								<!-- CSRF Token -->
								<input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8') ?>">

								<div class="row">
									<div class="form-group col-sm-6 <?= isset($errors['instname']) ? 'has-error' : '' ?>">
										<label for="instname">Institution Name</label>
										<input type="text" class="form-control required" name="instname" id="instname"
											placeholder="Institution Name"
											value="<?= htmlspecialchars($_POST['instname'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
											maxlength="100" pattern="[a-zA-Z0-9\s]+"
											title="Only letters, numbers, and spaces are allowed" required>
										<span class="help-block"><?= $errors['instname'] ?? '' ?></span>
									</div>
									<div class="form-group col-sm-6 <?= isset($errors['instowner']) ? 'has-error' : '' ?>">
										<label for="instowner">Center Owner Name</label>
										<input type="text" class="form-control required" name="instowner" id="instowner"
											placeholder="Center Owner Name"
											value="<?= htmlspecialchars($_POST['instowner'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
											maxlength="100" pattern="[a-zA-Z\s]+"
											title="Only letters and spaces are allowed" required>
										<span class="help-block"><?= $errors['instowner'] ?? '' ?></span>
									</div>
								</div>

								<div class="row">
									<div class="form-group col-sm-6 <?= isset($errors['email']) ? 'has-error' : '' ?>">
										<label for="email">Email Address</label>
										<input type="email" class="form-control required" name="email" id="email"
											placeholder="Email Address"
											value="<?= htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
										<span class="help-block"><?= $errors['email'] ?? '' ?></span>
									</div>
									<div class="form-group col-sm-6 <?= isset($errors['mobile']) ? 'has-error' : '' ?>">
										<label for="mobile">Mobile Number</label>
										<input type="tel" class="form-control required" name="mobile" id="mobile"
											placeholder="Mobile Number"
											value="<?= htmlspecialchars($_POST['mobile'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
											maxlength="10" pattern="\d{10}"
											title="Mobile number must be 10 digits" required>
										<span class="help-block"><?= $errors['mobile'] ?? '' ?></span>
									</div>
								</div>

								<div class="row">
									<div class="form-group col-sm-6 <?= isset($errors['address1']) ? 'has-error' : '' ?>">
										<label for="address1">Full Address</label>
										<textarea class="form-control required" name="address1" id="address1"
											placeholder="Full Address (Landmark, Road, Building/House No., etc.)"
											maxlength="255" required><?= htmlspecialchars($_POST['address1'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
										<span class="help-block"><?= $errors['address1'] ?? '' ?></span>
									</div>
									<div class="form-group col-sm-6 <?= isset($errors['taluka']) ? 'has-error' : '' ?>">
										<label for="taluka">Taluka Name</label>
										<input type="text" class="form-control required" name="taluka" id="taluka"
											placeholder="Taluka Name"
											value="<?= htmlspecialchars($_POST['taluka'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
											maxlength="100" pattern="[a-zA-Z\s]+"
											title="Only letters and spaces are allowed" required>
										<span class="help-block"><?= $errors['taluka'] ?? '' ?></span>
									</div>
								</div>

								<div class="row">
									<div class="form-group col-sm-6 <?= isset($errors['postcode']) ? 'has-error' : '' ?>">
										<label for="postcode">Postal Code</label>
										<input type="text" class="form-control required" name="postcode" id="postcode"
											placeholder="Postal Code"
											value="<?= htmlspecialchars($_POST['postcode'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
											maxlength="6" pattern="\d{6}"
											title="Postal code must be 6 digits" required>
										<span class="help-block"><?= $errors['postcode'] ?? '' ?></span>
									</div>
									<div class="form-group col-sm-6 <?= isset($errors['state']) ? 'has-error' : '' ?>">
										<label for="state">State</label>
										<select class="form-control select2" name="state" id="state"
											onchange="getCitiesByState(this.value)" required>
											<option value="">--Select State--</option>
											<?php
											$state = htmlspecialchars($_POST['state'] ?? '', ENT_QUOTES, 'UTF-8');
											echo $db->MenuItemsDropdown('states_master', 'STATE_ID', 'STATE_NAME', 'STATE_ID,STATE_NAME', $state, ' ORDER BY STATE_NAME ASC');
											?>
										</select>
										<span class="help-block"><?= $errors['state'] ?? '' ?></span>
									</div>
								</div>

								<div class="row">
									<div class="form-group col-sm-6 <?= isset($errors['city']) ? 'has-error' : '' ?>">
										<label for="city">City</label>
										<input type="text" class="form-control required" name="city" id="city"
											placeholder="City"
											value="<?= htmlspecialchars($_POST['city'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
											maxlength="100" pattern="[a-zA-Z\s]+"
											title="Only letters and spaces are allowed" required>
										<span class="help-block"><?= $errors['city'] ?? '' ?></span>
									</div>
									<div class="form-group col-sm-6 <?= isset($errors['country']) ? 'has-error' : '' ?>">
										<label for="country_sel">Country</label>
										<select class="form-control select2" name="country_sel" id="country_sel" disabled>
											<?php
											$country = htmlspecialchars($_POST['country'] ?? 1, ENT_QUOTES, 'UTF-8');
											echo $db->MenuItemsDropdown('countries_master', 'COUNTRY_ID', 'COUNTRY_NAME', 'COUNTRY_ID,COUNTRY_NAME', $country, ' WHERE COUNTRY_ID=1');
											?>
										</select>
										<span class="help-block"><?= $errors['country'] ?? '' ?></span>
									</div>
								</div>

								<div class="row">
									<div class="form-group col-sm-6 <?= isset($errors['latitude']) ? 'has-error' : '' ?>">
										<label for="latitude">Latitude</label>
										<input type="text" class="form-control required" name="latitude" id="latitude"
											placeholder="Latitude"
											value="<?= htmlspecialchars($_POST['latitude'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
											maxlength="15"
											title="Enter a valid latitude (e.g., 12.345678)" required>
										<span class="help-block"><?= $errors['latitude'] ?? '' ?></span>
									</div>
									<div class="form-group col-sm-6 <?= isset($errors['longitude']) ? 'has-error' : '' ?>">
										<label for="longitude">Longitude</label>
										<input type="text" class="form-control required" name="longitude" id="longitude"
											placeholder="Longitude"
											value="<?= htmlspecialchars($_POST['longitude'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
											maxlength="15"
											title="Enter a valid longitude (e.g., 123.456789)" required>
										<span class="help-block"><?= $errors['longitude'] ?? '' ?></span>
									</div>
								</div>

								<hr>
								<div class="text-center">
									<input type="submit" name="add_institute" class="btn btn-primary instSubmitBtn" value="Submit">
									<a href="<?= HTTP_HOST ?>/FranchiseEnquiry" class="btn btn-danger instSubmitBtn">Cancel</a>
								</div>
							</div>
						</fieldset>
					</form>

				</div><!-- .check-out-box end -->
			</div>
		</div>
	</div>
</div>