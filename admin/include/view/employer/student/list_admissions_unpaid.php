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
	$examstatus 	= $db->test(isset($_REQUEST['examstatus']) ? $_REQUEST['examstatus'] : '1');
	$examtype 		= $db->test(isset($_REQUEST['examtype']) ? $_REQUEST['examtype'] : '');
	$res = '';

	$selsql = "SELECT A.*,get_student_name(A.STUDENT_ID) AS STUDENT_NAME,get_stud_photo(A.STUDENT_ID) AS STUDENT_PHOTO, DATE_FORMAT(A.CREATED_ON, '%d-%m-%Y') AS CREATED_DATE,B.PLAN_FEES  FROM student_course_details A INNER JOIN institute_courses B ON A.INSTITUTE_COURSE_ID = B.INSTITUTE_COURSE_ID WHERE A.DELETE_FLAG=0 AND A.INSTITUTE_ID='$institute_id' AND A.ADMISSION_CONFIRMED=0";


	if ($studid != '')
		$selsql .= " AND A.STUDENT_ID='$studid'";
	if ($courseid != '')
		$selsql .= " AND A.INSTITUTE_COURSE_ID='$courseid'";
	$selsql .= " ORDER BY A.CREATED_ON DESC";
	$result = $db->execQuery($selsql);
	if ($result && $result->num_rows > 0)
		$res = $result;
	//$res 			= $exam->filter_aicpe_exams($studid,$institute_id, $courseid,$examtype, $examstatus);

	$action = isset($_POST['pay_admission']) ? $_POST['pay_admission'] : '';
	$checkstud = isset($_POST['checkstud']) ? $_POST['checkstud'] : '';
	if ($action != '') {
		$result = $exam->add_student_admission_paid();
		$result = json_decode($result, true);
		$success = isset($result['success']) ? $result['success'] : '';
		$message = isset($result['message']) ? $result['message'] : '';
		$errors = isset($result['errors']) ? $result['errors'] : '';
		if ($success == true) {
			$_SESSION['msg'] = $message;
			$_SESSION['msg_flag'] = $success;
			header('location:page.php?page=list-admissions-unpaid');
		}
	}
	?>
 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
 	<!-- Content Header (Page header) -->
 	<section class="content-header">
 		<h1>
 			Admissions

 		</h1>
 		<ol class="breadcrumb">
 			<li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>

 			<li class="active">Admissions</li>

 		</ol>
 	</section>

 	<!-- Main content -->
 	<section class="content">

 		<div class="row">
 			<div class="col-xs-12">

 				<?php if ($db->permission('add_enquiry')) { ?>
 					<div class="form-group col-sm-2">
 						<label> &nbsp;</label>
 						<a href="page.php?page=add-student-enquiry" class="form-control btn btn-sm btn-primary"><i class="fa fa-plus"></i> New Enquiry</a>
 					</div>
 				<?php } ?>
 				<?php if ($db->permission('add_admission')) { ?>
 					<div class="form-group col-sm-2">
 						<label> &nbsp;</label>
 						<a href="page.php?page=list-student-enquiries" class="form-control btn btn-sm btn-primary"><i class="fa fa-plus"></i> Register Admission</a>
 					</div>
 				<?php } ?>
 			</div>

 		</div>
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
 		<form action="" method="post" onsubmit="return confirm('Are you sure? Do you want to make payment?'); pageLoaderOverlay('show')">
 			<input type="hidden" name="institute_id" value="<?= $institute_id ?>" />
 			<div class="row">

 				<div class="col-xs-12">
 					<div class="box box-warning">

 						<div class="box-body">

 							<table class="table table-bordered table-striped table-hover">
 								<thead>
 									<tr>
 										<th width="4%"><input type="checkbox" name="selectall" id="selectall" /></th>
 										<th width="3%">#</th>
 										<th width="5%">Action</th>
 										<th width="5%">Photo</th>
 										<th width="40%">Student</th>
 										<th>Course</th>
 										<!--	<th>Course Fees</th>													
					<th>Balance</th>-->
 										<th>Admission Date</th>
 										<th>Exam Fees</th>

 									</tr>
 								</thead>
 								<tbody>
 									<?php
										$countTotalToPay = 0;
										if ($res != '') {
											$courseSrNo = 1;

											while ($courseData = $res->fetch_assoc()) {

												extract($courseData);
												$TOTAL_BALANCE_FEES = 'N/A';
												$sql2 = "SELECT ($TOTAL_COURSE_FEES - SUM(B.FEES_PAID)) AS TOTAL_BALANCE_FEES FROM student_payments B WHERE B.STUD_COURSE_DETAIL_ID='$STUD_COURSE_DETAIL_ID' AND B.DELETE_FLAG=0";
												$res2 = $db->execQuery($sql2);
												if ($res2 && $res2->num_rows > 0) {
													$data2 = $res2->fetch_assoc();
													$TOTAL_BALANCE_FEES = $data2['TOTAL_BALANCE_FEES'];
												}
												$courseinfo 	= $db->get_inst_course_info($INSTITUTE_COURSE_ID);
												//print_r($course_info);
												$COURSE_NAME = isset($courseinfo['COURSE_NAME_MODIFY']) ? $courseinfo['COURSE_NAME_MODIFY'] : '';
												//	$EXAM_FEES = isset($courseinfo['COURSE_FEES'])?$courseinfo['COURSE_FEES']:'';

												$EXAM_FEES = $PLAN_FEES;
												if ($ACTIVE == 1)
													$ACTIVE = '<a href="javascript:void(0)" style="color:#3c763d" onclick="changeStudentCourseStatus(' . $STUD_COURSE_DETAIL_ID . ',0)"><i class="fa fa-check"></i></a>';
												elseif ($ACTIVE == 0)
													$ACTIVE = '<a href="javascript:void(0)" style="color:#f00" onclick="changeStudentCourseStatus(' . $STUD_COURSE_DETAIL_ID . ',1)"><i class="fa fa-times"></i></a>';
												$action = '';
												if ($db->permission('update_admission'))
													$action .= "<a href='page.php?page=update-admission&id=$STUD_COURSE_DETAIL_ID' class='btn btn-xs btn-link' title='Edit'><i class=' fa fa-pencil'></i></a>";

												/*	if($db->permission('delete_admission'))	
						$action .= "<a href='javascript:void(0)' onclick='deleteStudentAdmission($STUD_COURSE_DETAIL_ID)' class='btn btn-link' title='Delete'><i class=' fa fa-trash'></i></a>";
					*/
												$examstatus_class = '';
												switch ($EXAM_STATUS) {
													case ('1'):
														$examstatus_class = 'btn-warning';
														break;
													case ('2'):
														$examstatus_class = 'btn-success';
														break;
													case ('3'):
														$examstatus_class = 'btn-info';
														break;
													default:
														$examstatus_class = 'btn-primary';
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

												//$STUDENT_PHOTO = ($STUDENT_PHOTO!='')?STUDENT_DOCUMENTS_PATH.'/'.$STUDENT_ID.'/'.$STUDENT_PHOTO :'../uploads/default_user.png';

												if ($STUDENT_PHOTO != '') {
													$STUDENT_PHOTO = SHOW_IMG_AWS . STUDENT_DOCUMENTS_PATH . $STUDENT_ID . '/' . $STUDENT_PHOTO;
												} else {
													$STUDENT_PHOTO = SHOW_IMG_AWS . '/default_user.png';
												}
										?>
 											<tr id="row-<?= $STUD_COURSE_DETAIL_ID ?>">
 												<td><input type="checkbox" name="checkstud[]" id="checkstud<?= $STUD_COURSE_DETAIL_ID ?>" value="<?= $STUD_COURSE_DETAIL_ID ?>" <?= ($checkstud != '' && in_array($STUD_COURSE_DETAIL_ID, $checkstud)) ? 'checked="false"' : 'checked="true"' ?> /></td>

 												<td><?= $courseSrNo ?></td>
 												<td><?= $action ?></td>
 												<td><img src="<?= $STUDENT_PHOTO ?>" class="img img-responsive img-thumbnail" style="width:50px; height:50px"></td>
 												<td><?= $STUDENT_NAME ?></td>

 												<td><?= $COURSE_NAME ?></td>
 												<!--<td><?= $COURSE_FEES ?></td>	
					  <td><?= $TOTAL_BALANCE_FEES ?></td> -->
 												<td><?= $CREATED_DATE ?></td>
 												<td><i class="fa fa-inr"></i> <?= $EXAM_FEES ?></td>

 											</tr>
 									<?php
												$countTotalToPay += $EXAM_FEES;
												$courseSrNo++;
											}
										}

										?>
 								</tbody>
 								<?php if ($countTotalToPay != 0) { ?>
 									<tfoot>
 										<tr>

 											<td colspan="7" align="right"><strong>Total Fees :</strong></td>
 											<td><strong><i class="fa fa-inr"></i> <?= number_format($countTotalToPay, 2) ?></strong></td>
 										</tr>
 										<!-- <tr>
						
						<td colspan="7" align="right"><strong>Additional Delivery Postal Charges:</strong></td>
						<td><strong><i class="fa fa-inr"></i> <?= number_format(100, 2) ?></strong></td>
					</tr> -->
 										<tr>

 											<td colspan="7" align="right"><strong>Total Amount to be Paid:</strong></td>
 											<td><strong><i class="fa fa-inr"></i> <?= number_format($countTotalToPay, 2) ?></strong></td>
 										</tr>
 									</tfoot>
 								<?php } ?>
 							</table>
 							<?php if ($countTotalToPay != 0) { ?>
 								<div class="box-footer text-center">
 									<input type="submit" class="btn btn-primary" name="pay_admission" value="PAY TO DITRP" />
 								</div>
 							<?php } ?>
 						</div>

 						<!-- /.box-body -->
 					</div>
 					<!-- /.box -->
 					<!-- /.box -->
 				</div>
 		</form>
 		<!-- /.col -->
 </div>

 <!-- /.row -->
 </section>
 <!-- /.content -->
 </div>