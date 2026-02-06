<?php
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
$user_role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : '';
if ($user_role == 5) {
	$institute_id = $db->get_parent_id($user_role, $user_id);
	$staff_id = $user_id;
} else {
	$institute_id = $user_id;
	$staff_id = 0;
}

$action = isset($_POST['add_course']) ? $_POST['add_course'] : '';
include_once('include/classes/coursemultisub.class.php');
$coursemultisub = new coursemultisub();
if ($action != '') {
	//print_r($_POST); exit();
	$result = $coursemultisub->institute_add_aicpe_coursemulti();
	$result = json_decode($result, true);
	$success = isset($result['success']) ? $result['success'] : '';
	$message = isset($result['message']) ? $result['message'] : '';
	$errors = isset($result['errors']) ? $result['errors'] : '';
	if ($success == true) {
		$_SESSION['msg'] = $message;
		$_SESSION['msg_flag'] = $success;
		header('location:page.php?page=listCoursesMultiSub');
	}
	//print_r($errors);
}
?>
<style>
	table.dataTable th:nth-child(3) {
		width: 250px !important;
		max-width: 250px !important;
		word-break: unset !important;
		white-space: unset !important;
	}

	table.dataTable td:nth-child(3) {
		width: 250px !important;
		max-width: 250px !important;
		word-break: unset !important;
		white-space: unset !important;
		line-height: 16px;
	}
</style>

<div class="content-wrapper">
	<div class="col-lg-12 stretch-card">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title">Add Course Multiple Subject </h4>
				<form class="form-validate" action="" method="post">
					<input type="submit" name="add_course" class="btn btn-primary" value="Add Course" style="float: right; margin-top: -60px;" />
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

					<input type="hidden" name="institute" value="<?= $_SESSION['user_id'] ?>" />
					<input type="hidden" name="course_type" value="1" />
					<div class="table-responsive pt-3">
						<table id="order-listing" class="table">
							<thead>
								<tr>
									<th><input type="checkbox" id="selectall" class="" name="selectall" value="all" /></th>
									<th>Course Code</th>
									<th>Course Name</th>
									<th>Subject Name</th>
									<th>Course Duration</th>
									<th>Exam Fees</th>
									<th>Course Fee</th>
									<th>Minimum Fee</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$records = '';
								$srno = 1;
								$res = $coursemultisub->list_notadded_courses_multi_sub($institute_id);
								if ($res != '') {
									while ($data = $res->fetch_assoc()) {
										$MULTI_SUB_COURSE_ID 	= $data['MULTI_SUB_COURSE_ID'];
										$MULTI_SUB_COURSE_CODE 	= $data['MULTI_SUB_COURSE_CODE'];
										$MULTI_SUB_COURSE_DURATION 	= $data['MULTI_SUB_COURSE_DURATION'];
										$MULTI_SUB_COURSE_NAME 	= $data['COURSE_NAME_MODIFY'];
										$COURSE_EXAM_FEES 	= $data['MULTI_SUB_COURSE_FEES'];
										$PLAN_FEES 	= $data['PLAN_FEES'];

										// if($COURSE_NAME=='')
										//  $COURSE_NAME = $COURSE_CODE;
										$row_err_cls = isset($errors['coursefees_' . $MULTI_SUB_COURSE_ID]) ? 'class="danger"' : '';
										$require_err = isset($errors['coursefees_' . $MULTI_SUB_COURSE_ID]) ? '<span style="color:#f00">' . $errors['coursefees_' . $MULTI_SUB_COURSE_ID] . '</span>' : '';

										$require_err_minfees = isset($errors['courseminimumfees_' . $COURSE_ID]) ? '<span style="color:#f00">' . $errors['courseminimumfees_' . $COURSE_ID] . '</span>' : '';

										$checked = '';
										$course = isset($_POST['course']) ? $_POST['course'] : '';
										if (is_array($course) && !empty($course)) {
											if (in_array($MULTI_SUB_COURSE_ID, $course))
												$checked = 'checked="checked"';
										}

										$coursefees = isset($_POST['coursefees_' . $MULTI_SUB_COURSE_ID]) ? $_POST['coursefees_' . $MULTI_SUB_COURSE_ID] : '';

										$coursefeesminimum = isset($_POST['coursefeesminimum_' . $MULTI_SUB_COURSE_ID]) ? $_POST['coursefeesminimum_' . $MULTI_SUB_COURSE_ID] : '';

										$docData1 = $coursemultisub->get_course_subject_added_by_institute($MULTI_SUB_COURSE_ID, $institute_id, false);
										$sr1 = 0;
										$tbl1 = '';
										if (!empty($docData1)) {

											$tbl1 = '<table class="table table-bordered">';
											$tbl1 .= '<tr>
								<th>Sr.No</th>
								<th>Subject Name</th>
								<th>Position</th>
								</tr>';
											foreach ($docData1 as $key => $value) {
												extract($value);
												$tbl1 .= '<tr>';
												$tbl1 .= '<td>' . ++$sr1 . '</td>';
												$tbl1 .= '<td>';
												$tbl1 .= $SUBJECT_NAME;
												$tbl1 .= '</td>';
												$tbl1 .= '<td>';
												$tbl1 .= $POSITION;
												$tbl1 .= '</td>';
												$tbl1 .= '</tr>';
											}
											$tbl1 .= '</table>';
										}


										$records .= '<tr ' . $row_err_cls . '>
											<td><input type="checkbox" class="" name="course[]" value="' . $MULTI_SUB_COURSE_ID . '" ' . $checked . ' />
											</td>
											<td>' . $MULTI_SUB_COURSE_CODE . '</td>
											<td>' . $MULTI_SUB_COURSE_NAME . '</td>
											<td>' . $tbl1 . '</td>
											<td>' . $MULTI_SUB_COURSE_DURATION . '</td>
											<td>' . $PLAN_FEES . '</td>
											<td><input type="text" name="coursefees_' . $MULTI_SUB_COURSE_ID . '" id="coursefees_' . $MULTI_SUB_COURSE_ID . '" value="' . $coursefees . '" />
											' . $require_err . '
											</td>
											<td><input type="text" name="coursefeesminimum_' . $MULTI_SUB_COURSE_ID . '" id="coursefeesminimum_' . $MULTI_SUB_COURSE_ID . '" value="' . $coursefeesminimum . '" />
											' . $require_err_minfees . '
											</td>
											<td><a href="page.php?page=addCoursesMultiSubSubjects&id=' . $MULTI_SUB_COURSE_ID . '" class="btn btn-primary" title="Add Subjects"><i class=" fa fa-pencil"></i>Add Subjects</a></td>
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
							<div class="box-footer text-center">
								<a href="page.php?page=listCoursesMultiSub" class="btn btn-danger">Cancel</a>
								<input type="submit" name="add_course" class="btn btn-primary" value="Add Course" />
							</div>
						<?php } ?>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>