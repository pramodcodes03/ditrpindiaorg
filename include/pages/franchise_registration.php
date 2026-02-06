<?php
if (empty($_SESSION['csrf_token'])) {
	$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$action = isset($_POST['add_institute']) ? $_POST['add_institute'] : '';
if ($action != '') {


	if (isset($_POST["captcha"]) && $_POST["captcha"] != "") {

		include_once('include/classes/account.class.php');
		$account = new account();
		$result	= $account->add_institute();
		$result = json_decode($result, true);
		$success = isset($result['success']) ? $result['success'] : '';
		$message = isset($result['message']) ? $result['message'] : '';
		$errors = isset($result['errors']) ? $result['errors'] : '';

		if ($success == true) {
			$_SESSION['msg'] = $message;
			$_SESSION['msg_flag'] = $success;

			header('location:/FranchiseRegistrationSuccess');
		} else {
			$_SESSION['msg'] = $message;
			$_SESSION['msg_flag'] = $success;
		}
	} else {
		$message = "Entered captha content not matched! Please try again!";
		$success = false;
	}
}
?>
<!-- rs-check-out Here-->
<div class="rs-check-out sec-spacer">
	<div class="container">
		<div class="row">
			<div class="col-lg-12 col-md-12 form1">
				<h3 class="title-bg">Franchise Registration</h3>
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
								<input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
								<input id="instcode" name="instcode" value="<?= isset($_POST['instcode']) ? $_POST['instcode'] : $access->generate_institute_code() ?>" type="hidden" />
								<div class="row">
									<div class="form-group col-sm-6 <?= (isset($errors['instname'])) ? 'has-error' : '' ?>">
										<lable>Institution Name</lable>
										<input type="text" class=" form-control required" name="instname" placeholder="Institution Name" value="<?= isset($_POST['instname']) ? $_POST['instname'] : '' ?>" required="required" />
										<span class="input-icon"><i class=" icon-user"></i></span>
										<span class="help-block"><?= (isset($errors['instname'])) ? $errors['instname'] : '' ?></span>
									</div>
									<div class="form-group col-sm-6 <?= (isset($errors['instowner'])) ? 'has-error' : '' ?>">
										<lable>Center Owner Name</lable>
										<input type="text" class=" form-control required" name="instowner" placeholder="Center Owner Name" value="<?= isset($_POST['instowner']) ? $_POST['instowner'] : '' ?>" required="required" />
										<span class="input-icon"><i class="icon-user"></i></span>
										<span class="help-block"><?= (isset($errors['instowner'])) ? $errors['instowner'] : '' ?></span>
									</div>
								</div>
								<div class="row">
									<div class="form-group col-sm-6 <?= (isset($errors['designation'])) ? 'has-error' : '' ?>">
										<div class="custom-select">
											<lable>Select Designation</lable>
											<select class="form-control select2" name="designation" id="designation" required="required">
												<option>--Select Designation---</option>
												<?php
												$designation = isset($_POST['designation']) ? $_POST['designation'] : '';
												echo $db->MenuItemsDropdown('designation_master', 'DESIGNATION_ID', 'DESIGNATION', 'DESIGNATION_ID,DESIGNATION', $designation, ' ORDER BY DESIGNATION ASC');
												?>
											</select>
										</div>

										<span class="help-block"><?= (isset($errors['designation'])) ? $errors['designation'] : '' ?></span>
									</div>
									<div class="form-group col-sm-6 <?= (isset($errors['dob'])) ? 'has-error' : '' ?>">
										<lable>Date of Birth</lable>
										<input type="date" class="form-control required datepicker" name="dob" placeholder="Date of Birth" value="<?= isset($_POST['dob']) ? $_POST['dob'] : '' ?>" required="required" max="2999-12-31" />
										<span class="help-block"><?= (isset($errors['dob'])) ? $errors['dob'] : '' ?></span>
									</div>
								</div>
								<div class="row">
									<div class="form-group col-sm-6 <?= (isset($errors['email'])) ? 'has-error' : '' ?>">
										<lable>Email Address</lable>
										<input type="text" class=" form-control required" name="email" placeholder="Email Address" value="<?= isset($_POST['email']) ? $_POST['email'] : '' ?>" required="required" />
										<span class="input-icon"><i class=" icon-email"></i></span>
										<span class="help-block"><?= (isset($errors['email'])) ? $errors['email'] : '' ?></span>
									</div>
									<div class="form-group col-sm-6 <?= (isset($errors['mobile'])) ? 'has-error' : '' ?>">
										<lable>Mobile Number</lable>
										<input type="text" class=" form-control required" name="mobile" placeholder="Mobile Number" value="<?= isset($_POST['mobile']) ? $_POST['mobile'] : '' ?>" required="required" maxlength="10" />
										<span class="input-icon"><i class=" icon-mobile"></i></span>
										<span class="help-block"><?= (isset($errors['mobile'])) ? $errors['mobile'] : '' ?></span>
									</div>
									<div class="clearfix"></div>
								</div>
								<div class="row">
									<div class="form-group col-sm-6 <?= (isset($errors['address1'])) ? 'has-error' : '' ?>">
										<textarea type="text" class=" form-control required" name="address1" placeholder="Full Address (Example: Landmark, Road, Builiding/House no. etc.)" required="required"><?= isset($_POST['address1']) ? $_POST['address1'] : '' ?></textarea>
										<span class="help-block"><?= (isset($errors['address1'])) ? $errors['address1'] : '' ?></span>
									</div>
									<!-- <div class="form-group col-sm-6 <?= (isset($errors['address2'])) ? 'has-error' : '' ?>">
									<input type="text" class=" form-control required" name="address2" placeholder="Address Line 2" required="required" value="<?= isset($_POST['address2']) ? $_POST['address2'] : '' ?>" />
									<span class="help-block"><?= (isset($errors['address2'])) ? $errors['address2'] : '' ?></span>
								</div> -->

									<div class="form-group col-sm-6 <?= (isset($errors['taluka'])) ? 'has-error' : '' ?>">
										<input type="text" class=" form-control required" name="taluka" placeholder="Taluka Name" required="required" value="<?= isset($_POST['taluka']) ? $_POST['taluka'] : '' ?>" />
										<span class="help-block"><?= (isset($errors['taluka'])) ? $errors['taluka'] : '' ?></span>
									</div>
								</div>
								<div class="row">
									<div class="form-group col-sm-6 <?= (isset($errors['postcode'])) ? 'has-error' : '' ?>">
										<input type="text" class=" form-control required" name="postcode" placeholder="Postal Code" value="<?= isset($_POST['postcode']) ? $_POST['postcode'] : '' ?>" required="required" />
										<span class="help-block"><?= (isset($errors['postcode'])) ? $errors['postcode'] : '' ?></span>
									</div>
									<div class="form-group col-sm-6 <?= (isset($errors['state'])) ? 'has-error' : '' ?>">
										<div class="custom-select">
											<select class="form-control select2" name="state" id="state" onchange="getCitiesByState(this.value)" required="required">
												<option value="">--Select State---</option>
												<?php
												$state = isset($_POST['state']) ? $_POST['state'] : '';
												echo $db->MenuItemsDropdown('states_master', 'STATE_ID', 'STATE_NAME', 'STATE_ID,STATE_NAME', $state, ' ORDER BY STATE_NAME ASC'); ?>
											</select>
										</div>
										<span class="help-block"><?= (isset($errors['state'])) ? $errors['state'] : '' ?></span>
									</div>
								</div>
								<div class="row">
									<div class="form-group col-sm-6 <?= (isset($errors['city'])) ? 'has-error' : '' ?>">
										<input type="text" class=" form-control required" name="city" placeholder="City" value="<?= isset($_POST['city']) ? $_POST['city'] : '' ?>" required="required" />
										<span class="help-block"><?= (isset($errors['city'])) ? $errors['city'] : '' ?></span>
									</div>
									<div class="form-group col-sm-6 <?= (isset($errors['country'])) ? 'has-error' : '' ?>">
										<div class="custom-select">
											<select class="form-control select2" name="country_sel" id="country_sel" disabled>
												<?php
												$country = isset($_POST['country']) ? $_POST['country'] : 1;
												echo $db->MenuItemsDropdown('countries_master', 'COUNTRY_ID', 'COUNTRY_NAME', 'COUNTRY_ID,COUNTRY_NAME', $country, ' WHERE COUNTRY_ID=1'); ?>
											</select>
										</div>
										<span class="help-block"><?= (isset($errors['country'])) ? $errors['country'] : '' ?></span>
									</div>
								</div>

								<div class="row">
									<div class="form-group col-sm-6 <?= (isset($errors['no_of_comp'])) ? 'has-error' : '' ?>">
										<input type="text" class=" form-control required" name="no_of_comp" placeholder="Total number of computers" value="<?= isset($_POST['no_of_comp']) ? $_POST['no_of_comp'] : '' ?>" required="required" />
										<span class="input-icon"><i class=" icon-email"></i></span>
										<span class="help-block"><?= (isset($errors['no_of_comp'])) ? $errors['no_of_comp'] : '' ?></span>
									</div>
									<div class="form-group col-sm-6 <?= (isset($errors['no_of_staff'])) ? 'has-error' : '' ?>">
										<input type="text" class=" form-control required" name="no_of_staff" placeholder="Total number of staff" value="<?= isset($_POST['no_of_staff']) ? $_POST['no_of_staff'] : '' ?>" required="required" maxlength="10" />
										<span class="input-icon"><i class=" icon-mobile"></i></span>
										<span class="help-block"><?= (isset($errors['no_of_staff'])) ? $errors['no_of_staff'] : '' ?></span>
									</div>
									<div class="clearfix"></div>
								</div>


								<div class="row">
									<div class="form-group col-sm-12 <?= (isset($errors['location'])) ? 'has-error' : '' ?>">
										<input type="text" class=" form-control required" name="location" placeholder="Map Location" value="<?= isset($_POST['location']) ? $_POST['location'] : '' ?>" />
										<span class="input-icon"><i class=" icon-email"></i></span>
										<span class="help-block"><?= (isset($errors['location'])) ? $errors['location'] : '' ?></span>
									</div>

									<div class="form-group col-sm-6 <?= (isset($errors['latitude'])) ? 'has-error' : '' ?>">
										<input type="text" class=" form-control required" name="latitude" placeholder="Latitude" value="<?= isset($_POST['latitude']) ? $_POST['latitude'] : '' ?>" />
										<span class="input-icon"><i class=" icon-mobile"></i></span>
										<span class="help-block"><?= (isset($errors['latitude'])) ? $errors['latitude'] : '' ?></span>
									</div>

									<div class="form-group col-sm-6 <?= (isset($errors['longitude'])) ? 'has-error' : '' ?>">
										<input type="text" class=" form-control required" name="longitude" placeholder="Longitude" value="<?= isset($_POST['longitude']) ? $_POST['longitude'] : '' ?>" />
										<span class="input-icon"><i class=" icon-mobile"></i></span>
										<span class="help-block"><?= (isset($errors['longitude'])) ? $errors['longitude'] : '' ?></span>
									</div>

								</div>


								<div class="row">
									<!-- <div class="form-group col-sm-12 <?= (isset($errors['instdetails'])) ? 'has-error' : '' ?>">					
									<textarea class="form-control required" id="instdetails" name="instdetails" placeholder="Please provide details about Staff,Infrastructure, Current business, and Reason of joining DITRP" required="required"><?= isset($_POST['instdetails']) ? $_POST['instdetails'] : '' ?></textarea>
									<span class="help-block"><?= (isset($errors['instdetails'])) ? $errors['instdetails'] : '' ?></span>
								</div> -->
									<div class="form-group col-sm-12 <?= (isset($errors['agree'])) ? 'has-error' : '' ?>">
										<input type="checkbox" class="required" name="agree" checked="checked" disabled /> I agree to the terms and conditions for franchise.
										<span class="help-block"><?= (isset($errors['agree'])) ? $errors['agree'] : '' ?></span>
									</div>
									<div class="form-group col-sm-12 <?= (isset($errors['agree'])) ? 'has-error' : '' ?>">
										<input type="checkbox" class="required" name="agree" checked="checked" /> Please check this tick to agree to recieve SMS, Email, Call.
										<span class="help-block"><?= (isset($errors['agree'])) ? $errors['agree'] : '' ?></span>
									</div>
								</div>
								<div class="form-group col-sm-12 text-center">
									<label>Please Enter AMC Refferal Code (Not Mandatory) </label>
									<input type="text" name="amc_code" placeholder="AMC Code (If Any)" value="<?= isset($_POST['amc_code']) ? $_POST['amc_code'] : '' ?>">
								</div>
								<div class="form-group col-sm-12 text-center">
									<label>Enter the contents of image </label>
									<input name="captcha" type="text">
									<img src="<?= HTTP_HOST ?>/include/classes/captcha.php" />
								</div>
								<hr>
								<div class="text-center">
									<input type="submit" name="add_institute" class="btn btn-primary instSubmitBtn" value="Register" />
									<a href="<?= HTTP_HOST ?>/FranchiseRegistration" class="btn btn-danger instSubmitBtn">Cancel</a>
								</div>
								<br><br>
						</fieldset>
					</form>
				</div><!-- .check-out-box end -->
			</div>
		</div>
	</div>
</div>