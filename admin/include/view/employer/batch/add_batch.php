<?php
$action		= isset($_POST['add_batch']) ? $_POST['add_batch'] : '';
include_once('include/classes/institute.class.php');
$institute = new institute();

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
$user_role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : '';
if ($user_role == 5) {
	$institute_id = $db->get_parent_id($user_role, $user_id);
	$staff_id = $user_id;
} else {
	$institute_id = $user_id;
	$staff_id = 0;
}

if ($action != '') {

	$result = $institute->add_batch();
	$result = json_decode($result, true);
	$success = isset($result['success']) ? $result['success'] : '';
	$message = isset($result['message']) ? $result['message'] : '';
	$errors = isset($result['errors']) ? $result['errors'] : '';
	if ($success == true) {
		$_SESSION['msg'] = $message;
		$_SESSION['msg_flag'] = $success;
		header('location:page.php?page=listBatches');
	}
	//print_r($errors);
}
?>

<div class="content-wrapper">
	<div class="row">
		<div class="col-12 grid-margin stretch-card">
			<div class="card">
				<div class="card-body">
					<h4 class="card-title">Add New Batch</h4>
					<form role="form" class="form-validate" action="" method="post" enctype="multipart/form-data">
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

										</div>
									</div>
								</div>
							<?php
							}
							?>
							<div class="row">
								<!-- <div class="form-group col-sm-6 <?= (isset($errors['course_id'])) ? 'has-error' : '' ?>">
			                  <label for="course_id">Select Course :</label>
							  <?php $course_id  = isset($_POST['course_id']) ? $_POST['course_id'] : ''; ?>
								 <select class="form-control select2" name="course_id" data-placeholder="Select a Course">		
								 <option name="" value="">Select a Course</option>						
								<?php
								$sql = "SELECT A.INSTITUTE_COURSE_ID, A.COURSE_ID, A.MULTI_SUB_COURSE_ID FROM institute_courses A WHERE A.DELETE_FLAG=0";
								//echo $sql;
								$ex = $db->execQuery($sql);
								if ($ex && $ex->num_rows > 0) {
									while ($data = $ex->fetch_assoc()) {
										$INSTITUTE_COURSE_ID = $data['INSTITUTE_COURSE_ID'];
										$COURSE_ID 			 = $data['COURSE_ID'];
										$MULTI_SUB_COURSE_ID = $data['MULTI_SUB_COURSE_ID'];

										if ($COURSE_ID != '' && !empty($COURSE_ID) && $COURSE_ID != '0') {
											$course 			 = $db->get_course_detail($COURSE_ID);
											$course_name 		 = $course['COURSE_NAME_MODIFY'];
										}

										if ($MULTI_SUB_COURSE_ID != '' && !empty($MULTI_SUB_COURSE_ID) && $MULTI_SUB_COURSE_ID != '0') {
											$course 			 = $db->get_course_detail_multi_sub($MULTI_SUB_COURSE_ID);
											$course_name 		 = $course['COURSE_NAME_MODIFY'];
										}

										$selected = (is_array($course_id) && in_array($INSTITUTE_COURSE_ID, $course_id)) ? 'selected="selected"' : '';

										echo '<option value="' . $INSTITUTE_COURSE_ID . '" ' . $selected . '>' . $course_name . '</option>';
									}
								}
								?>

								 </select>
							  <span class="help-block"><?= (isset($errors['course_id'])) ? $errors['course_id'] : '' ?></span>
			                </div> -->

								<input type="hidden" name="inst_id" class="form-control" id="inst_id" value="<?= $institute_id ?>">

								<div class="form-group col-sm-6 <?= (isset($errors['batch_name'])) ? 'has-error' : '' ?>">
									<label for="batch_name">Batch Name</label>
									<input type="text" name="batch_name" class="form-control" id="batch_name" value="<?= isset($_POST['batch_name']) ? $_POST['batch_name'] : '' ?>" placeholder="Batch Name">
									<span class="help-block"><?= (isset($errors['batch_name'])) ? $errors['batch_name'] : '' ?></span>
								</div>

								<!-- <div class="form-group col-sm-6 <?= (isset($errors['start_date'])) ? 'has-error' : '' ?>">
			                  <label for="start_date">Start Date</label>
			                  <input type="date" name="start_date" class="form-control" id="start_date" value="<?= isset($_POST['start_date']) ? $_POST['start_date'] : '' ?>" placeholder="Start Date" >
							   <span class="help-block"><?= (isset($errors['start_date'])) ? $errors['start_date'] : '' ?></span>
			                </div>

							<div class="form-group col-sm-6 <?= (isset($errors['end_date'])) ? 'has-error' : '' ?>">
			                  <label for="end_date">End Date</label>
			                  <input type="date" name="end_date" class="form-control" id="end_date" value="<?= isset($_POST['end_date']) ? $_POST['end_date'] : '' ?>" placeholder="End Date" >
							   <span class="help-block"><?= (isset($errors['end_date'])) ? $errors['end_date'] : '' ?></span>
			                </div> -->

								<div class="form-group col-sm-6 <?= (isset($errors['timing'])) ? 'has-error' : '' ?>">
									<label for="timing">Batch Timing</label>
									<input type="text" name="timing" class="form-control" id="timing" value="<?= isset($_POST['timing']) ? $_POST['timing'] : '' ?>" placeholder="Batch Timing">
									<span class="help-block"><?= (isset($errors['timing'])) ? $errors['timing'] : '' ?></span>
								</div>

								<div class="form-group col-sm-6 <?= (isset($errors['numberofstudent'])) ? 'has-error' : '' ?>">
									<label for="numberofstudent">Number Of Students</label>
									<input type="text" name="numberofstudent" class="form-control" id="numberofstudent" value="<?= isset($_POST['numberofstudent']) ? $_POST['numberofstudent'] : '' ?>">
									<span class="help-block"><?= (isset($errors['numberofstudent'])) ? $errors['numberofstudent'] : '' ?></span>
								</div>

							</div>

							<div class="box-footer text-center">
								<input type="submit" class="btn btn-primary" name="add_batch" value="Submit" />
								&nbsp;&nbsp;&nbsp;

								<a href="page.php?page=listBatches" class="btn btn-danger" title="Cancel">Cancel</a>
								&nbsp;&nbsp;&nbsp;

							</div>

						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>