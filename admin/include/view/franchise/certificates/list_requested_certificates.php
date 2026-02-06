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



$res 	= $exam->list_certificates_requests('', $studid, $institute_id, $cond);

?>

<div class="content-wrapper">
	<div class="col-lg-12 stretch-card">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title"> All Certificate Requests
				</h4>
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



				<div class="table-responsive pt-3">
					<form action="" method="post" class="form-inline" onsubmit="return confirm('Confirm! Apply student for certificates?'); pageLoaderOverlay('show')">

						<table id="order-listing" class="table">
							<thead>
								<tr>
									<th>#</th>
									<th>View Certificates</th>
									<th>Photo</th>
									<th>Student</th>
									<th>Course</th>
									<th>Exam Mode</th>
									<th>Percentage</th>
									<th>Grade</th>
									<th>Result</th>
									<th>Certificate Status</th>
									<th>Student Exam Date</th>
									<th>Certificates Approve Date</th>

								</tr>
							</thead>
							<tbody>
								<?php
								if ($res != '') {
									$srno = 1;
									while ($data = $res->fetch_assoc()) {
										extract($data);
										//print_r($data); exit();
										$PHOTO = '../uploads/default_user.png';
										if ($STUDENT_PHOTO != '')
											$PHOTO = STUDENT_DOCUMENTS_PATH . '/' . $STUDENT_ID . '/' . $STUDENT_PHOTO;
										$EXAM_TYPE_NAME = !empty($EXAM_TYPE_NAME) ? $EXAM_TYPE_NAME : '-';
										$GRADE = !empty($GRADE) ? $GRADE : '-';

										/*$action = "<a href='javascript:void(0)' onclick='deleteStudentResult(this.id)' id='result$CERTIFICATE_REQUEST_ID' class='btn' title='Delete'><i class='fa fa-trash'></i></a>";*/

										$action = "<a href='page.php?page=view-student-certificate&checkstud=$STUDENT_ID&certreq=$CERTIFICATE_REQUEST_ID&course=$COURSE_ID&course_multi_sub=$MULTI_SUB_COURSE_ID&course_typing=$TYPING_COURSE_ID' target='_blank' class='btn btn-primary table-btn' title='View Certificate'><i class='mdi mdi-eye'></i></a>";
										$action .= "<a href='page.php?page=print-requested-marksheet&checkstud=$STUDENT_ID&certreq=$CERTIFICATE_REQUEST_ID&course=$COURSE_ID&course_multi_sub=$MULTI_SUB_COURSE_ID&course_typing=$TYPING_COURSE_ID' target='_blank' class='btn btn-primary table-btn' title='View Marksheet'><i class='mdi mdi-file-pdf'></i></a>";

										if ($REQUEST_STATUS == '2') {
											echo "<tr id='row-$CERTIFICATE_REQUEST_ID'>						

								<td>$srno</td>
								<td>$action</td> 

								<td><img src='$PHOTO' class='img img-responsive img-circle' style='width:50px; height:50px'></td>

								<td>$STUDENT_NAME</td>

								<td>$EXAM_TITLE</td>							

								<td>$EXAM_TYPE_NAME</td>	

								<td>$MARKS_PER</td>

								<td>$GRADE</td>

								<td>$RESULT_STATUS</td>													

								<td>$REQUEST_STATUS_NAME</td>													

								<td>$CREATED_DATE</td>
								
								<td> $CREATED_ON</td>

							

								</tr>

								";

											$srno++;
										}
									}
								}
								?>
							</tbody>
						</table>
				</div>
			</div>
		</div>
	</div>
</div>