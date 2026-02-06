<?php
$enquiry_id = $db->test(isset($_GET['id']) ? $_GET['id'] : '');
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
$user_role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : '';
if ($user_role == 5) {
	$institute_id = $db->get_parent_id($user_role, $user_id);
	$staff_id = $user_id;
} else {
	$institute_id = $user_id;
	$staff_id = 0;
}

$action = isset($_POST['action']) ? $_POST['action'] : '';
include_once('include/classes/student.class.php');
$student = new student();
if ($action != '') {
	$result = $student->update_student_enquiry();
	$result = json_decode($result, true);
	$success = isset($result['success']) ? $result['success'] : '';
	$message = isset($result['message']) ? $result['message'] : '';
	$errors = isset($result['errors']) ? $result['errors'] : '';
	if ($success == true) {
		$_SESSION['msg'] = $message;
		$_SESSION['msg_flag'] = $success;
		header('location:page.php?page=studentEnquiry');
	}
}
$res = $student->list_student_enquiry($enquiry_id, '', '', '');
if ($res != '') {
	$srno = 1;
	while ($data = $res->fetch_assoc()) {
		extract($data);
	}
}
?>
<div class="content-wrapper">
	<div class="row">
		<div class="col-12 grid-margin stretch-card">
			<div class="card">
				<div class="card-body">
					<h4 class="card-title">Update Student Enquiry</h4>
					<form role="form" class="form-validate" action="" method="post" enctype="multipart/form-data" onsubmit="pageLoaderOverlay('show');" id="add_student">
						<div class="box-body">
							<input type="hidden" name="enquiry_id" value="<?= $ENQUIRY_ID ?>" />
							<div class="row">
								<div class="form-group col-sm-1">
									<label for="abbreviation">Abbreviation</label>
									<?php $ABBREVIATION = isset($_POST['abbreviation']) ? $_POST['abbreviation'] : $ABBREVIATION;	?>
									<select class="form-control" name="abbreviation">
										<option class="form-control" value="Mr" <?php echo ($ABBREVIATION == 'MR') ? 'selected="selected"' : '' ?>>Mr.</option>
										<option class="form-control" value="Miss" <?php echo ($ABBREVIATION == 'MISS') ? 'selected="selected"' : '' ?>>Miss </option>
										<option class="form-control" value="Mrs" <?php echo ($ABBREVIATION == 'MRS') ? 'selected="selected"' : '' ?>>Mrs</option>
										<option class="form-control" value="Ms" <?php echo ($ABBREVIATION == 'MS') ? 'selected="selected"' : '' ?>>Ms</option>
									</select>
								</div>
								<div class="col-sm-3">
									<div class="form-group <?= (isset($errors['fname'])) ? 'has-error' : '' ?>">
										<label for="fname">Student Name <span class="asterisk">*</span></label>
										<input type="text" name="fname" class="form-control" value="<?= isset($_POST['fname']) ? $_POST['fname'] : $STUDENT_FNAME ?>" id="fname" placeholder="Enter Student Name">
										<span class="help-block"><?= isset($errors['fname']) ? $errors['fname'] : '' ?></span>
									</div>
								</div>

								<div class="form-group col-sm-1" style="width:110px;">
									<label for="sonof">Select One</label>
									<select class="form-control" name="sonof">
										<option class="form-control" value=" ">--Select One--</option>
										<option class="form-control" value="S/O" <?php echo ($SONOF == 'S/O') ? 'selected="selected"' : '' ?>>S/O</option>
										<option class="form-control" value="D/O" <?php echo ($SONOF == 'D/O') ? 'selected="selected"' : '' ?>>D/O</option>
										<option class="form-control" value="W/O" <?php echo ($SONOF == 'W/O') ? 'selected="selected"' : '' ?>>W/O</option>
									</select>
								</div>

								<div class="col-sm-2">
									<div class="form-group <?= (isset($errors['mname'])) ? 'has-error' : '' ?>">
										<label for="mname">Father / Husband Name</label>
										<input type="text" name="mname" class="form-control" value="<?= isset($_POST['mname']) ? $_POST['mname'] : $STUDENT_MNAME ?>" id="mname" placeholder="Father Name / Husband Name">
										<span class="help-block"><?= isset($errors['mname']) ? $errors['mname'] : '' ?></span>
									</div>
								</div>
								<div class="col-sm-2">
									<div class="form-group <?= (isset($errors['lname'])) ? 'has-error' : '' ?>">
										<label for="lname">Surname Name</label>
										<input type="text" name="lname" class="form-control" value="<?= isset($_POST['lname']) ? $_POST['lname'] : $STUDENT_LNAME ?>" id="lname" placeholder="Enter Surname Name">
										<span class="help-block"><?= isset($errors['lname']) ? $errors['lname'] : '' ?></span>
									</div>
								</div>
								<div class="form-group col-sm-2 <?= (isset($errors['mothername'])) ? 'has-error' : '' ?>">
									<label for="mothername">Mother Name</label>
									<input type="text" name="mothername" class="form-control" value="<?= isset($_POST['mothername']) ? $_POST['mothername'] : $STUDENT_MOTHERNAME ?>" id="mothername" placeholder="Mother Name ">
									<span class="help-block"><?= isset($errors['mothername']) ? $errors['mothername'] : '' ?></span>
								</div>


								<div class="form-group col-sm-6 <?= (isset($errors['interested_course'])) ? 'has-error' : '' ?>">
									<label for="interested_course">Course of interest <span class="asterisk">*</span></label>
									<?php $interested_course  = isset($_POST['interested_course']) ? $_POST['interested_course'] : $INSTRESTED_COURSE; ?>
									<select class="form-control select2" name="interested_course" data-placeholder="Select a Course">
										<?php
										$sql = "SELECT A.INSTITUTE_COURSE_ID, A.COURSE_ID, A.MULTI_SUB_COURSE_ID, A.COURSE_TYPE, A.TYPING_COURSE_ID FROM institute_courses A WHERE  A.INSTITUTE_ID='$institute_id' AND A.DELETE_FLAG=0 AND A.ACTIVE=1";
										//echo $sql;
										$ex = $db->execQuery($sql);
										if ($ex && $ex->num_rows > 0) {
											while ($data = $ex->fetch_assoc()) {
												//print_r($data);
												$INSTITUTE_COURSE_ID = $data['INSTITUTE_COURSE_ID'];
												$COURSE_ID 			 = $data['COURSE_ID'];
												$MULTI_SUB_COURSE_ID = $data['MULTI_SUB_COURSE_ID'];
												$TYPING_COURSE_ID 	 = $data['TYPING_COURSE_ID'];

												if ($COURSE_ID != '' && !empty($COURSE_ID) && $COURSE_ID != '0') {
													$course 			 = $db->get_course_detail($COURSE_ID);
													$course_name 		 = $course['COURSE_NAME_MODIFY'];
												}

												if ($MULTI_SUB_COURSE_ID != '' && !empty($MULTI_SUB_COURSE_ID) && $MULTI_SUB_COURSE_ID != '0') {
													$course 			 = $db->get_course_detail_multi_sub($MULTI_SUB_COURSE_ID);
													$course_name 		 = $course['COURSE_NAME_MODIFY'];
												}

												if ($TYPING_COURSE_ID != '' && !empty($TYPING_COURSE_ID) && $TYPING_COURSE_ID != '0') {
													$course = $db->get_course_detail_typing($TYPING_COURSE_ID);
													$course_name 	= $course['COURSE_NAME_MODIFY'];
												}

												$selected = '';
												if ($INSTITUTE_COURSE_ID == $interested_course) {
													$selected = 'selected="selected"';
												}

												echo '<option value="' . $INSTITUTE_COURSE_ID . '" ' . $selected . '>' . $course_name . '</option>';
											}
										}
										?>
									</select>
									<span class="help-block"><?= (isset($errors['interested_course'])) ? $errors['interested_course'] : '' ?></span>
								</div>


								<div class="form-group col-sm-6 <?= (isset($errors['mobile'])) ? 'has-error' : '' ?>">
									<label for="mobile">Student Mobile <span class="asterisk">*</span></label>
									<input type="text" name="mobile" class="form-control" pattern="\d*" id="mobile" maxlength="10" value="<?= isset($_POST['mobile']) ? $_POST['mobile'] : $STUDENT_MOBILE ?>" placeholder="Student Mobile">
									<span class="help-block"><?= (isset($errors['mobile'])) ? $errors['mobile'] : '' ?></span>
								</div>

								<div class="form-group col-sm-6 <?= (isset($errors['mobile2'])) ? 'has-error' : '' ?>">
									<label for="mobile2">Alternate Mobile</label>
									<input type="text" name="mobile2" class="form-control" pattern="\d*" id="mobile2" maxlength="10" value="<?= isset($_POST['mobile2']) ? $_POST['mobile2'] : $STUDENT_MOBILE2 ?>" placeholder="Alternate Mobile">
									<span class="help-block"><?= (isset($errors['mobile2'])) ? $errors['mobile2'] : '' ?></span>
								</div>

								<div class="form-group col-sm-6 <?= (isset($errors['email'])) ? 'has-error' : '' ?>">
									<label for="email">Email <span class="asterisk">*</span></label>
									<input type="email" name="email" class="form-control" id="email" value="<?= isset($_POST['email']) ? $_POST['email'] : $STUDENT_EMAIL ?>" placeholder="Email" onkeyup="document.getElementById('uname').value = this.value;" onchange="document.getElementById('uname').value = this.value;">
									<span class="help-block"><?= (isset($errors['email'])) ? $errors['email'] : '' ?></span>
								</div>

								<div class="form-group col-sm-6 <?= (isset($errors['dob'])) ? 'has-error' : '' ?>">
									<label>Date Of Birth <span class="asterisk">*</span></label>
									<input class="form-control pull-right" name="dob" value="<?= isset($_POST['dob']) ? $_POST['dob'] : $STUDENT_DOB ?>" id="dob" type="text" max="2999-12-31">
									<span class="help-block"><?= (isset($errors['dob'])) ? $errors['dob'] : '' ?></span>
								</div>

								<div class="form-group col-sm-6 <?= (isset($errors['gender'])) ? 'has-error' : '' ?>">
									<label>Gender <span class="asterisk">*</span></label>
									<?php $gender = isset($_POST['gender']) ? $_POST['gender'] : $STUDENT_GENDER; ?>
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
									$STAFF_STATE = isset($_POST['state']) ? $_POST['state'] : $STUDENT_STATE;
									?>
									<select class="form-control select2" name="state" id="state" style="width: 100%;" onchange="getCitiesByState(this.value)">
										<?php echo $db->MenuItemsDropdown('states_master', "STATE_ID", "STATE_NAME", "STATE_ID, STATE_NAME", $STAFF_STATE, " ORDER BY STATE_NAME"); ?>
									</select>
									<span class="help-block"><?= (isset($errors['state'])) ? $errors['state'] : '' ?></span>
								</div>

								<div class="form-group col-sm-6 <?= (isset($errors['city'])) ? 'has-error' : '' ?>">
									<label>City</label>
									<?php $STUDENT_CITY = isset($_POST['city']) ? $_POST['city'] : $STUDENT_CITY;	?>
									<input type="text" class="form-control" placeholder="city" name="city" value="<?= isset($_POST['city']) ? $_POST['city'] : $STUDENT_CITY ?>">

									<span class="help-block"><?= (isset($errors['city'])) ? $errors['city'] : '' ?></span>
								</div>


								<div class="form-group col-sm-6 <?= (isset($errors['pincode'])) ? 'has-error' : '' ?>">
									<label for="pincode">Postcode</label>
									<input type="text" class="form-control" placeholder="Postcode" maxlength="6" name="pincode" value="<?= isset($_POST['pincode']) ? $_POST['pincode'] : $STUDENT_PINCODE ?>">
									<span class="help-block"><?= (isset($errors['pincode'])) ? $errors['pincode'] : '' ?></span>
								</div>

								<div class="form-group col-sm-6 <?= (isset($errors['per_add'])) ? 'has-error' : '' ?>">
									<label>Permanent Address</label>
									<textarea class="form-control" rows="3" placeholder="Permanent Address ..." name="per_add"><?= isset($_POST['per_add']) ? $_POST['per_add'] : $STUDENT_PER_ADD ?></textarea>
									<span class="help-block"><?= (isset($errors['per_add'])) ? $errors['per_add'] : '' ?></span>
								</div>

								<div class="form-group col-sm-6 <?= (isset($errors['refferal_code'])) ? 'has-error' : '' ?>">
									<label for="refferal_code">Refferal Code (If Any)</label>
									<input type="text" class="form-control" placeholder="Refferal Code (If Any)" name="refferal_code" value="<?= isset($_POST['refferal_code']) ? $_POST['refferal_code'] : $REFFERAL_CODE ?>">
									<span class="help-block"><?= (isset($errors['refferal_code'])) ? $errors['refferal_code'] : '' ?></span>
								</div>

								<div class="form-group col-sm-6 <?= (isset($errors['enquiry_date'])) ? 'has-error' : '' ?>">
									<label>Enquiry Date </label>
									<input class="form-control pull-right" name="enquiry_date" value="<?= isset($_POST['enquiry_date']) ? $_POST['enquiry_date'] : $ENQ_DATE ?>" id="enquiry_date" type="date" max="2999-12-31">
									<span class="help-block"><?= (isset($errors['enquiry_date'])) ? $errors['enquiry_date'] : '' ?></span>
								</div>

								<div class="form-group col-sm-6 <?= (isset($errors['remark'])) ? 'has-error' : '' ?>">
									<label>Remark</label>
									<textarea class="form-control" rows="3" placeholder="Remark" name="remark"><?= isset($_POST['remark']) ? $_POST['remark'] : $REMARK ?></textarea>
									<span class="help-block"><?= (isset($errors['remark'])) ? $errors['remark'] : '' ?></span>
								</div>
							</div>

							<div class="box-footer text-center">
								<input type="submit" class="btn btn-primary" name="action" value="Update Enquiry" /> &nbsp;&nbsp;&nbsp;
								<a href="page.php?page=studentEnquiry" class="btn btn-warning" title="Cancel">Cancel</a>
							</div>

						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>