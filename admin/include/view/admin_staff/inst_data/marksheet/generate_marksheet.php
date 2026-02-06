 <?php

	include('include/classes/exam.class.php');

	$exam = new exam();
	//$action= isset($_POST['add_marksheet'])?$_POST['add_marksheet']:'';
	$id = $db->test(isset($_GET['id']) ? $_GET['id'] : '');

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

	if ($action == 'Apply marksheet') {
		print_r($_POST);

		$result = $exam->apply_for_marksheet();
		//print_r($result);exit;

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

	$cond = '';

	if ($resultstatus != '') $cond .= " AND A.RESULT_STATUS='$resultstatus'";

	if ($examtype != '') $cond .= " AND A.EXAM_TYPE='$examtype'";

	if ($examtitle != '') $cond .= " AND A.EXAM_TITLE='$examtitle'";

	$cond .= " AND A.MARKSHEET_REQUEST_STATUS=2";



	$res 	= $exam->list_certificates_requests('', $studid, $institute_id, $cond);

	?>

 <!-- Content Wrapper. Contains page content -->

 <div class="content-wrapper">

 	<!-- Content Header (Page header) -->

 	<section class="content-header">

 		<h1>

 			All Marksheet Requests



 		</h1>

 		<ol class="breadcrumb">

 			<li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>

 			<li><a href="#"> Marksheet</a></li>

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

 			<form action="" method="post" class="form-inline" onsubmit="return confirm('Confirm! Apply student for marksheet?'); pageLoaderOverlay('show')">

 				<div class="col-xs-12">

 					<div class="box box-warning">

 						<div class="box-header">



 							<!--  <input type="submit" class="btn btn-primary" name="submit"  value="Apply For Certificate" />

             <input type="hidden" class="btn btn-sm btn-primary" name="action" value="applyforcertificate">

             <input type="hidden" class="btn btn-sm btn-primary" name="examstatus1" value="2">

            -->

 							<div class="clearfix"></div>

 							<div class="form-group col-sm-1">

 								<label> &nbsp;</label>

 								<!-- <input type="submit" class="form-control btn btn-sm btn-primary" value="Apply marksheet" name="action"  />	 -->

 							</div>

 						</div>

 						<!-- /.box-header -->


 						<div class="box-body">

 							<table class="table table-bordered data-tbl">

 								<thead>

 									<!-- <?php if ($db->permission('apply_certificate')) { ?>	<th><input type="checkbox" name="selectall" id="selectall" /></th><?php  }  ?> -->

 									<th>#</th>

 									<th>Photo</th>

 									<th>Student</th>

 									<th>Course</th>

 									<th>Exam Mode</th>

 									<th>Percentage</th>

 									<th>Grade</th>

 									<th>Result</th>

 									<th>Marksheet Status</th>

 									<th>Created On</th>

 									<th>Action</th>

 								</thead>
 								<tbody>

 									<?php

										if ($res != '') {

											$srno = 1;

											while ($data = $res->fetch_assoc()) {

												extract($data);

												$PHOTO = SHOW_IMG_AWS . '/default_user.png';

												if ($STUDENT_PHOTO != '')

													$PHOTO = SHOW_IMG_AWS . STUDENT_DOCUMENTS_PATH . '/' . $STUDENT_ID . '/' . $STUDENT_PHOTO;

												$EXAM_TYPE_NAME = !empty($EXAM_TYPE_NAME) ? $EXAM_TYPE_NAME : '-';

												$GRADE = !empty($GRADE) ? $GRADE : '-';




												// $action = '<a href="#"  onclick="getpopup('.$CERTIFICATE_REQUEST_ID.')" value="'.$CERTIFICATE_REQUEST_ID.'"><i class="fa fa-plus" aria-hidden="true"></i>Add</a>';
												$action = '<a href="page.php?page=print-student-marksheet&id=' . $CERTIFICATE_REQUEST_ID . '"><i class="fa fa-plus" aria-hidden="true"></i>Print</a>';

												$status = "";
												if ($MARKSHEET_REQUEST_STATUS == 1) {
													$status = "Applied";
												} elseif ($MARKSHEET_REQUEST_STATUS == 0) {
													$status = "Not Applied";
												} elseif ($MARKSHEET_REQUEST_STATUS == 2) {
													$status = "Approved";
												}
												//$action .= '<a href="#" data-target="#myModalupdate" class="edit" data-toggle="modal">edit</a>';




												//$APPLY_FOR_CERTIFICATE_LABEL = ($APPLY_FOR_CERTIFICATE==0)?'No':'Yes';
												//$disableCheck = ($APPLY_FOR_CERTIFICATE==1)?'disabled':'';
												$checkstud = isset($_POST['checkstud']) ? $_POST['checkstud'] : '';
												$checkbox = "";
												// if($db->permission('apply_certificate'))
												// 	$checkbox = "<td><input type='checkbox' name='checkstud[]' id='checkstud$CERTIFICATE_REQUEST_ID' value='$CERTIFICATE_REQUEST_ID'  disableCheck /></td>";





												echo "
						<tr id='$CERTIFICATE_REQUEST_ID'>
							$checkbox
						

							<td>$srno</td>

							<td><img src='$PHOTO' class='img img-responsive img-circle' style='width:50px; height:50px'></td>

							<td>$STUDENT_NAME</td>

							<td>$EXAM_TITLE</td>							

							<td>$EXAM_TYPE_NAME</td>	

							<td>$MARKS_PER</td>

							<td>$GRADE</td>

							<td>$RESULT_STATUS</td>													

							<td>$status</td>													

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


 <!-- Button trigger modal -->

 <!-- Modal -->
 <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
 	<div class="modal-dialog" role="document">
 		<div class="modal-content">
 			<div class="modal-header">
 				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
 				<h4 class="modal-title" id="myModalLabel">Add marksheet Details</h4>
 			</div>
 			<form class="form-horizontal form-validate" id="frmmarksheet" action="" method="post">
 				<input type="hidden" name="certificate_requests_id" id="certificate_requests_id" value="">

 				<input type="hidden" name="marksheet_requests_id" id="marksheet_requests_id" value>
 				<input type="hidden" name="action" value="get_marksheet_detail">
 				<div class="modal-body">
 					<div>
 						<label for="Subject" class="col-xs-3 control-label"> Add Subject</label>
 						<textarea rows="4" cols="30" class="form-control" id="subject" name="subject" placeholder="Add Marksheet Subject here">
</textarea>
 					</div>

 					<div>

 						<label for="Subject" class="col-xs-3 control-label">Add pactical </label>
 						<input type="text" name="marks" id="marks" class="form-control" placeholder="Add practical Marks here">
 					</div>
 				</div>
 				<div class="modal-footer">
 					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
 					<button type="submit" class="btn btn-primary" value="add_marksheet" name="action">Save changes</button>

 				</div>

 			</form>
 		</div>
 	</div>
 </div>
 <!-- Editmodel -->
 <div class="modal fade" id="myModalupdate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
 	<div class="modal-dialog" role="document">
 		<div class="modal-content">
 			<div class="modal-header">
 				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
 				<h4 class="modal-title" id="myModalLabel">Udate marksheet Details</h4>
 			</div>
 			<form class="form-horizontal form-validate" id="" action="" method="post">
 				<input type="hidden" name="marksheet_request_id">
 				<input type="hidden" name="certificate_requests_id" value="<?= $CERTIFICATE_REQUEST_ID ?>">

 				<div class="modal-body">
 					<div>
 						<label for="Subject" class="col-xs-3 control-label"> Add Subject</label>
 						<textarea rows="4" cols="30" class="form-control" name="subject" placeholder="Add Marksheet Subject here"> <?= isset($_POST['subject']) ? $_POST['subject'] : $MARKSHEET_SUBJECT;
																																	?>
</textarea>
 					</div>

 					<div>
 						<label for="Subject" class="col-xs-3 control-label">Add pactical </label>
 						<input type="text" name="marks" class="form-control" value=<?= isset($_POST['marks']) ? $_POST['marks'] : $MARKSHEET_MARKS;
																					?> placeholder="Add practical Marks here">
 					</div>
 				</div>
 				<div class="modal-footer">
 					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
 					<button type="submit" class="btn btn-primary" value="update_marksheet" name="action">Save changes</button>

 				</div>

 			</form>
 		</div>
 	</div>
 </div>