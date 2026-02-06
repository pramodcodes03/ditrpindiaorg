<?php
$id = isset($id) ?? '';

$action	= isset($_POST['register']) ? $_POST['register'] : '';
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
		//$enquiry_id = isset($result['enquiry_id'])?$result['enquiry_id']:'';	
		//header('location:course-enquiry');

	}
}
?>
<!-- rs-check-out Here-->
<div class="rs-check-out sec-spacer">
	<div class="container">
		<div class="row">
			<div class="col-lg-12 col-md-12 form1">
				<h3 class="title-bg">Course Enquiry Form</h3>
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
					<form id="contact-form" action="" method="post">
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
								<div class="col-md-6 form-group">
									<label>Student Name <span class="asterisk"> * </span></label>
									<input id="fname" name="fname" class="form-control" type="text" value="<?= isset($_POST['fname']) ? $_POST['fname'] : '' ?>">
									<span class="help-block"><?= isset($errors['fname']) ? $errors['fname'] : '' ?></span>
								</div>


								<div class="col-md-6 form-group">
									<label>Course of interest <span class="asterisk"> * </span></label>
									<?php $interested_course  = isset($_POST['interested_course']) ? $_POST['interested_course'] : $id; ?>
									<select class="form-control selectpicker des" data-show-subtext="false" data-live-search="true" style="-webkit-appearance: none;" name="interested_course" data-placeholder="Select a Course">
										<option name="" value=" ">Select a Course</option>
										<?php
										$sql = "SELECT A.INSTITUTE_COURSE_ID, A.COURSE_ID, A.MULTI_SUB_COURSE_ID, A.TYPING_COURSE_ID FROM institute_courses A WHERE A.DELETE_FLAG=0 AND A.ACTIVE=1";
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
									<label>Student Mobile <span class="asterisk"> * </span></label>
									<input id="mobile" name="mobile" class="form-control" type="text" value="<?= isset($_POST['mobile']) ? $_POST['mobile'] : '' ?>" pattern="\d*" maxlength="10">
									<span class="help-block"><?= isset($errors['mobile']) ? $errors['mobile'] : '' ?></span>
								</div>


								<div class="col-md-6 form-group">
									<label>Alternate Mobile</label>
									<input id="mobile2" name="mobile2" class="form-control" type="text" value="<?= isset($_POST['mobile2']) ? $_POST['mobile2'] : '' ?>" pattern="\d*" maxlength="10">
									<span class="help-block"><?= isset($errors['mobile2']) ? $errors['mobile2'] : '' ?></span>
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

							<div class="rs-payment-system">
								<input class="btn btn-primary submitButton" type="submit" name="register" value="Submit">
							</div>
						</fieldset>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>