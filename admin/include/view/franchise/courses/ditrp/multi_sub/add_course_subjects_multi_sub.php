<?php

$inst_course_id = isset($_GET['id']) ? $_GET['id'] : '';

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
$user_role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : '';
if ($user_role == 5) {
	$institute_id = $db->get_parent_id($user_role, $user_id);
	$staff_id = $user_id;
} else {
	$institute_id = $user_id;
	$staff_id = 0;
}

$action = isset($_POST['add_subject']) ? $_POST['add_subject'] : '';
include_once('include/classes/coursemultisub.class.php');
$coursemultisub = new coursemultisub();
if ($action != '') {
	//print_r($_POST);
	$result = $coursemultisub->institute_add_aicpe_course_multi_sub();
	$result = json_decode($result, true);
	$success = isset($result['success']) ? $result['success'] : '';
	$message = isset($result['message']) ? $result['message'] : '';
	$errors = isset($result['errors']) ? $result['errors'] : '';
	if ($success == true) {
		$_SESSION['msg'] = $message;
		$_SESSION['msg_flag'] = $success;
		header('location:page.php?page=addCoursesMultiSub');
	}
	//print_r($errors);
}
/* get course details */
$res = $coursemultisub->list_courses_multi_sub($inst_course_id, '');
if ($res != '') {
	$srno = 1;
	while ($data = $res->fetch_assoc()) {
		extract($data);
	}
}
?>
<div class="content-wrapper">
	<div class="col-lg-12 stretch-card">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title">Add Course Multiple Subject</h4>
				<form class="form-validate" action="" method="post">
					<?php
					if (isset($success)) {
					?>
						<div class="row">
							<div class="col-sm-12">
								<div class="alert alert-<?= ($success == true) ? 'success' : 'danger' ?> alert-dismissible" id="messages">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>
									<h4><i class="icon fa fa-check"></i> <?= ($success == true) ? 'Success' : 'Error' ?>:</h4>
									<?= isset($message) ? $message : 'Please correct the errors'; ?>
								</div>
							</div>
						</div>
					<?php
					}
					?>
					<h3 class="box-title">Course Name : <?= $COURSE_AWARD_NAME ?> IN <?= $MULTI_SUB_COURSE_NAME ?> (<?= $MULTI_SUB_COURSE_CODE ?>)</h3>
					<p style="color:red;"> Please Select Subject Minimum 1 And Maximum 10</p>

					<input type="hidden" name="course_id" value="<?= $MULTI_SUB_COURSE_ID ?>" />

					<div class="table-responsive pt-3">
						<table id="order-listing" class="table">
							<thead>
								<tr>
									<th>Sr No.</th>
									<th>Subject Name</th>
									<!--<th>Subject Details</th>-->
									<th>Subject Position</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$records = '';
								$srno = 1;
								$res = $coursemultisub->list_added_courses_subject_multi_sub($inst_course_id);
								if ($res != '') {
									while ($data = $res->fetch_assoc()) {
										$COURSE_SUBJECT_ID1 	= $data['COURSE_SUBJECT_ID'];
										$MULTI_SUB_COURSE_ID 	= $data['MULTI_SUB_COURSE_ID'];
										$COURSE_SUBJECT_NAME 	= $data['COURSE_SUBJECT_NAME'];

										$checked = '';
										$action = '';

										$sub = '';
										$pos = '';
										$disabled = '';

										$docData1 = $coursemultisub->get_course_subject_added_by_institute($MULTI_SUB_COURSE_ID, $institute_id, false);
										if (!empty($docData1)) {
											foreach ($docData1 as $key => $value) {
												extract($value);
												//echo $SUBJECT_ID;
												//echo $COURSE_SUBJECT_ID1; exit();
												if ($SUBJECT_ID == $COURSE_SUBJECT_ID1) {
													$checked = 'checked="checked" disabled="true"';
													$action .= "<a href='javascript:void(0)' onclick='deleteInstCourseSubject($INSTITUTE_SUBJECT_ID)' class='btn btn-danger table-btn' title='Remove'><i class=' mdi mdi-delete'></i></a>";

													$disabled = 'disabled="true"';
													$sub = $SUBJECT_DETAILS;
													$pos = $POSITION;
												}
											}
										}

										$records .= '<tr>													
													<td>' . $srno . '</td>
													<td>' . $COURSE_SUBJECT_NAME . '</td>
													
													<!-- <td><input type="text" name="subjectdetails' . $COURSE_SUBJECT_ID1 . '" id="subjectdetails' . $COURSE_SUBJECT_ID1 . '" value="' . $sub . '" ' . $disabled . '/>	

													</td>-->

													<td><input type="number" name="position' . $COURSE_SUBJECT_ID1 . '" id="subjectdetails' . $COURSE_SUBJECT_ID1 . '" value="' . $pos . '" ' . $disabled . '/> 
													
													
													<td><input type="checkbox" class="" name="course_subject_id[]" onchange="validatesubjectcount()" value="' . $COURSE_SUBJECT_ID1 . '" ' . $checked . '/>
													' . $action . '
													</td>
												
												</tr>';

										$srno++;
									}
									echo $records;
								} else {

									/* echo '<tr>
								  <td colspan="4" align="middle"> <strong>Note:</strong> There are no DITRP courses to add in your Institute. Either you have added all the courses or there are no courses added by DITRP.</td>
								  </tr>'; */
								}
								?>
							</tbody>
						</table>
						<?php if ($res != '') { ?>
							<p class="alert alert-danger"> Note: Please Select Minimum 1 And Maximum 10 Subjects Then Add Subject Button Is Active Otherwise Button Is Disabled. </p>
							<div class="box-footer text-center">
								<a href="page.php?page=addCoursesMultiSub" class="btn btn-default">Cancel</a>
								<input type="submit" name="add_subject" id="add_subject" disabled="true" class="btn btn-info" value="Add Subject" />
							</div>
						<?php } ?>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>