 <?php
	include('include/classes/exam.class.php');
	$exam = new exam();

	$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
	$user_role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : '';

	$studid 		= isset($_REQUEST['studid']) ? $_REQUEST['studid'] : '';
	$courseid	 	= isset($_REQUEST['courseid']) ? $_REQUEST['courseid'] : '';
	$coursetype 	= isset($_REQUEST['coursetype']) ? $_REQUEST['coursetype'] : '';
	$examstatus 	= isset($_REQUEST['examstatus']) ? $_REQUEST['examstatus'] : '';
	$examtype 		= isset($_REQUEST['examtype']) ? $_REQUEST['examtype'] : '';

	$datefrom 	= isset($_REQUEST['datefrom']) ? $_REQUEST['datefrom'] : date('d-m-Y', strtotime("-360 days"));
	$dateto 	= isset($_REQUEST['dateto']) ? $_REQUEST['dateto'] : date('d-m-Y');
	$state	 	= isset($_REQUEST['state']) ? $_REQUEST['state'] : '';
	$city	 	= isset($_REQUEST['city']) ? $_REQUEST['city'] : '';
	$institute_id = isset($_REQUEST['institute_id']) ? $_REQUEST['institute_id'] : '';

	$datefrom1 	= date('Y-m-d', strtotime($datefrom));
	$dateto1 	= date('Y-m-d', strtotime($dateto));
	$cond = "";
	//$cond .= " AND A.CREATED_ON BETWEEN  '$datefrom1' AND '$dateto1' ";
	/* if($state!='')
		$cond .= " AND B.STUDENT_STATE=$state";
	if($city!='')
		$cond .= " AND B.STUDENT_CITY=$city"; */
	if ($examstatus != '')
		$cond .= " AND A.EXAM_STATUS=$examstatus ";
	if ($coursetype != '')
		$cond .= " AND C.COURSE_TYPE=$coursetype ";
	if ($examtype != '')
		$cond .= " AND A.EXAM_TYPE=$examtype ";

	//apply pagination
	$num_rec_per_page = isset($_GET['per_page']) ? $_GET['per_page'] : 100;
	$num_rec_per_page = ($num_rec_per_page == '') ? 100 : $num_rec_per_page;
	if (isset($_GET["pg"])) {
		$page  = $_GET["pg"];
	} else {
		$page = 1;
	};
	$start_from = ($page - 1) * $num_rec_per_page;

	$rescount 	= $exam->student_reports_count($studid, $institute_id, $courseid, $cond);
	$total_records = 0;
	if ($rescount != '') {
		$total_records = $rescount;
	}
	$total_pages = ceil($total_records / $num_rec_per_page);
	$cond .= " ORDER BY A.CREATED_ON DESC LIMIT $start_from, $num_rec_per_page ";
	$res 	= $exam->student_reports($studid, $institute_id, $courseid, $cond);
	$param = "";
	foreach ($_GET as $key => $value) {
		$param .= "$key=$value&";
	}
	?>
 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
 	<!-- Content Header (Page header) -->
 	<section class="content-header">
 		<h1>
 			Students Admission Reports
 		</h1>
 		<ol class="breadcrumb">
 			<li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
 			<li><a href="#"> Reports</a></li>
 			<li class="active">Student Reports</li>

 		</ol>
 	</section>

 	<!-- Main content -->
 	<section class="content">

 		<div class="row">
 			<div class="col-xs-12">
 				<div class="box box-primary">
 					<!-- /.box-header -->
 					<div class="box-header">

 						<h3 class="box-title">Search By Filters</h3>
 					</div>
 					<div class="box-body">
 						<form action="" method="post" onsubmit="pageLoaderOverlay('show')">
 							<div class="form-group col-sm-2">
 								<label>Date From</label>
 								<input class="form-control" name="datefrom" value="<?= $datefrom ?>" id="dob" type="text">
 							</div>

 							<div class="form-group col-sm-2">
 								<label>Date To</label>
 								<input class="form-control" name="dateto" value="<?= $dateto ?>" id="doj" type="text">
 							</div>
 							<div class="form-group col-sm-2">
 								<label>Course Type</label>
 								<select class="form-control" name="coursetype" id="coursetype">
 									<?php echo $db->MenuItemsDropdown("institute_courses A LEFT JOIN course_type_master B ON A.COURSE_TYPE=B.COURSE_TYPE_ID", "COURSE_TYPE", "COURSE_TYPE_NAME", "DISTINCT A.COURSE_TYPE,B.COURSE_TYPE AS COURSE_TYPE_NAME", $coursetype, " WHERE A.DELETE_FLAG=0"); ?>
 								</select>
 							</div>
 							<div class="form-group col-sm-2">
 								<label>Exams Status</label>
 								<select class="form-control" name="examstatus" id="examstatus">
 									<?php echo $db->MenuItemsDropdown('exam_status_master', "EXAM_STATUS_ID", "EXAM_STATUS", "EXAM_STATUS_ID, EXAM_STATUS", $examstatus, " WHERE ACTIVE=1 AND DELETE_FLAG=0"); ?>
 								</select>
 							</div>
 							<div class="form-group col-sm-2">
 								<label>Exam Type</label>
 								<select class="form-control" name="examtype" id="examtype">
 									<?php echo $db->MenuItemsDropdown('student_course_details B', "EXAM_TYPE", "EXAM_TYPE_NAME", "DISTINCT B.EXAM_TYPE, (SELECT A.EXAM_TYPE FROM exam_types_master A WHERE A.EXAM_TYPE_ID=B.EXAM_TYPE) AS EXAM_TYPE_NAME", $examtype, " WHERE B.EXAM_TYPE!=0 AND B.EXAM_TYPE!=''"); ?>
 								</select>
 							</div>
 							<div class="form-group col-sm-2">
 								<label>Certificate</label>
 								<select class="form-control" name="examtype" id="examtype">
 									<?php echo $db->MenuItemsDropdown('student_course_details B', "EXAM_TYPE", "EXAM_TYPE_NAME", "DISTINCT B.EXAM_TYPE, (SELECT A.EXAM_TYPE FROM exam_types_master A WHERE A.EXAM_TYPE_ID=B.EXAM_TYPE) AS EXAM_TYPE_NAME", $examtype, " WHERE B.EXAM_TYPE!=0 AND B.EXAM_TYPE!=''"); ?>
 								</select>
 							</div>
 							<div class="form-group col-sm-4">
 								<label>Institute</label>
 								<select class="form-control select2" name="institute_id" value="<?= $institute_id ?>" id="city">
 									<?php echo $db->MenuItemsDropdown("institute_details A", "INSTITUTE_ID", "INSTITUTE_NAME", "DISTINCT A.INSTITUTE_ID,A.INSTITUTE_NAME", $institute_id, " WHERE A.DELETE_FLAG=0 ORDER BY A.INSTITUTE_NAME ASC"); ?>
 								</select>
 							</div>

 							<!--<div class="form-group col-sm-3">-->
 							<!--  <label>Student</label>-->
 							<!--  <select class="form-control select2" name="studid" id="studid">-->
 							<!--	  <?php echo $db->MenuItemsDropdown('student_details', "STUDENT_ID", "STUDENT_NAME", "STUDENT_ID, CONCAT(CONCAT(STUDENT_FNAME,' ',STUDENT_MNAME),' ',STUDENT_LNAME) STUDENT_NAME", $studid, " WHERE DELETE_FLAG=0"); ?>-->
 							<!--	</select>	-->
 							<!--</div>-->
 							<div class="form-group col-sm-3">
 								<label>Exams</label>
 								<select class="form-control select2" name="courseid" id="courseid">
 									<?php echo $db->MenuItemsDropdown('student_course_details A LEFT JOIN institute_courses B ON A.INSTITUTE_COURSE_ID=B.INSTITUTE_COURSE_ID', "INSTITUTE_COURSE_ID", "COURSE_NAME", "DISTINCT A.INSTITUTE_COURSE_ID, (SELECT C.COURSE_NAME FROM courses C WHERE C.COURSE_ID=B.COURSE_ID) AS COURSE_NAME", $courseid, " WHERE A.DELETE_FLAG=0 AND B.DELETE_FLAG=0 AND B.COURSE_TYPE=1"); ?>
 								</select>
 							</div>

 							<div class="form-group col-sm-1">
 								<label> &nbsp;</label>
 								<input type="submit" class="form-control btn btn-sm btn-primary" value="Filter" name="search" />
 							</div>
 							<div class="form-group col-sm-1">
 								<label> &nbsp;</label>
 								<a class="form-control btn btn-sm btn-warning" href="admission-reports">Clear</a>
 							</div>
 						</form>
 					</div>
 				</div>
 			</div>
 		</div>

 		<div class="row">
 			<div class="col-xs-12">

 				<div class="box box-warning">

 					<!-- /.box-header -->
 					<div class="box-body">
 						<div>
 							<select onchange="window.location.href='page.php?<?= $param ?>per_page='+this.value;">
 								<option value="100" <?= ($num_rec_per_page == '100') ? 'selected="selected"' : '' ?>>100</option>
 								<option value="500" <?= ($num_rec_per_page == '500') ? 'selected="selected"' : '' ?>>500</option>
 								<option value="<?= $total_records ?>" <?= ($num_rec_per_page == $total_records) ? 'selected="selected"' : '' ?>>All</option>
 							</select>
 							<span class="label label-success"><strong>Total: <?= $total_records ?></strong></span>
 							<nav class="pull-right">
 								<ul class="pagination">
 									<?php
										$pg = isset($_GET['pg']) ? $_GET['pg'] : '1';

										for ($i = 1; $i <= $total_pages; $i++) {


											$active_class = ($pg == $i) ? 'active' : '';
											echo '<li class="page-item ' . $active_class . '"><a class="page-link" href="page.php?' . $param . 'pg=' . $i . '">' . $i . '</a></li>';
										};
										?>


 								</ul>
 							</nav>
 						</div>
 						<table class="table table-bordered table-hover">
 							<thead>
 								<tr>
 									<th>#</th>
 									<th>Photo</th>
 									<th>Student Name</th>
 									<th>Institute Name</th>
 									<th>ATC</th>
 									<th>Course Name</th>
 									<th>Course Type</th>
 									<th>Exam Type</th>
 									<th>Exam Status</th>
 									<th>Course Fees</th>
 									<th>Balance Fees</th>
 									<th>Exam Fees</th>

 									<!--	<th>Action</th> -->
 								</tr>
 							</thead>
 							<tbody>
 								<?php
									$total_course_fee = 0;
									$total_balance_fee = 0;
									$total_exam_fee = 0;
									if ($res != '') {
										$courseSrNo = 1;
										while ($courseData = $res->fetch_assoc()) {

											extract($courseData);

											$EXAM_MODE_TYPE = array();
											//$COURSE_NAME 	= $db->get_inst_course_name($INSTITUTE_COURSE_ID);
											$courseinfo 	= $db->get_inst_course_info($INSTITUTE_COURSE_ID);

											$COURSE_NAME = isset($courseinfo['COURSE_NAME_MODIFY']) ? $courseinfo['COURSE_NAME_MODIFY'] : $courseinfo['COURSE_NAME'];
											$EXAM_FEES = isset($courseinfo['COURSE_FEES']) ? $courseinfo['COURSE_FEES'] : '';
											$total_exam_fee += $EXAM_FEES;

											//Institute Login
											$PASS_WORD 			= $courseData['PASS_WORD'];
											$USER_NAME 			= $courseData['USER_NAME'];
											$params = "'$USER_NAME','" . md5($PASS_WORD) . "'";
											$loginBtn = "<a href='javascript:void(0)' class='btn btn-primary btn-xs' title='LOGIN' onclick=\"loginToInst($params)\"><i class=' fa fa-sign-in'></i>Login</a>";

											if ($ACTIVE == 1)
												$ACTIVE = '<a href="javascript:void(0)" style="color:#3c763d" onclick="changeStudentCourseStatus(' . $STUD_COURSE_DETAIL_ID . ',0)"><i class="fa fa-check"></i></a>';
											elseif ($ACTIVE == 0)
												$ACTIVE = '<a href="javascript:void(0)" style="color:#f00" onclick="changeStudentCourseStatus(' . $STUD_COURSE_DETAIL_ID . ',1)"><i class="fa fa-times"></i></a>';

											$action = "";

											$course_type = ($COURSE_TYPE == 1) ? 'DITRP' : 'NON-DITRP';

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

											// validations before exam apply
											/* ----------------------------------------------------------------- */

											$instcourse = $db->get_inst_course_info($INSTITUTE_COURSE_ID);

											$COURSE_ID = isset($instcourse['COURSE_ID']) ? $instcourse['COURSE_ID'] : '';
											$MULTI_SUB_COURSE_ID = isset($instcourse['MULTI_SUB_COURSE_ID']) ? $instcourse['MULTI_SUB_COURSE_ID'] : '';

											//$aicpe_course_id=($COURSE_ID!='')?$COURSE_ID:$MULTI_SUB_COURSE_ID;
											$aicpe_course_id = $COURSE_ID;
											$aicpe_course_id_multi = $MULTI_SUB_COURSE_ID;
											$valid_exam = $db->validate_apply_exam($aicpe_course_id, $aicpe_course_id_multi);

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
											$total_course_fee += $COURSE_FEES;
											$total_balance_fee += ($BALANCE_FEES == '') ? $COURSE_FEES : $BALANCE_FEES;
											/* ----------------------------------------------------------------- */
									?>
 										<tr id="row-<?= $STUD_COURSE_DETAIL_ID ?>" <?= $rowclass ?>>

 											<td>
 												<?php echo $courseSrNo; ?>
 											</td>

 											<td><img src="<?= $STUDENT_PHOTO ?>" class="img img-responsive img-circle" style="width:50px; height:50px"></td>
 											<td><?= $STUDENT_NAME ?></td>
 											<td><?= $INSTITUTE_NAME ?></td>
 											<td><a href="page.php?page=update-institute&id=<?= $INSTITUTE_ID ?>" data-toggle="tooltip" data-placement='right' title="<?= $INSTITUTE_NAME ?>" target="_blank"><?= $INSTITUTE_CODE ?></a>
 												<p><?= $loginBtn ?></p>
 											</td>
 											<td><?= $COURSE_NAME ?></td>

 											<td><?= $course_type ?></td>
 											<td id="exam-type-<?= $STUD_COURSE_DETAIL_ID ?>">
 												<?php

													if ($success_flag == true) {
														$sqlExam = "SELECT * FROM exam_types_master $cond";
														$resExam = $db->execQuery($sqlExam);
														if ($resExam && $resExam->num_rows > 0) {
															echo '<ul>';
															while ($dataExam = $resExam->fetch_assoc()) {
																echo '<li>' . $dataExam['EXAM_TYPE'] . '</li>';
															}
															echo '</ul>';
														}
													?>
 												<?php } else {
														foreach ($errors as $value) {
															echo $value . "<br>";
														}
													} ?>
 											</td>

 											<td id="exam-status-<?= $STUD_COURSE_DETAIL_ID ?>">
 												<?php if ($success_flag == true) {
														$sqlExamStatus = "SELECT EXAM_STATUS_ID,EXAM_STATUS FROM exam_status_master";
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
													}  ?>
 											</td>


 											<td><?= $COURSE_FEES ?></td>
 											<td><?= ($BALANCE_FEES == '') ? $COURSE_FEES : $BALANCE_FEES ?></td>
 											<td><?= $EXAM_FEES ?></td>
 										</tr>
 								<?php
											$courseSrNo++;
										}
									}

									?>
 							</tbody>
 							<tfoot>
 								<tr>
 									<th colspan="8">Total</th>
 									<th> <i class="fa fa-inr"></i> <?= $total_course_fee ?> </th>
 									<th> <i class="fa fa-inr"></i> <?= $total_balance_fee ?></th>
 									<th> <i class="fa fa-inr"></i> <?= $total_exam_fee ?></th>
 								</tr>
 							</tfoot>

 						</table>
 						<div>
 							<nav class="pull-right">
 								<ul class="pagination">
 									<?php
										$pg = isset($_GET['pg']) ? $_GET['pg'] : 1;
										for ($i = 1; $i <= $total_pages; $i++) {
											$active_class = ($pg == $i) ? 'active' : '';
											echo '<li class="page-item ' . $active_class . '"><a class="page-link" href="page.php?' . $param . 'pg=' . $i . '">' . $i . '</a></li>';
										};
										?>

 								</ul>
 							</nav>
 						</div>
 					</div>
 					<!-- /.box-body -->
 				</div>
 				<!-- /.box -->
 				<!-- /.box -->
 			</div>

 			<!-- /.col -->
 		</div>

 		<!-- /.row -->
 	</section>
 	<!-- /.content -->
 </div>