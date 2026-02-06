<?php
$action = isset($_POST['add_institute']) ? $_POST['add_institute'] : '';
include_once('include/classes/institute.class.php');
$institute = new institute();

if ($action != '') {
	$result	= $institute->add_institute();
	$result = json_decode($result, true);
	//print_r($result);
	$success = isset($result['success']) ? $result['success'] : '';
	$message = $result['message'];
	$errors = isset($result['errors']) ? $result['errors'] : '';
	if ($success == true) {
		$_SESSION['msg'] = $message;
		$_SESSION['msg_flag'] = $success;
		header('location:page.php?page=listFranchise');
	}
}
?>

<div class="content-wrapper">
	<div class="row">
		<div class="col-12 grid-margin stretch-card">
			<div class="card">
				<div class="card-body">
					<h4 class="card-title"> Add New Franchise</h4>
					<form role="form" class="form-validate" action="" method="post" enctype="multipart/form-data" onsubmit="pageLoaderOverlay('show');" id="add_student">
						<div class="box-body">
							<!-- left column -->
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
												foreach ($errors as $key => $value) {
													echo '<li>' . $value . '</li>';
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

							<div class="row">

								<div class="form-group col-sm-4 <?= (isset($errors['instname'])) ? 'has-error' : '' ?>">
									<label>Franchise Name</label>
									<input class="form-control" id="instname" name="instname" placeholder="Franchise name" value="<?= isset($_POST['instname']) ? $_POST['instname'] : '' ?>" type="text">
									<span class="help-block"><?= (isset($errors['instname'])) ? $errors['instname'] : '' ?></span>
								</div>

								<div class="form-group  col-sm-4  <?= (isset($errors['instowner'])) ? 'has-error' : '' ?>">
									<label>Owner Name</label>
									<input class="form-control" id="instowner" name="instowner" placeholder="Franchise owner name" value="<?= isset($_POST['instowner']) ? $_POST['instowner'] : '' ?>" type="text">
									<span class="help-block"><?= (isset($errors['instowner'])) ? $errors['instowner'] : '' ?></span>
								</div>

								<div class="form-group col-sm-4 <?= (isset($errors['designation'])) ? 'has-error' : '' ?>">
									<label>Designation</label>
									<select class="form-control" name="designation" id="designation">
										<?php
										$designation = isset($_POST['designation']) ? $_POST['designation'] : '';
										echo $db->MenuItemsDropdown('designation_master', 'DESIGNATION_ID', 'DESIGNATION', 'DESIGNATION_ID,DESIGNATION', $designation, ' WHERE ROLE=2'); ?>
									</select>
									<span class="help-block"><?= (isset($errors['designation'])) ? $errors['designation'] : '' ?></span>
								</div>

								<div class="form-group col-sm-4 <?= (isset($errors['dob'])) ? 'has-error' : '' ?>">
									<label>Date Of Birth</label>
									<input class="form-control pull-right" name="dob" value="<?= isset($_POST['dob']) ? $_POST['dob'] : '' ?>" id="dob" type="date" max="2999-12-31">
									<span class="help-block"><?= (isset($errors['dob'])) ? $errors['dob'] : '' ?></span>
								</div>

								<div class="form-group col-sm-4 <?= (isset($errors['email'])) ? 'has-error' : '' ?>">
									<label>Email</label>
									<input class="form-control" id="email" name="email" placeholder="Email" value="<?= isset($_POST['email']) ? $_POST['email'] : '' ?>" type="email" onchange="document.getElementById('uname').value = this.value;">
									<span class="help-block"><?= (isset($errors['email'])) ? $errors['email'] : '' ?></span>
								</div>

								<div class="form-group  col-sm-4 <?= (isset($errors['mobile'])) ? 'has-error' : '' ?>">
									<label>Mobile</label>
									<input class="form-control" id="mobile" name="mobile" maxlength="10" placeholder="Mobile" value="<?= isset($_POST['mobile']) ? $_POST['mobile'] : '' ?>" type="text">
									<span class="help-block"><?= (isset($errors['mobile'])) ? $errors['mobile'] : '' ?></span>
								</div>

								<div class="form-group  col-sm-4  <?= (isset($errors['address1'])) ? 'has-error' : '' ?>">
									<label>Address </label>
									<textarea class="form-control" id="address1" name="address1" placeholder="Address" type="text"><?= isset($_POST['address1']) ? $_POST['address1'] : '' ?></textarea>
									<span class="help-block"><?= (isset($errors['address1'])) ? $errors['address1'] : '' ?></span>
								</div>

								<!--<div class="form-group">
					<label for="address2" class="col-sm-3 control-label">Address Line 2</label>
					<div class="col-sm-9">
						<input class="form-control" id="address2" name="address2"  maxlength="100" placeholder="Address Line 2" type="text" value="<?= isset($_POST['address2']) ? $_POST['address2'] : '' ?>" />
					</div>
					</div>-->

								<div class="form-group col-sm-4 <?= (isset($errors['city'])) ? 'has-error' : '' ?>">
									<label>State</label>
									<select class="form-control select2" name="state" id="state" onchange="getCitiesByState(this.value)">
										<?php
										$state = isset($_POST['state']) ? $_POST['state'] : '';
										echo $db->MenuItemsDropdown('states_master', 'STATE_ID', 'STATE_NAME', 'STATE_ID,STATE_NAME', $state, ' ORDER BY STATE_NAME ASC'); ?>
									</select>
									<span class="help-block"><?= (isset($errors['state'])) ? $errors['state'] : '' ?></span>
								</div>

								<div class="form-group  col-sm-4 <?= (isset($errors['city'])) ? 'has-error' : '' ?>">
									<label>City</label>
									<input type="text" class=" form-control" name="city" placeholder="City" value="<?= isset($_POST['city']) ? $_POST['city'] : '' ?>" />
									<span class="help-block"><?= (isset($errors['city'])) ? $errors['city'] : '' ?></span>
								</div>

								<div class="form-group col-sm-4">
									<label>Taluka</label>
									<input class="form-control" id="taluka" name="taluka" placeholder="Taluka Name" type="text" value="<?= isset($_POST['taluka']) ? $_POST['taluka'] : '' ?>" />
								</div>

								<div class="form-group col-sm-4">
									<label>Country</label>
									<select class="form-control" name="country_sel" id="country_sel" disabled>
										<?php
										$country = isset($_POST['country']) ? $_POST['country'] : 1;
										echo $db->MenuItemsDropdown('countries_master', 'COUNTRY_ID', 'COUNTRY_NAME', 'COUNTRY_ID,COUNTRY_NAME', $country, ' WHERE COUNTRY_ID=1 ORDER BY COUNTRY_NAME ASC'); ?>
									</select>
									<input type="hidden" name="country" id="country" value="1" />
								</div>

								<div class="form-group col-sm-4">
									<label>Postal Code</label>
									<input class="form-control" id="postcode" name="postcode" maxlength="6" placeholder="Postcode" value="<?= isset($_POST['postcode']) ? $_POST['postcode'] : '' ?>" type="text">
								</div>

								<div class="form-group col-sm-4">
									<label>Total number of computers</label>
									<input type="text" class=" form-control" name="no_of_comp" placeholder="Total number of computers" value="<?= isset($_POST['no_of_comp']) ? $_POST['no_of_comp'] : '' ?>" />
								</div>

								<div class="form-group col-sm-4">
									<label>Total number of staff</label>
									<input type="text" class=" form-control" name="no_of_staff" placeholder="Total number of staff" value="<?= isset($_POST['no_of_staff']) ? $_POST['no_of_staff'] : '' ?>" />
								</div>

								<!--<div class="form-group">
					<label for="instdetails" class="col-sm-3 control-label">Details about Franchise</label>
					<div class="col-sm-9">
						<textarea class="form-control" id="instdetails" name="instdetails" placeholder="Please provide details about Staff,Infrastructure, Current business, and Reason of joining DITRP" type="text"><?= isset($_POST['instdetails']) ? $_POST['instdetails'] : '' ?></textarea>
					</div>
					</div>-->

								<div class="form-group col-sm-4 <?= (isset($errors['uname'])) ? 'has-error' : '' ?>">
									<label>Username</label>
									<input class="form-control" id="uname" name="uname" placeholder="Username" value="<?= isset($_POST['uname']) ? $_POST['uname'] : '' ?>" type="email" readonly>
									<span class="help-block"><?= (isset($errors['uname'])) ? $errors['uname'] : '' ?></span>
								</div>

								<div class="form-group col-sm-4 <?= (isset($errors['pword'])) ? 'has-error' : '' ?>">
									<label>Password</label>
									<input class="form-control" id="pword" name="pword" placeholder="Password" value="<?= isset($_POST['pword']) ? $_POST['pword'] : '' ?>" type="password">
									<span class="help-block"><?= (isset($errors['pword'])) ? $errors['pword'] : '' ?></span>
								</div>

								<div class="form-group col-sm-4 <?= (isset($errors['confpword'])) ? 'has-error' : '' ?>">
									<label>Confirm Password</label>
									<input class="form-control" id="confpword" name="confpword" placeholder="Confirm Password" value="<?= isset($_POST['confpword']) ? $_POST['confpword'] : '' ?>" type="password">
									<span class="help-block"><?= (isset($errors['confpword'])) ? $errors['confpword'] : '' ?></span>
								</div>

								<div class="form-group  col-sm-4 <?= (isset($errors['plan'])) ? 'has-error' : '' ?>">
									<label>Select Plan For Franchise</label>
									<select class="form-control" id="plan" name="plan">
										<?php
										$plan = isset($_POST['plan']) ? $_POST['plan'] : '';
										echo $db->MenuItemsDropdown('institute_plans', 'PLAN_ID', 'PLAN_NAME', 'PLAN_ID,PLAN_NAME', $plan, ' WHERE ACTIVE=1 AND DELETE_FLAG=0');
										?>
									</select>
									<span class="help-block"><?= isset($errors['plan']) ? $errors['plan'] : '' ?></span>
								</div>

								<div class="form-group col-sm-4">
									<?php
									$status = isset($_POST['status']) ? $_POST['status'] : 0;
									?>
									<label>Status</label>
									<div class="radio">
										<label>
											<input name="status" id="optionsRadios1" value="1" <?= ($status == 1) ? "checked=''" : ''  ?> type="radio">
											Active
										</label>
										<label>
											<input name="status" id="optionsRadios2" value="0" <?= ($status == 0) ? "checked=''" : ''  ?> type="radio">
											Inactive
										</label>
									</div>
								</div>

								<div class="form-group col-sm-4">
									<?php
									$verify = isset($_POST['verify']) ? $_POST['verify'] : 0;
									?>
									<label>Verify</label>
									<div class="radio">
										<label>
											<input name="verify" id="optionsRadios3" value="1" <?= ($verify == 1) ? "checked=''" : ''  ?> type="radio">
											Yes
										</label>
										<label>
											<input name="verify" id="optionsRadios4" value="0" <?= ($verify == 0) ? "checked=''" : ''  ?> type="radio">
											No
										</label>
									</div>
								</div>

								<div class="form-group col-sm-4">
									<label>GST Number</label>
									<div>
										<input class="form-control" id="gstno" name="gstno" placeholder="GST Number" value="<?= isset($_POST['gstno']) ? $_POST['gstno'] : '' ?>" type="text">
									</div>
								</div>

								<!-- <div class="form-group col-sm-4">
						<?php
						$prime = isset($_POST['prime']) ? $_POST['prime'] : 0;
						?>
						<label>Prime Member</label>
						<div class="radio">
							<label>
							<input name="prime" id="optionsRadios3" value="1" <?= ($prime == 1) ? "checked=''" : ''  ?> type="radio">
							Yes
							</label>
							<label>
							<input name="prime" id="optionsRadios4" value="0" <?= ($prime == 0) ? "checked=''" : ''  ?> type="radio">
							No
							</label>
						</div>
					</div> -->


								<div class="col-md-12">
									<!-- general form elements -->
									<div class="box box-primary">
										<div class="box-header with-border">
											<h3 class="box-title">Upload Franchise Documents</h3>
										</div>
										<div class="box-body row">
											<div class="form-group col-sm-4 <?= (isset($errors['instlogo'])) ? 'has-error' : '' ?>">
												<label>Franchise Logo</label>
												<input id="instlogo" name="instlogo" type="file">
												<p class="help-block"><?= (isset($errors['instlogo'])) ? $errors['instlogo'] : 'Logo' ?></p>
											</div>


											<div class="form-group  col-sm-4 <?= (isset($errors['passphoto'])) ? 'has-error' : '' ?>">
												<label>Owner Passport Photo</label>
												<input id="passphoto" name="passphoto" type="file">
												<p class="help-block"><?= (isset($errors['passphoto'])) ? $errors['passphoto'] : 'Photo' ?></p>
											</div>

											<div class="form-group col-sm-4  <?= (isset($errors['instsign'])) ? 'has-error' : '' ?>">
												<label>Franchise Signatute</label>
												<input id="instsign" name="instsign" type="file">
												<p class="help-block"><?= (isset($errors['instsign'])) ? $errors['instsign'] : 'Sign' ?></p>
											</div>

										</div>
									</div>
								</div>

								<div class="col-md-12">
									<div class="box box-primary">
										<div class="box-header with-border">
											<h3 class="box-title">For Office Use</h3>
										</div>
										<div class="box-body row">
											<!--<div class="form-group <?= (isset($errors['creditcount'])) ? 'has-error' : '' ?>">
							  <label for="creditcount" class="col-sm-4 control-label">Credit</label>
							  <div class="col-sm-8">
								<input class="form-control" id="creditcount" name="creditcount" value="<?= isset($_POST['creditcount']) ? $_POST['creditcount'] : '100' ?>"  placeholder="Credit" type="number">
								<span class="help-block"><?= (isset($errors['creditcount'])) ? $errors['creditcount'] : '' ?></span>
							  </div>
						</div>
						<div class="form-group <?= (isset($errors['democount'])) ? 'has-error' : '' ?>">
							  <label for="democount" class="col-sm-4 control-label">Demo</label>
							  <div class="col-sm-8">
								<input class="form-control" id="democount" name="democount" value="<?= isset($_POST['democount']) ? $_POST['democount'] : '10' ?>" placeholder="Demo per student" type="number">
								<span class="help-block"><?= (isset($errors['democount'])) ? $errors['democount'] : '' ?></span>
							  </div>
						</div>-->
											<div class="form-group col-sm-4">
												<label>Register Date</label>
												<input class="form-control pull-right" value="<?= isset($_POST['registrationdate']) ? $_POST['registrationdate'] : $access->curr_date(); ?>" id="registrationdate" type="date" name="registrationdate" max="2999-12-31" onchange="setAccExpDate(this.value)">
												<span class="help-block"><?= (isset($errors['registrationdate'])) ? $errors['registrationdate'] : '' ?></span>
											</div>

											<div class="form-group  col-sm-4 <?= (isset($errors['expirationdate'])) ? 'has-error' : '' ?>">
												<label>Expire Date</label>
												<input class="form-control pull-right" value="<?= isset($_POST['expirationdate']) ? $_POST['expirationdate'] : $access->acc_expiry_date(); ?>" id="expirationdate" type="date" name="expirationdate" max="2999-12-31">
												<span class="help-block"><?= (isset($errors['expirationdate'])) ? $errors['expirationdate'] : '' ?></span>
											</div>

										</div>
									</div>
								</div>

								<div class="box-footer text-center">
									<input type="submit" class="btn btn-primary" name="add_institute" value="Add Franchise" />
									&nbsp;&nbsp;&nbsp;

									<a href="page.php?page=listFranchise" class="btn btn-danger" title="Cancel">Cancel</a>
									&nbsp;&nbsp;&nbsp;

								</div>

							</div>

						</div>
					</form>
				</div>
			</div>
		</div>
	</div>