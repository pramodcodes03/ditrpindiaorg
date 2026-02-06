<?php
$inst_course_id = isset($_GET['id']) ? $_GET['id'] : '';
$action = isset($_POST['update_course']) ? $_POST['update_course'] : '';
include_once('include/classes/coursemultisub.class.php');
$coursemultisub = new coursemultisub();
if ($action != '') {
	$result = $coursemultisub->update_inst_course_fees();
	$result = json_decode($result, true);
	$success = isset($result['success']) ? $result['success'] : '';
	$message = $result['message'];
	$errors = isset($result['errors']) ? $result['errors'] : '';
	if ($success == true) {
		$_SESSION['msg'] = $message;
		$_SESSION['msg_flag'] = $success;
		header('location:page.php?page=listCoursesMultiSub');
	}
}
/* get course details */
$res = $coursemultisub->list_added_courses_single_multi_sub($inst_course_id);
if ($res != '') {
	$srno = 1;
	while ($data = $res->fetch_assoc()) {
		extract($data);
	}
}
?>
<div class="content-wrapper">
	<div class="row">
		<div class="col-12 grid-margin stretch-card">
			<div class="card">
				<div class="card-body">
					<h4 class="card-title"> Update Course Fees</h4>
					<form role="form" class="form-validate" action="" method="post" enctype="multipart/form-data" onsubmit="pageLoaderOverlay('show');" id="add_student">
						<div class="box-body">
							<?php
							if (isset($success)) {
							?>
								<div class="row">
									<div class="col-sm-12">
										<div class="alert alert-<?= ($success == true) ? 'success' : 'danger' ?> alert-dismissible" id="messages">
											<button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>
											<h4><i class="icon fa fa-check"></i> <?= ($success == true) ? 'Success' : 'Error' ?>:</h4>
											<?= isset($message) ? $message : 'Please correct the errors.'; ?>
											<?php
											echo "<ul>";
											foreach ($errors as $error) {
												echo "<li>$error</li>";
											}
											echo "<ul>";
											?>

										</div>
									</div>
								</div>
							<?php
							}
							?>
							<input type="hidden" name="inst_course_id" value="<?= isset($INSTITUTE_COURSE_ID) ? $INSTITUTE_COURSE_ID : '' ?>" />

							<div class="row">
								<div class="col-md-8">
									<div class="form-group <?= (isset($errors['coursefees'])) ? 'has-error' : '' ?>">
										<label for="coursefees" class="col-sm-3 control-label">Update Course Fees</label>
										<div class="col-sm-9">
											<input class="form-control" id="coursefees" name="coursefees" placeholder="Course name" value="<?= isset($_POST['coursefees']) ? $_POST['coursefees'] : $INSTITUTE_COURSE_FEES ?>" type="text">
											<span class="help-block"><?= isset($errors['coursefees']) ? $errors['coursefees'] : '' ?></span>
										</div>
									</div>
									<div class="form-group <?= (isset($errors['courseminimumfees'])) ? 'has-error' : '' ?>">
										<label for="courseminimumfees" class="col-sm-4 control-label">Update Course Minimum Fees</label>
										<div class="col-sm-8">
											<input class="form-control" id="courseminimumfees" name="courseminimumfees" value="<?= isset($_POST['courseminimumfees']) ? $_POST['courseminimumfees'] : $MINIMUM_FEES ?>" type="text">
											<span class="help-block"><?= isset($errors['courseminimumfees']) ? $errors['courseminimumfees'] : '' ?></span>
										</div>
									</div>
									<div class="box-footer text-center">
										<a href="page.php?page=listCoursesMultiSub" class="btn btn-default">Cancel</a>
										<input type="submit" name="update_course" class="btn btn-info" value="Update Course" />
									</div>
								</div>
								<div class="col-md-4">
									<div class="box box-primary">
										<div class="box-header with-border">
											<h3 class="box-title">Course Details</h3>
										</div>
										<div class="box-body">
											<input type="hidden" name="course_id" value="<?= isset($MULTI_SUB_COURSE_ID) ? $MULTI_SUB_COURSE_ID : '' ?>" />

											<table class="table table-bordered">
												<tr>
													<th>Course Code</th>
													<td><?= $MULTI_SUB_COURSE_CODE ?></td>
												</tr>
												<tr>
													<th>Course Name</th>
													<td><?= $MULTI_SUB_COURSE_NAME ?></td>
												</tr>
												<tr>
													<th>Course Award</th>
													<td><?= $COURSE_AWARD_NAME ?></td>
												</tr>
												<tr>
													<th>Exam Fees</th>
													<td><?= $PLAN_FEES ?></td>
												</tr>
												<tr>
													<th>Course Fees</th>
													<td><?= $INSTITUTE_COURSE_FEES ?></td>
												</tr>

											</table>

										</div>
										<!-- /.box-body -->

									</div>
								</div>

							</div>

						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>