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

	/* apply for certificates */

	$action = isset($_POST['action']) ? $_POST['action'] : '';

	$checkstud = isset($_POST['checkstud']) ? $_POST['checkstud'] : '';

	if ($action == 'applyforcertificate') {

		$result = $exam->apply_for_certificate();

		$result = json_decode($result, true);

		$success = isset($result['success']) ? $result['success'] : '';

		$message = isset($result['message']) ? $result['message'] : '';

		$errors = isset($result['errors']) ? $result['errors'] : '';

		if ($success == true) {

			$_SESSION['msg'] = $message;

			$_SESSION['msg_flag'] = $success;

			//header('location:page.php?page=list-exams');

		}
	}





	/* display exam results details */

	$studid 		= $db->test(isset($_REQUEST['studid']) ? $_REQUEST['studid'] : '');

	$examtitle	 	= $db->test(isset($_REQUEST['examtitle']) ? $_REQUEST['examtitle'] : '');

	$resultstatus 	= $db->test(isset($_REQUEST['resultstatus']) ? $_REQUEST['resultstatus'] : '');

	$examtype 		= $db->test(isset($_REQUEST['examtype']) ? $_REQUEST['examtype'] : '');

	$cond = '';

	if ($resultstatus != '') $cond .= " AND A.RESULT_STATUS='$resultstatus'";

	if ($examtype != '') $cond .= " AND A.EXAM_TYPE='$examtype'";

	if ($examtitle != '') $cond .= " AND A.EXAM_TITLE='$examtitle'";



	$res 	= $exam->list_order_certificates_requests('', $studid, $institute_id, $cond);

	?>

 <!-- Content Wrapper. Contains page content -->

 <div class="content-wrapper">

 	<!-- Content Header (Page header) -->

 	<section class="content-header">

 		<h1>
 			All Certificate Requests
 		</h1>

 		<ol class="breadcrumb">

 			<li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>

 			<li><a href="#"> Certificates</a></li>

 			<li class="active"> All Requests</li>

 		</ol>

 	</section>



 	<!-- Main content -->

 	<section class="content">

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



 		<div class="row">

 			<div class="col-xs-12">

 				<div class="box box-primary">

 					<!-- /.box-header -->

 					<div class="box-header">



 						<h3 class="box-title">Search By Filters</h3>

 					</div>

 					<div class="box-body">

 						<form action="" method="post" onsubmit="pageLoaderOverlay('show')">

 							<input type="hidden" name="page" value="list-exams" />

 							<div class="form-group col-sm-3">

 								<label>Student</label>

 								<select class="form-control select2" name="studid" id="studid">

 									<?php echo $db->MenuItemsDropdown('certificate_requests', "STUDENT_ID", "STUDENT_NAME", "DISTINCT STUDENT_ID, get_student_name(STUDENT_ID) AS STUDENT_NAME", $studid, " WHERE DELETE_FLAG=0"); ?>

 								</select>

 							</div>

 							<div class="form-group col-sm-3">

 								<label>Exams</label>

 								<select class="form-control select2" name="examtitle" id="examtitle">

 									<?php echo $db->MenuItemsDropdown('certificate_requests', "EXAM_TITLE", "EXAM_TITLE", "DISTINCT EXAM_TITLE", $examtitle, " WHERE DELETE_FLAG=0"); ?>

 								</select>

 							</div>

 							<div class="form-group col-sm-2">

 								<label>Result Status</label>

 								<select class="form-control" name="resultstatus" id="resultstatus">

 									<?php echo $db->MenuItemsDropdown('certificate_requests', "RESULT_STATUS", "RESULT_STATUS", "DISTINCT RESULT_STATUS", $resultstatus, " WHERE DELETE_FLAG=0"); ?>

 								</select>

 							</div>

 							<div class="form-group col-sm-2">

 								<label>Exam Type</label>

 								<select class="form-control" name="examtype" id="examtype">

 									<?php echo $db->MenuItemsDropdown('certificate_requests B', "EXAM_TYPE", "EXAM_TYPE_NAME", "DISTINCT B.EXAM_TYPE, (SELECT A.EXAM_TYPE FROM exam_types_master A WHERE A.EXAM_TYPE_ID=B.EXAM_TYPE) AS EXAM_TYPE_NAME", $examtype, " WHERE B.EXAM_TYPE!=0 AND B.EXAM_TYPE!=''"); ?>

 								</select>

 							</div>

 							<div class="form-group col-sm-1">

 								<label> &nbsp;</label>

 								<input type="submit" class="form-control btn btn-sm btn-primary" value="Filter" name="search" />

 							</div>

 							<div class="form-group col-sm-1">

 								<label> &nbsp;</label>

 								<a class="form-control btn btn-sm btn-warning" onclick="pageLoaderOverlay('show'); location.assign('list-requested-certificates')">Clear</a>

 							</div>

 						</form>

 					</div>

 				</div>

 			</div>

 		</div>

 		<div class="row">

 			<form action="" method="post" class="form-inline" onsubmit="return confirm('Confirm! Apply student for certificates?'); pageLoaderOverlay('show')">

 				<div class="col-xs-12">

 					<div class="box box-warning">

 						<div class="box-header">



 							<!--  <input type="submit" class="btn btn-primary" name="submit"  value="Apply For Certificate" />

             <input type="hidden" class="btn btn-sm btn-primary" name="action" value="applyforcertificate">

             <input type="hidden" class="btn btn-sm btn-primary" name="examstatus1" value="2">

            -->

 							<div class="clearfix"></div>



 						</div>

 						<!-- /.box-header -->

 						<div class="box-body">

 							<div class="table-responsive">

 								<table class="table table-bordered data-tbl">

 									<thead>



 										<th>#</th>

 										<th>Photo</th>

 										<th>Student</th>

 										<th>Course</th>

 										<th>Exam Mode</th>

 										<th>Percentage</th>

 										<th>Grade</th>

 										<th>Result</th>

 										<th>Certificate Status</th>

 										<th>Order Approved Date</th>

 										<th>View Certificates</th>

 									</thead>
 									<tbody>

 										<?php

											if ($res != '') {

												$srno = 1;

												while ($data = $res->fetch_assoc()) {

													extract($data);

													/*	$PHOTO = '../uploads/default_user.png';	*/
													$PHOTO = '../uploads/default_user.png';

													if ($STUDENT_PHOTO != '')

														$PHOTO = STUDENT_DOCUMENTS_PATH . '/' . $STUDENT_ID . '/' . $STUDENT_PHOTO;

													$EXAM_TYPE_NAME = !empty($EXAM_TYPE_NAME) ? $EXAM_TYPE_NAME : '-';

													$GRADE = !empty($GRADE) ? $GRADE : '-';





													/*$action = "<a href='javascript:void(0)' onclick='deleteStudentResult(this.id)' id='result$CERTIFICATE_REQUEST_ID' class='btn' title='Delete'><i class='fa fa-trash'></i></a>";*/

													$action = "<a href='view-order-student-certificate&checkstud=$STUDENT_ID&certreq=$CERTIFICATE_REQUEST_ID&course=$AICPE_COURSE_ID' target='_blank' class='btn' title='View Certificate'><i class='fa fa-eye'></i></a>";
													$action .= "<a href='print-order-requested-marksheet&checkstud=$STUDENT_ID&certreq=$CERTIFICATE_REQUEST_ID&course=$AICPE_COURSE_ID&course_multi_sub=$MULTI_SUB_COURSE_ID' target='_blank' class='btn' title='View Marksheet'><i class='fa fa-file-text-o'></i></a>";


													echo "<tr id='row-$CERTIFICATE_REQUEST_ID'>

						

							<td>$srno</td>

							<td><img src='$PHOTO' class='img img-responsive img-circle' style='width:50px; height:50px'></td>

							<td>$STUDENT_NAME</td>

							<td>$EXAM_TITLE</td>							

							<td>$EXAM_TYPE_NAME</td>	

							<td>$MARKS_PER</td>

							<td>$GRADE</td>

							<td>$RESULT_STATUS</td>													

							<td>$REQUEST_STATUS_NAME</td>	
							
							<td>$CREATED_DATE</td>

							<td>$action</td> 

							</tr>

							";

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

 		<!-- /.row -->

 	</section>

 	<!-- /.content -->

 </div>