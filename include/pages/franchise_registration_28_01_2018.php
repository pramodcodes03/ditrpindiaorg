<?php
$action = isset($_POST['add_institute']) ? $_POST['add_institute'] : '';
if ($action != '') {


	if (isset($_POST["captcha"]) && $_POST["captcha"] != "" && $_SESSION["code"] == $_POST["captcha"]) {

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

			header('location:' . HTTP_HOST . '/franchise-registration-success');
		}
	} else {
		$message = "Entered captha content not matched! Please try again!";
		$success = false;
	}
}

?>
<div class="banner1">
	<div class="w3_agileits_service_banner_info">
		<h2>Franchise Registration</h2>
	</div>
</div>

<div class="container">
	<form action="" method="post" enctype="multipart/form-data">
		<div class="row">
			<br>
			<div class="col-md-12 col-sm-8">
				<div id="login">

					<h3 class="w3l_header w3_agileits_header1">APPLY FOR AUTHORISED TRAINING CENTRE (ATC) -<span>DITRP</span></h3>
					<hr>
					<?php
					if (isset($success)) {
					?>
						<div class="row">
							<div class="col-sm-12">
								<div class="alert alert-<?= ($success == true) ? 'success' : 'danger' ?> alert-dismissible" id="messages">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>
									<h4><i class="icon fa fa-check"></i> <?= ($success == true) ? 'Success' : 'Error' ?>:</h4>
									<?= isset($message) ? $message : 'Please correct the errors.'; ?>
									<?php
									if (isset($errors) && !empty($errors)) {
										echo '<ul>';
										foreach ($errors as $err) {
											echo '<li>' . $err . '</li>';
										}
										echo '</ul>';
									}
									?>
								</div>
							</div>
						</div>
					<?php
					}
					?>
					<input id="instcode" name="instcode" value="<?= isset($_POST['instcode']) ? $_POST['instcode'] : $access->generate_institute_code() ?>" type="hidden" />
					<div class="row">
						<div class="form-group col-sm-6 <?= (isset($errors['instname'])) ? 'has-error' : '' ?>">
							<input type="text" class=" form-control required" name="instname" placeholder="Institution/Academy/Center/Organisation" value="<?= isset($_POST['instname']) ? $_POST['instname'] : '' ?>" required="required" />
							<span class="input-icon"><i class=" icon-user"></i></span>
							<span class="help-block"><?= (isset($errors['instname'])) ? $errors['instname'] : '' ?></span>
						</div>
						<div class="form-group col-sm-6 <?= (isset($errors['instowner'])) ? 'has-error' : '' ?>">
							<input type="text" class=" form-control required" name="instowner" placeholder="Contact person name" value="<?= isset($_POST['instowner']) ? $_POST['instowner'] : '' ?>" required="required" />
							<span class="input-icon"><i class="icon-user"></i></span>
							<span class="help-block"><?= (isset($errors['instowner'])) ? $errors['instowner'] : '' ?></span>
						</div>
					</div>
					<div class="row">
						<div class="form-group col-sm-6 <?= (isset($errors['designation'])) ? 'has-error' : '' ?>">
							<select class="form-control required" name="designation" id="designation" required="required">
								<option>--Select Designation---</option>
								<?php
								$designation = isset($_POST['designation']) ? $_POST['designation'] : '';
								echo $db->MenuItemsDropdown('designation_master', 'DESIGNATION_ID', 'DESIGNATION', 'DESIGNATION_ID,DESIGNATION', $designation, ' ORDER BY DESIGNATION ASC');
								?>
							</select>

							<span class="help-block"><?= (isset($errors['designation'])) ? $errors['designation'] : '' ?></span>
						</div>
						<div class="form-group col-sm-6 <?= (isset($errors['dob'])) ? 'has-error' : '' ?>">
							<input type="text" class="form-control required datepicker" name="dob" placeholder="Date of Birth" value="<?= isset($_POST['dob']) ? $_POST['dob'] : '' ?>" required="required" />
							<span class="help-block"><?= (isset($errors['dob'])) ? $errors['dob'] : '' ?></span>
						</div>
					</div>
					<div class="row">
						<div class="form-group col-sm-6 <?= (isset($errors['email'])) ? 'has-error' : '' ?>">
							<input type="text" class=" form-control required" name="email" placeholder="Email address" value="<?= isset($_POST['email']) ? $_POST['email'] : '' ?>" required="required" />
							<span class="input-icon"><i class=" icon-email"></i></span>
							<span class="help-block"><?= (isset($errors['email'])) ? $errors['email'] : '' ?></span>
						</div>
						<div class="form-group col-sm-6 <?= (isset($errors['mobile'])) ? 'has-error' : '' ?>">
							<input type="text" class=" form-control required" name="mobile" placeholder="Mobile Number" value="<?= isset($_POST['mobile']) ? $_POST['mobile'] : '' ?>" required="required" maxlength="10" />
							<span class="input-icon"><i class=" icon-mobile"></i></span>
							<span class="help-block"><?= (isset($errors['mobile'])) ? $errors['mobile'] : '' ?></span>
						</div>
						<div class="clearfix"></div>
					</div>
					<div class="row">
						<div class="form-group col-sm-6 <?= (isset($errors['address1'])) ? 'has-error' : '' ?>">
							<input type="text" class=" form-control required" name="address1" placeholder="Address Line 1" required="required" value="<?= isset($_POST['address1']) ? $_POST['address1'] : '' ?>" />
							<span class="help-block"><?= (isset($errors['address1'])) ? $errors['address1'] : '' ?></span>
						</div>
						<div class="form-group col-sm-6 <?= (isset($errors['address2'])) ? 'has-error' : '' ?>">
							<input type="text" class=" form-control required" name="address2" placeholder="Address Line 2" required="required" value="<?= isset($_POST['address2']) ? $_POST['address2'] : '' ?>" />
							<span class="help-block"><?= (isset($errors['address2'])) ? $errors['address2'] : '' ?></span>
						</div>
					</div>
					<div class="row">
						<div class="form-group col-sm-6 <?= (isset($errors['taluka'])) ? 'has-error' : '' ?>">
							<input type="text" class=" form-control required" name="taluka" placeholder="Taluka Name" required="required" value="<?= isset($_POST['taluka']) ? $_POST['taluka'] : '' ?>" />
							<span class="help-block"><?= (isset($errors['taluka'])) ? $errors['taluka'] : '' ?></span>
						</div>
						<div class="form-group col-sm-6 <?= (isset($errors['postcode'])) ? 'has-error' : '' ?>">
							<input type="text" class=" form-control required" name="postcode" placeholder="Postal Code" value="<?= isset($_POST['postcode']) ? $_POST['postcode'] : '' ?>" required="required" />
							<span class="help-block"><?= (isset($errors['postcode'])) ? $errors['postcode'] : '' ?></span>
						</div>
					</div>
					<div class="row">
						<div class="form-group col-sm-6 <?= (isset($errors['state'])) ? 'has-error' : '' ?>">
							<select class="form-control select2" name="state" id="state" onchange="getCitiesByState(this.value)" required="required">
								<option>--Select State---</option>
								<?php
								$state = isset($_POST['state']) ? $_POST['state'] : '';
								echo $db->MenuItemsDropdown('states_master', 'STATE_ID', 'STATE_NAME', 'STATE_ID,STATE_NAME', $state, ' ORDER BY STATE_NAME ASC'); ?>
							</select>
							<span class="help-block"><?= (isset($errors['state'])) ? $errors['state'] : '' ?></span>
						</div>
						<div class="form-group col-sm-6 <?= (isset($errors['city'])) ? 'has-error' : '' ?>">
							<select class="form-control select2" name="city" id="city" required="required">
								<option>-- Select Taluka | District ---</option>
								<?php
								$city = isset($_POST['city']) ? $_POST['city'] : '';
								echo $db->MenuItemsDropdown('city_master', 'CITY_ID', 'CITY_NAME', 'CITY_ID,CITY_NAME', $city, ' ORDER BY CITY_NAME ASC'); ?>
							</select>
							<span class="help-block"><?= (isset($errors['city'])) ? $errors['city'] : '' ?></span>
						</div>
					</div>
					<div class="row">
						<div class="form-group col-sm-6 <?= (isset($errors['country'])) ? 'has-error' : '' ?>">
							<select class="form-control" name="country_sel" id="country_sel" disabled>
								<?php
								$country = isset($_POST['country']) ? $_POST['country'] : 1;
								echo $db->MenuItemsDropdown('countries_master', 'COUNTRY_ID', 'COUNTRY_NAME', 'COUNTRY_ID,COUNTRY_NAME', $country, ' WHERE COUNTRY_ID=1'); ?>
							</select>
							<span class="help-block"><?= (isset($errors['country'])) ? $errors['country'] : '' ?></span>
						</div>

					</div>
					<div class="row">
						<div class="form-group col-sm-12 <?= (isset($errors['instdetails'])) ? 'has-error' : '' ?>">
							<textarea class="form-control required" id="instdetails" name="instdetails" placeholder="Please provide details about Staff,Infrastructure, Current business, and Reason of joining DITRP" required="required"><?= isset($_POST['instdetails']) ? $_POST['instdetails'] : '' ?></textarea>
							<span class="help-block"><?= (isset($errors['instdetails'])) ? $errors['instdetails'] : '' ?></span>
						</div>
						<div class="form-group col-sm-12 <?= (isset($errors['agree'])) ? 'has-error' : '' ?>">
							<input type="checkbox" class="required" name="agree" checked="checked" disabled /> I agree to the terms and conditions for DITRP ATC, Kindly consider my application and authorize us to become ATC for DITRP.
							<span class="help-block"><?= (isset($errors['agree'])) ? $errors['agree'] : '' ?></span>
						</div>
					</div>
					<div class="form-group col-sm-12 text-center">
						<label>Enter the contents of image </label>
						<input name="captcha" type="text">
						<img src="<?= HTTP_HOST ?>/include/classes/captcha.php" />
					</div>
					<hr>
					<div class="text-center">
						<input type="submit" name="add_institute" class="btn btn-primary" value="Register" />
						<a href="list-institutes" class="btn btn-primary">Cancel</a>

					</div>
					<br><br>
				</div>
			</div>


		</div>
	</form>
</div>