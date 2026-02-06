<?php
$id = isset($_GET['id']) ? $_GET['id'] : '';
include_once('include/classes/student.class.php');
$student = new student();
$action = isset($_POST['submit_admission']) ? $_POST['submit_admission'] : '';
if ($action != '') {
	$result = $student->add_student_admission_enquiry();
	$result = json_decode($result, true);
	$success = isset($result['success']) ? $result['success'] : '';
	$message = isset($result['message']) ? $result['message'] : '';
	$errors = isset($result['errors']) ? $result['errors'] : '';
	if ($success == true) {
		$_SESSION['msg'] = $message;
		$_SESSION['msg_flag'] = $success;
		unset($_POST);
	}
}

$res = $db->get_inst_course_info($id);
if ($res != '') {
	extract($res);
	//print_r($res);
	if (!empty(isset($COURSE_FEES)) || isset($COURSE_FEES) != '') {
		$COURSE_FEES = $COURSE_FEES;
	}
	if (!empty(isset($MINIMUM_AMOUNT)) || isset($MINIMUM_AMOUNT) != '') {
		$MINIMUM_AMOUNT = $MINIMUM_AMOUNT;
	}
	if (!empty(isset($MULTI_SUB_COURSE_FEES)) || isset($MULTI_SUB_COURSE_FEES) != '') {
		$COURSE_FEES = $MULTI_SUB_COURSE_FEES;
	}
	if (!empty(isset($MULTI_SUB_MINIMUM_AMOUNT)) || isset($MULTI_SUB_MINIMUM_AMOUNT) != '') {
		$MINIMUM_AMOUNT = $MULTI_SUB_MINIMUM_AMOUNT;
	}
	if (!empty(isset($TYPING_COURSE_FEES)) || isset($TYPING_COURSE_FEES) != '') {
		$COURSE_FEES = $TYPING_COURSE_FEES;
	}
	if (!empty(isset($TYPING_MINIMUM_AMOUNT)) || isset($TYPING_MINIMUM_AMOUNT) != '') {
		$MINIMUM_AMOUNT = $TYPING_MINIMUM_AMOUNT;
	}
}

?>
<!-- rs-check-out Here-->
<div class="rs-check-out sec-spacer">
	<div class="container">
		<div class="row">
			<div class="col-lg-12 col-md-12 form1">
				<h3 class="title-bg">Student Admission Form</h3>
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

								<div class="col-md-6 form-group">
									<label>Select Your State <span class="asterisk">*</span></label>
									<select name="state" id="state" class="form-control selectpicker des" data-show-subtext="false" data-live-search="true" style="-webkit-appearance: none;">
										<option value=''>Please Select</option>
										<?php
										$state = isset($_POST['state']) ? $_POST['state'] : '';
										echo $db->MenuItemsDropdown('states_master', 'STATE_ID', 'STATE_NAME', 'STATE_ID,STATE_NAME', $state, ' ORDER BY STATE_NAME ASC'); ?>
										?>
									</select>
									<span class="help-block"><?= isset($errors['state']) ? $errors['state'] : '' ?></span>
								</div>

								<div class="col-md-6 form-group">
									<label>Select Your Institute <span class="asterisk">*</span></label>
									<select name="institute_id" id="institute_id" onchange="getInstituteId(this.value)" class="form-control selectpicker des" data-show-subtext="false" data-live-search="true" style="-webkit-appearance: none;">
										<option value=''>Please Select</option>
										<?php
										$institute_id = isset($_POST['institute_id']) ? $_POST['institute_id'] : '';
										echo $db->MenuItemsDropdown('institute_details', 'INSTITUTE_ID', 'INSTITUTE_NAME', "INSTITUTE_ID,CONCAT(INSTITUTE_NAME,' - ',CITY,' - ',TALUKA,' - ',POSTCODE,' - (', STATE_NAME,')' ) as INSTITUTE_NAME", $institute_id, ' LEFT JOIN states_master ON states_master.STATE_ID = institute_details.STATE WHERE DELETE_FLAG = 0 AND SHOW_ON_WEBSITE = 1 ORDER BY INSTITUTE_ID ASC');
										?>
									</select>
									<span class="help-block"><?= isset($errors['institute_id']) ? $errors['institute_id'] : '' ?></span>
								</div>


								<div class="col-md-1 form-group">
									<label>Abbreviation<span class="asterisk">*</span></label>
									<select name="abbreviation">
										<option value="Mr">Mr</option>
										<option value="Miss">Miss</option>
										<option value="Mrs">Mrs</option>
										<option value="Ms">Ms</option>
									</select>
									<span class="help-block"><?= isset($errors['abbreviation']) ? $errors['abbreviation'] : '' ?></span>
								</div>
								<div class="col-md-3 form-group">
									<label>Student Name<span class="asterisk">*</span></label>
									<input id="fname" name="fname" class="form-control" type="text">
									<span class="help-block"><?= isset($errors['fname']) ? $errors['fname'] : '' ?></span>
								</div>
								<div class="col-md-2 form-group">
									<label>Select One</label>
									<select name="sonof">
										<option class="form-control" value=" ">--Select One--</option>
										<option value="S/O">S/O</option>
										<option value="D/O">D/O</option>
										<option value="W/O">W/O</option>
									</select>
									<span class="help-block"><?= isset($errors['sonof']) ? $errors['sonof'] : '' ?></span>
								</div>
								<div class="col-md-2 form-group">
									<label>Father / Husband Name</label>
									<input name="mname" class="form-control" type="text">
									<span class="help-block"><?= isset($errors['mname']) ? $errors['mname'] : '' ?></span>
								</div>
								<div class="col-md-2 form-group">
									<label>Surname</label>
									<input name="lname" class="form-control" type="text">
									<span class="help-block"><?= isset($errors['lname']) ? $errors['lname'] : '' ?></span>
								</div>
								<div class="col-md-2 form-group">
									<label>Mother Name</label>
									<input name="mothername" class="form-control" type="text">
									<span class="help-block"><?= isset($errors['mothername']) ? $errors['mothername'] : '' ?></span>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6 form-group">
									<label>Course of interest<span class="asterisk">*</span></label>
									<?php $interested_course  = isset($_POST['interested_course']) ? $_POST['interested_course'] : $id; ?>
									<select class="form-control selectpicker des" data-show-subtext="false" data-live-search="true" style="-webkit-appearance: none;" name="interested_course" data-placeholder="Select a Course" id="coursename" onchange="getcoursefees()" required>
										<option name="" value=" ">Select a Course</option>
										<?php
										$sql = "SELECT A.INSTITUTE_COURSE_ID, A.COURSE_ID, A.MULTI_SUB_COURSE_ID, A.COURSE_TYPE, A.TYPING_COURSE_ID FROM institute_courses A WHERE A.DELETE_FLAG=0 AND A.ACTIVE=1";
										//echo $sql;
										$ex = $db->execQuery($sql);
										if ($ex && $ex->num_rows > 0) {
											while ($data = $ex->fetch_assoc()) {
												$INSTITUTE_COURSE_ID = $data['INSTITUTE_COURSE_ID'];
												$COURSE_ID 			 = $data['COURSE_ID'];
												$MULTI_SUB_COURSE_ID = $data['MULTI_SUB_COURSE_ID'];
												$TYPING_COURSE_ID       = $data['TYPING_COURSE_ID'];

												if ($COURSE_ID != '' && !empty($COURSE_ID) && $COURSE_ID != '0') {
													$course 			 = $db->get_course_detail($COURSE_ID);
													$course_name 		 = $course['COURSE_NAME_MODIFY'];
												}

												if ($MULTI_SUB_COURSE_ID != '' && !empty($MULTI_SUB_COURSE_ID) && $MULTI_SUB_COURSE_ID != '0') {
													$course 			 = $db->get_course_detail_multi_sub($MULTI_SUB_COURSE_ID);
													$course_name 		 = $course['COURSE_NAME_MODIFY'];
												}

												if ($TYPING_COURSE_ID != '' && !empty($TYPING_COURSE_ID) && $TYPING_COURSE_ID != '0') {
													$course 			 = $db->get_course_detail_typing($TYPING_COURSE_ID);
													$course_name 		 = $course['COURSE_NAME_MODIFY'];
												}

												if ($INSTITUTE_COURSE_ID === $id) {
													$selected = 'selected="selected"';
													echo '<option value="' . $INSTITUTE_COURSE_ID . '" ' . $selected . '>' . $course_name . '</option>';
												} else {
													echo '<option value="' . $INSTITUTE_COURSE_ID . '">' . $course_name . '</option>';
												}
											}
										}
										?>
									</select>
									<span class="help-block"><?= isset($errors['interested_course']) ? $errors['interested_course'] : '' ?></span>
								</div>

								<div class="col-md-6 form-group">
									<label>Student Mobile <span class="asterisk">*</span></label>
									<input id="mobile" name="mobile" class="form-control" type="text" pattern="\d*" id="mobile" maxlength="10" value="<?= isset($_POST['mobile']) ? $_POST['mobile'] : '' ?>" placeholder="Student Mobile">
									<span class="help-block"><?= isset($errors['mobile']) ? $errors['mobile'] : '' ?></span>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6 form-group">
									<label>Alternate Mobile</label>
									<input id="mobile2" name="mobile2" class="form-control" type="text" pattern="\d*" maxlength="10" value="<?= isset($_POST['mobile2']) ? $_POST['mobile2'] : '' ?>" placeholder="Alternate Mobile">
									<span class="help-block"><?= isset($errors['mobile2']) ? $errors['mobile2'] : '' ?></span>
								</div>
								<div class="col-md-6 form-group">
									<label>Email <span class="asterisk">*</span></label>
									<input id="email" name="email" class="form-control" type="email" placeholder="Email">
									<span class="help-block"><?= isset($errors['email']) ? $errors['email'] : '' ?></span>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6 form-group">
									<label>Date Of Birth <span class="asterisk">*</span></label>
									<input name="dob" class="form-control" type="date" autocomplete="off">
									<span class="help-block"><?= isset($errors['dob']) ? $errors['dob'] : '' ?></span>
								</div>
								<div class="col-md-6 form-group">
									<label>Gender</label>
									<select name="gender">
										<option value="">Please Select </option>
										<option value="male">Male </option>
										<option value="female">Female </option>
										<option value="other">Other </option>
									</select>
									<span class="help-block"><?= isset($errors['gender']) ? $errors['gender'] : '' ?></span>
								</div>
							</div>

							<div class="row">
								<div class="col-md-6 form-group">
									<label>State</label>
									<select name="state" class="form-control selectpicker des" data-show-subtext="false" data-live-search="true" style="-webkit-appearance: none;">
										<option>Please Select State</option>
										<?php
										$state = isset($_POST['state']) ? $_POST['state'] : '';
										echo $db->MenuItemsDropdown('states_master', 'STATE_ID', 'STATE_NAME', 'STATE_ID,STATE_NAME', $state, ' ORDER BY STATE_NAME ASC');
										?>
									</select>
									<span class="help-block"><?= isset($errors['state']) ? $errors['state'] : '' ?></span>
								</div>
								<div class="col-md-6 form-group">
									<label>City</label>
									<input name="city" class="form-control" type="text">
									<span class="help-block"><?= isset($errors['city']) ? $errors['city'] : '' ?></span>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-6 col-md-12">
									<div class="form-group">
										<label>Postcode</label>
										<input name="postcode" class="form-control" type="text" maxlength="6">
										<span class="help-block"><?= isset($errors['postcode']) ? $errors['postcode'] : '' ?></span>
									</div>
								</div>

								<div class="col-lg-6 col-md-12">
									<div class="form-group">
										<label>Permanent Address</label>
										<textarea name="per_add" class="form-control" rows="3"></textarea>
										<span class="help-block"><?= isset($errors['per_add']) ? $errors['per_add'] : '' ?></span>
									</div>
								</div>
							</div>

							<div class="col-md-6 form-group">
								<label>Aadhar Card Number</label>
								<input name="stud_photo_id_desc" class="form-control" type="text" maxlength="12" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
								<span class="help-block"><?= isset($errors['stud_photo_id_desc']) ? $errors['stud_photo_id_desc'] : '' ?></span>
							</div>


							<div class="col-lg-3 col-md-12">
								<div class="form-group">
									<label>Refferal Code (If Any)</label>
									<input name="refferal_code" class="form-control" type="text" value="<?= isset($_POST['refferal_code']) ? $_POST['refferal_code'] : '' ?>">
									<span class="help-block"><?= isset($errors['refferal_code']) ? $errors['refferal_code'] : '' ?></span>
								</div>
							</div>

							<div class="clearfix"></div>
							<input type="hidden" class="btn btn-sm btn-primary" name="examtype1" value="1">
							<input type="hidden" class="btn btn-sm btn-primary" name="examstatus1" value="2">

							<div class="clearfix"></div>
							<div class="row">
								<!-- Pay <?= $MINIMUM_AMOUNT ?> type="submit" name="submit_enquiry" id="payamount"  -->
								<input class="btn btn-primary submitButton" type="submit" value="Submit" name="submit_admission">
							</div>
						</fieldset>
					</form>
				</div><!-- .check-out-box end -->
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
				var minimumamount = data.minimumamount;
				var payamount = "Pay " + minimumamount;
				$("#coursefees").val(courseFees);
				$("#minimumamount").val(minimumamount);
				//$("#payamount").val(payamount);     
			}

		});
	}
</script>