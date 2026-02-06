<?php
include('include/classes/exam.class.php');
$exam = new exam();
include('include/classes/exammultisub.class.php');
$exammultisub = new exammultisub();

include('include/classes/coursetypingexam.class.php');
$coursetypingexam = new coursetypingexam();


$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
$user_role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : '';
if ($user_role == 5) {
	$institute_id = $db->get_parent_id($user_role, $user_id);
	$staff_id = $user_id;
} else {
	$institute_id = $user_id;
	$staff_id = 0;
}
/* apply for certificates */
$action = isset($_POST['action']) ? $_POST['action'] : '';
$checkstud = isset($_POST['checkstud']) ? $_POST['checkstud'] : '';
$checkstud_multisub = isset($_POST['checkstud_multisub']) ? $_POST['checkstud_multisub'] : '';
if ($action == 'orderforcertificate') {
	//$result= $exam->apply_for_certificate();
	$result = $exam->order_for_certificate();
	$result = json_decode($result, true);
	$success = isset($result['success']) ? $result['success'] : '';
	$message = isset($result['message']) ? $result['message'] : '';
	$errors = isset($result['errors']) ? $result['errors'] : '';
	//print_r($result);
	if ($success == true) {
		$_SESSION['msg'] = $message;
		$_SESSION['msg_flag'] = $success;
		//require_once(ROOT."/include/email/config.php");						
		//require_once(ROOT."/include/email/templates/certificate_request_by_admin_to_inst.php");
		//header('location:page.php?page=list-exams');
	}
}

if ($action == 'add_marksheet') {
	$result = $exam->add_marksheet();
	//print_r($result);exit();

	$result = json_decode($result, true);

	$success = isset($result['success']) ? $result['success'] : '';

	$message = isset($result['message']) ? $result['message'] : '';

	$errors = isset($result['errors']) ? $result['errors'] : '';
}

if ($action == 'update_marksheet') {
	$result = $exam->update_marksheet();
	//print_r($result);exit();

	$result = json_decode($result, true);

	$success = isset($result['success']) ? $result['success'] : '';

	$message = isset($result['message']) ? $result['message'] : '';

	$errors = isset($result['errors']) ? $result['errors'] : '';
}

/* display exam results details */
$studid 		= $db->test(isset($_REQUEST['studid']) ? $_REQUEST['studid'] : '');
$examtitle	 	= $db->test(isset($_REQUEST['examtitle']) ? $_REQUEST['examtitle'] : '');
$resultstatus 	= $db->test(isset($_REQUEST['resultstatus']) ? $_REQUEST['resultstatus'] : '');
$examtype 		= $db->test(isset($_REQUEST['examtype']) ? $_REQUEST['examtype'] : '');
$cond = ' ';
if ($resultstatus != '') $cond .= " AND A.RESULT_STATUS='$resultstatus'";
if ($examtype != '') $cond .= " AND A.EXAM_TYPE='$examtype'";
if ($examtitle != '') $cond .= " AND A.EXAM_TITLE='$examtitle'";


$cond .= ' AND D.REQUEST_STATUS = 2';
$res 	= $exam->list_student_exam_results('', $studid, $institute_id, '', $cond);

$exam_result_info_multi_sub = $exammultisub->list_student_exam_results_multi_sub('', $studid, $institute_id, '',  $cond);

$exam_result_info_typing = $coursetypingexam->list_student_exam_results_typing('', $studid, $institute_id, '',  $cond);

?>

<div class="content-wrapper">

	<div class="row">
		<div class="col-12 mb-4 mb-xl-0" style="background-color:yellow; padding:10px; margin-top:-20px; margin-bottom:15px !important;">
			<h2 style="font-size:16px; font-weight:600; color:red">Important Notice : From Today 24th July 2024, 18% GST will be applicable on every transaction. Requesting to all DITRP centre's kindly pay amount with GST.
			</h2>
			<h3 style="font-size:16px; font-weight:600; color:red">For More Details kindly contact Team DITRP</h3>
		</div>
	</div>

	<div class="col-lg-12 stretch-card">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title"> All Order Certificate
				</h4>
				<section style="background-color: #ffff00; padding: 5px;">
					<h4><b>Note : </b> For Any Courier Related Query Please Call Or Message On This Number Call now : 8999462954 </h4>
					<h5 style="color: #f00;"><b>IMPORTANT NOTE:</b> From 1st March 2024 Postal charges have been changed (Due to increase of petrol, diesel price hike, postal charges has been increased.) including packing material & labour charges</h5>
					<h5 style="color: #f00;"><b>&nbsp;&nbsp; POSTAL CHARGES 2024:</b><br>
						&nbsp;&nbsp;&emsp;1 to 10 certificates order charges 120/- <br>
						&nbsp;&nbsp;&emsp;11 to 20 certificates order charges 240/- <br>
						&nbsp;&nbsp;&emsp;21 to 30 certificates order charges 360/- <br>
						&nbsp;&nbsp;&emsp;31 to 40 certificates order charges 480/- <br>
						&nbsp;&nbsp;&emsp;41 to 50 certificates order charges 600/- <br>
						&nbsp;&nbsp;&emsp;51 and above certificates order charges as per weight <br><br><br>
					</h5>
					<h5 style="color: #f00;"> NOTE: Please Go For Approval For Certificate First. After Certificate Approval The Certificate Is Shown For Order Certificate List.</h5>
					<h5 style="color: #f00;">Courier Related Query Please Call : 8999462954</h5>



					<!--	 <h4 style="color: #f00;"> Order charges: upto <b>10 certificates</b> => <b>&#x20B9; 100/-</b> , upto <b>15 certificates</b> => <b>&#x20B9; 130/-</b> , upto <b>20 certificates</b> => <b>&#x20B9; 160/-</b> , upto <b>30 certificates</b> => <b>&#x20B9; 220/-</b> </h4>
         <h5 style="color: #f00;">&nbsp;&nbsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;  &#x20B9; 100/- will be deducted instantly & remaining amount (if applicable) will be deducted within 24 hours of ordering.</h5>
         <h5 style="color: #f00;"> Note :  Please Go For Approval For Certificate First. After Certificate Approval The Certificate Is Shown For Order Certificate List.</h5>  -->


				</section>
				<br />

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
				<div class="box-header">

					<h3 class="box-title">Search By Filters</h3>
				</div>
				<div class="box-body">
					<form action="" method="post" onsubmit="pageLoaderOverlay('show')">
						<div class="row">
							<input type="hidden" name="page" value="list-exams" />
							<div class="form-group col-sm-3">
								<label>Student</label>
								<select class="form-control select2" name="studid" id="studid">
									<?php echo $db->MenuItemsDropdown('exam_result', "STUDENT_ID", "STUDENT_NAME", "DISTINCT STUDENT_ID, get_student_name(STUDENT_ID) AS STUDENT_NAME", $studid, " WHERE DELETE_FLAG=0"); ?>
								</select>
							</div>
							<div class="form-group col-sm-3">
								<label>Exams</label>
								<select class="form-control select2" name="examtitle" id="examtitle">
									<?php echo $db->MenuItemsDropdown('exam_result', "EXAM_TITLE", "EXAM_TITLE", "DISTINCT EXAM_TITLE", $examtitle, " WHERE DELETE_FLAG=0"); ?>
								</select>
							</div>
							<div class="form-group col-sm-2">
								<label>Result Status</label>
								<select class="form-control" name="resultstatus" id="resultstatus">
									<?php echo $db->MenuItemsDropdown('exam_result', "RESULT_STATUS", "RESULT_STATUS", "DISTINCT RESULT_STATUS", $resultstatus, " WHERE DELETE_FLAG=0"); ?>
								</select>
							</div>
							<div class="form-group col-sm-2">
								<label>Exam Type</label>
								<select class="form-control" name="examtype" id="examtype">
									<?php echo $db->MenuItemsDropdown('exam_result B', "EXAM_TYPE", "EXAM_TYPE_NAME", "DISTINCT B.EXAM_TYPE, (SELECT A.EXAM_TYPE FROM exam_types_master A WHERE A.EXAM_TYPE_ID=B.EXAM_TYPE) AS EXAM_TYPE_NAME", $examtype, " WHERE B.EXAM_TYPE!=0 AND B.EXAM_TYPE!=''"); ?>
								</select>
							</div>
							<div class="form-group col-sm-1">
								<label> &nbsp;</label>
								<input type="submit" class="form-control btn btn-sm btn-primary" value="Filter" name="search" />
							</div>
							<div class="form-group col-sm-1">
								<label> &nbsp;</label>
								<a class="form-control btn btn-sm btn-warning" onclick="pageLoaderOverlay('show'); location.assign('list-exam-results')">Clear</a>
							</div>
					</form>
				</div>

				<div class="table-responsive pt-3">
					<form action="" method="post" class="form-inline" onsubmit="return confirm('Confirm! Order for certificate? Share the OTP which you will receive before the delivery, with the delivery person to receive the parcel. This is mandatory.'); pageLoaderOverlay('show')">
						<div class="col-xs-12">
							<div class="box box-warning">
								<div class="box-header">

									<?php if ($db->permission('apply_certificate')) {	?>
										<input type="submit" class="btn btn-primary" name="submit" id="ordercert" value="Order For Certificate" disabled="true" />
									<?php } ?>
									<input type="hidden" name="action" value="orderforcertificate">
									<input type="hidden" name="examstatus1" value="2">
									<input type="hidden" name="institute_id" value="<?= $institute_id ?>">
									<input type="hidden" name="user_role" value="<?= 2 ?>">

									<input type="hidden" name="cert_req_master_id" value="">

									<div class="clearfix"></div>

								</div>
								<!-- /.box-header -->
								<div class="box-body">
									<div class="table-responsive">
										<table class="table table-bordered table-hover table-striped" id="order-listing">
											<thead>
												<th></th>
												<th>#</th>
												<th>Photo</th>
												<th>Student</th>
												<th>Course</th>
												<th>Exam Mode</th>
												<th>Objective Marks</th>
												<th>Practical Marks</th>
												<th>Percentage</th>
												<th>Grade</th>
												<th>Result</th>
												<th>Order For Certificate</th>
												<th>Status Order Certificate</th>
												<th>Approve Date</th>
												<th>Order Approved Date</th>
												<!-- <th>Action</th> -->
											</thead>
											<tbody>
												<?php
												if ($res != '') {
													$srno = 1;
													while ($data = $res->fetch_assoc()) {
														extract($data);
														$PHOTO = '../uploads/default_user.png';
														if ($STUDENT_PHOTO != '')
															$PHOTO = STUDENT_DOCUMENTS_PATH . '/' . $STUDENT_ID . '/' . $STUDENT_PHOTO;
														$EXAM_TYPE_NAME = !empty($EXAM_TYPE_NAME) ? $EXAM_TYPE_NAME : '-';
														$GRADE = !empty($GRADE) ? $GRADE : '-';
														$COURSE_NAME = $db->get_inst_course_name($INSTITUTE_COURSE_ID);
														//$action = "<!-- <a href='update-exam-results&id=$EXAM_RESULT_ID' class='btn' title='Edit'><i class='fa fa-pencil'></i></a> -->";
														$action = "";
														if ($db->permission('delete_exam_result'))
															$action .= "<a href='javascript:void(0)' onclick='deleteStudentResult(this.id)' id='result$EXAM_RESULT_ID' class='btn' title='Delete'><i class='fa fa-trash'></i></a>";


														$ORDER_FOR_CERTIFICATE_LABEL = ($ORDER_FOR_CERTIFICATE == 0) ? 'No' : 'Yes';
														$disableCheck = ($ORDER_FOR_CERTIFICATE == 1) ? 'disabled' : '';

														$checkstud = isset($_POST['checkstud']) ? $_POST['checkstud'] : '';
														$checkbox = "<td>";
														if ($db->permission('apply_certificate') && $ORDER_FOR_CERTIFICATE == 0)
															$checkbox .= "<input type='checkbox' name='checkstud[]' id='checkstud$EXAM_RESULT_ID' value='$EXAM_RESULT_ID' $disableCheck onchange='validateordercount()' />";
														$checkbox .= "</td>";

														$REQUEST_STATUS_LABEL = ($REQUEST_STATUS == 2) ? 'Accepted' : 'Pending';

														if ($RESULT_STATUS == 'Passed') {


															echo "<tr id='row-result$EXAM_RESULT_ID'>
							$checkbox
							<td>$srno</td>
							<td><img src='$PHOTO' class='img img-responsive img-circle' style='width:50px; height:50px'></td>
							<td>$STUDENT_NAME</td>
							<td>$COURSE_NAME</td>							
							<td>$EXAM_TYPE_NAME</td>
							<td>$MARKS_OBTAINED</td>
							<td>$PRACTICAL_MARKS</td>
							<td>$MARKS_PER</td>
							<td>$GRADE</td>
							<td>$RESULT_STATUS</td>													
							<td>$ORDER_FOR_CERTIFICATE_LABEL</td>
							<td>$REQUEST_STATUS_LABEL</td>
							<td>$APPROVE_DATE</td>
							<td>$ORDER_DATE</td>
							</tr>
							";
														}
														$srno++;
													}
												}
												if ($exam_result_info_multi_sub != '') {
													$srno = 1;
													while ($data = $exam_result_info_multi_sub->fetch_assoc()) {
														extract($data);
														//print_r($data);			
														$PHOTO = '../uploads/default_user.png';
														if ($STUDENT_PHOTO != '')
															$PHOTO = STUDENT_DOCUMENTS_PATH . '/' . $STUDENT_ID . '/' . $STUDENT_PHOTO;

														$GRADE = !empty($GRADE) ? $GRADE : '-';
														$COURSE_NAME = $db->get_inst_course_name($INSTITUTE_COURSE_ID);
														//$action = "<!-- <a href='update-exam-results&id=$EXAM_RESULT_FINAL_ID' class='btn' title='Edit'><i class='fa fa-pencil'></i></a> -->";
														$action = "";
														if ($db->permission('delete_exam_result'))
															$action .= "<a href='javascript:void(0)' onclick='deleteStudentResult_MultiSub(this.id)' id='result$EXAM_RESULT_FINAL_ID' class='btn' title='Delete'><i class='fa fa-trash'></i></a>";

														if ($ORDER_FOR_CERTIFICATE == 0)
															$ORDER_FOR_CERTIFICATE_LABEL = ($ORDER_FOR_CERTIFICATE == 0) ? 'No' : 'Yes';
														$disableCheck = ($ORDER_FOR_CERTIFICATE == 1) ? 'disabled' : '';

														$checkstud_multisub = isset($_POST['checkstud_multisub']) ? $_POST['checkstud_multisub'] : '';
														$checkbox1 = "<td>";
														if ($db->permission('apply_certificate') && $ORDER_FOR_CERTIFICATE == 0)
															$checkbox1 .= "<input type='checkbox' name='checkstud_multisub[]' id='checkstud_multisub$EXAM_RESULT_FINAL_ID' value='$EXAM_RESULT_FINAL_ID' $disableCheck onchange='validateordercount()' />";
														$checkbox1 .= "</td>";

														$REQUEST_STATUS_LABEL = ($REQUEST_STATUS == 2) ? 'Accepted' : 'Pending';

														if ($RESULT_STATUS == 'Passed') {

															echo "<tr id='row-result$EXAM_RESULT_FINAL_ID'>
							$checkbox1
							<td>$srno</td>
							<td><img src='$PHOTO' class='img img-responsive img-circle' style='width:50px; height:50px'></td>
							<td>$STUDENT_NAME</td>
							<td>$COURSE_NAME</td>							
							<td></td>
							<td>$MARKS_OBTAINED / $EXAM_TOTAL_MARKS</td>
							<td><table class='table table-bordered'>
											<tr>
												<th>Subject Name </th>
												<th>Theory Marks</th>
												<th>Practical Marks </th>	
											</tr>";
															$res2 = $exammultisub->list_student_exam_results_multi_sub_list('', $STUDENT_ID, $INSTITUTE_ID, $STUD_COURSE_ID, '');
															$resultInfo = '';
															if ($res2 != '') {
																$srno1 = 1;

																while ($data2 = $res2->fetch_assoc()) {
																	//print_r($data2);
																	$EXAM_RESULT_ID1 		= $data2['EXAM_RESULT_ID'];
																	$STUDENT_SUBJECT_ID1	= $data2['STUDENT_SUBJECT_ID'];
																	$EXAM_ID1 				= $data2['EXAM_ID'];
																	$INSTITUTE_COURSE_ID1 	= $data2['INSTITUTE_COURSE_ID'];
																	$SUBJECT_NAME1 			= $data2['SUBJECT_NAME'];
																	$EXAM_TITLE1 			= $data2['EXAM_TITLE'];
																	$MARKS_OBTAINED1 		= $data2['MARKS_OBTAINED'];
																	$PRACTICAL_MARKS1 		= $data2['PRACTICAL_MARKS'];
																	$TOTAL_MARKS1 			= $data2['TOTAL_MARKS'];

																	echo $resultInfo 	= '<tr>
												<td> ' . $SUBJECT_NAME1 . '</td>
												<td> ' . $MARKS_OBTAINED1 . ' </td>
												<td> ' . $PRACTICAL_MARKS1 . ' </td>
											</tr>	';
																	$srno1++;
																}
															}


															echo "</table></td>
							<td>$MARKS_PER</td>
							<td>$GRADE</td>
							<td>$RESULT_STATUS</td>													
							<td>$ORDER_FOR_CERTIFICATE_LABEL</td>
							<td>$REQUEST_STATUS_LABEL</td>
							<td>$CREATED_DATE</td>
							<td>$ORDER_DATE</td>
							</tr>
							";
														}
														$srno++;
													}
												}
												if ($exam_result_info_typing != '') {
													$srno = 1;
													while ($data = $exam_result_info_typing->fetch_assoc()) {
														extract($data);
														//print_r($data);	 exit();		
														$PHOTO = '../uploads/default_user.png';
														if ($STUDENT_PHOTO != '')
															$PHOTO = STUDENT_DOCUMENTS_PATH . '/' . $STUDENT_ID . '/' . $STUDENT_PHOTO;

														$GRADE = !empty($GRADE) ? $GRADE : '-';
														$COURSE_NAME = $db->get_inst_course_name($INSTITUTE_COURSE_ID);
														//$action = "<!-- <a href='update-exam-results&id=$EXAM_RESULT_FINAL_ID' class='btn' title='Edit'><i class='fa fa-pencil'></i></a> -->";
														$action = "";
														if ($db->permission('delete_exam_result'))
															$action .= "<a href='javascript:void(0)' onclick='deleteStudentResult_MultiSub(this.id)' id='result$EXAM_RESULT_FINAL_ID' class='btn' title='Delete'><i class='fa fa-trash'></i></a>";

														if ($ORDER_FOR_CERTIFICATE == 0)
															$ORDER_FOR_CERTIFICATE_LABEL = ($ORDER_FOR_CERTIFICATE == 0) ? 'No' : 'Yes';
														$disableCheck = ($ORDER_FOR_CERTIFICATE == 1) ? 'disabled' : '';

														$checkstud_typing = isset($_POST['checkstud_typing']) ? $_POST['checkstud_typing'] : '';
														$checkbox2 = "<td>";
														if ($db->permission('apply_certificate') && $ORDER_FOR_CERTIFICATE == 0)
															$checkbox2 .= "<input type='checkbox' name='checkstud_typing[]' id='checkstud_typing$EXAM_RESULT_FINAL_ID' value='$EXAM_RESULT_FINAL_ID' $disableCheck onchange='validateordercount()' />";
														$checkbox2 .= "</td>";

														$REQUEST_STATUS_LABEL = ($REQUEST_STATUS == 2) ? 'Accepted' : 'Pending';

														if ($RESULT_STATUS == 'Passed') {

															echo "<tr id='row-result$EXAM_RESULT_FINAL_ID'>
							$checkbox2
							<td>$srno</td>
							<td><img src='$PHOTO' class='img img-responsive img-circle' style='width:50px; height:50px'></td>
							<td>$STUDENT_NAME</td>
							<td>$COURSE_NAME</td>							
							<td></td>
							<td>$MARKS_OBTAINED / $EXAM_TOTAL_MARKS</td>
							<td><table class='table table-bordered'>
											<tr>
												<th>Subject Name </th>
												<th>Speed</th>
												<th>Marks Obtained</th>
											</tr>";
															$res2 = $coursetypingexam->list_student_exam_results_typing_list('', $STUDENT_ID, $INSTITUTE_ID, $STUD_COURSE_ID, '');
															$resultInfo = '';
															if ($res2 != '') {
																$srno1 = 1;

																while ($data2 = $res2->fetch_assoc()) {
																	//print_r($data2);
																	$EXAM_RESULT_ID1 		= $data2['EXAM_RESULT_ID'];
																	$STUDENT_SUBJECT_ID1	= $data2['STUDENT_SUBJECT_ID'];
																	$EXAM_ID1 				= $data2['EXAM_ID'];
																	$INSTITUTE_COURSE_ID1 	= $data2['INSTITUTE_COURSE_ID'];
																	$SUBJECT_NAME1 			= $data2['SUBJECT_NAME'];
																	$EXAM_TITLE1 			= $data2['EXAM_TITLE'];
																	$MARKS_OBTAINED1 		= $data2['MARKS_OBTAINED'];
																	$PRACTICAL_MARKS1 		= $data2['PRACTICAL_MARKS'];
																	$TOTAL_MARKS1 			= $data2['TOTAL_MARKS'];
																	$TYPING_COURSE_SPEED 			= $data2['TYPING_COURSE_SPEED'];

																	echo $resultInfo 	= '<tr>
												<td> ' . $SUBJECT_NAME1 . '</td>
												<td> ' . $TYPING_COURSE_SPEED . '</td>
												<td> ' . $MARKS_OBTAINED1 . ' </td>
											</tr>	';
																	$srno1++;
																}
															}


															echo "</table></td>
							<td>$MARKS_PER</td>
							<td>$GRADE</td>
							<td>$RESULT_STATUS</td>													
							<td>$ORDER_FOR_CERTIFICATE_LABEL</td>
							<td>$REQUEST_STATUS_LABEL</td>
							<td>$CREATED_DATE</td>
							<td>$ORDER_DATE</td>
							</tr>
							";
														}
														$srno++;
													}
												}

												?>
											</tbody>
										</table>
									</div>
								</div>
								<!-- /.box-body -->
							</div>
							<!-- /.box -->
							<!-- /.box -->
						</div>
						<!-- /.col -->
					</form>
				</div>
			</div>
		</div>
	</div>

	<!-- Popup notice -->

	<div class="modal fade" id="myModalsdsfs">
		<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
			<div class="modal-content" style="    width: 440px;
    margin: 5% auto;">

				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">

					<img src='resources/notice.jpeg' style='border-radius:0;width: 400px;
    height: 400px;' />

				</div>
			</div>
		</div>
	</div>
	<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"> -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

	<script type="text/javascript">
		$(window).on('load', function() {
			var delayMs = 1500; // delay in milliseconds

			setTimeout(function() {
				$('#myModalsdsfs').modal('show');
			}, delayMs);
		});
	</script>