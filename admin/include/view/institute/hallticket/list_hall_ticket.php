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
	// $examstatus 	= $db->test(isset($_REQUEST['examstatus'])?$_REQUEST['examstatus']:'');
	// $examtype 		= $db->test(isset($_REQUEST['examtype'])?$_REQUEST['examtype']:'');

	$res 			= $exam->list_hallticket($studid, $institute_id, $courseid);

	$action = isset($_POST['action']) ? $_POST['action'] : '';
	$checkstud = isset($_POST['checkstud']) ? $_POST['checkstud'] : '';
	if ($action == 'Generate hall Ticket') {

		$result = $exam->add_hallticket();

		$result 	= json_decode($result, true);
		$success 	= isset($result['success']) ? $result['success'] : '';
		$message 	= isset($result['message']) ? $result['message'] : '';
		$errors	 	= isset($result['errors']) ? $result['errors'] : '';
		$invalid	= isset($result['invalid']) ? $result['invalid'] : array();

		if ($success == true) {
			$_SESSION['msg'] = $message;
			$_SESSION['msg_flag'] = $success;
			header('location:page.php?page=generate-hallticket');
		}
	}
	?>
 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
 	<section class="content-header">
 		<h1>
 			List Students Halltickets
 			<small>All Halltickets</small>
 		</h1>
 		<ol class="breadcrumb">
 			<li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
 			<li><a <a href="#"> Student Exams</a></li>
 			<li class="active"> List Student Halltickets</li>
 		</ol>
 	</section>

 	<!-- Main content -->
 	<section class="content">
 		<?php
			if (isset($success)) {
				$message = "";
				if (isset($invalid) && !empty($invalid)) {
					$message .= "Sorry! Some exams are not applied! Exam mode for these courses are not available!<br>";
				}
				if (isset($photo) && !empty($photo)) {
					$message .= "Sorry! Following students have not uploaded their photos!";
					$message .= "<ul>";
					foreach ($photo as $stud) {
						$message .= "<li>$stud</li>";
					}
					$message .= "</ul>";
				}
				if (isset($photo_id) && !empty($photo_id)) {
					$message .= "Sorry! Following students have not uploaded their Photo ID!";
					$message .= "<ul>";
					foreach ($photo_id as $stud_name) {
						$message .= "<li>$stud_name</li>";
					}
					$message .= "</ul>";
				}
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

 		<form action="" method="post" class="form-inline" onsubmit="return confirm('Confirm! Apply student for Hallticket?'); pageLoaderOverlay('show')">

 			<div class="row">
 				<div class="col-xs-12">
 					<div class="box box-primary">
 						<!-- /.box-header -->
 						<div class="box-header">
 							<h3 class="box-title">Hallticket</h3>
 						</div>
 						<div class="box-body">
 							<?php if ($db->permission('add_hallticket')) { ?>

 								<div class="form-group col-sm-3">
 									<label for="exampleInputName2">Select Exam Date:</label>
 									<input class="form-control pull-right" name="examdate" value="" type="Date">
 								</div>
 								<div class="form-group col-sm-3">
 									<label for="exampleInputName2">Select Exam Start Time:</label>
 									<input type="Time" class="form-control" id="" placeholder="" name="examstarttime" value="">
 								</div>
 								<div class="form-group col-sm-3">
 									<label for="exampleInputName2">Select Exam End Time:</label>
 									<input type="Time" class="form-control" id="" placeholder="" name="examendtime" value="">
 								</div>
 								<div class="form-group col-sm-3">
 									<input type="submit" class="btn btn-primary" name="action" value="Generate hall Ticket" />
 								</div>
 								<div class="clearfix"></div>
 						</div>
 					</div>
 				</div>
 			</div>
 		<?php } ?>

 		<div class="row">
 			<div class="col-xs-12">
 				<div class="box">
 					<div class="box-header">
 						List Of Student
 					</div>
 					<div class="box-body">
 						<table class="table  table-hover table-striped data-tbl">
 							<thead>
 								<tr>
 									<?php if ($db->permission('add_hallticket')) { ?>
 										<th><input type="checkbox" name="selectall" id="selectall" /></th>
 									<?php } else echo '<th>#</th>'; ?>
 									<th>Exam Status</th>
 									<th>Photo</th>
 									<th>Student Name</th>
 									<th>Course Name</th>

 								</tr>
 							</thead>
 							<tbody>
 								<?php

									if ($res != '') {
										$courseSrNo = 1;
										while ($courseData = $res->fetch_assoc()) {

											extract($courseData);
											//print_r($courseData);
											//echo "<br>";
											//$EXAM_MODE_TYPE=array();
											$COURSE_NAME 	= $db->get_inst_course_name($INSTITUTE_COURSE_ID);

											$STUDENT_PHOTO = ($STUDENT_PHOTO != '') ? STUDENT_DOCUMENTS_PATH . '/' . $STUDENT_ID . '/' . $STUDENT_PHOTO : '../uploads/default_user.png';

											$cond = '';
											$errors = array();


											if ($EXAM_STATUS == 2) {
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
											}
											$instcourse = $db->get_inst_course_info($INSTITUTE_COURSE_ID);

											$aicpe_course_id = $instcourse['COURSE_ID'];
											$cond = '';
											$errors = array();
											$valid_exam = $db->validate_apply_exam($aicpe_course_id, '', '');
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
 										<input type="hidden" class="form-control" id="" placeholder="" name="stud_id" value="<?php echo $STUDENT_ID; ?>">
 										<input type="hidden" class="form-control" id="" placeholder="" name="inst_id" value="<?php echo $INSTITUTE_ID; ?>">
 										<input type="hidden" class="form-control" id="" placeholder="" name="course_id" value="<?php echo $INSTITUTE_COURSE_ID; ?>">

 										<tr id="row-<?= $STUD_COURSE_DETAIL_ID ?>">
 											<td>
 												<input type="checkbox" name="checkstud[]" id="checkstud<?= $STUD_COURSE_DETAIL_ID ?>" value="<?= $STUD_COURSE_DETAIL_ID ?>" <?= ($checkstud != '' && in_array($STUD_COURSE_DETAIL_ID, $checkstud)) ? 'checked="checked"' : '' ?> />
 											</td>
 											<td id="exam-status-<?= $STUD_COURSE_DETAIL_ID ?>">
 												<?php if ($success_flag == true) {
														$sqlExamStatus = "SELECT EXAM_STATUS_ID,EXAM_STATUS FROM exam_status_master WHERE EXAM_STATUS_ID=2";
														$resExamStatus = $db->execQuery($sqlExamStatus);
														if ($resExamStatus && $resExamStatus->num_rows > 0) {

															while ($dataExamStatus = $resExamStatus->fetch_assoc()) {
																if ($EXAM_STATUS == $dataExamStatus['EXAM_STATUS_ID'])
																	echo '<label class="label ' . $examstatus_class . '">' . $dataExamStatus['EXAM_STATUS'] . '</label>';
															}
														}
													?>
 												<?php } else {
														foreach ($errors as $value) {
															echo $value . "<br>";
														}
													}
													?>
 											</td>
 											<td><img src="<?= $STUDENT_PHOTO ?>" class="img img-responsive img-thumbnail" style="width:50px; height:50px">
 											</td>
 											<td><?= $STUDENT_NAME ?></td>
 											<td><?= $COURSE_NAME ?></td>
 										</tr>
 								<?php
											$courseSrNo++;
										}
									}
									?>
 							</tbody>
 						</table>
 					</div>
 				</div>
 			</div>
 		</div>
 		</form>
 	</section>
 </div>