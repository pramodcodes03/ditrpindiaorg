<?php
//include('include/controller/institute/staff/add_staff.php');
?>
<?php

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
$user_role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : '';
if ($user_role == 3) {
	$institute_id = $db->get_parent_id($user_role, $user_id);
	$staff_id = $user_id;
} else {
	$institute_id = $user_id;
	$staff_id = 0;
}

$action 	= '';
$save		= isset($_POST['save']) ? $_POST['save'] : '';
$register	= isset($_POST['register']) ? $_POST['register'] : '';
if ($save != '')
	$action		= $save;
if ($register != '')
	$action		= $register;

include_once('include/classes/student.class.php');
$student = new student();


if ($action != '') {

	$result = $student->add_student_enquiry();
	$result = json_decode($result, true);
	$success = isset($result['success']) ? $result['success'] : '';
	$message = isset($result['message']) ? $result['message'] : '';
	$errors = isset($result['errors']) ? $result['errors'] : '';
	if ($success == true) {
		$_SESSION['msg'] = $message;
		$_SESSION['msg_flag'] = $success;
		$enquiry_id = isset($result['enquiry_id']) ? $result['enquiry_id'] : '';

		header('location:page.php?page=studentEnquiry');
	}
	//print_r($errors);
}
?>

<div class="content-wrapper">
	<div class="row">
		<div class="col-12 grid-margin stretch-card">
			<div class="card">
				<div class="card-body">
					<h4 class="card-title">Add New Student Enquiry</h4>
					<form role="form" class="form-validate" action="" method="post" enctype="multipart/form-data" onsubmit="pageLoaderOverlay('show');" id="add_student">
						<div class="box-body">
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
											echo "<ul>";
											foreach ($errors as $error) {
												echo "<li>$error</li>";
											}
											echo "<ul>";
											?>

										</div>
									</div>
								</div>
							<?php
							}
							?>
							<input type="hidden" name="enquiry_status" value="" />
							<input type="hidden" name="institute_id" value="<?= $institute_id ?>" />

							<div class="row">
								<div class="form-group col-sm-1" style="width:110px;">
									<label for="fname">Abbreviation</label>
									<select class="form-control" name="abbreviation">
										<option class="form-control" value="Mr">Mr.</option>
										<option class="form-control" value="Miss">Miss </option>
										<option class="form-control" value="Mrs">Mrs</option>
										<option class="form-control" value="Ms">Ms</option>
									</select>
								</div>
								<div class="form-group col-sm-3 <?= (isset($errors['fname'])) ? 'has-error' : '' ?>">
									<label for="fname">Student Name <span class="asterisk">*</span></label>
									<input type="text" name="fname" class="form-control" value="<?= isset($_POST['fname']) ? $_POST['fname'] : '' ?>" id="fname" placeholder="Enter Student Name">
									<span class="help-block"><?= isset($errors['fname']) ? $errors['fname'] : '' ?></span>
								</div>

								<div class="form-group col-sm-1" style="width:110px;">
									<label for="sonof">Select One</label>
									<select class="form-control" name="sonof">
										<option class="form-control" value="S/O">S/O</option>
										<option class="form-control" value="D/O">D/O</option>
										<option class="form-control" value="W/O">W/O</option>
									</select>
								</div>

								<div class="form-group col-sm-3 <?= (isset($errors['mname'])) ? 'has-error' : '' ?>">
									<label for="mname">Father / Husband Name </label>
									<input type="text" name="mname" class="form-control" value="<?= isset($_POST['mname']) ? $_POST['mname'] : '' ?>" id="mname" placeholder="Father Name / Husband Name">
									<span class="help-block"><?= isset($errors['mname']) ? $errors['mname'] : '' ?></span>
								</div>
								<div class="form-group col-sm-2 <?= (isset($errors['lname'])) ? 'has-error' : '' ?>">
									<label for="lname">Surname Name</label>
									<input type="text" name="lname" class="form-control" value="<?= isset($_POST['lname']) ? $_POST['lname'] : '' ?>" id="lname" placeholder="Enter Surname">
									<span class="help-block"><?= isset($errors['lname']) ? $errors['lname'] : '' ?></span>
								</div>
								<div class="form-group col-sm-2 <?= (isset($errors['mothername'])) ? 'has-error' : '' ?>">
									<label for="mothername">Mother Name</label>
									<input type="text" name="mothername" class="form-control" value="<?= isset($_POST['mothername']) ? $_POST['mothername'] : '' ?>" id="mothername" placeholder="Mother Name ">
									<span class="help-block"><?= isset($errors['mothername']) ? $errors['mothername'] : '' ?></span>
								</div>

								<div class="form-group col-sm-12">
									<a onclick="getCourseCondition(' AND A.COURSE_ID !=0')" class="btn btn-primary mr-2" title="Cancel">Single</a>
									<a onclick="getCourseCondition(' AND A.MULTI_SUB_COURSE_ID !=0')" class="btn btn-primary mr-2" title="Cancel">Multiple</a>
									<a onclick="getCourseCondition(' AND A.TYPING_COURSE_ID !=0')" class="btn btn-primary mr-2" title="Cancel">Typing</a>
								</div>

								<div class="form-group col-sm-6 <?= (isset($errors['interested_course'])) ? 'has-error' : '' ?>">
									<label for="interested_course">Course of interest <span class="asterisk">*</span></label>
									<?php $interested_course  = isset($_POST['interested_course']) ? $_POST['interested_course'] : ''; ?>
									<select class="form-control select2" name="interested_course" id="coursename">
										<option name="" value=" ">Select a Course</option>

									</select>
									<span class="help-block"><?= (isset($errors['interested_course'])) ? $errors['interested_course'] : '' ?></span>
								</div>

								<div class="form-group col-sm-6 <?= (isset($errors['mobile'])) ? 'has-error' : '' ?>">
									<label for="mobile">Student Mobile <span class="asterisk">*</span></label>
									<input type="text" name="mobile" class="form-control" pattern="\d*" id="mobile" maxlength="10" value="<?= isset($_POST['mobile']) ? $_POST['mobile'] : '' ?>" placeholder="Student Mobile">
									<span class="help-block"><?= (isset($errors['mobile'])) ? $errors['mobile'] : '' ?></span>
								</div>

								<div class="form-group col-sm-6 <?= (isset($errors['mobile2'])) ? 'has-error' : '' ?>">
									<label for="mobile2">Alternate Mobile</label>
									<input type="text" name="mobile2" class="form-control" pattern="\d*" id="mobile2" maxlength="10" value="<?= isset($_POST['mobile2']) ? $_POST['mobile2'] : '' ?>" placeholder="Alternate Mobile">
									<span class="help-block"><?= (isset($errors['mobile2'])) ? $errors['mobile2'] : '' ?></span>
								</div>

								<div class="form-group col-sm-6 <?= (isset($errors['email'])) ? 'has-error' : '' ?>">
									<label for="email">Email <span class="asterisk">*</span></label>
									<input type="email" name="email" class="form-control" id="email" value="<?= isset($_POST['email']) ? $_POST['email'] : '' ?>" placeholder="Email" onkeyup="document.getElementById('uname').value = this.value;" onchange="document.getElementById('uname').value = this.value;">
									<span class="help-block"><?= (isset($errors['email'])) ? $errors['email'] : '' ?></span>
								</div>

								<div class="form-group col-sm-6 <?= (isset($errors['dob'])) ? 'has-error' : '' ?>">
									<label>Date Of Birth <span class="asterisk">*</span></label>
									<input class="form-control pull-right" name="dob" value="<?= isset($_POST['dob']) ? $_POST['dob'] : '' ?>" id="dob" type="date" max="2999-12-31">
									<span class="help-block"><?= (isset($errors['dob'])) ? $errors['dob'] : '' ?></span>
									<!-- /.input group -->
								</div>

								<div class="form-group col-sm-6 <?= (isset($errors['gender'])) ? 'has-error' : '' ?>">
									<label>Gender <span class="asterisk">*</span></label>
									<?php $gender = isset($_POST['gender']) ? $_POST['gender'] : ''; ?>
									<select class="form-control" name="gender" id="gender">
										<option <?= ($gender == '') ? 'selected="selected"' : '' ?> value="">--select--</option>
										<option value="male" <?= ($gender == 'male') ? 'selected="selected"' : '' ?>>Male</option>
										<option value="female" <?= ($gender == 'female') ? 'selected="selected"' : '' ?>>Female</option>
										<option value="other" <?= ($gender == 'other') ? 'selected="selected"' : '' ?>>Other</option>
									</select>
									<span class="help-block"><?= (isset($errors['gender'])) ? $errors['gender'] : '' ?></span>
								</div>

								<div class="form-group col-sm-6 <?= (isset($errors['state'])) ? 'has-error' : '' ?>">
									<label>State</label>
									<?php
									$STAFF_STATE = isset($_POST['state']) ? $_POST['state'] : '';
									?>
									<select class="form-control select2 " name="state" id="state" style="width: 100%;" onchange="getCitiesByState(this.value)">
										<?php echo $db->MenuItemsDropdown('states_master', "STATE_ID", "STATE_NAME", "STATE_ID, STATE_NAME", $STAFF_STATE, " ORDER BY STATE_NAME"); ?>
									</select>
									<span class="help-block"><?= (isset($errors['state'])) ? $errors['state'] : '' ?></span>
								</div>

								<div class="form-group col-sm-6 <?= (isset($errors['city'])) ? 'has-error' : '' ?>">
									<label>City</label>
									<input type="text" class="form-control" placeholder="city" name="city" value="<?= isset($_POST['city']) ? $_POST['city'] : '' ?>">
									<span class="help-block"><?= (isset($errors['city'])) ? $errors['city'] : '' ?></span>
								</div>

								<div class="form-group col-sm-6 <?= (isset($errors['pincode'])) ? 'has-error' : '' ?>">
									<label for="pincode">Postcode</label>
									<input type="text" class="form-control" placeholder="Postcode" maxlength="6" name="pincode" value="<?= isset($_POST['pincode']) ? $_POST['pincode'] : '' ?>">
									<span class="help-block"><?= (isset($errors['pincode'])) ? $errors['pincode'] : '' ?></span>
								</div>


								<div class="form-group col-sm-6 <?= (isset($errors['per_add'])) ? 'has-error' : '' ?>">
									<label>Permanent Address</label>
									<textarea class="form-control" rows="3" placeholder="Permanent Address ..." name="per_add"><?= isset($_POST['per_add']) ? $_POST['per_add'] : '' ?></textarea>
									<span class="help-block"><?= (isset($errors['per_add'])) ? $errors['per_add'] : '' ?></span>
								</div>

								<div class="form-group col-sm-6 <?= (isset($errors['refferal_code'])) ? 'has-error' : '' ?>">
									<label for="refferal_code">Refferal Code (If Any)</label>
									<input type="text" class="form-control" placeholder="Refferal Code (If Any)" name="refferal_code" value="<?= isset($_POST['refferal_code']) ? $_POST['refferal_code'] : '' ?>">
									<span class="help-block"><?= (isset($errors['refferal_code'])) ? $errors['refferal_code'] : '' ?></span>
								</div>

								<div class="form-group col-sm-6 <?= (isset($errors['enquiry_date'])) ? 'has-error' : '' ?>">
									<label>Enquiry Date </label>
									<input class="form-control pull-right" name="enquiry_date" value="<?= isset($_POST['enquiry_date']) ? $_POST['enquiry_date'] : '' ?>" id="enquiry_date" type="date" max="2999-12-31">
									<span class="help-block"><?= (isset($errors['enquiry_date'])) ? $errors['enquiry_date'] : '' ?></span>
								</div>

								<div class="form-group col-sm-6 <?= (isset($errors['remark'])) ? 'has-error' : '' ?>">
									<label>Remark</label>
									<textarea class="form-control" rows="3" placeholder="Remark" name="remark"><?= isset($_POST['remark']) ? $_POST['remark'] : '' ?></textarea>
									<span class="help-block"><?= (isset($errors['remark'])) ? $errors['remark'] : '' ?></span>
								</div>

							</div>

							<div class="box-footer text-center">
								<input type="hidden" class="btn btn-primary" name="action" value="Add Enquiry" />

								<input type="submit" class="btn btn-primary" name="register" value="Save Admission" />
								&nbsp;&nbsp;&nbsp;

								<a href="page.php?page=studentEnquiry" class="btn btn-danger" title="Cancel">Cancel</a>
								&nbsp;&nbsp;&nbsp;

							</div>

						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>