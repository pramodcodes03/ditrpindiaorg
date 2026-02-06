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
include_once('include/classes/course.class.php');
$course = new course();
if ($action != '') {
	//print_r($_POST); exit();
	$result = $course->institute_add_aicpe_course();
	$result = json_decode($result, true);
	$success = isset($result['success']) ? $result['success'] : '';
	$message = isset($result['message']) ? $result['message'] : '';
	$errors = isset($result['errors']) ? $result['errors'] : '';
	if ($success == true) {
		$_SESSION['msg'] = $message;
		$_SESSION['msg_flag'] = $success;
		header('location:page.php?page=listCourses');
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
				<h4 class="card-title">Add Course with Single Subject </h4>
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
									<th>Course Duration</th>
									<th>Exam Fees</th>
									<th>Course Fee</th>
									<th>Minimum Fee</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$records = '';
								$srno = 1;

								$res = $course->list_notadded_courses($institute_id);
								if ($res != '') {
									while ($data = $res->fetch_assoc()) {
										//print_r($data); exit();
										$COURSE_ID 	= $data['COURSE_ID'];
										$COURSE_CODE 	= $data['COURSE_CODE'];
										$COURSE_DURATION 	= $data['COURSE_DURATION'];
										$COURSE_NAME 	= $data['COURSE_NAME_MODIFY'];
										$COURSE_EXAM_FEES 	= $data['COURSE_FEES'];
										$PLAN_FEES 	= $data['PLAN_FEES'];
										$MINIMUM_FEES 	= $data['MINIMUM_FEES'];
										// if($COURSE_NAME=='')
										//  $COURSE_NAME = $COURSE_CODE;
										$row_err_cls = isset($errors['coursefees_' . $COURSE_ID]) ? 'class="danger"' : '';
										$require_err = isset($errors['coursefees_' . $COURSE_ID]) ? '<span style="color:#f00">' . $errors['coursefees_' . $COURSE_ID] . '</span>' : '';

										$require_err_minfees = isset($errors['courseminimumfees_' . $COURSE_ID]) ? '<span style="color:#f00">' . $errors['courseminimumfees_' . $COURSE_ID] . '</span>' : '';

										$checked = '';
										$course = isset($_POST['course']) ? $_POST['course'] : '';
										if (is_array($course) && !empty($course)) {
											if (in_array($COURSE_ID, $course))
												$checked = 'checked="checked"';
										}

										$coursefees = isset($_POST['coursefees_' . $COURSE_ID]) ? $_POST['coursefees_' . $COURSE_ID] : '';
										$courseminimumfees = isset($_POST['courseminimumfees_' . $COURSE_ID]) ? $_POST['courseminimumfees_' . $COURSE_ID] : '';

										$records .= '<tr ' . $row_err_cls . '>
												<td><input type="checkbox" class="" name="course[]" value="' . $COURSE_ID . '" ' . $checked . ' />
												</td>
												<td>' . $COURSE_CODE . '</td>
												<td>' . $COURSE_NAME . '</td>
												<td>' . $COURSE_DURATION . '</td>
												<td>' . $PLAN_FEES . '</td>
												<td><input type="text" name="coursefees_' . $COURSE_ID . '" id="coursefees_' . $COURSE_ID . '" value="' . $coursefees . '" />
												' . $require_err . '
												</td>
												<td><input type="text" name="courseminimumfees_' . $COURSE_ID . '" id="courseminimumfees_' . $COURSE_ID . '" value="' . $courseminimumfees . '" />
												' . $require_err_minfees . '
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
							<div class="box-footer text-center">
								<a href="page.php?page=listCourses" class="btn btn-danger ">Cancel</a>
								<input type="submit" name="add_course" class="btn btn-primary" value="Add Course" />
							</div>
						<?php } ?>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>