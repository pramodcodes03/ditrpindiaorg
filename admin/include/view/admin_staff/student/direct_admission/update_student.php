<?php
$stud_id = $db->test(isset($_GET['id']) ? $_GET['id'] : '');
$courseid = $db->test(isset($_GET['courseid']) ? $_GET['courseid'] : '');
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

	$result = $student->update_student_direct_admission();
	$result = json_decode($result, true);
	$success = isset($result['success']) ? $result['success'] : '';
	$message = isset($result['message']) ? $result['message'] : '';
	$errors = isset($result['errors']) ? $result['errors'] : '';
	if ($success == true) {
		$_SESSION['msg'] = $message;
		$_SESSION['msg_flag'] = $success;
		header('location:page.php?page=studentAdmission');
	}
}

$res = $student->list_student_direct_admission($stud_id, '', '', " AND A.INSTITUTE_COURSE_ID = $courseid");
if ($res != '') {
	$srno = 1;
	while ($data = $res->fetch_assoc()) {
		extract($data);
		//print_r($data); exit();
		$inst_course_id = $INSTITUTE_COURSE_ID;
	}
}
?>
<div class="content-wrapper">
	<div class="row">
		<div class="col-12 grid-margin stretch-card">
			<div class="card">
				<div class="card-body">
					<h4 class="card-title"> Update Student Admission</h4>

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
					<form role="form" class="form-validate" action="" method="post" enctype="multipart/form-data" onsubmit="pageLoaderOverlay('show');" id="add_student">
						<div class="box-body">

							<input type="hidden" name="enquiry_status" value="" />
							<div class="row">
								<input type="hidden" name="student_id" value="<?= $STUDENT_ID ?>" />
								<input type="hidden" name="stud_course_detail_id" value="<?= $STUD_COURSE_DETAIL_ID ?>" />
								<input type="hidden" name="interested_course" value="<?= $INSTITUTE_COURSE_ID ?>" />
								<input type="hidden" name="institute_id" value="<?= $institute_id	 ?>" />


								<div class="col-md-12 row">
									<?php
									$photo_path  = 'resources/dummy/dummy-photo.png';
									$photo_path_thumb  = 'resources/dummy/dummy-photo.png';
									$disp_photo = '<img src="resources/dummy/dummy-photo.png" class="img img-responsive thumbnail" style="width:130px; height:150px;" />';

									$sign_path  = 'resources/dummy/dummy-signature.png';
									$sign_path_thumb  = 'resources/dummy/dummy-signature.png';
									$sign_photo = '<img src="resources/dummy/dummy-signature.png" class="img img-responsive thumbnail" style="width:220px; height:70px;" />';


									$photo = $student->get_student_docs($STUDENT_ID, ' AND FILE_LABEL="' . STUD_PHOTO . '"');
									if (!empty($photo)) {
										foreach ($photo as $fileId => $photoinfo)
											extract($photoinfo);
										$photo_path  = STUDENT_DOCUMENTS_PATH . '/' . $STUDENT_ID . '/' . $FILE_NAME;
										$photo_path_thumb  = STUDENT_DOCUMENTS_PATH . '/' . $STUDENT_ID . '/' . $FILE_NAME;
										$disp_photo = '<div id="img-' . $FILE_ID . '">';
										$disp_photo .= '<a href="javascript:void(0)" onclick="deleteStudFile(' . $FILE_ID . ')"><i class="fa fa-trash"></i></a>';
										$disp_photo .= '<a href="' . $photo_path . '" target="_blank"><img id="stud_photo" src="' . $photo_path_thumb . '" class="img img-responsive thumbnail" style="width:130px; height:150px;border: 1px solid #000;" /></a>	';
										$disp_photo .= '</div>';
									}

									$sign = $student->get_student_docs($STUDENT_ID, ' AND FILE_LABEL="' . STUD_PHOTO_SIGN . '"');
									if (!empty($sign)) {
										foreach ($sign as $fileId => $signinfo)
											extract($signinfo);
										$sign_path  = STUDENT_DOCUMENTS_PATH . '/' . $STUDENT_ID . '/' . $FILE_NAME;
										$sign_path_thumb  = STUDENT_DOCUMENTS_PATH . '/' . $STUDENT_ID . '/' . $FILE_NAME;
										$disp_sign = '<div id="img-' . $FILE_ID . '">';
										$disp_sign .= '<a href="javascript:void(0)" onclick="deleteStudFile(' . $FILE_ID . ')"><i class="fa fa-trash"></i></a>';
										$disp_sign .= '<a href="' . $sign_path . '" target="_blank"><img id="stud_sign" src="' . $sign_path_thumb . '" class="img img-responsive thumbnail" style="width:220px; height:70px;border: 1px solid #000;" /></a>	';
										$disp_sign .= '</div>';
									}
									?>
									<div class="form-group col-md-3 <?= (isset($errors['stud_photo'])) ? 'has-error' : '' ?>">
										<?= $disp_photo ?>
										<label for="stud_photo">Student Photo </label> <span> (Max file size 250 KB)</span>
										<input type="file" name="stud_photo" onchange="readURL1(this);">
										<p class="help-block"><?= (isset($errors['stud_photo'])) ? $errors['stud_photo'] : '' ?></p>
									</div>

									<div class="form-group col-md-3 <?= (isset($errors['stud_sign'])) ? 'has-error' : '' ?>">
										<?= $disp_sign ?>
										<label for="stud_sign">Student Signature </label> <span> (Max file size 250 KB)</span>
										<input type="file" name="stud_sign" onchange="readURL2(this);">
										<p class="help-block"><?= (isset($errors['stud_sign'])) ? $errors['stud_sign'] : '' ?></p>
									</div>

									<div class="form-group col-sm-3 <?= (isset($errors['roll_number'])) ? 'has-error' : '' ?>">
										<label for="roll_number">Roll Number <span class="asterisk">*</span></label>
										<input type="text" name="roll_number" class="form-control" value="<?= isset($_POST['roll_number']) ? $_POST['roll_number'] : $ROLL_NUMBER ?>" id="roll_number" placeholder="Enter Roll Number">
										<span class="help-block"><?= isset($errors['roll_number']) ? $errors['roll_number'] : '' ?></span>
									</div>
								</div>

								<div class="form-group col-sm-1" style="width:110px;">
									<label for="abbreviation">Abbreviation</label>
									<?php $ABBREVIATION = isset($_POST['abbreviation']) ? $_POST['abbreviation'] : $ABBREVIATION;	?>
									<select class="form-control" name="abbreviation">
										<option class="form-control" value="Mr" <?php echo ($ABBREVIATION == 'MR') ? 'selected="selected"' : '' ?>>Mr.</option>
										<option class="form-control" value="Miss" <?php echo ($ABBREVIATION == 'MISS') ? 'selected="selected"' : '' ?>>Miss </option>
										<option class="form-control" value="Mrs" <?php echo ($ABBREVIATION == 'MRS') ? 'selected="selected"' : '' ?>>Mrs</option>
										<option class="form-control" value="Ms" <?php echo ($ABBREVIATION == 'MS') ? 'selected="selected"' : '' ?>>Ms</option>
									</select>
								</div>
								<div class="form-group col-sm-3 <?= (isset($errors['fname'])) ? 'has-error' : '' ?>">
									<label for="fname">Student Name <span class="asterisk">*</span></label>
									<input type="text" name="fname" class="form-control" value="<?= isset($_POST['fname']) ? $_POST['fname'] : $STUDENT_FNAME ?>" id="fname" placeholder="Enter Student Name">
									<span class="help-block"><?= isset($errors['fname']) ? $errors['fname'] : '' ?></span>
								</div>

								<div class="form-group col-sm-2" style="width:110px;">
									<label for="sonof">Select One</label>
									<?php $SONOF = isset($_POST['sonof']) ? $_POST['sonof'] : $SONOF;	?>
									<select class="form-control" name="sonof">
										<option class="form-control" value=" ">--Select One--</option>
										<option class="form-control" value="S/O" <?php echo ($SONOF == 'S/O') ? 'selected="selected"' : '' ?>>S/O</option>
										<option class="form-control" value="D/O" <?php echo ($SONOF == 'D/O') ? 'selected="selected"' : '' ?>>D/O</option>
										<option class="form-control" value="W/O" <?php echo ($SONOF == 'W/O') ? 'selected="selected"' : '' ?>>W/O</option>
									</select>
								</div>

								<div class="form-group col-sm-2 <?= (isset($errors['mname'])) ? 'has-error' : '' ?>">
									<label for="mname">Father / Husband Name </label>
									<input type="text" name="mname" class="form-control" value="<?= isset($_POST['mname']) ? $_POST['mname'] : $STUDENT_MNAME ?>" id="mname" placeholder="Father Name / Husband Name">
									<?php $cert_mname = isset($_POST['cert_mname']) ? $_POST['cert_mname'] : $CERT_MNAME;
									?>
									<a href="javascript:void(0)" tabindex="-1" class="info-popup">Show on Certificate:</a>
									<input type="checkbox" tabindex="-1" name="cert_mname" value="1" <?= ($cert_mname == '1') ? 'checked="true"' : '' ?> />
									<span class="help-block"><?= isset($errors['mname']) ? $errors['mname'] : '' ?></span>
								</div>
								<div class="form-group col-sm-2 <?= (isset($errors['lname'])) ? 'has-error' : '' ?>">
									<label for="lname">Surname Name</label>
									<input type="text" name="lname" class="form-control" value="<?= isset($_POST['lname']) ? $_POST['lname'] : $STUDENT_LNAME ?>" id="lname" placeholder="Enter Surname">
									<?php $cert_lname = isset($_POST['cert_lname']) ? $_POST['cert_lname'] : $CERT_LNAME;
									?>
									<a href="javascript:void(0)" tabindex="-1" class="info-popup">Show on Certificate:</a>
									<input type="checkbox" tabindex="-1" name="cert_lname" value="1" <?= ($cert_lname == '1') ? 'checked="true"' : '' ?> />
									<span class="help-block"><?= isset($errors['lname']) ? $errors['lname'] : '' ?></span>
								</div>
								<div class="form-group col-sm-2 <?= (isset($errors['mothername'])) ? 'has-error' : '' ?>">
									<label for="mothername">Mother Name</label>
									<input type="text" name="mothername" class="form-control" value="<?= isset($_POST['mothername']) ? $_POST['mothername'] : $STUDENT_MOTHERNAME ?>" id="mothername" placeholder="Mother Name ">
									<span class="help-block"><?= isset($errors['mothername']) ? $errors['mothername'] : '' ?></span>
								</div>



								<div class="form-group col-sm-4 <?= (isset($errors['interested_course'])) ? 'has-error' : '' ?>">
									<label for="interested_course">Course of interest <span class="asterisk">*</span></label>
									<?php $interested_course  = isset($_POST['interested_course']) ? $_POST['interested_course'] : $INSTITUTE_COURSE_ID; ?>
									<select class="form-control" name="interested_course" data-placeholder="Select a Course" id="coursename" onchange="getcoursefees()" disabled="disabled" required>
										<option name="" value="">Select a Course</option>
										<?php
										$sql = "SELECT A.INSTITUTE_COURSE_ID, A.COURSE_ID, A.MULTI_SUB_COURSE_ID, A.COURSE_TYPE, A.TYPING_COURSE_ID FROM institute_courses A WHERE  A.INSTITUTE_ID='$institute_id' AND A.DELETE_FLAG=0 AND A.ACTIVE=1";
										//echo $sql;
										$ex = $db->execQuery($sql);
										if ($ex && $ex->num_rows > 0) {
											while ($data = $ex->fetch_assoc()) {
												$INSTITUTE_COURSE_ID = $data['INSTITUTE_COURSE_ID'];
												$COURSE_ID 			 = $data['COURSE_ID'];
												$MULTI_SUB_COURSE_ID = $data['MULTI_SUB_COURSE_ID'];
												$TYPING_COURSE_ID 	 = $data['TYPING_COURSE_ID'];

												if ($COURSE_ID != ''  && !empty($COURSE_ID) && $COURSE_ID != '0') {
													$course 			 = $db->get_course_detail($COURSE_ID);
													$course_name 		 = $course['COURSE_NAME_MODIFY'];
												}

												if ($MULTI_SUB_COURSE_ID != ''  && !empty($MULTI_SUB_COURSE_ID) && $MULTI_SUB_COURSE_ID != '0') {
													$course 			 = $db->get_course_detail_multi_sub($MULTI_SUB_COURSE_ID);
													$course_name 		 = $course['COURSE_NAME_MODIFY'];
												}

												if ($TYPING_COURSE_ID != '' && !empty($TYPING_COURSE_ID) && $TYPING_COURSE_ID != '0') {
													$course = $db->get_course_detail_typing($TYPING_COURSE_ID);
													$course_name 	= $course['COURSE_NAME_MODIFY'];
												}

												//$selected = (is_array($interested_course) && in_array($INSTITUTE_COURSE_ID,$interested_course))?'selected="selected"':'';

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


								<div class="form-group col-sm-4 <?= (isset($errors['mobile'])) ? 'has-error' : '' ?>">
									<label for="mobile">Student Mobile <span class="asterisk">*</span></label>
									<input type="text" name="mobile" class="form-control" pattern="\d*" id="mobile" maxlength="10" value="<?= isset($_POST['mobile']) ? $_POST['mobile'] : $STUDENT_MOBILE ?>" placeholder="Student Mobile">
									<span class="help-block"><?= (isset($errors['mobile'])) ? $errors['mobile'] : '' ?></span>
								</div>

								<div class="form-group col-sm-4 <?= (isset($errors['mobile2'])) ? 'has-error' : '' ?>">
									<label for="mobile2">Alternate Mobile</label>
									<input type="text" name="mobile2" class="form-control" pattern="\d*" id="mobile2" maxlength="10" value="<?= isset($_POST['mobile2']) ? $_POST['mobile2'] : $STUDENT_MOBILE2 ?>" placeholder="Alternate Mobile">
									<span class="help-block"><?= (isset($errors['mobile2'])) ? $errors['mobile2'] : '' ?></span>
								</div>


								<div class="form-group col-sm-4 <?= (isset($errors['email'])) ? 'has-error' : '' ?>">
									<label for="email">Email</label>
									<input type="email" name="email" class="form-control" id="email" value="<?= isset($_POST['email']) ? $_POST['email'] : $STUDENT_EMAIL ?>" placeholder="Email" onkeyup="document.getElementById('uname').value = this.value;" onchange="document.getElementById('uname').value = this.value;">
									<span class="help-block"><?= (isset($errors['email'])) ? $errors['email'] : '' ?></span>
								</div>

								<div class="form-group col-sm-4 <?= (isset($errors['dob'])) ? 'has-error' : '' ?>">
									<label>Date Of Birth <span class="asterisk">*</span></label>
									<input class="form-control pull-right" name="dob" value="<?= isset($_POST['dob']) ? $_POST['dob'] : $STUDENT_DOB ?>" id="dob" type="date" autocomplete="off" max="2999-12-31">
									<span class="help-block"><?= (isset($errors['dob'])) ? $errors['dob'] : '' ?></span>
									<!-- /.input group -->
								</div>


								<div class="form-group col-sm-4 <?= (isset($errors['gender'])) ? 'has-error' : '' ?>">
									<label>Gender </label>
									<?php $gender = isset($_POST['gender']) ? $_POST['gender'] : $STUDENT_GENDER; ?>
									<select class="form-control" name="gender" id="gender">
										<option <?= ($gender == '') ? 'selected="selected"' : '' ?> value="">--select--</option>
										<option value="male" <?= ($gender == 'male') ? 'selected="selected"' : '' ?>>Male</option>
										<option value="female" <?= ($gender == 'female') ? 'selected="selected"' : '' ?>>Female</option>
										<option value="other" <?= ($gender == 'other') ? 'selected="selected"' : '' ?>>Other</option>
									</select>
									<span class="help-block"><?= (isset($errors['gender'])) ? $errors['gender'] : '' ?></span>
								</div>

								<div class="form-group col-sm-4 <?= (isset($errors['state'])) ? 'has-error' : '' ?>">
									<label>State</label>
									<?php
									$STUDENT_STATE = isset($_POST['state']) ? $_POST['state'] : $STUDENT_STATE;
									?>
									<select class="form-control select2 " name="state" id="state" style="width: 100%;" onchange="getCitiesByState(this.value)">
										<?php echo $db->MenuItemsDropdown('states_master', "STATE_ID", "STATE_NAME", "STATE_ID, STATE_NAME", $STUDENT_STATE, " ORDER BY STATE_NAME"); ?>
									</select>
									<span class="help-block"><?= (isset($errors['state'])) ? $errors['state'] : '' ?></span>
								</div>

								<div class="form-group col-sm-4 <?= (isset($errors['city'])) ? 'has-error' : '' ?>">
									<label>City</label>
									<?php $STUDENT_CITY = isset($_POST['city']) ? $_POST['city'] : $STUDENT_CITY;	?>
									<input class="form-control" type="text" name="city" id="city" value="<?= isset($_POST['city']) ? $_POST['city'] : $STUDENT_CITY ?>">
									<span class="help-block"><?= (isset($errors['city'])) ? $errors['city'] : '' ?></span>
								</div>


								<div class="form-group col-sm-4<?= (isset($errors['pincode'])) ? 'has-error' : '' ?>">
									<label for="pincode">Postcode</label>
									<input type="text" class="form-control" placeholder="Postcode" maxlength="6" name="pincode" value="<?= isset($_POST['pincode']) ? $_POST['pincode'] : $STUDENT_PINCODE ?>">
									<span class="help-block"><?= (isset($errors['pincode'])) ? $errors['pincode'] : '' ?></span>
								</div>


								<div class="form-group col-sm-4 <?= (isset($errors['per_add'])) ? 'has-error' : '' ?>">
									<label>Permanent Address</label>
									<textarea class="form-control" rows="3" placeholder="Permanent Address ..." name="per_add"><?= isset($_POST['per_add']) ? $_POST['per_add'] : $STUDENT_PER_ADD ?></textarea>
									<span class="help-block"><?= (isset($errors['per_add'])) ? $errors['per_add'] : '' ?></span>
								</div>

								<div class="form-group col-sm-4 <?= (isset($errors['examtype1'])) ? 'has-error' : '' ?>">
									<label for="exampleInputName2">Select Exam Type <span class="asterisk">*</span></label>
									<?php $examtype1 = isset($_POST['examtype1']) ? $_POST['examtype1'] : $EXAM_TYPE; ?>
									<select class="form-control" name="examtype1" id="examtype">
										<?php echo $db->MenuItemsDropdown('exam_types_master', "EXAM_TYPE_ID", "EXAM_TYPE", "EXAM_TYPE_ID, EXAM_TYPE", $examtype1, " WHERE ACTIVE=1 AND DELETE_FLAG=0"); ?>
									</select>
								</div>
								<input type="hidden" class="btn btn-sm btn-primary" name="examstatus1" value="2">


								<div class="form-group col-sm-4 <?= (isset($errors['stud_photo_id_desc'])) ? 'has-error' : '' ?>">
									<label for="stud_photo_id_desc">Aadhar Card Number</label>
									<input type="text" class="form-control" placeholder="Aadhar Card Number" name="stud_photo_id_desc" value="<?= isset($_POST['stud_photo_id_desc']) ? $_POST['stud_photo_id_desc'] : $STUDENT_ADHAR_NUMBER ?>" maxlength="12" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
									<span class="help-block"><?= (isset($errors['stud_photo_id_desc'])) ? $errors['stud_photo_id_desc'] : '' ?></span>
								</div>

								<div class="form-group col-sm-4 <?= (isset($errors['caste'])) ? 'has-error' : '' ?>">
									<label for="caste">Caste</label>
									<input type="text" class="form-control" placeholder="Caste" name="caste" value="<?= isset($_POST['caste']) ? $_POST['caste'] : $CASTE ?>">
									<span class="help-block"><?= (isset($errors['caste'])) ? $errors['caste'] : '' ?></span>
								</div>

								<div class="form-group col-sm-4 <?= (isset($errors['qualification'])) ? 'has-error' : '' ?>">
									<label for="qualification">Qualification</label>
									<input type="text" class="form-control" placeholder="Qualification" name="qualification" value="<?= isset($_POST['qualification']) ? $_POST['qualification'] : $EDUCATIONAL_QUALIFICATION ?>">
									<span class="help-block"><?= (isset($errors['qualification'])) ? $errors['qualification'] : '' ?></span>
								</div>

								<div class="form-group col-sm-4 <?= (isset($errors['occupation'])) ? 'has-error' : '' ?>">
									<label for="occupation">Occupation</label>
									<input type="text" class="form-control" placeholder="Occupation" name="occupation" value="<?= isset($_POST['occupation']) ? $_POST['occupation'] : $OCCUPATION ?>">
									<span class="help-block"><?= (isset($errors['occupation'])) ? $errors['occupation'] : '' ?></span>
								</div>

								<div class="form-group col-md-12">

									<label>Installment Details</label>
									<table class="table table-bordered">
										<thead>
											<tr>
												<th>Installment Name</th>
												<th>Amount</th>
												<th>Date</th>
											</tr>
										</thead>
										<tbody id="courses-rows">

											<?php
											$docData = $student->get_student_installments($STUDENT_ID, $inst_course_id, false);
											$sr = 0;
											if (!empty($docData)) {

												foreach ($docData as $key => $value) {
													extract($value);
											?>
													<input type="hidden" name="installment_id<?= $sr ?>" value="<?= $INSTALLMENT_ID ?>" />
													<tr>
														<td>
															<?php $installment_name = isset($_POST['installment_name' . $sr]) ? $_POST['installment_name' . $sr] : $INSTALLMENT_NAME; ?>
															<input type="text" class="form-control" name="installment_name<?= $sr ?>" id="installment_name<?= $sr ?>" value="<?= isset($_POST['installment_name' . $sr]) ? $_POST['installment_name' . $sr] : $installment_name ?>" />
														</td>

														<td>
															<input type="text" class="form-control" name="installment_amount<?= $sr ?>" id="installment_amount<?= $sr ?>" value="<?= isset($_POST['installment_amount' . $sr]) ? $_POST['installment_amount' . $sr] : $AMOUNT ?>" />
														</td>

														<td>
															<input type="date" class="form-control" name="installment_date<?= $sr ?>" id="installment_date<?= $sr ?>" value="<?= isset($_POST['installment_date' . $sr]) ? $_POST['installment_date' . $sr] : $DATE ?>" max="2999-12-31" />
														</td>

													</tr>

											<?php
													$sr++;
												}
											}
											?>
											<input type="hidden" name="filecount4" id="filecount4" value="<?= $sr ?>" />

											<a href="javascript:void(0)" class="btn btn-warning" onclick="addMoreInstallments()" style="padding: 10px 14px;margin-bottom: 20px;"><i class="fa fa-plus"></i> Add More</a>

											<tr id="add_more_installments">

											</tr>
										</tbody>
									</table>
								</div>

								<div class="form-group col-sm-4 <?= (isset($errors['batch'])) ? 'has-error' : '' ?>">
									<label> Select Batch For Student <span class="asterisk">*</span></label>
									<?php $batch = isset($_POST['batch']) ? $_POST['batch'] : $BATCH_ID; ?>
									<select class="form-control select2 " name="batch" id="batch" style="width: 100%;" onchange="seeRemaining(this.value,<?= $institute_id ?>)">
										<?php echo $db->MenuItemsDropdown('course_batches', "id", "batch_name", "id, batch_name", $batch, " WHERE delete_flag = 0 AND inst_id = $institute_id ORDER BY id"); ?>
									</select>
									<span class="help-block"><?= (isset($errors['batch'])) ? $errors['batch'] : '' ?></span>
								</div>
								<div class="form-group col-sm-4">
									<label> Remaining Seats For This Batch <span class="asterisk">*</span> </label>
									<input type="text" class="remaining form-control" name="remainingStudent" id="remainingStudent" readonly value="" />
									<span class="help-block"><?= (isset($errors['remainingStudent'])) ? $errors['remainingStudent'] : '' ?></span>
								</div>

								<div class="form-group col-sm-4 <?= (isset($errors['admission_date'])) ? 'has-error' : '' ?>">
									<label>Admission Date <span class="asterisk"></span></label>
									<input class="form-control pull-right" name="admission_date" value="<?= isset($_POST['admission_date']) ? $_POST['admission_date'] : $ADMISSION_DATE ?>" id="admission_date" type="date" autocomplete="off" max="2999-12-31">
									<span class="help-block"><?= (isset($errors['admission_date'])) ? $errors['admission_date'] : '' ?></span>
								</div>

								<div class="col-md-12 form-group row">
									<?php $display_status = isset($_POST['display_status']) ? $_POST['display_status'] : $DISPLAY_FORM_STATUS;  ?>
									<label class="col-sm-4 col-form-label">Display Admission Form / Id Card / Fees Receipt</label>
									<div class="col-sm-1">
										<div class="form-check">
											<label class="form-check-label">
												<input type="radio" class="form-check-input" name="display_status" id="optionsRadios1" value="1" <?= ($display_status == 1) ? "checked=''" : ''  ?>>
												Yes
											</label>
										</div>
									</div>
									<div class="col-sm-1">
										<div class="form-check">
											<label class="form-check-label">
												<input type="radio" class="form-check-input" name="display_status" id="optionsRadios2" value="0" <?= ($display_status == 0) ? "checked=''" : ''  ?>>
												No
											</label>
										</div>
									</div>
								</div>

							</div>
							<div class="row">
								<div class="box-footer text-center">
									<input type="submit" class="btn btn-primary" name="action" value="Update Admission" />
									&nbsp;&nbsp;&nbsp;

									<a href="page.php?page=studentAdmission" class="btn btn-danger" title="Cancel">Cancel</a>
									&nbsp;&nbsp;&nbsp;
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	function minmax(value, min, max) {
		if (parseInt(value) < min || isNaN(parseInt(value)))
			return 0;
		else if (parseInt(value) > max)
			return 50;
		else {
			calPracticalResult();
			return value;
		}
	}

	//photo and signature
	function readURL1(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();

			reader.onload = function(e) {
				$('#stud_photo')
					.attr('src', e.target.result)
					.width(130)
					.height(150);
			};

			reader.readAsDataURL(input.files[0]);
		}
	}

	function readURL2(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();

			reader.onload = function(e) {
				$('#stud_sign')
					.attr('src', e.target.result)
					.width(220)
					.height(70);
			};

			reader.readAsDataURL(input.files[0]);
		}
	}

	function getcoursefees() {
		var instcourseid = $("#coursename").val();
		//alert(instcourseid);

		$.ajax({
			type: 'post',
			url: '/admin/include/classes/ajax.php',
			data: {
				action: 'get_inst_course_fees_enquiry',
				instcourseid: instcourseid
			},
			success: function(data) {
				//console.log(data);
				var data = JSON.parse(data);
				var courseFees = data.coursefees;
				var minAmount = data.minamount;
				var balanceFees = data.balance;
				$("#coursefees").val(courseFees);
				$("#totalcoursefee").val(courseFees);
				$("#amtrecieved").val(minAmount);
				$("#amtbalance").val(balanceFees);
			}

		});
	}
</script>