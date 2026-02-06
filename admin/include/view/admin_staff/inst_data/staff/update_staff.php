<?php
$staff_id = isset($_GET['id']) ? $_GET['id'] : '';
include_once('include/classes/institute.class.php');
$institute = new institute();
$action = isset($_POST['action']) ? $_POST['action'] : '';

if ($action != '') {

	$institute = new institute();
	$result = $institute->update_institute_staff();
	$result = json_decode($result, true);
	$success = $result['success'];
	$message = $result['message'];
	$errors = isset($result['errors']) ? $result['errors'] : '';
	if ($success == true) {
		$_SESSION['msg'] = $message;
		$_SESSION['msg_flag'] = $success;
		header('location:page.php?page=list-staffs');
	}
}
$res = $institute->list_institute_staff($staff_id, $_SESSION['user_id']);
if ($res != '') {
	$srno = 1;
	while ($data = $res->fetch_assoc()) {
		$STAFF_ID 			= $data['STAFF_ID'];
		$INSTITUTE_ID 		= $data['INSTITUTE_ID'];
		$STAFF_FULLNAME 	= $data['STAFF_FULLNAME'];
		$STAFF_GENDER 		= $data['STAFF_GENDER'];
		$STAFF_DOB 			= $data['STAFF_DOB'];
		$STAFF_DOB_FORMATED = $data['STAFF_DOB_FORMATED'];
		$STAFF_EMAIL 		= $data['STAFF_EMAIL'];
		$STAFF_MOBILE 		= $data['STAFF_MOBILE'];
		$STAFF_TEMP_ADDRESS = $data['STAFF_TEMP_ADDRESS'];
		$STAFF_PER_ADDRESS 	= $data['STAFF_PER_ADDRESS'];
		$STAFF_CITY 		= $data['STAFF_CITY'];
		$STAFF_STATE 		= $data['STAFF_STATE'];
		$STAFF_PINCODE 		= $data['STAFF_PINCODE'];
		$STAFF_EDUCATION 	= $data['STAFF_EDUCATION'];
		$STAFF_PHOTO 		= $data['STAFF_PHOTO'];
		$STAFF_PHOTO_ID		= $data['STAFF_PHOTO_ID'];
		$STAFF_DESIGNATION 	= $data['STAFF_DESIGNATION'];
		$STAFF_DOJ		 	= $data['STAFF_DOJ'];
		$STAFF_DOJ_FORMATED		 	= $data['STAFF_DOJ_FORMATED'];
		$STAFF_RESPONSIBILITIES 	= json_decode($data['STAFF_RESPONSIBILITIES']);
		$ACTIVE 			= $data['ACTIVE'];
		$INCENTIVE_IN		= $data['INCENTIVE_IN'];
		$INCENTIVE_VALUE	= $data['INCENTIVE_VALUE'];
		$USER_LOGIN_ID 			= $data['USER_LOGIN_ID'];
		$USER_NAME 			= $data['USER_NAME'];
		$PHOTO = SHOW_IMG_AWS . '/default_user.png';
		$PHOTO_ID = SHOW_IMG_AWS . 'default_user.png';
		if ($STAFF_PHOTO != '')
			$PHOTO = SHOW_IMG_AWS . INSTITUTE_STAFF_PHOTO_PATH . '/' . $STAFF_ID . '/' . $STAFF_PHOTO;
		if ($STAFF_PHOTO_ID != '')
			$PHOTO_ID = SHOW_IMG_AWS . INSTITUTE_STAFF_PHOTO_PATH . '/' . $STAFF_ID . '/' . $STAFF_PHOTO_ID;
	}
}

?>
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Update Staff Member

		</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="#">Staff</a></li>
			<li class="active">Update Staff</li>
		</ol>
	</section>

	<!-- Main content -->
	<form role="form" class="form-validate" method="post" action="" enctype="multipart/form-data" onsubmit="pageLoaderOverlay('show');">
		<section class="content">
			<?php
			if (isset($success)) {
			?>
				<div class="row">
					<div class="col-sm-12">
						<div class="alert alert-<?= ($success == true) ? 'success' : 'danger' ?> alert-dismissible" id="messages">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>
							<h4><i class="icon fa fa-check"></i> <?= ($success == true) ? 'Success' : 'Error' ?>:</h4>
							<?= isset($message) ? $message : 'Please correct the errors.'; ?>
						</div>
					</div>
				</div>
			<?php
			}
			?>
			<div class="row">
				<div class="col-md-8">
					<!-- general form elements -->
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title">Update Staff Member</h3>
						</div>
						<!-- form start -->

						<input type="hidden" name="action" id="action" value="update_staff" />
						<input type="hidden" name="staff_id" id="staff_id" value="<?= $STAFF_ID ?>" />
						<input type="hidden" name="institute_id" id="institute_id" value="<?= $INSTITUTE_ID ?>" />
						<input type="hidden" name="login_id" id="login_id" value="<?= $USER_LOGIN_ID ?>" />
						<div class="box-body">
							<div class="form-group <?= (isset($errors['fullname'])) ? 'has-error' : '' ?>">
								<label for="fullname">Fullname</label>
								<input type="text" name="fullname" class="form-control" value="<?= isset($_POST['fullname']) ? $_POST['fullname'] : $STAFF_FULLNAME ?>" id="fullname" placeholder="Enter fullname" required>
								<span class="help-block"><?= isset($errors['fullname']) ? $errors['fullname'] : '' ?></span>
							</div>
							<div class="col-sm-6">
								<div class="form-group <?= (isset($errors['email'])) ? 'has-error' : '' ?>">
									<label for="email">Email</label>
									<input type="email" name="email" class="form-control" id="email" value="<?= isset($_POST['email']) ? $_POST['email'] : $STAFF_EMAIL ?>" placeholder="Email" required onkeyup="document.getElementById('uname').value = this.value;" onchange="document.getElementById('uname').value = this.value;">
									<span class="help-block" id="errorEmail"><?= (isset($errors['email'])) ? $errors['email'] : '' ?></span>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group <?= (isset($errors['mobile'])) ? 'has-error' : '' ?>">
									<label for="mobile">Mobile</label>
									<input type="text" name="mobile" class="form-control" id="mobile" maxlength="10" value="<?= isset($_POST['mobile']) ? $_POST['mobile'] : $STAFF_MOBILE ?>" placeholder="Mobile" required>
									<span class="help-block" id="errorMobile"><?= (isset($errors['mobile'])) ? $errors['mobile'] : '' ?></span>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group <?= (isset($errors['dob'])) ? 'has-error' : '' ?>">
									<label>Date Of Birth:</label>
									<div class="input-group date">
										<div class="input-group-addon">
											<i class="fa fa-calendar"></i>
										</div>
										<input class="form-control pull-right" name="dob" value="<?= isset($_POST['dob']) ? $_POST['dob'] : $STAFF_DOB_FORMATED ?>" id="dob" type="text">
									</div>
									<span class="help-block" id="errorMobile"><?= (isset($errors['dob'])) ? $errors['dob'] : '' ?></span>
									<!-- /.input group -->
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group <?= (isset($errors['gender'])) ? 'has-error' : '' ?>">
									<label>Gender</label>
									<?php
									$STAFF_GENDER = isset($_POST['gender']) ? $_POST['gender'] : $STAFF_GENDER;
									?>
									<select class="form-control" name="gender">
										<option>--select--</option>
										<option value="male" <?= ($STAFF_GENDER == 'male') ? 'selected="selected"' : '' ?>>Male</option>
										<option value="female" <?= ($STAFF_GENDER == 'female') ? 'selected="selected"' : '' ?>>Female</option>
									</select>
									<span class="help-block" id="errorMobile"><?= (isset($errors['gender'])) ? $errors['gender'] : '' ?></span>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group <?= (isset($errors['temp_add'])) ? 'has-error' : '' ?>">
									<label>Temporary Address</label>
									<textarea class="form-control" rows="3" name="temp_add" placeholder="Temporary Address ..."><?= isset($_POST['temp_add']) ? $_POST['temp_add'] : $STAFF_TEMP_ADDRESS ?></textarea>
									<span class="help-block" id="errorMobile"><?= (isset($errors['temp_add'])) ? $errors['temp_add'] : '' ?></span>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group <?= (isset($errors['per_add'])) ? 'has-error' : '' ?>">
									<label>Permanent Address</label>
									<textarea class="form-control" rows="3" placeholder="Permanent Address ..." name="per_add"><?= isset($_POST['per_add']) ? $_POST['per_add'] : $STAFF_PER_ADDRESS ?></textarea>
									<span class="help-block" id="errorMobile"><?= (isset($errors['per_add'])) ? $errors['per_add'] : '' ?></span>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>State</label>
									<?php
									$STAFF_STATE = isset($_POST['state']) ? $_POST['state'] : $STAFF_STATE;
									?>
									<select class="form-control" name="state" id="state" style="width: 100%;" onchange="getCitiesByState(this.value)">
										<?php echo $db->MenuItemsDropdown('states_master', "STATE_ID", "STATE_NAME", "STATE_ID, STATE_NAME", $STAFF_STATE, " ORDER BY STATE_NAME"); ?>
									</select>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>City</label>
									<?php $STAFF_CITY = isset($_POST['city']) ? $_POST['city'] : $STAFF_CITY;	?>
									<select class="form-control" name="city" id="city" style="width: 100%;">
										<?php echo $db->MenuItemsDropdown('city_master', "CITY_ID", "CITY_NAME", "CITY_ID, CITY_NAME", $STAFF_CITY, " ORDER BY CITY_NAME"); ?>
									</select>
								</div>
							</div>

							<div class="col-sm-6">
								<div class="form-group <?= (isset($errors['pincode'])) ? 'has-error' : '' ?>">
									<label for="pincode">Pincode</label>
									<input type="text" class="form-control" placeholder="Pincode" maxlength="6" name="pincode" value="<?= isset($_POST['pincode']) ? $_POST['pincode'] : $STAFF_PINCODE ?>">
									<span class="help-block" id="errorMobile"><?= (isset($errors['pincode'])) ? $errors['pincode'] : '' ?></span>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group <?= (isset($errors['designation'])) ? 'has-error' : '' ?>">
									<label for="designation">Designation</label>
									<input type="text" class="form-control" id="designation" placeholder="Designation" value="<?= isset($_POST['designation']) ? $_POST['designation'] : $STAFF_DESIGNATION ?>" name="designation">
									<span class="help-block" id="errorMobile"><?= (isset($errors['designation'])) ? $errors['designation'] : '' ?></span>
								</div>

							</div>
							<div class="col-sm-6">
								<div class="form-group <?= (isset($errors['qualification'])) ? 'has-error' : '' ?>">
									<label for="qualification">Qualification</label>
									<input type="text" class="form-control" id="qualification" value="<?= isset($_POST['qualification']) ? $_POST['qualification'] : $STAFF_EDUCATION ?>" placeholder="Qualification" name="qualification">
									<span class="help-block" id="errorMobile"><?= (isset($errors['qualification'])) ? $errors['qualification'] : '' ?></span>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>Date Of Joining:</label>
									<div class="input-group date">
										<div class="input-group-addon">
											<i class="fa fa-calendar"></i>
										</div>
										<input class="form-control pull-right" name="doj" value="<?= isset($_POST['doj']) ? $_POST['doj'] : $STAFF_DOJ_FORMATED ?>" id="doj" type="text">
									</div>
									<!-- /.input group -->
								</div>
							</div>
							<div class="form-group <?= (isset($errors['uname'])) ? 'has-error' : '' ?>">
								<label for="uname">Username</label>
								<input type="email" class="form-control" id="uname" placeholder="Username" name="uname" value="<?= isset($_POST['uname']) ? $_POST['uname'] : $USER_NAME ?>" required>
								<span class="help-block" id="errorUname"><?= (isset($errors['uname'])) ? $errors['uname'] : '' ?></span>
							</div>
							<div class="form-group">
								<label for="pword">New Password</label>
								<input type="password" class="form-control" id="pword" placeholder="New Password" value="<?= isset($_POST['pword']) ? $_POST['pword'] : '' ?>" name="pword">
								<span class="help-block" id="errorPword"></span>
							</div>
							<div class="form-group <?= (isset($errors['confpword'])) ? 'has-error' : '' ?>">
								<label for="confpword">Confirm New Password</label>
								<input type="password" class="form-control" id="confpword" placeholder="Confirm new password" value="<?= isset($_POST['confpword']) ? $_POST['confpword'] : '' ?>" name="confpword">
								<span class="help-block" id="errorConfpword"><?= (isset($errors['confpword'])) ? $errors['confpword'] : '' ?></span>
							</div>
							<div class="col-sm-6">
								<div class="form-group <?= (isset($errors['staff_photo'])) ? 'has-error' : '' ?>">
									<label for="photo">Photo</label>
									<input type="file" name="staff_photo" id="staff_photo">

									<p class="help-block"><?= (isset($errors['staff_photo'])) ? $errors['staff_photo'] : '' ?></p>
								</div>
							</div>
							<div class="col-sm-6">
								<img src="<?= $PHOTO ?>" id="img_preview" class="img img-responsive" style="height:150px" />
							</div>
							<div class="col-sm-6">
								<div class="form-group <?= (isset($errors['staff_photo_id'])) ? 'has-error' : '' ?>">
									<label for="photo">Photo ID Proof</label>
									<input type="file" name="staff_photo_id" id="staff_photo_id">

									<p class="help-block"><?= (isset($errors['staff_photo_id'])) ? $errors['staff_photo_id'] : '' ?></p>
								</div>
							</div>
							<div class="col-sm-6">
								<img src="<?= $PHOTO_ID ?>" id="staff_photo_id_preview" class="img img-responsive" style="height:150px" />
							</div>
							<div class="form-group">
								<label for="status">Status</label>
								<?php
								$ACTIVE =  isset($_POST['status']) ? $_POST['status'] : $ACTIVE;
								?>
								<div class="radio">
									<label>
										<input name="status" id="status1" value="1" <?= ($ACTIVE == 1) ? 'checked="checked"' : '' ?> type="radio">
										Active
									</label>
									<label>
										<input name="status" id="status2" value="0" <?= ($ACTIVE == 0) ? 'checked="checked"' : '' ?> type="radio">
										In-Active
									</label>
								</div>
							</div>

							<!-- /.box-body -->

							<div class="box-footer text-center">
								<input type="submit" class="btn btn-primary" name="submit" value="Update Staff" /> &nbsp;&nbsp;&nbsp;
								<a href="page.php?page=list-staffs" class="btn btn-warning" title="Cancel">Cancel</a>

							</div>

						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="box box-info">
						<div class="box-header">
							<i class="fa fa-inr"></i>
							<h3 class="box-title">Incentive Details</h3>
						</div>
						<div class="box-body">
							<div class="form-group <?= (isset($errors['incentive_mode'])) ? 'has-error' : '' ?>">
								<label for="incentive_mode">Incentive Type</label>
								<?php
								$incentive_mode =  isset($_POST['incentive_mode']) ? $_POST['incentive_mode'] : $INCENTIVE_IN;
								?>
								<div class="radio">
									<label>
										<input name="incentive_mode" id="status1" value="amount" <?= ($incentive_mode == 'amount') ? 'checked="checked"' : '' ?> type="radio">
										Amount
									</label>
									<label>
										<input name="incentive_mode" id="status2" value="percentage" <?= ($incentive_mode == 'percentage') ? 'checked="checked"' : '' ?> type="radio">
										Percentage
									</label>
								</div>
								<span class="help-block"><?= (isset($errors['incentive_mode'])) ? $errors['incentive_mode'] : '' ?>
							</div>
							<div class="form-group <?= (isset($errors['incentive_value'])) ? 'has-error' : '' ?>">
								<label for="incentive_value">Incentive Value</label>
								<input type="text" class="form-control" id="incentive_value" placeholder="Value" name="incentive_value" value="<?= isset($_POST['incentive_value']) ? $_POST['incentive_value'] : $INCENTIVE_VALUE ?>">
								<span class="help-block"><?= (isset($errors['incentive_value'])) ? $errors['incentive_value'] : '' ?></span>
							</div>
						</div>
					</div>
					<?php if ($user_role == 2) { ?>

						<div class="box box-primary">
							<div class="box-header with-border">
								<h3 class="box-title">Add Responsibilities</h3>
							</div>
							<div class="box-body">
								<div class="form-group <?= (isset($errors['responsibilities'])) ? 'has-error' : '' ?>">
									<span class="help-block"><?= isset($errors['responsibilities']) ? $errors['responsibilities'] : '' ?></span>
									<?php
									$responsibilities = isset($_POST['responsibilities']) ? $_POST['responsibilities'] : $STAFF_RESPONSIBILITIES;
									$sqlmenu = "SELECT DISTINCT MENU_NAME FROM user_responsibilities_master WHERE USER_ROLE=2";
									$resmenu = $db->execQuery($sqlmenu);
									if ($resmenu && $resmenu->num_rows > 0) {
										//echo '<ul>';
										while ($datamenu = $resmenu->fetch_assoc()) {
											$menuname = $datamenu['MENU_NAME'];
											echo "<h4>$menuname</h4>";
											$resp = $db->get_responsibilities('', 2, " AND MENU_NAME='$menuname'");
											if ($resp != '') {
												while ($data = $resp->fetch_assoc()) {
													extract($data);
													$checked = '';
													if (is_array($responsibilities) && !empty($responsibilities)) {
														$checked = in_array($RESPONSIBILITY_STR, $responsibilities) ? 'checked="checked"' : '';
													}
									?>
													<div class="checkbox">
														<label>
															<input type="checkbox" value="<?= $RESPONSIBILITY_STR ?>" name="responsibilities[]" <?= $checked ?>>
															<?= $RESPONSIBILITY ?>
														</label>
													</div>

									<?php

												}
											}
										}
										//echo '</ul>';
									}
									?>

								</div>
							</div>
						</div>

					<?php } ?>
				</div>
				<!-- /.row -->
		</section>
	</form>
	<!-- /.content -->
</div>