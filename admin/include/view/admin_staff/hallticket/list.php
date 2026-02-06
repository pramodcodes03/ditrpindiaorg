 <?php
	include('include/classes/exam.class.php');
	$exam = new exam();

	$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
	$user_role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : '';
	if ($user_role == 5) {
		$institute_id = $db->get_parent_id($user_role, $user_id);
		$staff_id = $user_id;
	} else {
		$institute_id = $user_id;
		$staff_id = 0;
	}
	$studid 		= $db->test(isset($_REQUEST['studid']) ? $_REQUEST['studid'] : '');
	$courseid	 	= $db->test(isset($_REQUEST['courseid']) ? $_REQUEST['courseid'] : '');
	$coursetype 	= $db->test(isset($_REQUEST['coursetype']) ? $_REQUEST['coursetype'] : '');
	$examstatus 	= $db->test(isset($_REQUEST['examstatus']) ? $_REQUEST['examstatus'] : '');
	$examtype 		= $db->test(isset($_REQUEST['examtype']) ? $_REQUEST['examtype'] : '');

	$res 			= $exam->filter_aicpe_exams($studid, $institute_id, $courseid, $examtype, 2);

	$action = isset($_POST['action']) ? $_POST['action'] : '';
	$checkstud = isset($_POST['checkstud']) ? $_POST['checkstud'] : '';
	if ($action == 'applyforexam') {
		$result = $exam->add_student_exam();
		$result = json_decode($result, true);
		$success = isset($result['success']) ? $result['success'] : '';
		$message = isset($result['message']) ? $result['message'] : '';
		$errors = isset($result['errors']) ? $result['errors'] : '';
		$invalid = isset($result['invalid']) ? $result['invalid'] : array();
		$photo = isset($result['photo']) ? $result['photo'] : array();
		$photo_id = isset($result['photo_id']) ? $result['photo_id'] : array();

		//print_r($result);
		if ($success == true) {
			$_SESSION['msg'] = $message;
			$_SESSION['msg_flag'] = $success;
			header('location:page.php?page=resetExam');
		}
	}
	?>
 <div class="content-wrapper">
 	<div class="col-lg-12 stretch-card">
 		<div class="card">
 			<div class="card-body">
 				<h4 class="card-title"> List Students Hallticket </h4>
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
 					<div class="col-md-12">

 						<form action="print.php" method="get" class="form-inline" onsubmit="return confirm('Confirm! Are You Want To Generate Hall Ticket?'); pageLoaderOverlay('show')" target="_blank">
 							<input type="hidden" name="page" value="print-hallticket">
 							<div class="row">
 								<div class="box box-warning">
 									<div class="row col-md-12">
 										<div class="form-group  col-md-3">
 											<label for="examdate">Exam Date:</label>
 											<div class="col-md-12 <?= (isset($errors['examdate'])) ? 'has-error' : '' ?>" style="padding:0px;">
 												<?php $examdate = isset($_POST['examdate']) ? $_POST['examdate'] : ''; ?>
 												<input type="date" name="examdate" class="form-control" id="dob" value="<?php echo $examdate ?>" placeholder="Select Exam Date" style="width: inherit;">
 											</div>

 										</div>
 										<div class="form-group  col-md-3">
 											<label for="starthh">Start Time:</label>
 											<div class="<?= (isset($errors['starthh'])) ? 'has-error' : '' ?>">
 												<?php
													$starthh = isset($_POST['starthh']) ? $_POST['starthh'] : '';
													$startmm = isset($_POST['startmm']) ? $_POST['startmm'] : '';
													$starttime = isset($_POST['starttime']) ? $_POST['starttime'] : '';
													?>
 												<input type="text" name="starthh" class="form-control" placeholder="HH" value="<?php echo $starthh ?>" style="width:28%;">
 												<input type="text" name="startmm" class="form-control" placeholder="MM" value="<?php echo $startmm ?>" style="width:28%;">
 												<select class="form-control" name="starttime" id="starttime">
 													<option value="AM">AM </option>
 													<option value="PM">PM </option>
 												</select>
 											</div>
 										</div>


 										<div class="form-group  col-md-3">
 											<label for="endhh">End Time:</label>
 											<div class="<?= (isset($errors['endhh'])) ? 'has-error' : '' ?>">
 												<?php
													$endhh = isset($_POST['endhh']) ? $_POST['endhh'] : '';
													$endmm = isset($_POST['endmm']) ? $_POST['endmm'] : '';
													$endtime = isset($_POST['endtime']) ? $_POST['endtime'] : '';
													?>
 												<input type="text" name="endhh" class="form-control" placeholder="HH" value="<?php echo $endhh ?>" style="width:28%;">
 												<input type="text" name="endmm" class="form-control" placeholder="MM" value="<?php echo $endmm ?>" style="width:28%;">
 												<select class="form-control" name="endtime" id="endtime">
 													<option value="AM">AM </option>
 													<option value="PM">PM </option>
 												</select>
 											</div>
 										</div>

 										<div class="form-group  col-md-3">
 											<label for="reporthh">Reporting Time:</label>
 											<div class="<?= (isset($errors['reporthh'])) ? 'has-error' : '' ?>">
 												<?php
													$reporthh = isset($_POST['reporthh']) ? $_POST['reporthh'] : '';
													$reportmm = isset($_POST['reportmm']) ? $_POST['reportmm'] : '';
													$reporttime = isset($_POST['reporttime']) ? $_POST['reporttime'] : '';
													?>
 												<input type="text" name="reporthh" class="form-control" placeholder="HH" value="<?php echo $reporthh ?>" style="width:28%;">
 												<input type="text" name="reportmm" class="form-control" placeholder="MM" value="<?php echo $reportmm ?>" style="width:28%;">
 												<select class="form-control" name="reporttime" id="reporttime">
 													<option value="AM">AM </option>
 													<option value="PM">PM </option>
 												</select>
 											</div>
 										</div>
 										<div class="col-md-2" style="margin-top: 25px;">
 											<input type="submit" class="btn btn-primary" name="submit" value="Generate Hallticket" />
 										</div>
 									</div>
 								</div>
 							</div>


 							<div class="col-md-12" style="margin-top:25px">
 								<div class="table-responsive pt-3">
 									<table id="order-listing" class="table">
 										<thead>
 											<tr>
 												<?php if ($db->permission('add_stud_exam')) { ?>
 													<th><input type="checkbox" name="selectall" id="selectall" /></th>
 												<?php } else echo '<th>#</th>'; ?>

 												<th>Photo</th>
 												<th>Student Name</th>
 												<th>Course Name</th>
 												<th>Course Fees</th>
 												<th>Exam Mode</th>
 												<th>Balance Fees</th>
 											</tr>
 										</thead>
 										<tbody>
 											<?php

												if ($res != '') {
													$courseSrNo = 1;
													while ($courseData = $res->fetch_assoc()) {

														extract($courseData);

														$EXAM_MODE_TYPE = array();
														$COURSE_NAME 	= $db->get_inst_course_name($INSTITUTE_COURSE_ID);
														if ($ACTIVE == 1)
															$ACTIVE = '<a href="javascript:void(0)" style="color:#3c763d" onclick="changeStudentCourseStatus(' . $STUD_COURSE_DETAIL_ID . ',0)"><i class="fa fa-check"></i></a>';
														elseif ($ACTIVE == 0)
															$ACTIVE = '<a href="javascript:void(0)" style="color:#f00" onclick="changeStudentCourseStatus(' . $STUD_COURSE_DETAIL_ID . ',1)"><i class="fa fa-times"></i></a>';


														$action = '';
														$reset = '';
														if ($db->permission('delete_stud_exam'))
															$action .= "<a href='javascript:void(0)' onclick='deleteStudentExamDetail($STUD_COURSE_DETAIL_ID)' class='btn btn-link' title='Delete'><i class=' fa fa-trash'></i></a>";

														if ($EXAM_STATUS == 3 && !$exam->check_certificate_applied($STUD_COURSE_DETAIL_ID))
															$reset = "&nbsp;&nbsp;&nbsp;<a href='javascript:void(0)' class='resetexam btn btn-xs btn-primary' title='Reset' id='rest$STUD_COURSE_DETAIL_ID'><i class=' fa fa-refresh'></i> Reset</a>";

														$examstatus_class = '';
														switch ($EXAM_STATUS) {
															case ('1'):
																$examstatus_class = 'label-warning';
																break;
															case ('2'):
																$examstatus_class = 'label-success';
																break;
															case ('3'):
																$examstatus_class = 'label-info';
																break;
															default:
																$examstatus_class 	 = 'label-primary';
																break;
														}
														$examtype_class = '';
														switch ($EXAM_TYPE) {
															case ('1'):
																$examtype_class = 'btn-success';
																break;
															case ('2'):
																$examtype_class = 'btn-danger';
																break;
															case ('3'):
																$examtype_class = 'btn-warning';
																break;
															default:
																$examtype_class = 'btn-primary';
																break;
														}
														$STUDENT_PHOTO = ($STUDENT_PHOTO != '') ? STUDENT_DOCUMENTS_PATH . '/' . $STUDENT_ID . '/' . $STUDENT_PHOTO : '../uploads/default_user.png';
														/* ----------------------------------------------------------------- */
														$instcourse = $db->get_inst_course_info($INSTITUTE_COURSE_ID);
														$COURSE_ID = isset($instcourse['COURSE_ID']) ? $instcourse['COURSE_ID'] : '';
														$MULTI_SUB_COURSE_ID = isset($instcourse['MULTI_SUB_COURSE_ID']) ? $instcourse['MULTI_SUB_COURSE_ID'] : '';
														$TYPING_COURSE_ID = isset($instcourse['TYPING_COURSE_ID']) ? $instcourse['TYPING_COURSE_ID'] : '';


														//$aicpe_course_id=($COURSE_ID!='')?$COURSE_ID:$MULTI_SUB_COURSE_ID;
														$aicpe_course_id = $COURSE_ID;
														$aicpe_course_id_multi = $MULTI_SUB_COURSE_ID;
														$course_typing = $TYPING_COURSE_ID;
														$valid_exam = $db->validate_apply_exam($aicpe_course_id, $aicpe_course_id_multi, $course_typing);

														/*	$aicpe_course_id = $instcourse['COURSE_ID'];
							$cond='';
							$errors = array();
								$valid_exam = $db->validate_apply_exam($aicpe_course_id);*/
														if (!empty($valid_exam)) {
															$errors 	= isset($valid_exam['errors']) ? $valid_exam['errors'] : '';
															$success_flag 	= isset($valid_exam['success']) ? $valid_exam['success'] : '';
															if ($success_flag == true) {
																$exam_modes = isset($valid_exam['exam_modes']) ? $valid_exam['exam_modes'] : '';
																if ($exam_modes != '') {
																	$str = '';
																	$exam_modes = json_decode($exam_modes);
																	if (!empty($exam_modes)) {
																		foreach ($exam_modes as $value) {
																			$str .= "'$value',";
																		}
																		$str = rtrim($str, ",");
																		$cond = "WHERE EXAM_TYPE_ID IN($str)";
																	}
																}
															}
														} else {
															$success_flag = false;
															$errors['exam_unavailable'] = "Exam Unavailable!";
														}
														//check for submit form validations
														$rowclass = "";
														if (isset($invalid) && in_array($STUD_COURSE_DETAIL_ID, $invalid)) {
															$rowclass = "class='danger'";
														}
														/* ----------------------------------------------------------------- */
												?>
 													<tr id="row-<?= $STUD_COURSE_DETAIL_ID ?>" <?= $rowclass ?>>
 														<td>
 															<?php if ($db->permission('add_stud_exam')) { ?>
 																<?php if ($success_flag == true && $EXAM_STATUS == 2) { ?>
 																	<input type="checkbox" name="checkstud[]" id="checkstud<?= $STUD_COURSE_DETAIL_ID ?>" value="<?= $STUD_COURSE_DETAIL_ID ?>" <?= ($checkstud != '' && in_array($STUD_COURSE_DETAIL_ID, $checkstud)) ? 'checked="checked"' : '' ?> />
 																<?php } else { ?>
 																	<span></span>
 																<?php } ?>
 															<?php } else echo $courseSrNo; ?>
 														</td>
 														<td><img src="<?= $STUDENT_PHOTO ?>" class="img img-responsive img-thumbnail" style="width:50px; height:50px"></td>
 														<td><?= $STUDENT_NAME ?></td>
 														<td><?= $COURSE_NAME ?></td>
 														<td><?= $COURSE_FEES ?></td>
 														<td><?= $EXAM_TYPE_NAME ?></td>
 														<td><?= ($BALANCE_FEES == '') ? $COURSE_FEES : $BALANCE_FEES ?></td>


 													</tr>
 											<?php
														$courseSrNo++;
													}
												}

												?>
 										</tbody>
 									</table>
 								</div>
 						</form>
 					</div>
 				</div>
 			</div>
 		</div>
 	</div>
 </div>